<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
        <h1>TRANSFERENCIAS</h1>
      <!-- <br /> -->

      <div class="contenedorT">
        <MenuTransferencias />
        <div class="recuadro-central gris">
          <h3>Realizar transferencia</h3><br>

            <form @submit.prevent="realizarTransferencia">
            <label for="cuentaOrigen">Cuenta de origen:</label>
            <select v-model="transferencia.cuentaOrigen" id="cuentaOrigen" required>
              <option v-for="cuenta in cuentas" :key="cuenta.id" :value="cuenta.id">
                {{ cuenta.nombre }} (Saldo: {{ cuenta.saldo }})
              </option>
            </select> <br>

            <label for="cuentaDestino">Cuenta de destino:</label>
            <input type="text" v-model="transferencia.cuentaDestino" id="cuentaDestino" required />
            <br>
            <label for="cantidad">Cantidad:</label>
            <input type="number" v-model="transferencia.cantidad" id="cantidad" required />
            <br>
            <label for="Descripcion">Descripcion:</label>
            <select v-model="transferencia.Descripcion" id="Descripcion" required>
              <option v-for="Descripcion in Descripcions" :key="Descripcion" :value="Descripcion">
                {{ Descripcion }}
              </option>
            </select> <br>
            <button class="btn-orange" id="btn-transfer" type="submit">Realizar transferencia</button>
          </form>
          
        </div>
      </div>
    </div>
  </div>
  <br><br><br><br><br><br><br>
  <FooterInicio />

  <div v-if="mostrarPopup" class="popup-overlay">
    <div class="popup">
      <p>¿Está seguro de realizar la transferencia?</p>
      <button @click="realizarTransferencia">Aceptar</button>
      <button @click="cerrarPopup">Cancelar</button>
    </div>
  </div>
</template>



<script setup>
  import { ref } from "vue";
  import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
  import FooterInicio from "../../components/Cliente/FooterInicio.vue";
  import MenuTransferencias from "../../components/Cliente/MenuTransferencia.vue";

  // Reactive data
  const cuentas = ref([
    { id: 1, nombre: "Cuenta Online Skybank", saldo: 1000 },
    { id: 2, nombre: "Cuenta Ahorro Skybank", saldo: 5000 },
  ]);

  const Descripcions = ref(["Pago de servicios", "Transferencia familiar", "Otros"]);

  const transferencia = ref({
    cuentaOrigen: null,
    cuentaDestino: "",
    cantidad: 0,
    Descripcion: "",
  });

  // Method to handle transfer
  const realizarTransferencia = () => {
    // Here you can add the logic to perform the transfer
    console.log("Transferencia:", transferencia.value);
    // Logic to send data to a backend, etc.
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

</style>
