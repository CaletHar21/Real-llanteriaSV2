import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useFetchData } from '../../hooks/useFetchData';
import './AdminAsistencia.css';

export default function AdminAsistencia() {
  const { data: asistencias, loading, error: fetchError } = useFetchData('/asistencia-vial/admin/todas');
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [usuarios, setUsuarios] = useState([]);
  const [selectedAsistencia, setSelectedAsistencia] = useState(null);
  const [showAssignForm, setShowAssignForm] = useState(false);
  const [mecanicId, setMecanicId] = useState('');

  console.log('🚗 AdminAsistencia - asistencias recibidas:', Array.isArray(asistencias) ? asistencias.length : 'no es array', asistencias.slice ? asistencias.slice(0, 1) : asistencias);

  const token = localStorage.getItem('token');
  const API_URL = 'http://localhost:3000/api';

  useEffect(() => {
    fetchUsuarios();
  }, []);

  const fetchUsuarios = async () => {
    try {
      const response = await axios.get(`${API_URL}/usuarios`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      // Handle both paginated and direct array responses
      let allUsuarios = [];
      if (response.data && response.data.data && Array.isArray(response.data.data)) {
        allUsuarios = response.data.data;
      } else if (Array.isArray(response.data)) {
        allUsuarios = response.data;
      }
      const mecanicos = allUsuarios.filter(u => u.ROL === 'mecanico' || u.rol === 'mecanico');
      setUsuarios(mecanicos);
    } catch (err) {
      console.error('Error fetching usuarios:', err);
    }
  };

  const handleAssignMechanic = async () => {
    if (!mecanicId) {
      setError('Selecciona un mecánico');
      return;
    }

    try {
      await axios.post(
        `${API_URL}/asistencia-vial/${selectedAsistencia.id}/asignar`,
        { mecanico_id: mecanicId },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      setSuccess('✅ Mecánico asignado con éxito');
      setError('');
      setShowAssignForm(false);
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error asignando mecánico:', err);
      setError('❌ Error al asignar mecánico');
      setSuccess('');
    }
  };

  const handleStatusChange = async (asistenciaId, newStatus) => {
    try {
      await axios.patch(
        `${API_URL}/asistencia-vial/${asistenciaId}`,
        { ESTADO: newStatus },
        { headers: { Authorization: `Bearer ${token}` } }
      );
      setSuccess('✅ Estado actualizado con éxito');
      setError('');
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error actualizando estado:', err);
      setError('❌ Error al actualizar estado');
      setSuccess('');
    }
  };

  const getStatusColor = (status) => {
    const colors = {
      'solicitada': '#ffc107',
      'asignada': '#17a2b8',
      'en_atencion': '#007bff',
      'resuelta': '#28a745',
      'cancelada': '#dc3545'
    };
    return colors[status] || '#6c757d';
  };

  if (loading) return <div className="admin-asistencia-container"><p>⏳ Cargando asistencias...</p></div>;

  return (
    <div className="admin-asistencia-container">
      <div className="asistencia-header">
        <h2>Gestión de Asistencia Vial ({asistencias.length})</h2>
        <div className="asistencia-stats">
          <div className="stat">
            <span className="stat-label">Total:</span>
            <span className="stat-value">{asistencias.length}</span>
          </div>
          <div className="stat">
            <span className="stat-label">Solicitadas:</span>
            <span className="stat-value">{asistencias.filter(a => (a.ESTADO || a.estado) === 'solicitada').length}</span>
          </div>
          <div className="stat">
            <span className="stat-label">Resueltas:</span>
            <span className="stat-value">{asistencias.filter(a => (a.ESTADO || a.estado) === 'resuelta').length}</span>
          </div>
        </div>
      </div>

      {(error || fetchError) && <div className="alert-error">{error || fetchError}</div>}
      {success && <div className="alert-success">{success}</div>}

      {showAssignForm && selectedAsistencia && (
        <div className="modal-overlay" onClick={() => setShowAssignForm(false)}>
          <div className="modal-content" onClick={e => e.stopPropagation()}>
            <div className="modal-header">
              <h3>Asignar Mecánico - Solicitud #{selectedAsistencia.id}</h3>
              <button className="btn-close" onClick={() => setShowAssignForm(false)}>×</button>
            </div>
            <div className="modal-body">
              <div className="form-group">
                <label>Selecciona un Mecánico:</label>
                <select 
                  value={mecanicId}
                  onChange={(e) => setMecanicId(e.target.value)}
                  className="form-control"
                >
                  <option value="">-- Selecciona --</option>
                  {usuarios.map(usuario => (
                    <option key={usuario.id} value={usuario.id}>
                      {usuario.NOMBRES || usuario.nombre}
                    </option>
                  ))}
                </select>
              </div>
              <div className="form-actions">
                <button 
                  className="btn-save"
                  onClick={handleAssignMechanic}
                >
                  Asignar
                </button>
                <button 
                  className="btn-cancel"
                  onClick={() => setShowAssignForm(false)}
                >
                  Cancelar
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      <div className="asistencia-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Cliente</th>
              <th>Vehículo</th>
              <th>Problema</th>
              <th>Mecánico</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {asistencias && asistencias.length > 0 ? (
              asistencias.map(asistencia => (
                <tr key={asistencia.id}>
                  <td>#{asistencia.id}</td>
                  <td>{asistencia.usuario?.NOMBRES || asistencia.usuario?.nombre || 'N/A'}</td>
                  <td>{asistencia.PLACA || asistencia.placa || 'N/A'}</td>
                  <td>{asistencia.PROBLEMA_TIPO || asistencia.tipo_problema || 'N/A'}</td>
                  <td>{asistencia.mecanico?.NOMBRES || asistencia.mecanico?.nombre || 'Sin asignar'}</td>
                  <td>
                    <select 
                      value={asistencia.ESTADO || asistencia.estado}
                      onChange={(e) => handleStatusChange(asistencia.id, e.target.value)}
                      className="status-select"
                      style={{backgroundColor: getStatusColor(asistencia.ESTADO || asistencia.estado)}}
                    >
                      <option value="solicitada">Solicitada</option>
                      <option value="asignada">Asignada</option>
                      <option value="en_atencion">En Atención</option>
                      <option value="resuelta">Resuelta</option>
                      <option value="cancelada">Cancelada</option>
                    </select>
                  </td>
                  <td className="acciones">
                    {!asistencia.mecanico && (
                      <button 
                        className="btn-assign"
                        onClick={() => {
                          setSelectedAsistencia(asistencia);
                          setShowAssignForm(true);
                        }}
                      >
                        Asignar
                      </button>
                    )}
                    {asistencia.mecanico && (
                      <span className="assigned-text">Asignado</span>
                    )}
                  </td>
                </tr>
              ))
            ) : (
              <tr><td colSpan="7" style={{textAlign: 'center'}}>No hay solicitudes</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
