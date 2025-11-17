import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useFetchData } from '../../hooks/useFetchData';
import Modal from '../../components/Modal';
import './AdminUsuarios.css';

export default function AdminUsuarios() {
  const { data: usuariosApi, loading, error: fetchError } = useFetchData('/usuarios');
  const [usuarios, setUsuarios] = useState([]);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [showForm, setShowForm] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [submitting, setSubmitting] = useState(false);
  const [modalSuccess, setModalSuccess] = useState('');
  
  console.log('👥 AdminUsuarios - usuarios recibidos:', usuariosApi.length, usuariosApi.slice(0, 2));
  
  // Sincronizar usuarios del API con estado local
  useEffect(() => {
    if (Array.isArray(usuariosApi)) {
      setUsuarios(usuariosApi);
    }
  }, [usuariosApi]);

  const [formData, setFormData] = useState({
    NOMBRES: '',
    CORREO: '',
    TELEFONO: '',
    ROL: 'usuario'
  });

  const token = localStorage.getItem('token');
  const API_URL = 'http://localhost:3000/api';

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Validar teléfono (formato XXXX-XXXX)
    if (formData.TELEFONO && !formData.TELEFONO.match(/^\d{4}-\d{4}$/)) {
      setError('❌ El teléfono debe tener formato: XXXX-XXXX (8 dígitos con guion)');
      return;
    }
    
    setSubmitting(true);
    try {
      if (editingId) {
        await axios.patch(`${API_URL}/usuarios/${editingId}`, formData, {
          headers: { Authorization: `Bearer ${token}` },
          timeout: 60000
        });
        setUsuarios(usuarios.map(u => u.id === editingId ? {...u, ...formData} : u));
        setModalSuccess('✅ Registro actualizado con éxito');
        setSuccess('✅ Registro actualizado con éxito');
        setError('');
      } else {
        const response = await axios.post(`${API_URL}/usuarios`, formData, {
          headers: { Authorization: `Bearer ${token}` },
          timeout: 60000
        });
        setUsuarios([...usuarios, response.data]);
        setModalSuccess('✅ Usuario guardado con éxito');
        setSuccess('✅ Usuario guardado con éxito');
        setError('');
      }
      setTimeout(() => {
        setShowForm(false);
        setModalSuccess('');
        setFormData({ NOMBRES: '', CORREO: '', TELEFONO: '', ROL: 'usuario' });
        setEditingId(null);
        setSubmitting(false);
      }, 1500);
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error guardando usuario:', err);
      const errorMsg = err.response?.data?.message || err.response?.data?.error || err.message || 'Error al guardar usuario';
      setError('❌ ' + errorMsg);
      setSuccess('');
      setModalSuccess('');
      setSubmitting(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm('¿Está seguro de eliminar este usuario?')) return;
    
    try {
      await axios.delete(`${API_URL}/usuarios/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
      });
      // Remover usuario de la lista local
      setUsuarios(usuarios.filter(u => u.id !== id));
      setSuccess('✅ Usuario eliminado con éxito');
      setError('');
      setTimeout(() => setSuccess(''), 3000);
    } catch (err) {
      console.error('Error eliminando usuario:', err);
      setError('❌ ' + (err.response?.data?.message || 'Error al eliminar usuario'));
      setSuccess('');
    }
  };

  if (loading) return <div className="admin-usuarios-container"><p>⏳ Cargando usuarios...</p></div>;

  return (
    <div className="admin-usuarios-container">
      <div className="usuarios-header">
        <h2>Gestión de Usuarios ({usuarios.length})</h2>
        <button 
          className="btn-add"
          onClick={() => {
            setFormData({ NOMBRES: '', CORREO: '', TELEFONO: '', ROL: 'usuario' });
            setEditingId(null);
            setShowForm(true);
          }}
        >
          + Nuevo Usuario
        </button>
      </div>

      {(error || fetchError) && <div className="alert-error">{error || fetchError}</div>}
      {success && <div className="alert-success">{success}</div>}

      <Modal 
        isOpen={showForm} 
        onClose={() => !submitting && setShowForm(false)}
        title={editingId ? 'Editar Usuario' : 'Nuevo Usuario'}
      >
        {modalSuccess ? (
          <div className="modal-success-message">
            <div className="success-icon">✅</div>
            <h3>{editingId ? 'Usuario Actualizado' : 'Usuario Creado'}</h3>
            <p>{modalSuccess}</p>
            <p style={{fontSize: '12px', color: '#95a5a6'}}>El modal se cerrará automáticamente...</p>
          </div>
        ) : (
          <>
            {error && <div style={{backgroundColor: '#fadbd8', color: '#c0392b', padding: '12px 15px', borderRadius: '6px', marginBottom: '15px', borderLeft: '4px solid #c0392b'}}>{error}</div>}
            <form onSubmit={handleSubmit} className="admin-form">
              <div className="form-group">
                <label>Nombre</label>
                <input
                type="text"
                value={formData.NOMBRES}
                onChange={(e) => setFormData({...formData, NOMBRES: e.target.value})}
                required
                placeholder="Ingrese nombre completo"
                disabled={submitting}
              />
            </div>
            <div className="form-group">
              <label>Email</label>
              <input
                type="email"
                value={formData.CORREO}
                onChange={(e) => setFormData({...formData, CORREO: e.target.value})}
                required
                placeholder="Ingrese email"
                disabled={submitting}
              />
            </div>
            <div className="form-group">
              <label>Teléfono</label>
              <input
                type="tel"
                value={formData.TELEFONO}
                onChange={(e) => {
                  let val = e.target.value.replace(/\D/g, ''); // Solo dígitos
                  if (val.length >= 4) {
                    val = val.slice(0, 4) + '-' + val.slice(4, 8);
                  }
                  setFormData({...formData, TELEFONO: val});
                }}
                placeholder="Ejemplo: 7772-7857"
                maxLength="9"
                disabled={submitting}
              />
              <small style={{color: '#95a5a6', marginTop: '5px', display: 'block'}}>Formato: XXXX-XXXX (8 dígitos)</small>
            </div>
            <div className="form-group">
              <label>Rol</label>
              <select 
                value={formData.ROL} 
                onChange={(e) => setFormData({...formData, ROL: e.target.value})}
                disabled={submitting}
              >
              <option value="usuario">Usuario</option>
              <option value="ADMIN">Administrador</option>
              <option value="conductor">Conductor</option>
              <option value="mecanico">Mecánico</option>
              <option value="repartidor">Repartidor</option>
              <option value="asistencia_vial">Asistencia Vial</option>
            </select>
          </div>
          <div className="form-actions">
            <button type="submit" className="btn-save" disabled={submitting}>
              {submitting ? '⏳ Guardando...' : 'Guardar'}
            </button>
            <button type="button" className="btn-cancel" onClick={() => setShowForm(false)} disabled={submitting}>
              Cancelar
            </button>
          </div>
        </form>
          </>
        )}
      </Modal>

      <div className="usuarios-table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Teléfono</th>
              <th>Rol</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            {usuarios && usuarios.length > 0 ? (
              usuarios.map(usuario => (
                <tr key={usuario.id}>
                  <td>{usuario.id}</td>
                  <td>{usuario.NOMBRES || '-'}</td>
                  <td>{usuario.CORREO || '-'}</td>
                  <td>{usuario.TELEFONO || '-'}</td>
                  <td>
                    <span className={`rol-badge rol-${(usuario.ROL || '').toLowerCase()}`}>
                      {usuario.ROL || '-'}
                    </span>
                  </td>
                  <td className="acciones">
                    <button 
                      className="btn-edit"
                      onClick={() => {
                        setFormData(usuario);
                        setEditingId(usuario.id);
                        setShowForm(true);
                      }}
                    >
                      Editar
                    </button>
                    <button 
                      className="btn-delete"
                      onClick={() => handleDelete(usuario.id)}
                    >
                      Eliminar
                    </button>
                  </td>
                </tr>
              ))
            ) : (
              <tr><td colSpan="6" style={{textAlign: 'center'}}>No hay usuarios</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
