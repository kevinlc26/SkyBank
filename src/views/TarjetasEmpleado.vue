<template>
     <HeaderEmpleado /> 
     <div class="main">
        <h1>tarjetas</h1>

        <FiltroEmpleado />
        <TablaEmpleado :headers="tableHeaders" :rows="tableRows" />

      </div> 
     <FooterEmpleado /> 
</template>

<script>
import FooterEmpleado from '../components/FooterEmpleado.vue'
import HeaderEmpleado from '../components/HeaderEmpleado.vue'
import FiltroEmpleado from '../components/FiltroEmpleado.vue'
import TablaEmpleado from '../components/TablaEmpleado.vue'


export default {
  components: {
    HeaderEmpleado,
    FooterEmpleado,
    FiltroEmpleado,
    TablaEmpleado,
  },
  data() {
      return {
        tableHeaders: ["ID", "Nombre", "NIE", "Saldo", "Estado", "Fecha", "Opciones"], // Cabecera dinámica
        tableRows: [ // Datos de las filas
          { ID: 1, Nombre: "Juan Pérez", NIE: "12345678A", Saldo: 5000, Estado: "Activo", Fecha: "2025-02-18" },
          { ID: 2, Nombre: "Ana García", NIE: "23456789B", Saldo: 3200, Estado: "Inactivo", Fecha: "2025-02-17" },
          { ID: 3, Nombre: "Carlos López", NIE: "34567890C", Saldo: 4500, Estado: "Activo", Fecha: "2025-02-15" },
        ],
      };
    },
};
</script>

<style>


</style>