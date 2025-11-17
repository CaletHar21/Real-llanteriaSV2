import axios from 'axios';

// Configurar URL base usando variable de entorno o localhost en desarrollo
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:3000/api';

const API = axios.create({
  baseURL: API_URL,
  timeout: 60000,
  withCredentials: false,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
});

// Interceptor para manejar errores de red
API.interceptors.response.use(
  response => response,
  error => {
    if (error.message === 'Network Error' && !error.response) {
      console.error('❌ Error de red - Verifica que el backend esté corriendo');
      console.error('❌ API_URL:', API_URL);
    }
    return Promise.reject(error);
  }
);

export const login = async (data) => {
  console.log('🔍 LOGIN - Enviando datos:', data);
  console.log('🔍 LOGIN - URL:', `${API_URL}/login`);
  console.log('🔍 LOGIN - Base URL:', API.defaults.baseURL);
  
  try {
    const res = await API.post('/login', data);
    console.log('✅ LOGIN - Respuesta exitosa:', res.data);
    return res.data; // ← incluye token y usuario
  } catch (error) {
    console.error('❌ LOGIN - Error completo:', error);
    console.error('❌ LOGIN - Mensaje:', error.message);
    console.error('❌ LOGIN - Status:', error.response?.status);
    console.error('❌ LOGIN - Datos de error:', error.response?.data);
    throw error;
  }
};

export const register = async (data) => {
  const res = await API.post('/register', data);
  return res.data; // ← incluye token y usuario
};


export const getPerfil = async (token) => {
  const res = await API.get('/perfil', {
    headers: {
      Authorization: `Bearer ${token}`
    }
  });
  return res.data.usuario;
};


export const logout = async (token) => {
  await API.post('/logout', {}, {
    headers: { Authorization: `Bearer ${token}` }
  });
  localStorage.removeItem('token');
};
