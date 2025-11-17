import { useState, useEffect } from 'react';
import axios from 'axios';

export function useFetchData(endpoint) {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const token = localStorage.getItem('token');
  const API_URL = 'http://localhost:3000/api';

  useEffect(() => {
    const fetchData = async () => {
      try {
        if (!token) {
          console.log('⚠️ No hay token disponible');
          setError('No autenticado - token no encontrado');
          setLoading(false);
          return;
        }

        console.log('🔍 Iniciando fetch para:', endpoint);
        console.log('📝 Token:', token.substring(0, 20) + '...');
        
        const response = await axios.get(`${API_URL}${endpoint}`, {
          headers: { 
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json'
          },
          timeout: 60000
        });

        console.log('📡 Respuesta completa de:', endpoint, JSON.stringify(response.data).substring(0, 200));

        // Handle paginated responses
        let items = [];
        if (response.data && response.data.data && Array.isArray(response.data.data)) {
          items = response.data.data;
          console.log('✅ Items extraídos de respuesta paginada (data.data):', items.length, 'items');
        } else if (response.data && response.data.value && Array.isArray(response.data.value)) {
          items = response.data.value;
          console.log('✅ Items extraídos de respuesta (data.value):', items.length, 'items');
        } else if (Array.isArray(response.data)) {
          items = response.data;
          console.log('✅ Items extraídos de array directo:', items.length, 'items');
        } else {
          console.warn('⚠️ Respuesta no es array:', typeof response.data);
          items = [];
        }

        setData(items);
        console.log('💾 setData llamado con:', JSON.stringify(items.slice(0, 2)).substring(0, 300));
        setError('');
        setLoading(false);
      } catch (err) {
        console.error('❌ Error en:', endpoint);
        console.error('Mensaje:', err.message);
        console.error('Status:', err.response?.status);
        console.error('Data:', err.response?.data);
        setError(`Error: ${err.message}`);
        setData([]);
        setLoading(false);
      }
    };

    fetchData();
  }, [endpoint, token]);

  return { data, loading, error };
}
