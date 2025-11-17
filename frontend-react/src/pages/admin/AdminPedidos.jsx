import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useFetchData } from '../../hooks/useFetchData';
import './AdminPedidos.css';

export default function AdminPedidos() {
  const { data: pedidosApi, loading, error: fetchError } = useFetchData('/pedidos/admin/todos');
  const { data: repartidoresApi } = useFetchData('/usuarios?rol=repartidor&per_page=100');
  const [pedidos, setPedidos] = useState([]);
  const [repartidores, setRepartidores] = useState([]);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [selectedPedido, setSelectedPedido] = useState(null);
  const [showDetails, setShowDetails] = useState(false);
  const [showAssignModal, setShowAssignModal] = useState(false);
  const [selectedRepartidor, setSelectedRepartidor] = useState('');
  const [submitting, setSubmitting] = useState(false);

  console.log('📦 AdminPedidos - pedidos recibidos:', Array.isArray(pedidosApi) ? pedidosApi.length : 'no es array', pedidosApi.slice ? pedidosApi.slice(0, 1) : pedidosApi);

  // Sincronizar pedidos del API con estado local
  useEffect(() => {
    if (Array.isArray(pedidosApi)) {
      setPedidos(pedidosApi);
    }
  }, [pedidosApi]);

  // Sincronizar repartidores del API
  useEffect(() => {
    console.log('🚚 AdminPedidos - repartidoresApi recibido:', repartidoresApi);
    if (Array.isArray(repartidoresApi)) {
      setRepartidores(repartidoresApi);
      console.log('✅ Repartidores (array):', repartidoresApi.length);
    } else if (repartidoresApi?.data && Array.isArray(repartidoresApi.data)) {
      setRepartidores(repartidoresApi.data);
      console.log('✅ Repartidores (paginado):', repartidoresApi.data.length);
    } else if (repartidoresApi?.datosActuales && Array.isArray(repartidoresApi.datosActuales)) {
      setRepartidores(repartidoresApi.datosActuales);
      console.log('✅ Repartidores (datosActuales):', repartidoresApi.datosActuales.length);
    } else {
      console.log('⚠️ Repartidores no es array:', typeof repartidoresApi, repartidoresApi);
      setRepartidores([]);
    }
  }, [repartidoresApi]);

  const token = localStorage.getItem('token');
  const API_URL = 'http://localhost:3000/api';

  const handleStatusChange = async (pedidoId, newStatus) => {
    try {
      await axios.patch(
        `${API_URL}/pedidos/${pedidoId}`,
        { ESTADO: newStatus },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      // Actualizar pedido en la lista local
      setPedidos(pedidos.map(p => p.id === pedidoId ? {...p, estado: newStatus} : p));
      setSuccess('✅ Estado actualizado con éxito');
      setError('');
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error actualizando estado:', err);
      setError('❌ ' + (err.response?.data?.message || 'Error al actualizar estado'));
      setSuccess('');
    }
  };

  const handleDelete = async (pedidoId) => {
    if (!window.confirm('¿Está seguro de cancelar este pedido?')) return;
    
    try {
      await axios.delete(`${API_URL}/pedidos/${pedidoId}`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      // Remover pedido de la lista local
      setPedidos(pedidos.filter(p => p.id !== pedidoId));
      setShowDetails(false);
      setSuccess('✅ Pedido cancelado con éxito');
      setError('');
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error cancelando pedido:', err);
      setError('❌ ' + (err.response?.data?.message || 'Error al cancelar pedido'));
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
      // Crear entrega asignando el repartidor
      await axios.post(
        `${API_URL}/entregas`,
        {
          pedido_id: selectedPedido.id,
          usuario_id: selectedRepartidor,
          direccion: selectedPedido.direccion || 'N/A',
          ciudad: selectedPedido.ciudad || 'N/A',
          telefono: selectedPedido.telefono || 'N/A',
          latitud: selectedPedido.latitud || null,
          longitud: selectedPedido.longitud || null,
          estado: 'asignada'
        },
        { headers: { Authorization: `Bearer ${token}` } }
      );

      setSuccess('✅ ¡Repartidor asignado correctamente!');
      setError('');
      setShowAssignModal(false);
      setSelectedRepartidor('');
      setSelectedPedido(null);
      setShowDetails(false);
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
      'pendiente': '#ffc107',
      'confirmado': '#17a2b8',
      'enviando': '#007bff',
      'entregado': '#28a745',
      'cancelado': '#dc3545'
    };
    return colors[status] || '#6c757d';
  };

  if (loading) return <div className="admin-pedidos-container"><p>⏳ Cargando pedidos...</p></div>;

  return (
    <div className="admin-pedidos-container">
      <div className="pedidos-header">
        <h2>Gestión de Pedidos</h2>
        <div className="pedidos-stats">
          <div className="stat">
            <span className="stat-label">Total Pedidos:</span>
            <span className="stat-value">{Array.isArray(pedidos) ? pedidos.length : 0}</span>
          </div>
          <div className="stat">
            <span className="stat-label">Pendientes:</span>
            <span className="stat-value">{Array.isArray(pedidos) ? pedidos.filter(p => p.estado === 'pendiente').length : 0}</span>
          </div>
          <div className="stat">
            <span className="stat-label">Entregados:</span>
            <span className="stat-value">{pedidos.filter(p => p.estado === 'entregado').length}</span>
          </div>
        </div>
      </div>

      {error && <div className="alert-error">{error}</div>}
      {success && <div className="alert-success">{success}</div>}

      {showDetails && selectedPedido && (
        <div className="modal-overlay" onClick={() => setShowDetails(false)}>
          <div className="modal-content" onClick={e => e.stopPropagation()}>
            <div className="modal-header">
              <h3>Detalles del Pedido #{selectedPedido.id}</h3>
              <button className="btn-close" onClick={() => setShowDetails(false)}>×</button>
            </div>
            <div className="modal-body">
              <div className="detail-section">
                <h4>Información General</h4>
                <p><strong>Usuario:</strong> {selectedPedido.usuario?.nombre || 'N/A'}</p>
                <p><strong>Estado:</strong> 
                  <span className="status-badge" style={{backgroundColor: getStatusColor(selectedPedido.estado)}}>
                    {selectedPedido.estado}
                  </span>
                </p>
                <p><strong>Total:</strong> ${selectedPedido.total?.toFixed(2) || '0.00'}</p>
                <p><strong>Fecha de Entrega Solicitada:</strong> {selectedPedido.fecha_entrega_solicitada || 'N/A'}</p>
              </div>
              {selectedPedido.detalle_pedidos && selectedPedido.detalle_pedidos.length > 0 && (
                <div className="detail-section">
                  <h4>Llantas en este Pedido</h4>
                  <table className="items-table">
                    <thead>
                      <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      {selectedPedido.detalle_pedidos.map(item => (
                        <tr key={item.id}>
                          <td>{item.llanta?.descripcion || 'N/A'}</td>
                          <td>{item.cantidad}</td>
                          <td>${item.precio_unitario?.toFixed(2)}</td>
                          <td>${item.subtotal?.toFixed(2)}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
              {selectedPedido.notas && (
                <div className="detail-section">
                  <h4>Notas</h4>
                  <p>{selectedPedido.notas}</p>
                </div>
              )}
            </div>
          </div>
        </div>
      )}

      {/* Modal Asignar Repartidor */}
      {showAssignModal && selectedPedido && (
        <div className="modal-overlay" onClick={() => setShowAssignModal(false)}>
          <div className="modal-content" onClick={e => e.stopPropagation()}>
            <div className="modal-header">
              <h3>🚚 Asignar Repartidor a Pedido #{selectedPedido.id}</h3>
              <button className="btn-close" onClick={() => setShowAssignModal(false)}>×</button>
            </div>

            <div className="modal-body">
              <div className="info-block">
                <p><strong>Cliente:</strong> {selectedPedido.usuario?.NOMBRES || 'N/A'}</p>
                <p><strong>Total:</strong> ${parseFloat(selectedPedido.total || 0).toFixed(2)}</p>
                <p><strong>Llantas:</strong> {selectedPedido.detalle_pedidos?.length || 0} artículo(s)</p>
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

      <div className="pedidos-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Usuario</th>
              <th>Total</th>
              <th>Estado</th>
              <th>Fecha Entrega</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {Array.isArray(pedidos) && pedidos.map(pedido => (
              <tr key={pedido.id}>
                <td>#{pedido.id}</td>
                <td>{pedido.usuario?.NOMBRES || 'N/A'}</td>
                <td>${parseFloat(pedido.total || 0).toFixed(2)}</td>
                <td>
                  <select 
                    value={pedido.estado}
                    onChange={(e) => handleStatusChange(pedido.id, e.target.value)}
                    className="status-select"
                    style={{backgroundColor: getStatusColor(pedido.estado)}}
                  >
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmado">Confirmado</option>
                    <option value="enviando">Enviando</option>
                    <option value="entregado">Entregado</option>
                    <option value="cancelado">Cancelado</option>
                  </select>
                </td>
                <td>{pedido.fecha_entrega_solicitada || 'N/A'}</td>
                <td className="acciones">
                  <button 
                    className="btn-assign"
                    onClick={() => {
                      setSelectedPedido(pedido);
                      setSelectedRepartidor('');
                      setShowAssignModal(true);
                    }}
                    title="Asignar repartidor"
                  >
                    🚚 Asignar
                  </button>
                  <button 
                    className="btn-view"
                    onClick={() => {
                      setSelectedPedido(pedido);
                      setShowDetails(true);
                    }}
                  >
                    Ver
                  </button>
                  <button 
                    className="btn-delete"
                    onClick={() => handleDelete(pedido.id)}
                  >
                    Cancelar
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
