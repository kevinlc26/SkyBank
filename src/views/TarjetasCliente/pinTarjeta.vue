<template>
  <HeaderCliente />
  <div class="main">
    <h1>MI TARJETA {{ ID_tarjeta }}</h1>
    <br />

    <div class="contenedorT">
      <MenuTarjeta/>
      <div class="recuadro-central gris">
        <h3>Consultar PIN</h3><br>
        <div class="pin">
          <label for="limitediario">PIN actual: </label>
          <span>{{ pinActual }}</span>
        </div>

        <div class="pin">
          <label for="nuevoPin">Nuevo PIN: </label>
          <input
            type="number"
            name="nuevoPin"
            id="nuevoPin"
            v-model="nuevoPin"
            @input="validarPin"
          >
        </div><br>
        <p> ¡Recuerda! El nuevo PIN solo admite valores numéricos</p>
        <div class="botones">
          <button class="btn-orange" @click="actualizarPin">Confirmar</button>
        </div>
      </div>
    </div>
  </div>
  <br />
  <FooterInicio />
</template>

<script setup>
import { ref, onMounted } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTarjeta from "../../components/Cliente/menuTarjeta.vue";
import { getCookie } from "../../utils/cookies";

const ID_tarjeta = getCookie("ID_tarjeta");
const nuevoPin = ref("");
const pinActual = ref("");

const validarPin = (event) => {
  const input = event.target;
  const value = input.value;

  const numericValue = value.replace(/[^0-9]/g, '');
  if (numericValue.length > 4) {
    input.value = numericValue.slice(0, 4);
    nuevoPin.value = numericValue.slice(0, 4); 
  } else {
    nuevoPin.value = numericValue;
  }
};

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

const actualizarPin = async () => {
  if (nuevoPin.value.length !== 4) {
    alert("El PIN debe tener 4 dígitos.");
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
      alert("PIN actualizado correctamente.");
      pinActual.value = nuevoPin.value;
      nuevoPin.value = "";
    } else {
      alert("Error al actualizar el PIN: " + (data.error || "Desconocido"));
    }
  } catch (error) {
    console.error("Error de red:", error);
    alert("Error al conectar con el servidor.");
  }
};

onMounted(() => {
  obtenerPinActual();
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