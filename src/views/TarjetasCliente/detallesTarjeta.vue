<template>
  <HeaderCliente />

  <div class="main">
    <h1>{{ textos.tituloTarjeta }} {{ ID_tarjeta }}</h1>
    <br/>

    <div class="contenedorT">
      <MenuTarjeta />
      <div class="recuadro-central gris">
        <h3>{{ textos.tituloDetalles }}</h3><br>
        <div class="detalles">
          <label for="text">{{ textos.labelTipoTarjeta }}</label>
          <span>{{ detalle.Tipo_tarjeta }}</span> <br> <br>

          <label for="num">{{ textos.labelNumeroTarjeta }}</label>
          <span>{{ detalle.ID_tarjeta }}</span> <br> <br>

          <label for="text">{{ textos.labelEstadoTarjeta }}</label>
          <span>{{ detalle.Estado_tarjeta }}</span> <br> <br>

          <label for="caducidad">{{ textos.labelFechaCaducidad }}</label>
          <span>{{ detalle.Fecha_caducidad }}</span> <br> <br>

          <label for="cuenta">{{ textos.labelCuentaVinculada }}</label>
          <span>{{ detalle.ID_cuenta }}</span>
        </div><br>
      </div>
    </div>
  </div>
  
  <FooterInicio />
</template>

<script setup>
import { ref, onMounted, computed, watch, inject } from "vue";
import { getCookie } from "../../utils/cookies";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTarjeta from "../../components/Cliente/menuTarjeta.vue";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const detalles = ref(null);
const detalle = computed(() => detalles.value ?? {});
const ID_tarjeta = ref(null);

const textos = ref({
  tituloTarjeta: "MI TARJETA",
  tituloDetalles: "Detalles tarjeta",
  labelTipoTarjeta: "Tipo de tarjeta:",
  labelNumeroTarjeta: "Número de tarjeta:",
  labelEstadoTarjeta: "Estado de tarjeta:",
  labelFechaCaducidad: "Fecha de caducidad:",
  labelCuentaVinculada: "Cuenta vinculada:"
});

onMounted(async () => {
  ID_tarjeta.value = getCookie("ID_tarjeta");

  if (ID_tarjeta.value) {
    obtenerDetalles(ID_tarjeta.value);
  } else {
    console.error("No se encontró ID_tarjeta en las cookies.");
  }

  await gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

const obtenerDetalles = async (id) => {
  try {
    const response = await fetch(
      `http://localhost/SkyBank/backend/public/api.php/tarjetas?ID_tarjeta=${id}`
    );
    const data = await response.json();

    if (response.ok) {
      detalles.value = data;
    } else {
      console.error("Error en respuesta del servidor:", data.error || data);
    }
  } catch (error) {
    console.error("Error en la petición fetch:", error);
  }
};
</script>


<style>
  .detalle{
      text-align: center;
  }
  h1{
      text-align: center;
      padding-left: 0%    ;
  }
</style>