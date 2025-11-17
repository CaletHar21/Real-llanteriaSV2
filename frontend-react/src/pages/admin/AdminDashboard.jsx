import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './AdminDashboard.css';

export default function AdminDashboard() {
  const [stats, setStats] = useState({
    llantas: 0,
    pedidosPendientes: 0,
    usuarios: 0,
    clientes: 0,
    entregas: 0,
    asistencias: 0
  });
  const [loading, setLoading] = useState(true);

  const token = localStorage.getItem('token');
  const API_URL = 'http://localhost:3000/api';

  useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    try {
      setLoading(true);
      
      // Traer llantas
      const llantasRes = await axios.get(`${API_URL}/llantas`, {
        headers: { Authorization: `Bearer ${token}` },
        timeout: 60000
      });
      const llantasCount = Array.isArray(llantasRes.data) ? llantasRes.data.length : 
                          (llantasRes.data?.data ? llantasRes.data.data.length : 0);

      // Traer pedidos
      const pedidosRes = await axios.get(`${API_URL}/pedidos/admin/todos`, {
        headers: { Authorization: `Bearer ${token}` },
        timeout: 60000
      });
      const pedidosArray = Array.isArray(pedidosRes.data) ? pedidosRes.data :
                          (pedidosRes.data?.value ? pedidosRes.data.value : []);
      const pedidosPendientes = pedidosArray.filter(p => p.ESTADO === 'pendiente' || p.estado === 'pendiente').length;

      // Traer usuarios
      const usuariosRes = await axios.get(`${API_URL}/usuarios`, {
        headers: { Authorization: `Bearer ${token}` },
        timeout: 60000
      });
      const usuariosArray = usuariosRes.data?.data || usuariosRes.data || [];
      const usuariosCount = Array.isArray(usuariosArray) ? usuariosArray.length : 0;
      const clientesCount = usuariosArray.filter(u => u.ROL === 'cliente' || u.rol === 'cliente').length;

      // Traer entregas
      const entregasRes = await axios.get(`${API_URL}/entregas/admin/todas`, {
        headers: { Authorization: `Bearer ${token}` },
        timeout: 60000
      });
      const entregasArray = Array.isArray(entregasRes.data) ? entregasRes.data :
                           (entregasRes.data?.data ? entregasRes.data.data : []);
      const entregasCount = entregasArray.length;

      // Traer asistencias
      const asistenciasRes = await axios.get(`${API_URL}/asistencia-vial/admin/todas`, {
        headers: { Authorization: `Bearer ${token}` },
        timeout: 60000
      });
      const asistenciasArray = Array.isArray(asistenciasRes.data) ? asistenciasRes.data :
                              (asistenciasRes.data?.data ? asistenciasRes.data.data : []);
      const asistenciasCount = asistenciasArray.length;

      setStats({
        llantas: llantasCount,
        pedidosPendientes: pedidosPendientes,
        usuarios: usuariosCount,
        clientes: clientesCount,
        entregas: entregasCount,
        asistencias: asistenciasCount
      });
    } catch (err) {
      console.error('Error fetching stats:', err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="dashboard-container">
      <div className="dashboard-header">
        <h1>Panel de Administración</h1>
        <button className="btn-refresh" onClick={fetchStats} disabled={loading}>
          🔄 {loading ? 'Cargando...' : 'Actualizar'}
        </button>
      </div>

      {loading ? (
        <div className="loading">⏳ Cargando datos...</div>
      ) : (
        <>
          <div className="dashboard-grid">
            <div className="card stats-card">
              <div className="card-icon">🛞</div>
              <div className="card-content">
                <h3>Llantas en Stock</h3>
                <p className="stat-number">{stats.llantas}</p>
                <p className="stat-label">Productos activos</p>
              </div>
            </div>

            <div className="card stats-card">
              <div className="card-icon">📦</div>
              <div className="card-content">
                <h3>Pedidos Pendientes</h3>
                <p className="stat-number">{stats.pedidosPendientes}</p>
                <p className="stat-label">Requieren atención</p>
              </div>
            </div>

            <div className="card stats-card">
              <div className="card-icon">👥</div>
              <div className="card-content">
                <h3>Usuarios Registrados</h3>
                <p className="stat-number">{stats.usuarios}</p>
                <p className="stat-label">{stats.clientes} clientes activos</p>
              </div>
            </div>

            <div className="card stats-card">
              <div className="card-icon">🚚</div>
              <div className="card-content">
                <h3>Entregas</h3>
                <p className="stat-number">{stats.entregas}</p>
                <p className="stat-label">Total en sistema</p>
              </div>
            </div>

            <div className="card stats-card">
              <div className="card-icon">🚗</div>
              <div className="card-content">
                <h3>Asistencia Vial</h3>
                <p className="stat-number">{stats.asistencias}</p>
                <p className="stat-label">Solicitudes en sistema</p>
              </div>
            </div>
          </div>
        </>
      )}
    </div>
  );
}
