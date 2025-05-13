<template>
  <div class="recuadro-lateral">
    <ul>
      <li><router-link to="/perfil-cliente">{{ textos.menuDatosPersonales }}</router-link></li>
      <li><router-link to="/contratos-cliente">{{ textos.menuContratos }}</router-link></li>
      <li><router-link to="/updateDNI">{{ textos.menuActualizarDni }}</router-link></li>
      <li><router-link to="/bajaCliente">{{ textos.menuBajaCliente }}</router-link></li>
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
  menuDatosPersonales: "Datos personales",
  menuContratos: "Contratos y comisiones",
  menuActualizarDni: "Actualizar DNI",
  menuBajaCliente: "Darse de baja"
});

</script>