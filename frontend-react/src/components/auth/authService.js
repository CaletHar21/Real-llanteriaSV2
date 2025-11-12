import axios from 'axios';

// Configurar URL base usando variable de entorno
const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:3000/api';

const API = axios.create({
  baseURL: API_URL,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
});

export const login = async (data) => {
  console.log('🔍 LOGIN - Enviando datos:', data);
  console.log('🔍 LOGIN - URL:', `${API_URL}/login`);
  
  try {
    const res = await API.post('/login', data);
    console.log('✅ LOGIN - Respuesta exitosa:', res.data);
    return res.data; // ← incluye token y usuario
  } catch (error) {
    console.error('❌ LOGIN - Error:', error);
    console.error('❌ LOGIN - Respuesta error:', error.response?.data);
    console.error('❌ LOGIN - Status:', error.response?.status);
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
