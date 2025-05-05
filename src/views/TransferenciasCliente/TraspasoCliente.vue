<template>
    <HeaderCliente />
    <div class="main">
      <div class="contenedorGrande">
          <h1>TRANSFERENCIAS</h1>
        <div class="contenedorT">
          <MenuTransferencias />
          <div class="recuadro-central gris">
            <h3>Realizar traspaso entre cuentas</h3><br>
  
            <form @submit.prevent="realizarTransferencia">
              <label for="cuentaOrigen">Cuenta de origen:</label>
              <select v-model="transferencia.cuentaOrigen" id="cuentaOrigen" required>
                <option v-for="cuenta in cuentas" :key="cuenta.ID_cuenta" :value="cuenta.ID_cuenta">
                  Cuenta SkyBank {{ cuenta.Tipo_cuenta }} (Saldo: {{ cuenta.Saldo }}€)
                </option>
              </select> <br>
  
              <label for="cuentaDestino">Cuenta de destino:</label>
              <select v-model="transferencia.cuentaDestino" id="cuentaDestino" required>
                <option v-for="cuenta in cuentasFiltradas" :key="cuenta.ID_cuenta" :value="cuenta.ID_cuenta">
                 Cuenta SkyBank {{ cuenta.Tipo_cuenta }} (Saldo: {{ cuenta.Saldo }}€)
                </option>
              </select> <br>
  
              <label for="cantidad">Cantidad:</label>
              <input type="number" v-model="transferencia.cantidad" id="cantidad" required />
  
            <br>
              <label for="Descripcion">Descripción:</label>
              <input type="text" v-model="transferencia.Descripcion" id="Descripcion" required />
            
              <br>
              <button class="btn-orange" type="submit">Realizar traspaso</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <br><br><br><br><br><br><br>
    <FooterInicio />
  </template>

  
<script setup>
import { ref, computed, onMounted } from "vue";
import { getCookie } from "../../utils/cookies";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTransferencias from "../../components/Cliente/MenuTransferencia.vue";

const cuentas = ref([]);
const transferencia = ref({
  cuentaOrigen: null,
  cuentaDestino: null,
  cantidad: 0,
  Descripcion: "",
});

const cuentasFiltradas = computed(() => {
  return cuentas.value.filter(cuenta => cuenta.ID_cuenta !== transferencia.value.cuentaOrigen);
});

const realizarTransferencia = async () => {
  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/transferencias", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ID_cliente: idCliente,
        cuentaOrigen: transferencia.value.cuentaOrigen,
        cuentaDestino: transferencia.value.cuentaDestino,
        cantidad: parseFloat(transferencia.value.cantidad),
        Descripcion: transferencia.value.Descripcion
      }),
    });

    const data = await response.json();

    if (response.ok) {
      alert("Traspaso realizado correctamente");
      obtenerCuentas();
    } else {
      alert("Error en traspaso: " + data.error);
    }
  } catch (error) {
    console.error("Error al realizar traspaso:", error);
    alert("Error inesperado al conectar con el servidor.");
  }
};


const idCliente = getCookie("ID_cliente");
console.log("ID del cliente:", idCliente);


const obtenerCuentas = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/cuentas?ID_cliente_cuentas=${idCliente}`);
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

onMounted(() => {
  obtenerCuentas();
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
  
  </style>
  
 