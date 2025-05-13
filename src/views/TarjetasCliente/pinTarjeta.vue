<template>
  <HeaderCliente />

  <div class="main">
    <h1>{{ textos.tituloTarjeta }} {{ ID_tarjeta }}</h1>
    <br />

    <div class="contenedorT">
      <MenuTarjeta />
      <div class="recuadro-central gris">
        <h3>{{ textos.tituloConsultarPin }}</h3><br>
        <div class="pin">
          <label for="pinActual">{{ textos.labelPinActual }}</label>
          <span>{{ pinActual }}</span>
        </div>

        <div class="pin">
          <label for="nuevoPin">{{ textos.labelNuevoPin }}</label>
          <input
            type="number"
            name="nuevoPin"
            id="nuevoPin"
            v-model="nuevoPin"
            @input="validarPin"
          >
        </div><br>
        <p>{{ textos.mensajeRecordatorio }}</p>
        <div class="botones">
          <button class="btn-orange" @click="actualizarPin">{{ textos.btnConfirmar }}</button>
        </div>
      </div>
    </div>
  </div>
  
  <FooterInicio />
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTarjeta from "../../components/Cliente/menuTarjeta.vue";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const ID_tarjeta = getCookie("ID_tarjeta");
const nuevoPin = ref("");
const pinActual = ref("");

const textos = ref({
  tituloTarjeta: "MI TARJETA",
  tituloConsultarPin: "Consultar PIN",
  labelPinActual: "PIN actual:",
  labelNuevoPin: "Nuevo PIN:",
  mensajeRecordatorio: "¡Recuerda! El nuevo PIN solo admite valores numéricos",
  btnConfirmar: "Confirmar",
  mensajeErrorPin: "El PIN debe tener 4 dígitos.",
  mensajeExito: "PIN actualizado correctamente.",
  mensajeErrorActualizar: "Error al actualizar el PIN:",
  mensajeErrorConexion: "Error al conectar con el servidor."
});

// Validar entrada de PIN
const validarPin = (event) => {
  const input = event.target;
  const value = input.value.replace(/[^0-9]/g, "");

  if (value.length > 4) {
    input.value = value.slice(0, 4);
    nuevoPin.value = value.slice(0, 4); 
  } else {
    nuevoPin.value = value;
  }
};

// Obtener PIN actual desde la API
const obtenerPinActual = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/tarjetas?TarjetaPIN=${encodeURIComponent(ID_tarjeta)}`);
    const data = await response.json();

    if (response.ok && data.PIN) {
      pinActual.value = data.PIN;
    } else {
      console.error("No se pudo obtener el PIN:", data);
    }
  } catch (error) {
    console.error("Error de red al obtener el PIN:", error);
  }
};

// Actualizar PIN de la tarjeta
const actualizarPin = async () => {
  if (nuevoPin.value.length !== 4) {
    alert(textos.value.mensajeErrorPin);
    return;
  }

  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/tarjetas", {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ID_tarjeta: ID_tarjeta,
        nuevoPin: nuevoPin.value,
      }),
    });

    const data = await response.json();

    if (response.ok) {
      alert(textos.value.mensajeExito);
      pinActual.value = nuevoPin.value;
      nuevoPin.value = "";
    } else {
      alert(`${textos.value.mensajeErrorActualizar} ${data.error || "Desconocido"}`);
    }
  } catch (error) {
    console.error("Error de red:", error);
    alert(textos.value.mensajeErrorConexion);
  }
};

// Traducir textos dinámicamente
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
  obtenerPinActual();
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});
</script>



<style scoped>
h1{
    text-align: center;
    padding-left: 0%    ;
}
.pin {
  width: 80%;
  display: flex;
  flex-direction: row;
}
</style>