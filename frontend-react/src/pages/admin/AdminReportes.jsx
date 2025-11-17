import React from 'react';
import './AdminReportes.css';

export default function AdminReportes() {
  return (
    <div className="admin-reportes-container">
      <div className="reportes-header">
        <h2>Reportes y Análisis</h2>
      </div>

      <div className="construction-message">
        <div className="construction-icon">🔨</div>
        <h3>Sección en Construcción</h3>
        <p>Los reportes y análisis están siendo desarrollados.</p>
        <p>Pronto estarán disponibles estadísticas detalladas del negocio.</p>
      </div>
    </div>
  );
}
