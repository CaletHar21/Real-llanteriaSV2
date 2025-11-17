import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useFetchData } from '../../hooks/useFetchData';
import Modal from '../../components/Modal';
import './AdminClientes.css';

export default function AdminClientes() {
  const { data: clientesApi, loading, error: fetchError } = useFetchData('/clientes');
  const [clientes, setClientes] = useState([]);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [selectedCliente, setSelectedCliente] = useState(null);
  const [showDetails, setShowDetails] = useState(false);

  console.log('👥 AdminClientes - clientes recibidos:', Array.isArray(clientesApi) ? clientesApi.length : 'no es array', clientesApi.slice ? clientesApi.slice(0, 2) : clientesApi);

  // Sincronizar clientes del API con estado local
  useEffect(() => {
    if (Array.isArray(clientesApi)) {
      setClientes(clientesApi);
    }
  }, [clientesApi]);

  const token = localStorage.getItem('token');
  const API_URL = 'http://localhost:3000/api';

  const handleDelete = async (clienteId) => {
    if (!window.confirm('¿Está seguro de eliminar este cliente?')) return;
    
    try {
      await axios.delete(`${API_URL}/usuarios/${clienteId}`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      // Remover cliente de la lista local
      setClientes(clientes.filter(c => c.id !== clienteId));
      setShowDetails(false);
      setSuccess('✅ Cliente eliminado con éxito');
      setError('');
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error eliminando cliente:', err);
      setError('❌ ' + (err.response?.data?.message || 'Error al eliminar cliente'));
      setSuccess('');
    }
  };

  if (loading) return <div className="admin-clientes-container"><p>⏳ Cargando clientes...</p></div>;

  return (
    <div className="admin-clientes-container">
      <div className="clientes-header">
        <h2>Gestión de Clientes</h2>
        <div className="clientes-stats">
          <div className="stat">
            <span className="stat-label">Total Clientes:</span>
            <span className="stat-value">{Array.isArray(clientes) ? clientes.length : 0}</span>
          </div>
        </div>
      </div>

      {error && <div className="alert-error">{error}</div>}
      {success && <div className="alert-success">{success}</div>}

      <Modal 
        isOpen={showDetails} 
        onClose={() => setShowDetails(false)}
        title="Detalles del Cliente"
      >
        {selectedCliente && (
          <>
            <div className="detail-group">
              <label>Nombre:</label>
              <p>{selectedCliente.NOMBRES}</p>
            </div>
            <div className="detail-group">
              <label>Email:</label>
              <p>{selectedCliente.CORREO}</p>
            </div>
            <div className="detail-group">
              <label>Teléfono:</label>
              <p>{selectedCliente.TELEFONO || 'No registrado'}</p>
            </div>
            <div className="detail-group">
              <label>Rol:</label>
              <p>
                <span className="role-badge role-usuario">{selectedCliente.ROL}</span>
              </p>
            </div>
            <div className="detail-group">
              <label>Miembro desde:</label>
              <p>{new Date(selectedCliente.created_at).toLocaleDateString('es-ES')}</p>
            </div>
          </>
        )}
      </Modal>

      <div className="clientes-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Miembro desde</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {Array.isArray(clientes) && clientes.map(cliente => (
              <tr key={cliente.id}>
                <td>#{cliente.id}</td>
                <td>{cliente.NOMBRES}</td>
                <td>{cliente.CORREO}</td>
                <td>{cliente.TELEFONO || '-'}</td>
                <td>{new Date(cliente.created_at).toLocaleDateString('es-ES')}</td>
                <td className="acciones">
                  <button 
                    className="btn-view"
                    onClick={() => {
                      setSelectedCliente(cliente);
                      setShowDetails(true);
                    }}
                  >
                    Ver
                  </button>
                  <button 
                    className="btn-delete"
                    onClick={() => handleDelete(cliente.id)}
                  >
                    Eliminar
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
