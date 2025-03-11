<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <div class="recuadro-thin verde">
        <b><p>TRANSFERENCIAS</p></b>
      </div>
      <!-- <br /> -->

      <div class="contenedorT">
        <MenuTransferencias />
        <div class="recuadro-central gris">
          <h1>Realizar transferencia</h1><br>

          <form @submit.prevent="realizarTransferencia">
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

            <label for="Descripcion">Descripcion:</label>
            <select v-model="transferencia.Descripcion" id="Descripcion" required>
              <option v-for="Descripcion in Descripcions" :key="Descripcion" :value="Descripcion">
                {{ Descripcion }}
              </option>
            </select>
            <button type="submit">Realizar transferencia</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <FooterInicio />
</template>

<style>
input{
    background-color: #263E33;
    border-radius: 5px;
    width: 100%;
    height: 30px;
    color: aliceblue;
    border: 1px solid black;
}
/* Estilos para el formulario */
form {
  display: flex;
  flex-direction: column;
}

label {
  margin-bottom: 5px;
  font-weight: bold;
}
input, select {
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
.permisos{
    display: flex;
    margin-top: 1%;
}
.permisos input[type="checkbox"]{
  margin: 0px;
}

</style>

<script>
import { ref } from "vue";
import HeaderCliente from "../../components/HeaderCliente.vue";
import FooterInicio from "../../components/FooterInicio.vue";
import MenuTransferencias from "../../components/MenuTransferencia.vue";

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

    const Descripcions = ref(["Pago de servicios", "Transferencia familiar", "Otros"]);

    const transferencia = ref({
      cuentaOrigen: null,
      cuentaDestino: "",
      cantidad: 0,
      Descripcion: "",
    });

    const realizarTransferencia = () => {
      // Aquí puedes agregar la lógica para realizar la transferencia
      console.log("Transferencia:", transferencia.value);
      // Lógica para enviar los datos a un backend, etc.
    };

    return { cuentas, Descripcions, transferencia, realizarTransferencia };
  },
};
</script>