<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloTransferencias }}</h1>
      <div class="contenedorT">
        <MenuTransferencias />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloRealizarTransferencia }}</h3><br>

          <form @submit.prevent="confirmarTransferencia">
            <label for="cuentaOrigen">{{ textos.labelCuentaOrigen }}</label>
            <select v-model="transferencia.cuentaOrigen" id="cuentaOrigen" required>
              <option value="" disabled>{{ textos.opcionSeleccioneCuenta }}</option>
              <option
                v-for="cuenta in cuentasFiltradas"
                :key="cuenta.ID_cuenta"
                :value="cuenta.ID_cuenta"
              >
                {{ cuenta.Tipo_cuenta }} ({{ textos.textoSaldo }} {{ formatSaldo(cuenta.Saldo) }} €)
              </option>
            </select> <br>

            <label for="cuentaDestino">{{ textos.labelCuentaDestino }}</label>
            <input type="text" v-model="transferencia.cuentaDestino" id="cuentaDestino" required />
            <br>
            <label for="cantidad">{{ textos.labelImporte }}</label>
            <input type="number" v-model="transferencia.cantidad" id="cantidad" required />
            <br>
            <label for="Descripcion">{{ textos.labelDescripcion }}</label>
            <input type="text" v-model="transferencia.Descripcion" id="Descripcion" required /> <br>
            <button class="btn-orange" id="btn-transfer" type="submit">{{ textos.btnRealizarTransferencia }}</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <FooterInicio />

  <div v-if="mostrarPopup" class="popup-overlay">
    <div class="popup">
      <p>{{ textos.mensajeConfirmacion }}</p><br>
      <button @click="realizarTransferenciaConfirmada" class="btn-orange">{{ textos.btnAceptar }}</button>
      <button @click="cerrarPopup" class="btn-blanco">{{ textos.btnCancelar }}</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch, inject } from "vue";
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
  cuentaDestino: "",
  cantidad: 0,
  Descripcion: "",
});
const mostrarPopup = ref(false);

const textos = ref({
  tituloTransferencias: "TRANSFERENCIAS",
  tituloRealizarTransferencia: "Realizar transferencia",
  labelCuentaOrigen: "Cuenta de origen:",
  opcionSeleccioneCuenta: "Seleccione una cuenta",
  textoSaldo: "Saldo:",
  labelCuentaDestino: "Cuenta de destino:",
  labelImporte: "Importe (€):",
  labelDescripcion: "Descripción:",
  btnRealizarTransferencia: "Realizar transferencia",
  mensajeConfirmacion: "¿Está seguro de realizar la transferencia?",
  btnAceptar: "Aceptar",
  btnCancelar: "Cancelar",
  mensajeErrorCampos: "Todos los campos son obligatorios.",
  mensajeExito: "Transferencia realizada con éxito",
  mensajeErrorTransferencia: "Error al realizar la transferencia:",
  mensajeErrorConexion: "Error de red al realizar la transferencia"
});

// Obtener cuentas
const obtenerCuentas = async () => {
  if (!ID_cliente) {
    console.error("ID de cliente no encontrado.");
    return;
  }
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
  return cuentas.value.filter(cuenta => cuenta.Tipo_cuenta !== "Ahorro");
});

// Formatear saldo
const formatSaldo = (saldo) => {
  return typeof saldo === "number" ? saldo.toFixed(2) : "Error de formato";
};

// Cerrar popup
const cerrarPopup = () => {
  mostrarPopup.value = false;
};

// Confirmar transferencia
const confirmarTransferencia = () => {
  if (
    !transferencia.value.cuentaOrigen ||
    !transferencia.value.cuentaDestino ||
    transferencia.value.cantidad <= 0 ||
    !transferencia.value.Descripcion
  ) {
    alert(textos.value.mensajeErrorCampos);
    return;
  }
  mostrarPopup.value = true;
};

// Realizar transferencia confirmada
const realizarTransferenciaConfirmada = async () => {
  mostrarPopup.value = false;
  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/transferencias", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        cuenta_origen: transferencia.value.cuentaOrigen,
        cuenta_destino: transferencia.value.cuentaDestino,
        importe: transferencia.value.cantidad,
        concepto: transferencia.value.Descripcion,
        ID_cliente: ID_cliente,
      }),
    });

    const data = await response.json();
    if (response.ok) {
      alert(textos.value.mensajeExito);
      transferencia.value = {
        cuentaOrigen: null,
        cuentaDestino: "",
        cantidad: 0,
        Descripcion: "",
      };
      await obtenerCuentas();
    } else {
      alert(`${textos.value.mensajeErrorTransferencia} ${data.error}`);
    }
  } catch (error) {
    console.error("Error de red:", error);
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


<style scoped>

.cuenta-inicio {
    background-color: #eee9e0;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .movimientos {
    margin-top: 30px;
    padding: 20px;
    background-color:#9DAC7B;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }


.cuenta-inicio label {
  font-weight: normal;
}

#btn-transfer {
  width: 300px;
}
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.popup {
  background: #efe7da;
  padding: 20px;
  border-radius: 8px;
  border: 1px solid #780000;
}

</style>