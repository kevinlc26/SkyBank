<template>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-column">
        <p class="footer-title">{{ textos.tituloContacto }}</p>
        <p>{{ textos.atencionCliente }} <br> {{ textos.emailContacto }}</p> <br>
        <div class="social-icons">
          <img src="../../assets/icons/icons-fb.svg" alt="Facebook">
          <img src="../../assets/icons/icons-x.svg" alt="Twitter">
          <img src="../../assets/icons/icons-ig.svg" alt="Instagram">
        </div>
      </div>

      <div class="footer-column">
        <p class="footer-title">{{ textos.tituloLegal }}</p>
        <p>
          <a href="#">{{ textos.terminosCondiciones }}</a> <br>
          <a href="#">{{ textos.politicaPrivacidad }}</a> <br>
          <a href="#">{{ textos.seguridadBancaria }}</a>
        </p>
      </div>

      <div class="footer-column1">
        <p>{{ textos.derechosReservados }}</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import { gestionarTextos } from "../../utils/traductor.js";

// TRADUCCIÓN
const selectedLang = inject("selectedLang");

onMounted(() => {
  gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});


const textos = ref({
  tituloContacto: "Información de contacto",
  atencionCliente: "At. al Cliente: 630094422",
  emailContacto: "Consultas@skybank.com",
  tituloLegal: "Información Legal",
  terminosCondiciones: "Términos y condiciones de uso",
  politicaPrivacidad: "Política de Privacidad",
  seguridadBancaria: "Información sobre seguridad bancaria",
  derechosReservados: "© 2025 SkyBank. Todos los derechos reservados."
});
</script>

<style scoped>
.footer {
  background-color: #263E33;
  color: white;
  padding: 20px;
  width: 97,5%;
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
