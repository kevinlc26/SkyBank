<template>
  <div class="modal-background" @click.self="cerrar">
    <div class="modal-contenido">
      <h3>{{ textos.tituloAltaTarjeta }}</h3>

      <label for="cuenta">{{ textos.labelSeleccionarCuenta }}</label>
      <select v-model="cuentaSeleccionada" id="cuenta">
        <option disabled value="">{{ textos.opcionSeleccionaCuenta }}</option>
        <option v-for="cuenta in cuentas" :key="cuenta.ID" :value="cuenta.ID_cuenta">
          {{ textos.textoCuenta }} {{ cuenta.Tipo_cuenta }} - {{ cuenta.ID_cuenta }}
        </option>
      </select>

      <label for="tipo">{{ textos.labelTipoTarjeta }}</label>
      <select v-model="tipoTarjeta" id="tipo">
        <option disabled value="">{{ textos.opcionSeleccionaTipo }}</option>
        <option value="Skydebit">{{ textos.opcionDebito }}</option>
        <option value="Skycredit">{{ textos.opcionCredito }}</option>
      </select>

      <button @click="procesarAlta" class="btn-orange">{{ textos.btnConfirmar }}</button>
      <button @click="cerrar" class="btn-blanco">{{ textos.btnCancelar }}</button>

      <p v-if="mensaje" class="mensaje">{{ mensaje }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import { generarNumero, numAleatorio } from "../../utils/formUtils";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const emit = defineEmits(["cerrar"]);
const selectedLang = inject("selectedLang");

const cuentaSeleccionada = ref("");
const tipoTarjeta = ref("");
const cuentas = ref([]);
const mensaje = ref("");

const ID_cliente = getCookie("ID_cliente");

const textos = ref({
  tituloAltaTarjeta: "Alta de nueva tarjeta",
  labelSeleccionarCuenta: "Selecciona una cuenta:",
  opcionSeleccionaCuenta: "-- Selecciona una cuenta --",
  textoCuenta: "Cuenta SkyBank",
  labelTipoTarjeta: "Tipo de tarjeta:",
  opcionSeleccionaTipo: "-- Selecciona tipo --",
  opcionDebito: "Débito",
  opcionCredito: "Crédito",
  btnConfirmar: "Confirmar",
  btnCancelar: "Cancelar",
  mensajeErrorSeleccion: "Debe seleccionar cuenta y tipo de tarjeta.",
  mensajeErrorAlta: "Ocurrió un error al procesar la solicitud.",
  mensajeExitoAlta: "Tarjeta creada correctamente."
});

const cerrar = () => {
  emit("cerrar");
};

const obtenerCuentas = async () => {
  try {
    const res = await fetch(`http://localhost/SkyBank/backend/public/api.php/cuentas?cli_ID_Cuentas=${ID_cliente}`);
    const data = await res.json();
    cuentas.value = data;
  } catch (err) {
    console.error("Error al obtener cuentas", err);
  }
};

onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
  obtenerCuentas();
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

const procesarAlta = async () => {
  if (!cuentaSeleccionada.value || !tipoTarjeta.value) {
    mensaje.value = textos.value.mensajeErrorSeleccion;
    return;
  }

  generarNumero("tarjetas");

  let limite = tipoTarjeta.value === "Skydebit" ? 600 : calcularLimiteCredito();

  const payload = {
    ID: numAleatorio.value,
    Tipo_tarjeta: tipoTarjeta.value,
    ID_cuenta: cuentaSeleccionada.value,
    Limite_operativo: limite
  };

  try {
    const res = await fetch("http://localhost/SkyBank/backend/public/api.php/tarjetas", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const result = await res.json();
    mensaje.value = result.mensaje || textos.value.mensajeExitoAlta;
  } catch (error) {
    console.error("Error al dar de alta tarjeta:", error);
    mensaje.value = textos.value.mensajeErrorAlta;
  }
};

const calcularLimiteCredito = () => {
  const cuenta = cuentas.value.find(c => c.ID_cuenta === cuentaSeleccionada.value);
  let saldo = parseFloat(cuenta?.Saldo || 0);
  return Math.min(saldo * 1.5, 5000);
};
</script>

  
<style scoped>
  .modal-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }
  
  .modal-contenido {
    background: #efe7da;
    padding: 25px;
    border-radius: 12px;
    width: 350px;
    text-align: center;
    box-shadow: 0 0 10px #00000055;
  }
  
  .modal-contenido select,
  .modal-contenido button {
    margin-top: 15px;
    padding: 10px;
    width: 100%;
  }
  
  .mensaje {
    margin-top: 15px;
    color: #780000;
    font-weight: bold;
  }
</style>
  