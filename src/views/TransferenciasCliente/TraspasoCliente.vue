<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloTransferencias }}</h1>
      <div class="contenedorT">
        <MenuTransferencias />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloRealizarTraspaso }}</h3><br>

          <form @submit.prevent="confirmarTraspaso">
            <label for="cuentaOrigen">{{ textos.labelCuentaOrigen }}</label>
            <select v-model="transferencia.cuentaOrigen" id="cuentaOrigen" required>
              <option v-for="cuenta in cuentas" :key="cuenta.ID_cuenta" :value="cuenta.ID_cuenta">
                {{ textos.textoCuenta }} {{ cuenta.Tipo_cuenta }} ({{ textos.textoSaldo }} {{ cuenta.Saldo }}€)
              </option>
            </select><br>

            <label for="cuentaDestino">{{ textos.labelCuentaDestino }}</label>
            <select v-model="transferencia.cuentaDestino" id="cuentaDestino" required>
              <option v-for="cuenta in cuentasFiltradas" :key="cuenta.ID_cuenta" :value="cuenta.ID_cuenta">
                {{ textos.textoCuenta }} {{ cuenta.Tipo_cuenta }} ({{ textos.textoSaldo }} {{ cuenta.Saldo }}€)
              </option>
            </select><br>

            <label for="cantidad">{{ textos.labelCantidad }}</label>
            <input type="number" v-model="transferencia.cantidad" id="cantidad" required />

            <br>
            <label for="Descripcion">{{ textos.labelDescripcion }}</label>
            <input type="text" v-model="transferencia.Descripcion" id="Descripcion" required />

            <br>
            <button class="btn-orange" type="submit">{{ textos.btnRealizarTraspaso }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <FooterInicio />

  <div v-if="mostrarPopup" class="popup-overlay">
    <div class="popup">
      <p>{{ textos.mensajeConfirmacion }}</p><br>
      <button @click="realizarTraspasoConfirmado" class="btn-orange">{{ textos.btnAceptar }}</button>
      <button @click="cerrarPopup" class="btn-blanco">{{ textos.btnCancelar }}</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTransferencias from "../../components/Cliente/MenuTransferencia.vue";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const ID_cliente = getCookie("ID_cliente");
const cuentas = ref([]);
const transferencia = ref({
  cuentaOrigen: null,
  cuentaDestino: null,
  cantidad: 0,
  Descripcion: "",
});
const mostrarPopup = ref(false);

const textos = ref({
  tituloTransferencias: "TRANSFERENCIAS",
  tituloRealizarTraspaso: "Realizar traspaso entre cuentas",
  labelCuentaOrigen: "Cuenta de origen:",
  labelCuentaDestino: "Cuenta de destino:",
  labelCantidad: "Cantidad:",
  labelDescripcion: "Descripción:",
  btnRealizarTraspaso: "Realizar traspaso",
  mensajeConfirmacion: "¿Está seguro de realizar el traspaso?",
  btnAceptar: "Aceptar",
  btnCancelar: "Cancelar",
  textoCuenta: "Cuenta SkyBank",
  textoSaldo: "Saldo:",
  mensajeErrorSaldo: "La cantidad del traspaso no puede ser mayor que el saldo de la cuenta origen.",
  mensajeExito: "Traspaso realizado correctamente",
  mensajeErrorTraspaso: "Error en traspaso:",
  mensajeErrorConexion: "Error inesperado al conectar con el servidor."
});

// Obtener cuentas
const obtenerCuentas = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/cuentas?ID_cliente_cuentas=${ID_cliente}`);
    const data = await response.json();
    if (response.ok) {
      cuentas.value = data.map(cuenta => ({
        ID_cuenta: cuenta.ID_cuenta,
        Tipo_cuenta: cuenta.Tipo_cuenta,
        Saldo: parseFloat(cuenta.Saldo),
      }));
    } else {
      console.error("Error en API:", data.error);
    }
  } catch (error) {
    console.error("Error al obtener cuentas:", error);
  }
};

// Filtrar cuentas
const cuentasFiltradas = computed(() => {
  return cuentas.value.filter(cuenta => cuenta.ID_cuenta !== transferencia.value.cuentaOrigen);
});

// Cerrar popup
const cerrarPopup = () => {
  mostrarPopup.value = false;
};

// Confirmar traspaso
const confirmarTraspaso = () => {
  const cuentaOrigen = cuentas.value.find(cuenta => cuenta.ID_cuenta === transferencia.value.cuentaOrigen);

  if (cuentaOrigen && transferencia.value.cantidad > cuentaOrigen.Saldo) {
    alert(textos.value.mensajeErrorSaldo);
    return;
  }
  
  mostrarPopup.value = true;
  console.log(mostrarPopup.value);
};

// Realizar traspaso confirmado
const realizarTraspasoConfirmado = async () => {
  mostrarPopup.value = false;
  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/transferencias", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ID_cliente: ID_cliente,
        cuentaOrigen: transferencia.value.cuentaOrigen,
        cuentaDestino: transferencia.value.cuentaDestino,
        cantidad: parseFloat(transferencia.value.cantidad),
        Descripcion: transferencia.value.Descripcion
      }),
    });

    const data = await response.json();
    if (response.ok) {
      alert(textos.value.mensajeExito);
      obtenerCuentas();
    } else {
      alert(`${textos.value.mensajeErrorTraspaso} ${data.error}`);
    }
  } catch (error) {
    console.error("Error al realizar traspaso:", error);
    alert(textos.value.mensajeErrorConexion);
  }
};

// Traducir textos dinámicamente
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
  obtenerCuentas();
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});
</script>


  <style>
  
  form {
    display: flex;
    flex-direction: column;
  }
  
  .permisos{
      display: flex;
      margin-top: 1%;
  }
  .permisos input[type="checkbox"]{
    margin: 0px;
  }
  .popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.popup {
  background:#efe7da;
  padding: 30px;
  border-radius: 10px;
  text-align: center;
  max-width: 400px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.25);
}

  </style>
  
 