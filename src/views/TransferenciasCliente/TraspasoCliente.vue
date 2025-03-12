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
                <option v-for="cuenta in cuentas" :key="cuenta.id" :value="cuenta.id">
                  {{ cuenta.nombre }} (Saldo: {{ cuenta.saldo }})
                </option>
              </select> <br>
  
              <label for="cuentaDestino">Cuenta de destino:</label>
              <select v-model="transferencia.cuentaDestino" id="cuentaDestino" required>
                <option v-for="cuenta in cuentasFiltradas" :key="cuenta.id" :value="cuenta.id">
                  {{ cuenta.nombre }} (Saldo: {{ cuenta.saldo }})
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
    <FooterInicio />
  </template>

  
   <script setup>
   import { ref, computed } from "vue";
   import HeaderCliente from "../../components/HeaderCliente.vue";
   import FooterInicio from "../../components/FooterInicio.vue";
   import MenuTransferencias from "../../components/MenuTransferencia.vue";
 
   // Reactive variables
   const cuentas = ref([
     { id: 1, nombre: "Cuenta Online Skybank", saldo: 1000 },
     { id: 2, nombre: "Cuenta Ahorro Skybank", saldo: 5000 },
   ]);
 
   const transferencia = ref({
     cuentaOrigen: null,
     cuentaDestino: null,
     cantidad: 0,
     Descripcion: "",
   });
 
   // Computed property to filter cuentas based on cuentaOrigen
   const cuentasFiltradas = computed(() => {
     return cuentas.value.filter(cuenta => cuenta.id !== transferencia.value.cuentaOrigen);
   });
 
   // Method to perform the transfer
   const realizarTransferencia = () => {
     console.log("Transferencia:", transferencia.value);
   };
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
  
 