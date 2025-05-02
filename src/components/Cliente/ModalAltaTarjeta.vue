<template>
    <div class="modal-background" @click.self="cerrar">
      <div class="modal-contenido">
        <h3>Alta de nueva tarjeta</h3>
  
        <label for="cuenta">Selecciona una cuenta:</label>
        <select v-model="cuentaSeleccionada" id="cuenta">
          <option disabled value="">-- Selecciona una cuenta --</option>
          <option v-for="cuenta in cuentas" :key="cuenta.ID" :value="cuenta.ID_cuenta">
            {{ cuenta.Tipo_cuenta }} - {{ cuenta.ID_cuenta }}
          </option>
        </select>
        
        <label for="tipo">Tipo de tarjeta:</label>
        <select v-model="tipoTarjeta" id="tipo">
          <option disabled value="">-- Selecciona tipo --</option>
          <option value="Skydebit">Débito</option>
          <option value="Skycredit">Crédito</option>
        </select>
  
        <button @click="procesarAlta" class="btn-orange">Confirmar</button>
        <button @click="cerrar" class="btn-blanco">Cancelar</button>
  
        <p v-if="mensaje" class="mensaje">{{ mensaje }}</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { generarNumero, numAleatorio } from '../../utils/formUtils';
  import { getCookie } from '../../utils/cookies';
  
  const emit = defineEmits(['cerrar']);
  
  const cuentaSeleccionada = ref("");
  const tipoTarjeta = ref("");
  const cuentas = ref([]);
  const mensaje = ref("");
  const Limite_operativo=ref("");
  
  const ID_cliente = getCookie("ID_cliente");
  
  const cerrar = () => {
    emit('cerrar');
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
  
  onMounted(() => {
    obtenerCuentas();
  });
  
  const procesarAlta = async () => {
  if (!cuentaSeleccionada.value || !tipoTarjeta.value) {
    mensaje.value = "Debe seleccionar cuenta y tipo de tarjeta.";
    return;
  }

  generarNumero("tarjetas");

  let limite = 0;

  if (tipoTarjeta.value === "Skydebit") {
    limite = 600;
  } else {
    const cuenta = cuentas.value.find(c => c.ID_cuenta === cuentaSeleccionada.value);
    let saldo = parseFloat(cuenta?.Saldo || 0);
    limite = Math.min(saldo * 1.5, 5000);
    console.log("Saldo encontrado:", saldo);
  }

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
    mensaje.value = result.mensaje || "Tarjeta creada correctamente.";
  } catch (error) {
    console.error("Error al dar de alta tarjeta:", error);
    mensaje.value = "Ocurrió un error al procesar la solicitud.";
  }
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
  