<template>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-column">
        <p class="footer-title">Información de contacto</p>
        <p>At. al Cliente: 630094422 <br> Consultas@skybank.com</p>
        <div class="social-icons">
          <img src="../assets/icons/icons-fb.svg" alt="Facebook">
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

      <div class="footer-column1">
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
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  max-width: 1200px;
  width: 100%;
  padding: 0 20px;
  gap: 20px;
}

.footer-column, .footer-column1 {
  flex: 1;
  text-align: left;
}

/* Ajuste de texto */
.footer-title {
  font-weight: bold;
  font-size: 18px;
  margin-bottom: 10px;
}

.social-icons {
  display: flex;
  gap: 10px;
}

.social-icons img {
  width: 24px;
  height: 24px;
}

.footer-column a, .footer-column1 a {
  color: white;
  text-decoration: none;
}

.footer-column a:hover, .footer-column1 a:hover {
  text-decoration: underline;
}

/* RESPONSIVE DESIGN */
@media (max-width: 768px) {
  .footer-container {
    flex-direction: column;
    text-align: center;
    align-items: center;
  }

  .footer-column1 {
    text-align: center;
    margin-bottom: 15px;
  }
  .footer-column{
    display: none;
  }

  .social-icons {
    justify-content: center;
  }
}
</style>
