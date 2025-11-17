import { useState, useMemo } from 'react';
import { Link, Outlet, useNavigate } from 'react-router-dom';
import '../styles/AdminLayout.css';

export default function AdminLayout({ children }) {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const navigate = useNavigate();
  const rol = localStorage.getItem('rol') || 'usuario';
  const userName = localStorage.getItem('userName') || 'Usuario';

  // Menú dinámico según rol
  const menuItems = useMemo(() => {
    if (rol === 'ADMIN') {
      return [
        { id: 1, label: 'Dashboard', icon: '📊', path: '/admin/dashboard' },
        { id: 2, label: 'Gestión de Llantas', icon: '🛞', path: '/admin/llantas' },
        { id: 3, label: 'Gestión de Usuarios', icon: '👥', path: '/admin/usuarios' },
        { id: 4, label: 'Gestión de Clientes', icon: '🧑‍💼', path: '/admin/clientes' },
        { id: 5, label: 'Pedidos', icon: '📦', path: '/admin/pedidos' },
        { id: 6, label: 'Entregas', icon: '🚚', path: '/admin/entregas' },
        { id: 7, label: 'Asistencia Vial', icon: '🆘', path: '/admin/asistencia' },
        { id: 8, label: 'Reportes', icon: '📈', path: '/admin/reportes' },
      ];
    } else if (rol === 'repartidor') {
      return [
        { id: 1, label: 'Mis Entregas', icon: '📦', path: '/repartidor/entregas' },
      ];
    } else if (rol === 'asistencia_vial') {
      return [
        { id: 1, label: 'Mis Solicitudes', icon: '🆘', path: '/asistencia/solicitudes' },
      ];
    } else {
      return [
        { id: 1, label: 'Dashboard', icon: '📊', path: '/admin/dashboard' },
      ];
    }
  }, [rol]);

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('rol');
    localStorage.removeItem('userId');
    localStorage.removeItem('userName');
    navigate('/');
  };

  const getRoleBadge = () => {
    const badges = {
      'ADMIN': '👑 Admin',
      'repartidor': '🚚 Repartidor',
      'asistencia_vial': '🆘 Asistencia',
      'conductor': '🚗 Conductor',
      'mecanico': '🔧 Mecánico',
      'usuario': '👤 Usuario'
    };
    return badges[rol] || '👤 ' + rol;
  };

  return (
    <div className="admin-container">
      <aside className={`admin-sidebar ${sidebarOpen ? 'open' : 'closed'}`}>
        <div className="sidebar-header">
          <h2>LLANTA-SV</h2>
          <button 
            className="toggle-btn"
            onClick={() => setSidebarOpen(!sidebarOpen)}
          >
            {sidebarOpen ? '◀' : '▶'}
          </button>
        </div>

        <nav className="sidebar-nav">
          {menuItems.map(item => (
            <Link 
              key={item.id} 
              to={item.path}
              className="nav-item"
              title={item.label}
            >
              <span className="nav-icon">{item.icon}</span>
              {sidebarOpen && <span className="nav-label">{item.label}</span>}
            </Link>
          ))}
        </nav>

        <div className="sidebar-footer">
          <button 
            className="logout-btn"
            onClick={handleLogout}
            title="Cerrar sesión"
          >
            <span>🚪</span>
            {sidebarOpen && <span>Salir</span>}
          </button>
        </div>
      </aside>

      <main className="admin-content">
        <header className="admin-header">
          <div className="header-left">
            <h1>Panel de {rol === 'ADMIN' ? 'Administración' : 'Usuario'}</h1>
          </div>
          <div className="header-right">
            <span className="user-info">
              <span className="user-name">{userName}</span>
              <span className="user-badge">{getRoleBadge()}</span>
            </span>
          </div>
        </header>

        <div className="content-area">
          {children || <Outlet />}
        </div>
      </main>
    </div>
  );
}
