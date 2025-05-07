<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>TRANSFERENCIAS</h1>
      <div class="contenedorT">
        <MenuTransferencias />
        <div class="recuadro-central gris">
          <h3>Realizar transferencia</h3><br>

          <form @submit.prevent="confirmarTransferencia">
            <label for="cuentaOrigen">Cuenta de origen:</label>
            <select v-model="transferencia.cuentaOrigen" id="cuentaOrigen" required>
              <option value="" disabled>Seleccione una cuenta</option>
              <option
                v-for="cuenta in cuentasFiltradas"
                :key="cuenta.ID_cuenta"
                :value="cuenta.ID_cuenta"
              >
                {{ cuenta.Tipo_cuenta }} (Saldo: {{ formatSaldo(cuenta.Saldo) }} €)
              </option>
            </select> <br>

            <label for="cuentaDestino">Cuenta de destino:</label>
            <input type="text" v-model="transferencia.cuentaDestino" id="cuentaDestino" required />
            <br>
            <label for="cantidad">Import (€):</label>
            <input type="number" v-model="transferencia.cantidad" id="cantidad" required />
            <br>
            <label for="Descripcion">Descripción:</label>
            <input type="text" v-model="transferencia.Descripcion" id="Descripcion" required /> <br>
            <button class="btn-orange" id="btn-transfer" type="submit">Realizar transferencia</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <FooterInicio />

  <div v-if="mostrarPopup" class="popup-overlay">
  <div class="popup">
    <p>¿Está seguro de realizar la transferencia?</p><br>
    <button @click="realizarTransferenciaConfirmada" class="btn-orange">Aceptar</button>
    <button @click="cerrarPopup" class="btn-blanco">Cancelar</button>
  </div>
</div>

</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTransferencias from "../../components/Cliente/MenuTransferencia.vue";
import { getCookie } from "../../utils/cookies";

const cuentas = ref([]);
const ID_cliente = getCookie("ID_cliente");

const transferencia = ref({
  cuentaOrigen: null,
  cuentaDestino: "",
  cantidad: 0,
  Descripcion: "",
});

const mostrarPopup = ref(false);

onMounted(() => {
  obtenerCuentas();
});

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

const cuentasFiltradas = computed(() => {
  return cuentas.value.filter(cuenta => cuenta.Tipo_cuenta !== 'Ahorro');
});

const formatSaldo = (saldo) => {
  return typeof saldo === 'number' ? saldo.toFixed(2) : 'Error de formato';
};

const cerrarPopup = () => {
  mostrarPopup.value = false;
};

const confirmarTransferencia = () => {
  console.log("Función confirmarTransferencia llamada");
  if (
    !transferencia.value.cuentaOrigen ||
    !transferencia.value.cuentaDestino ||
    transferencia.value.cantidad <= 0 ||
    !transferencia.value.Descripcion
  ) {
    alert("Todos los campos son obligatorios.");
    return;
  }
  mostrarPopup.value = true;
};

const realizarTransferenciaConfirmada = async () => {
  mostrarPopup.value = false;
  console.log(transferencia.value.cuentaOrigen)
  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/transferencias', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        cuenta_origen: transferencia.value.cuentaOrigen,
        cuenta_destino: transferencia.value.cuentaDestino,
        importe: transferencia.value.cantidad,
        concepto: transferencia.value.Descripcion,
        ID_cliente: ID_cliente
      }),
    });

    const data = await response.json();
    if (response.ok) {
      alert('Transferencia realizada con éxito');
      transferencia.value = {
        cuentaOrigen: null,
        cuentaDestino: "",
        cantidad: 0,
        Descripcion: "",
      };
      await obtenerCuentas();
    } else {
      console.error('Error al realizar la transferencia:', data.error);
      alert('Error: ' + data.error);
    }
  } catch (error) {
    console.error('Error de red:', error);
    alert('Error de red al realizar la transferencia');
  }
};
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