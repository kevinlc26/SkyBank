<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <div class="recuadro-thin verde">
        <b><p>TRANSFERENCIAS</p></b>
      </div>

      <div class="contenedorT">
        <MenuTransferencias />
        <div class="recuadro-central gris">
          <h1>Realizar transferencia</h1><br>

          <form @submit.prevent="confirmarTransferencia">
            <label for="cuentaOrigen">Cuenta de origen:</label>
            <select v-model="transferencia.cuentaOrigen" id="cuentaOrigen" required>
              <option v-for="cuenta in cuentas" :key="cuenta.id" :value="cuenta.id">
                {{ cuenta.nombre }} (Saldo: {{ cuenta.saldo }})
              </option>
            </select>

            <label for="cuentaDestino">Cuenta de destino:</label>
            <input type="text" v-model="transferencia.cuentaDestino" id="cuentaDestino" required />

            <label for="cantidad">Cantidad:</label>
            <input type="number" v-model="transferencia.cantidad" id="cantidad" required />

            <label for="Descripcion">Descripción:</label>
            <input type="text" v-model="transferencia.Descripcion" id="Descripcion" required />
            
            <button type="submit">Realizar transferencia</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <FooterInicio />

  <div v-if="mostrarPopup" class="popup-overlay">
    <div class="popup">
      <p>¿Está seguro de realizar la transferencia?</p>
      <button @click="realizarTransferencia">Aceptar</button>
      <button @click="cerrarPopup">Cancelar</button>
    </div>
  </div>
</template>

<style>
input, textarea {
    background-color: #263E33;
    border-radius: 5px;
    width: 100%;
    height: 30px;
    color: aliceblue;
    border: 1px solid black;
}
textarea {
    height: 60px;
    resize: none;
}
form {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
  font-weight: bold;
}
input, select, textarea {
  padding: 8px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 3px;
}
button {
  background-color: #FF7F00; 
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 3px;
  cursor: pointer;
}
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.popup {
  background: white;
  padding: 20px;
  border-radius: 5px;
  text-align: center;
}
.popup button {
  margin: 10px;
}
</style>

<script>
import { ref } from "vue";
import HeaderCliente from "../../components/HeaderCliente.vue";
import FooterInicio from "../../components/FooterInicio.vue";
import MenuTransferencias from "../../components/Cliente/MenuTransferencia.vue";

export default {
  components: {
    HeaderCliente,
    FooterInicio,
    MenuTransferencias,
  },
  setup() {
    const cuentas = ref([
      { id: 1, nombre: "Cuenta Online Skybank", saldo: 1000 },
      { id: 2, nombre: "Cuenta Ahorro Skybank", saldo: 5000 },
    ]);

    const transferencia = ref({
      cuentaOrigen: null,
      cuentaDestino: "",
      cantidad: 0,
      Descripcion: "",
    });

    const mostrarPopup = ref(false);

    const confirmarTransferencia = () => {
      mostrarPopup.value = true;
    };

    const realizarTransferencia = () => {
      mostrarPopup.value = false;
      alert("Operación realizada correctamente");
      console.log("Transferencia:", transferencia.value);
    };

    const cerrarPopup = () => {
      mostrarPopup.value = false;
    };

    return { cuentas, transferencia, mostrarPopup, confirmarTransferencia, realizarTransferencia, cerrarPopup };
  },
};
</script>
