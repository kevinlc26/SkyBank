<template>
    <HeaderCliente/>
    <div class="main">
      <div class="contenedorGrande">
        <h1>Cuenta Online Skybank</h1>
        <br/>
        
        <div class="contenedorT">
          <menuCuenta/>
          <div class="recuadro-central gris">
            <h3>Ahorro de la cuenta</h3>
            <button class="btn-orange"><router-link to="/traspaso">Realizar aportación</router-link></button>
            <table class="tabla">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Concepto</th>
                  <th>Importe</th>
                  <th>Saldo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(ahorro, index) in ahorros" :key="index" >
                  <td>{{ ahorro.fecha }}</td>
                  <td>{{ ahorro.Concepto }}</td>
                  <td>{{ ahorro.importe }}</td>
                  <td>{{ ahorro.saldo }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <FooterInicio/>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import HeaderCliente from '../../components/Cliente/HeaderCliente.vue';
  import FooterInicio from '../../components/Cliente/FooterInicio.vue';
  import menuCuenta from '../../components/Cliente/menuCuenta.vue';
  import { RouterLink } from 'vue-router';

  // Reactive variables
  const cuentas = ref([
    "Cuenta Online Skybank",
    "Cuenta Ahorro Skybank"
  ]);

  const ahorros = ref([
    { fecha: "01/24", Concepto: "ahorro Mensual", importe: "80,00€", saldo: "120€" },
    { fecha: "02/25", Concepto: "ahorro Mensual", importe: "50,00€", saldo: "170€" },
    { fecha: "01/24", Concepto: "ahorro Mensual", importe: "80,00€", saldo: "250€" },
    { fecha: "01/24", Concepto: "ahorro Mensual", importe: "80,00€", saldo: "330€" },
    { fecha: "01/24", Concepto: "ahorro Mensual", importe: "80,00€", saldo: "410€" },
    { fecha: "01/24", Concepto: "ahorro Mensual", importe: "80,00€", saldo: "490€" }
  ]);
</script>


  
  <style scoped>
  .tabla {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  }
  
  .tabla th, .tabla td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
  }
  
  .tabla th {
  background-color: #9DAC7B;
  color: white;
  }
  
  .tabla tbody tr:nth-child(even) {
  background-color: #e4ded5;
  }
  
  .tabla tbody tr:nth-child(odd) {
  background-color: #eee9e0;
  }
  
  .tabla tbody tr:hover {
  background-color: #f1f1f1;
  }
  </style>