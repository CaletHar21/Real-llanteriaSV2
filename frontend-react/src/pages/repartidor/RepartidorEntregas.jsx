import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useFetchData } from '../../hooks/useFetchData';
import '../admin/AdminEntregas.css'; // Reutilizar estilos

export default function RepartidorEntregas() {
  const { data: entregasApi, loading, error: fetchError } = useFetchData('/entregas/admin/todas');
  const [entregas, setEntregas] = useState([]);
  const [misEntregas, setMisEntregas] = useState([]);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [showDetails, setShowDetails] = useState(false);
  const [selectedEntrega, setSelectedEntrega] = useState(null);
  const [newStatus, setNewStatus] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const token = localStorage.getItem('token');
  const userId = localStorage.getItem('userId');
  const API_URL = 'http://localhost:3000/api';

  console.log('🚚 RepartidorEntregas - userId:', userId, 'entregas recibidas:', Array.isArray(entregasApi) ? entregasApi.length : 'no es array');

  // Sincronizar entregas y filtrar por usuario actual
  useEffect(() => {
    if (Array.isArray(entregasApi)) {
      setEntregas(entregasApi);
      // Filtrar solo las entregas del repartidor actual
      const filtered = entregasApi.filter(e => e.usuario_id === parseInt(userId));
      setMisEntregas(filtered);
      console.log('📦 Mis entregas:', filtered.length, 'de', entregasApi.length);
    }
  }, [entregasApi, userId]);

  const getStatusColor = (status) => {
    const colors = {
      'asignada': '#ffc107',
      'en_transito': '#17a2b8',
      'entregada': '#28a745',
      'fallida': '#dc3545'
    };
    return colors[status] || '#6c757d';
  };

  const handleStatusChange = async () => {
    if (!newStatus) {
      setError('❌ Selecciona un estado');
      return;
    }

    setSubmitting(true);
    try {
      const updateData = { ESTADO: newStatus };
      
      // Si marca como entregada, agregar fecha
      if (newStatus === 'entregada') {
        updateData.fecha_entrega_realizada = new Date().toISOString();
      }

      await axios.patch(
        `${API_URL}/entregas/${selectedEntrega.id}`,
        updateData,
        { headers: { Authorization: `Bearer ${token}` } }
      );

      // Actualizar en lista local
      setMisEntregas(misEntregas.map(e =>
        e.id === selectedEntrega.id
          ? {
              ...e,
              estado: newStatus,
              fecha_entrega_realizada: newStatus === 'entregada' ? new Date().toISOString() : e.fecha_entrega_realizada
            }
          : e
      ));

      setSuccess(newStatus === 'entregada' 
        ? '✅ ¡Entrega completada! Fecha registrada.' 
        : '✅ Estado actualizado con éxito');
      setError('');
      setShowDetails(false);
      setSelectedEntrega(null);
      setNewStatus('');
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error actualizando estado:', err);
      setError('❌ ' + (err.response?.data?.message || 'Error al actualizar estado'));
      setSuccess('');
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) return <div className="admin-entregas-container"><p>⏳ Cargando entregas...</p></div>;

  return (
    <div className="admin-entregas-container">
      <div className="entregas-header">
        <h2>📦 Mis Entregas</h2>
        <div className="entregas-stats">
          <div className="stat">
            <span className="stat-label">Total:</span>
            <span className="stat-value">{misEntregas.length}</span>
          </div>
          <div className="stat">
            <span className="stat-label">En Tránsito:</span>
            <span className="stat-value">{misEntregas.filter(e => e.estado === 'en_transito').length}</span>
          </div>
          <div className="stat">
            <span className="stat-label">Entregadas:</span>
            <span className="stat-value">{misEntregas.filter(e => e.estado === 'entregada').length}</span>
          </div>
        </div>
      </div>

      {error && <div className="alert-error">{error}</div>}
      {success && <div className="alert-success">{success}</div>}

      {/* Modal de Detalles y Cambio de Estado */}
      {showDetails && selectedEntrega && (
        <div className="modal-overlay" onClick={() => setShowDetails(false)}>
          <div className="modal-content" onClick={e => e.stopPropagation()}>
            <div className="modal-header">
              <h3>📦 Entrega #{selectedEntrega.id}</h3>
              <button className="btn-close" onClick={() => setShowDetails(false)}>×</button>
            </div>

            <div className="modal-body">
              <div className="info-block">
                <p><strong>Pedido:</strong> #{selectedEntrega.pedido_id}</p>
                <p><strong>Dirección:</strong> {selectedEntrega.direccion}</p>
                <p><strong>Ciudad:</strong> {selectedEntrega.ciudad}</p>
                <p><strong>Teléfono:</strong> {selectedEntrega.telefono}</p>
                <p><strong>Estado Actual:</strong> 
                  <span style={{ marginLeft: '8px', padding: '4px 8px', borderRadius: '4px', background: getStatusColor(selectedEntrega.estado), color: 'white' }}>
                    {selectedEntrega.estado}
                  </span>
                </p>
                {selectedEntrega.fecha_entrega_realizada && (
                  <p><strong>Fecha Entrega:</strong> {new Date(selectedEntrega.fecha_entrega_realizada).toLocaleDateString()}</p>
                )}
              </div>

              <div className="form-group">
                <label>Cambiar Estado</label>
                <select
                  value={newStatus}
                  onChange={(e) => setNewStatus(e.target.value)}
                  disabled={submitting}
                  className="form-select"
                >
                  <option value="">-- Seleccionar nuevo estado --</option>
                  <option value="en_transito">En Tránsito (Saliendo)</option>
                  <option value="entregada">✅ Entregada (Completar)</option>
                  <option value="fallida">❌ Fallida (No se pudo entregar)</option>
                </select>
              </div>

              {error && <div className="alert-error">{error}</div>}

              <div className="modal-actions">
                <button
                  className="btn-cancel"
                  onClick={() => setShowDetails(false)}
                  disabled={submitting}
                >
                  Cancelar
                </button>
                <button
                  className="btn-save"
                  onClick={handleStatusChange}
                  disabled={submitting || !newStatus}
                >
                  {submitting ? '⏳ Actualizando...' : '💾 Guardar Cambio'}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Tabla de Entregas */}
      <div className="entregas-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Pedido</th>
              <th>Dirección</th>
              <th>Ciudad</th>
              <th>Teléfono</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {misEntregas.length > 0 ? (
              misEntregas.map(entrega => (
                <tr key={entrega.id}>
                  <td className="id-cell">#{entrega.id}</td>
                  <td className="pedido-cell">#{entrega.pedido_id}</td>
                  <td>{entrega.direccion}</td>
                  <td>{entrega.ciudad}</td>
                  <td>{entrega.telefono}</td>
                  <td>
                    <span style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      background: getStatusColor(entrega.estado),
                      color: 'white',
                      fontWeight: '600',
                      fontSize: '13px'
                    }}>
                      {entrega.estado}
                    </span>
                  </td>
                  <td className="acciones">
                    <button
                      className="btn-view"
                      onClick={() => {
                        setSelectedEntrega(entrega);
                        setNewStatus('');
                        setShowDetails(true);
                      }}
                    >
                      📋 Detalles
                    </button>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="7" style={{ textAlign: 'center', padding: '30px', color: '#999' }}>
                  📭 No tienes entregas asignadas
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
