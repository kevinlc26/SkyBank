<template>
  <HeaderCliente />

  <div class="main">
    <h1>{{ textos.tituloTarjeta }} {{ ID_tarjeta }}</h1>
    <br />

    <div class="contenedorT">
      <MenuTarjeta />
      <div class="recuadro-central gris">
        <h3>{{ textos.tituloModificarLimite }}</h3><br>

        <div class="limites">
          <label for="limitediario">{{ textos.labelLimiteDiario }}</label><br>
          <input type="number" v-model="limiteDiario" name="limitediario">
        </div>

        <div class="limites">
          <label for="limiteMensual">{{ textos.labelLimiteMensual }}</label><br>
          <input type="number" v-model="limiteMensual" name="limiteMensual">
        </div>

        <div class="permisos">
          <label>
            <input type="checkbox" v-model="comprasOnline"> {{ textos.labelComprasOnline }}
          </label><br>
          <label>
            <input type="checkbox" v-model="comprasInternacionales"> {{ textos.labelComprasInternacionales }}
          </label>
        </div><br>

        <div class="botones">
          <button @click.prevent="openConfirmModal" class="btn-orange">{{ textos.btnConfirmar }}</button>
        </div>
      </div>
    </div>
  </div>
  <br />

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
const limiteDiario = ref("");
const limiteMensual = ref("");
const comprasOnline = ref(false);
const comprasInternacionales = ref(false);

const textos = ref({
  tituloTarjeta: "MI TARJETA",
  tituloModificarLimite: "Modificar límite",
  labelLimiteDiario: "Límite de uso diario",
  labelLimiteMensual: "Límite de uso mensual",
  labelComprasOnline: "Compras Online",
  labelComprasInternacionales: "Compras en el extranjero",
  btnConfirmar: "Confirmar",
  mensajeExito: "Límites actualizados correctamente",
  mensajeError: "Error al actualizar los límites",
  mensajeErrorConexion: "Hubo un error al procesar la solicitud"
});

// Obtener límites de la tarjeta
onMounted(async () => {
  try {
    const url = `http://localhost/SkyBank/backend/public/api.php/tarjetas?LimiteTarjeta=${ID_tarjeta}`;
    const response = await fetch(url);
    const data = await response.json();

    if (response.ok && data.length > 0) {
      const tarjeta = data[0];
      limiteDiario.value = parseFloat(tarjeta.Limite_operativo);
      limiteMensual.value = parseFloat(tarjeta.Limite_Mensual);
      comprasOnline.value = tarjeta.Compras_online === 1;
      comprasInternacionales.value = tarjeta.Compras_internacional === 1;
    } else {
      console.error("No se encontraron datos para esta tarjeta.");
    }

    await gestionarTextos(textos, selectedLang.value);
  } catch (error) {
    console.error("Error al obtener los límites:", error);
  }
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

// Confirmar actualización de límites
const openConfirmModal = async () => {
  try {
    const url = `http://localhost/SkyBank/backend/public/api.php/tarjetas`;
    const data = {
      ID_tarjeta: ID_tarjeta,
      Limite_operativo: limiteDiario.value,
      Limite_Mensual: limiteMensual.value,
      Compras_online: comprasOnline.value,
      Compras_internacional: comprasInternacionales.value
    };

    const response = await fetch(url, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    });

    const result = await response.json();

    if (response.ok) {
      alert(result.mensaje || textos.value.mensajeExito);
    } else {
      alert(result.error || textos.value.mensajeError);
    }
  } catch (error) {
    console.error("Error:", error);
    alert(textos.value.mensajeErrorConexion);
  }
};
</script>



<style scoped>
.limites{
    width: 52%;
}

h1{
    text-align: center;
    padding-left: 0%    ;
}
.permisos{
    display: flex;
    margin-top: 2%;
}
.permisos input[type="checkbox"] {
  display: inline-block; /* Mostrar los checkboxes como elementos en línea */
  margin-right: 5px; 
  width: 18px !important;
  height: 18px !important;
}

</style>