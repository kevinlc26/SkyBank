<template>
  <HeaderCliente />

  <div class="tarjeta-container">
    <h1 class="tarjeta-titulo">{{ textos.tituloContratarTarjeta }}</h1>
    <div class="tarjeta-lista">
      <div v-for="tarjeta in tarjetas" :key="tarjeta.id" class="tarjeta-item">
        <CardTarjeta
          :tarjeta="tarjeta"
          :isSelected="tipoTarjeta === tarjeta.id"
          @select="tipoTarjeta = tarjeta.id"
        />
        <button class="boton-enviar" @click="abrirModal(tarjeta.id)">
          {{ textos.btnSolicitarTarjeta }}
        </button>
      </div>
    </div>

    <ModalAltaCliente
      v-if="mostrarModal"
      :key="tipoTarjeta"
      @cerrar="cerrarModal"
    />

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
import ModalAltaCliente from "../../components/Cliente/ModalAltaTarjeta.vue";
import tarjetaCredito from "../../assets/tarjetaCredito.jpg";
import tarjetaDebito from "../../assets/tarjetaDebito.jpg";
import { gestionarTextos } from "../../utils/traductor.js"; 

const selectedLang = inject("selectedLang");

const tipoTarjeta = ref("");
const mostrarModal = ref(false);
const mensaje = ref("");

const tarjetas = ref([
  {
    id: "debito",
    nombre: "Tarjeta de Débito SkyBank",
    descripcion: "Accede a tu dinero de forma segura y rápida, sin costos de mantenimiento.",
    imagen: tarjetaDebito
  },
  {
    id: "credito",
    nombre: "Tarjeta de Crédito Skycredit",
    descripcion: "Obtén financiamiento y beneficios exclusivos con nuestra tarjeta de crédito.",
    imagen: tarjetaCredito
  }
]);

const textos = ref({
  tituloContratarTarjeta: "Contrata una nueva tarjeta",
  btnSolicitarTarjeta: "Solicitar Tarjeta",
  mensajeError: "Ocurrió un error al procesar la solicitud."
});

const abrirModal = (tipo) => {
  tipoTarjeta.value = tipo;
  mostrarModal.value = true;
};

const cerrarModal = () => {
  mostrarModal.value = false;
};


onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

</script>


<style scoped>
.tarjeta-container {
  text-align: center;
  padding: 20px;
  background-color: #efe7da;
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

.boton-enviar {
  background-color: #d87c1a;
  color: white;
  border: none;
  cursor: pointer;
  padding: 10px 15px;
  font-size: 16px;
  border-radius: 5px;
  transition: background-color 0.3s;
  margin-top: 10px;
}

.boton-enviar:hover {
  background-color: #b35e14;
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
  background: white;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
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
