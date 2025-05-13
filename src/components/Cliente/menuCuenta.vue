<template>
  <div class="recuadro-lateral">
    <ul>
      <li><router-link to="/verCuenta">{{ textos.menuMovimientos }}</router-link></li>
      <li><router-link to="/recibosCliente">{{ textos.menuRecibos }}</router-link></li>
      <li><router-link to="/ahorroClientes">{{ textos.menuAhorro }}</router-link></li>
      <li><router-link to="/detallesCuenta">{{ textos.menuDetalleCuenta }}</router-link></li>
    </ul>
  </div>
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
  menuMovimientos: "Movimientos",
  menuRecibos: "Recibos",
  menuAhorro: "Ahorro",
  menuDetalleCuenta: "Detalle de la cuenta"
});


</script>