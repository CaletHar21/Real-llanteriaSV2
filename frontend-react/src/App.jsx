import { Routes, Route } from 'react-router-dom';
import Layout from './components/layout/Layout';
import AdminLayout from './layouts/AdminLayout';
import Home from './modules/core/Home';
import Llantas from './modules/llantas/Llantas';
import RegisterForm from './components/auth/RegisterForm';
import LoginForm from './components/auth/LoginForm';
import Perfil from './components/auth/Perfil';
import ProtectedRoute from './components/auth/ProtectedRoute';
import AdminLlantas from './components/admin/AdminLlantas';
import AdminCotizaciones from './components/admin/AdminCotizaciones';
import MisCotizaciones from './components/user/MisCotizaciones';
import About from './components/pages/About';
import AdminDashboard from './pages/admin/AdminDashboard';
import AdminUsuarios from './pages/admin/AdminUsuarios';
import AdminClientes from './pages/admin/AdminClientes';
import AdminPedidos from './pages/admin/AdminPedidos';
import AdminEntregas from './pages/admin/AdminEntregas';
import AdminAsistencia from './pages/admin/AdminAsistencia';
import AdminReportes from './pages/admin/AdminReportes';
import RepartidorEntregas from './pages/repartidor/RepartidorEntregas';

export default function App() {
  return (
    <Routes>
      {/* Rutas públicas con layout */}
      <Route
        path="/"
        element={
          <Layout>
            <Home />
          </Layout>
        }
      />
      <Route
        path="/llantas"
        element={
          <Layout>
            <Llantas />
          </Layout>
        }
      />
      <Route
        path="/register"
        element={
          <Layout>
            <RegisterForm />
          </Layout>
        }
      />
      <Route
        path="/login"
        element={
          <Layout>
            <LoginForm />
          </Layout>
        }
      />
      <Route
        path="/nosotros"
        element={
          <Layout>
            <About />
          </Layout>
        }
      />

      {/* Rutas protegidas con layout */}
      <Route
        path="/perfil"
        element={
          <ProtectedRoute>
            <Layout>
              <Perfil />
            </Layout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/llantas"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminLlantas />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/cotizaciones"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminCotizaciones />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
      {/* Admin Dashboard con layout sidebar */}
      <Route
        path="/admin/dashboard"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminDashboard />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/usuarios"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminUsuarios />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/clientes"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminClientes />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/pedidos"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminPedidos />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/entregas"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminEntregas />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/asistencia"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminAsistencia />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
      <Route
        path="/admin/reportes"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <AdminReportes />
            </AdminLayout>
          </ProtectedRoute>
        }
      />


      <Route
        path="/cotizaciones"
        element={
          <ProtectedRoute>
            <Layout>
              <MisCotizaciones />
            </Layout>
          </ProtectedRoute>
        }
      />

      {/* Rutas Repartidor */}
      <Route
        path="/repartidor/entregas"
        element={
          <ProtectedRoute>
            <AdminLayout>
              <RepartidorEntregas />
            </AdminLayout>
          </ProtectedRoute>
        }
      />
    </Routes>
  );
}
