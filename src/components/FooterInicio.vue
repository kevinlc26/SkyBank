<template>
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-column">
          <p class="footer-title">Información de contacto</p>
          <p>At. al Cliente: 630094422 <br> Consultas@skybank.com</p>
          <div class="social-icons">
            <img src="../assets/icons/icon-fb.svg" alt="Facebook">
            <img src="../assets/icons/icons-x.svg" alt="Twitter">
            <img src="../assets/icons/icons-ig.svg" alt="Instagram">
          </div>
        </div>
  
        <div class="footer-column">
          <p class="footer-title">Información Legal</p>
          <p>
            <a href="#">Términos y condiciones de uso</a> <br>
            <a href="#">Política de Privacidad</a> <br>
            <a href="#">Información sobre seguridad bancaria</a>
          </p>
        </div>
        <div class="footer-column">
          <p>&copy; 2024 SkyBank. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  </template>
  
  <style scoped>
  .footer {
      background-color: #263E33;
      color: white;
      padding: 20px;
      width: 100%;
      position: relative;
      bottom: 0;
  }
  
  .footer-container {
      display: flex;
      justify-content: space-between; /* Distribuye las columnas equitativamente */
      align-items: flex-start;
      max-width: 1200px;
      margin: 0 auto;
      gap: 20px;
  }
  
  .footer-column {
      flex: 1; /* Cada columna ocupa el mismo espacio */
      text-align: left;
  }
  
  .footer-title {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 10px;
  }
  
  .social-icons {
      display: flex;
      gap: 10px; /* Espacio entre los íconos */
  }
  
  .social-icons img {
      width: 24px; /* Ajusta el tamaño de los íconos */
      height: 24px;
  }
  
  .footer-column a {
      color: white;
      text-decoration: none;
  }
  
  .footer-column a:hover {
      text-decoration: underline;
  }
  </style>
  