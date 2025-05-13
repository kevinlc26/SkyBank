<template>
  <HeaderCliente />
  
  <div class="main">
    <h1>{{ textos.tituloServicio }}</h1> <br><br>

    <!-- Sección de Cuentas -->
    <p class="slogan">{{ textos.descripcionCuentas }}</p>
    
    <div class="tipo-servicio">
      <div class="opciones">
        <div class="opcion">
          <h4>{{ textos.tituloCuentaOnline }}</h4>
          <p>{{ textos.descripcionCuentaOnline }}</p>
        </div>
        <div class="opcion">
          <h4>{{ textos.tituloCuentaAhorro }}</h4>
          <p>{{ textos.descripcionCuentaAhorro }}</p>
        </div>
      </div>
      <br>
      <button class="btn-orange">
        <router-link to="/nuevaCuenta">{{ textos.btnContratarCuenta }}</router-link>
      </button>
    </div>

    <br><br>
    <!-- Sección de Tarjetas -->
    <p class="slogan">{{ textos.descripcionTarjetas }}</p>
    <div class="tipo-servicio">
      <div class="opciones">
        <div class="opcion">
          <h4>{{ textos.tituloTarjetaDebito }}</h4>
          <p>{{ textos.descripcionTarjetaDebito }}</p>
        </div>
        <div class="opcion">
          <h4>{{ textos.tituloTarjetaCredito }}</h4>
          <p>{{ textos.descripcionTarjetaCredito }}</p>
        </div>
      </div>
      <br>
      <button class="btn-orange">
        <router-link to="/nuevaTarjeta">{{ textos.btnSolicitarTarjeta }}</router-link>
      </button>
    </div>
  </div>

  <FooterInicio /> 
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import { gestionarTextos } from "../../utils/traductor.js";

const selectedLang = inject("selectedLang");

const textos = ref({
  tituloServicio: "Contrata un nuevo servicio",
  
  descripcionCuentas: "Elige la cuenta que mejor se adapte a tus necesidades y empieza a disfrutar de la banca digital con SkyBank.",
  tituloCuentaOnline: "Cuenta Online SkyBank",
  descripcionCuentaOnline: "Accede a tu dinero en cualquier momento sin comisiones ocultas.",
  tituloCuentaAhorro: "Cuenta de Ahorro",
  descripcionCuentaAhorro: "Haz crecer tu dinero con tasas de interés competitivas.",
  btnContratarCuenta: "Contratar",

  descripcionTarjetas: "Escoge la tarjeta que más te convenga y aprovecha sus beneficios exclusivos.",
  tituloTarjetaDebito: "Tarjeta de Débito SkyBank",
  descripcionTarjetaDebito: "Compra de forma segura y accede a tu dinero en cualquier parte del mundo.",
  tituloTarjetaCredito: "Tarjeta de Crédito SkyBank",
  descripcionTarjetaCredito: "Disfruta de financiamiento flexible y recompensas en cada compra.",
  btnSolicitarTarjeta: "Solicitar"
});

// Traducir textos dinámicamente según el idioma seleccionado
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});
</script>

<style scoped>
  
  .tipo-servicio {
    background-color: #9DAC7B;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    text-align: center;
  }

  .opciones {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
  }
  
  .opcion {
    background-color: #eee9e0;
    padding: 15px;
    border-radius: 8px;
    width: 300px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  
  .opcion h4 {
    color: #780000;
  }

  .slogan {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  a{
    text-decoration: none;
  }
</style>
  