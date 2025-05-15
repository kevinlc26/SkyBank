<template>
  <HeaderCliente />

  <div class="tarjeta-container">
    <h1 class="tarjeta-titulo">{{ textos.tituloContratarCuenta }}</h1>
    <div class="tarjeta-lista">
      <div v-for="cuenta in cuentas" :key="cuenta.id" class="tarjeta-item">
        <CardTarjeta
          :tarjeta="cuenta"
          :isSelected="tipoCuenta === cuenta.id"
          @select="tipoCuenta = cuenta.id"
        />
        <button class="btn-orange" @click="confirmarGestion(cuenta.id)">
          {{ textos.btnSolicitarCuenta }}
        </button>
      </div>
    </div>

    <div v-if="mostrarPopup" class="popup">
      <div class="popup-contenido">
        <p>{{ textos.mensajeConfirmacion }} {{ tipoCuenta }}?</p>
        <button @click="procesarSolicitud" class="btn-orange">
          {{ textos.btnConfirmar }}
        </button>
        <button @click="cerrarPopup" class="btn-blanco">
          {{ textos.btnCancelar }}
        </button>
      </div>
    </div>

    <div v-if="mensaje" class="mensaje-container">
      <p class="mensaje">{{ mensaje }}</p>
    </div>
  </div>

  <FooterInicio />
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import CardTarjeta from "../../components/Cliente/CardTarjeta.vue";
import ImgCuenta from "../../assets/img-cuenta.jpg";
import ImgAhorro from "../../assets/img-ahorro.jpg";
import { getCookie } from "../../utils/cookies";
import { generarNumero, formData, numAleatorio } from "../../utils/formUtils";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const ID_cliente = getCookie("ID_cliente");
const selectedLang = inject("selectedLang");

const tipoCuenta = ref("");
const mostrarPopup = ref(false);
const mensaje = ref("");

const cuentas = ref([
  {
    id: "online",
    nombre: "Cuenta Online",
    descripcion:
      "Accede a tu dinero de forma segura y rápida, sin costos de mantenimiento.",
    imagen: ImgCuenta,
  },
  {
    id: "ahorro",
    nombre: "Cuenta Ahorro",
    descripcion:
      "Empieza a ahorrar con intereses competitivos y sin costos de mantenimiento.",
    imagen: ImgAhorro,
  },
]);

const textos = ref({
  tituloContratarCuenta: "Contrata una nueva Cuenta",
  btnSolicitarCuenta: "Solicitar Cuenta",
  mensajeConfirmacion: "¿Confirmas la solicitud de la cuenta",
  btnConfirmar: "Confirmar",
  btnCancelar: "Cancelar",
  mensajeCuentaCreada: "Cuenta creada con éxito.",
  mensajeErrorCuenta: "Error al crear la cuenta.",
});

// Función para gestionar la traducción dinámica
const actualizarTextos = async () => {
  await gestionarTextos(textos, selectedLang.value);
};

const confirmarGestion = (id) => {
  tipoCuenta.value = id;
  mostrarPopup.value = true;
};

const cerrarPopup = () => {
  mostrarPopup.value = false;
};

const procesarSolicitud = async () => {
  generarNumero("cuentas");

  formData.value = {
    ID: numAleatorio.value,
    Tipo_cuenta: tipoCuenta.value,
    ID_cliente: ID_cliente,
  };

  try {
    const response = await fetch(
      "http://localhost/SkyBank/backend/public/api.php/cuentas",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData.value),
      }
    );

    const result = await response.json();
    mensaje.value = result.mensaje || textos.value.mensajeCuentaCreada;
  } catch (error) {
    console.error("Error al crear la cuenta:", error);
    mensaje.value = textos.value.mensajeErrorCuenta;
  }

  mostrarPopup.value = false;
};

// Traducir textos dinámicamente según el idioma seleccionado
onMounted(async () => {
  await actualizarTextos();
});

watch(selectedLang, async () => {
  await actualizarTextos();
});
</script>




  
<style scoped>
  .tarjeta-container {
    text-align: center;
    padding: 20px;

  }
  
  .tarjeta-titulo {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #780000;
  }
  
  .tarjeta-lista {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
    background-color: #9dac7b;
    padding: 20px;
    border-radius: 10px;
  }
  
  .tarjeta-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
  }
  
.popup {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-contenido {
  background: #efe7da;
  padding: 20px 30px;
  border-radius: 10px;
  text-align: center;
  border: 1px solid #780000;
  box-shadow: 0 8px 16px rgba(0,0,0,0.3);
  max-width: 90%;
  width: 400px; /* Opcional: fija un ancho para que esté bien centrado */
}

  
  .popup-contenido button {
    margin: 10px;
    padding: 10px;
    border: none;
    cursor: pointer;
  }
  
  .mensaje-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }
  
  .mensaje {
    font-size: 1.2rem;
    color: #780000;
    background-color: #9dac7b;
    padding: 15px;
    border-radius: 8px;
    width: 50%;
    text-align: center;
    font-weight: bold;
  }
</style>
  