import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useFetchData } from '../../hooks/useFetchData';
import './AdminEntregas.css';

export default function AdminEntregas() {
  const { data: entregasApi, loading, error: fetchError } = useFetchData('/entregas/admin/todas');
  const { data: repartidoresApi } = useFetchData('/usuarios?rol=repartidor&per_page=100');
  const [entregas, setEntregas] = useState([]);
  const [repartidores, setRepartidores] = useState([]);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [showMap, setShowMap] = useState(false);
  const [showAssignModal, setShowAssignModal] = useState(false);
  const [selectedEntrega, setSelectedEntrega] = useState(null);
  const [selectedRepartidor, setSelectedRepartidor] = useState('');
  const [submitting, setSubmitting] = useState(false);

  console.log('🚚 AdminEntregas - entregas recibidas:', Array.isArray(entregasApi) ? entregasApi.length : 'no es array', entregasApi.slice ? entregasApi.slice(0, 1) : entregasApi);

  // Sincronizar entregas del API con estado local
  useEffect(() => {
    if (Array.isArray(entregasApi)) {
      setEntregas(entregasApi);
    }
  }, [entregasApi]);

  // Sincronizar repartidores del API con estado local
  useEffect(() => {
    if (Array.isArray(repartidoresApi)) {
      setRepartidores(repartidoresApi);
    } else if (repartidoresApi?.data && Array.isArray(repartidoresApi.data)) {
      setRepartidores(repartidoresApi.data);
    }
  }, [repartidoresApi]);

  const token = localStorage.getItem('token');
  const API_URL = 'http://localhost:3000/api';

  const handleStatusChange = async (entregaId, newStatus) => {
    try {
      await axios.patch(
        `${API_URL}/entregas/${entregaId}`,
        { ESTADO: newStatus },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      // Actualizar entrega en la lista local
      setEntregas(entregas.map(e => e.id === entregaId ? {...e, estado: newStatus} : e));
      setSuccess('✅ Estado actualizado con éxito');
      setError('');
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error actualizando estado:', err);
      setError('❌ ' + (err.response?.data?.message || 'Error al actualizar estado'));
      setSuccess('');
    }
  };

  const handleAssignRepartidor = async () => {
    if (!selectedRepartidor) {
      setError('❌ Selecciona un repartidor');
      return;
    }

    setSubmitting(true);
    try {
      await axios.patch(
        `${API_URL}/entregas/${selectedEntrega.id}`,
        { usuario_id: selectedRepartidor },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      
      // Actualizar entrega con el repartidor asignado
      const repartidorSeleccionado = repartidores.find(r => r.id === parseInt(selectedRepartidor));
      setEntregas(entregas.map(e => 
        e.id === selectedEntrega.id 
          ? {...e, usuario_id: selectedRepartidor, usuario: repartidorSeleccionado} 
          : e
      ));
      
      setSuccess('✅ ¡Repartidor asignado correctamente!');
      setError('');
      setShowAssignModal(false);
      setSelectedRepartidor('');
      setSelectedEntrega(null);
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error asignando repartidor:', err);
      setError('❌ ' + (err.response?.data?.message || 'Error al asignar repartidor'));
      setSuccess('');
    } finally {
      setSubmitting(false);
    }
  };

  const getStatusColor = (status) => {
    const colors = {
      'asignada': '#ffc107',
      'en_transito': '#17a2b8',
      'entregada': '#28a745',
      'fallida': '#dc3545'
    };
    return colors[status] || '#6c757d';
  };

  if (loading) return <div className="admin-entregas-container"><p>⏳ Cargando entregas...</p></div>;

  return (
    <div className="admin-entregas-container">
      <div className="entregas-header">
        <h2>Gestión de Entregas</h2>
        <div className="entregas-stats">
          <div className="stat">
            <span className="stat-label">Total:</span>
            <span className="stat-value">{Array.isArray(entregas) ? entregas.length : 0}</span>
          </div>
          <div className="stat">
            <span className="stat-label">En Tránsito:</span>
            <span className="stat-value">{Array.isArray(entregas) ? entregas.filter(e => e.estado === 'en_transito').length : 0}</span>
          </div>
          <div className="stat">
            <span className="stat-label">Entregadas:</span>
            <span className="stat-value">{entregas.filter(e => e.estado === 'entregada').length}</span>
          </div>
        </div>
      </div>

      {error && <div className="alert-error">{error}</div>}
      {success && <div className="alert-success">{success}</div>}

      {/* Modal para Asignar Repartidor */}
      {showAssignModal && selectedEntrega && (
        <div className="modal-overlay" onClick={() => setShowAssignModal(false)}>
          <div className="modal-content" onClick={e => e.stopPropagation()}>
            <div className="modal-header">
              <h3>🚚 Asignar Repartidor a Entrega #{selectedEntrega.id}</h3>
              <button className="btn-close" onClick={() => setShowAssignModal(false)}>×</button>
            </div>
            
            <div className="modal-body">
              <div className="info-block">
                <p><strong>Pedido:</strong> #{selectedEntrega.pedido_id}</p>
                <p><strong>Dirección:</strong> {selectedEntrega.direccion}</p>
                <p><strong>Ciudad:</strong> {selectedEntrega.ciudad}</p>
                <p><strong>Repartidor Actual:</strong> {selectedEntrega.usuario?.NOMBRES || '❌ Sin asignar'}</p>
              </div>

              <div className="form-group">
                <label>Selecciona un Repartidor</label>
                <select 
                  value={selectedRepartidor}
                  onChange={(e) => setSelectedRepartidor(e.target.value)}
                  disabled={submitting}
                  className="form-select"
                >
                  <option value="">-- Seleccionar Repartidor --</option>
                  {Array.isArray(repartidores) && repartidores.map(rep => (
                    <option key={rep.id} value={rep.id}>
                      {rep.NOMBRES} - {rep.TELEFONO}
                    </option>
                  ))}
                </select>
              </div>

              {error && <div className="alert-error">{error}</div>}

              <div className="modal-actions">
                <button 
                  className="btn-cancel"
                  onClick={() => setShowAssignModal(false)}
                  disabled={submitting}
                >
                  Cancelar
                </button>
                <button 
                  className="btn-save"
                  onClick={handleAssignRepartidor}
                  disabled={submitting || !selectedRepartidor}
                >
                  {submitting ? '⏳ Asignando...' : '✅ Asignar Repartidor'}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Modal Ver Ubicación */}
      {showMap && selectedEntrega && (
        <div className="modal-overlay" onClick={() => setShowMap(false)}>
          <div className="modal-content" onClick={e => e.stopPropagation()}>
            <div className="modal-header">
              <h3>Ubicación de Entrega #{selectedEntrega.id}</h3>
              <button className="btn-close" onClick={() => setShowMap(false)}>×</button>
            </div>
            <div className="map-container">
              <div className="location-info">
                <p><strong>Dirección:</strong> {selectedEntrega.direccion}</p>
                <p><strong>Ciudad:</strong> {selectedEntrega.ciudad}</p>
                <p><strong>Teléfono:</strong> {selectedEntrega.telefono}</p>
                {selectedEntrega.latitud && selectedEntrega.longitud && (
                  <p><strong>Coordenadas:</strong> {selectedEntrega.latitud}, {selectedEntrega.longitud}</p>
                )}
              </div>
              {selectedEntrega.latitud && selectedEntrega.longitud && (
                <div className="map-placeholder">
                  <a 
                    href={`https://www.google.com/maps?q=${selectedEntrega.latitud},${selectedEntrega.longitud}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="btn-open-maps"
                  >
                    Abrir en Google Maps
                  </a>
                </div>
              )}
            </div>
          </div>
        </div>
      )}

      <div className="entregas-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Pedido</th>
              <th>Repartidor</th>
              <th>Dirección</th>
              <th>Ciudad</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {Array.isArray(entregas) && entregas.map(entrega => (
              <tr key={entrega.id}>
                <td className="id-cell">#{entrega.id}</td>
                <td className="pedido-cell">#{entrega.pedido_id}</td>
                <td className="repartidor-cell">
                  {entrega.usuario_id ? (
                    <span className="repartidor-badge">
                      <strong>#{entrega.usuario_id}</strong> - {entrega.usuario?.NOMBRES || 'N/A'}
                    </span>
                  ) : (
                    <span className="sin-asignar">❌ Sin asignar</span>
                  )}
                </td>
                <td>{entrega.direccion}</td>
                <td>{entrega.ciudad}</td>
                <td>
                  <select 
                    value={entrega.estado}
                    onChange={(e) => handleStatusChange(entrega.id, e.target.value)}
                    className="status-select"
                    style={{backgroundColor: getStatusColor(entrega.estado)}}
                  >
                    <option value="asignada">Asignada</option>
                    <option value="en_transito">En Tránsito</option>
                    <option value="entregada">Entregada</option>
                    <option value="fallida">Fallida</option>
                  </select>
                </td>
                <td className="acciones">
                  <button 
                    className="btn-assign"
                    onClick={() => {
                      setSelectedEntrega(entrega);
                      setSelectedRepartidor('');
                      setShowAssignModal(true);
                    }}
                    title="Asignar repartidor"
                  >
                    👤 Asignar
                  </button>
                  <button 
                    className="btn-view"
                    onClick={() => {
                      setSelectedEntrega(entrega);
                      setShowMap(true);
                    }}
                  >
                    📍 Ver
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
