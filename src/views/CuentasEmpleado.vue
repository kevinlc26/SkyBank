<template>
    <HeaderEmpleado /> 
      <div class="main">
        <h1>cuentas</h1>
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
        tableHeaders: ["ID", "Titulares", "Tipo", "Saldo", "Estado", "Saldo", "Fecha", "Opciones"], // Cabecera dinámica
        tableRows: [
        { ID: "ES91 2100 0418 4502 0005 1332", Titulares: "Juan Pérez", Tipo: "online", Saldo: 5000, Estado: "Activo", Fecha: "2025-02-18" },
        { ID: "ES91 2100 0418 4502 0005 1333", Titulares: "Ana García", Tipo: "online", Saldo: 3200, Estado: "Inactivo", Fecha: "2025-02-17" },
        { ID: "ES91 2100 0418 4502 0005 1334", Titulares: "Carlos López", Tipo: "estándar", Saldo: 4500, Estado: "Activo", Fecha: "2025-02-15" },
        { ID: "ES91 2100 0418 4502 0005 1335", Titulares: "Marta Ruiz", Tipo: "estándar", Saldo: 2700, Estado: "Activo", Fecha: "2025-02-14" },
        { ID: "ES91 2100 0418 4502 0005 1336", Titulares: "Pedro Jiménez", Tipo: "online", Saldo: 5900, Estado: "Activo", Fecha: "2025-02-13" },
        { ID: "ES91 2100 0418 4502 0005 1337", Titulares: "Laura García", Tipo: "estándar", Saldo: 1200, Estado: "Inactivo", Fecha: "2025-02-12" },
        { ID: "ES91 2100 0418 4502 0005 1338", Titulares: "Sergio Fernández", Tipo: "online", Saldo: 4200, Estado: "Activo", Fecha: "2025-02-11" },
        { ID: "ES91 2100 0418 4502 0005 1339", Titulares: "Julia López", Tipo: "online", Saldo: 2500, Estado: "Activo", Fecha: "2025-02-10" },
        { ID: "ES91 2100 0418 4502 0005 1340", Titulares: "Raúl Sánchez", Tipo: "estándar", Saldo: 8000, Estado: "Activo", Fecha: "2025-02-09" },
        { ID: "ES91 2100 0418 4502 0005 1341", Titulares: "Elena Morales", Tipo: "online", Saldo: 3600, Estado: "Inactivo", Fecha: "2025-02-08" },
        { ID: "ES91 2100 0418 4502 0005 1342", Titulares: "Víctor Rodríguez", Tipo: "estándar", Saldo: 4800, Estado: "Activo", Fecha: "2025-02-07" },
        { ID: "ES91 2100 0418 4502 0005 1343", Titulares: "Beatriz Martínez", Tipo: "online", Saldo: 3100, Estado: "Inactivo", Fecha: "2025-02-06" },
        { ID: "ES91 2100 0418 4502 0005 1344", Titulares: "Javier Pérez", Tipo: "estándar", Saldo: 6500, Estado: "Activo", Fecha: "2025-02-05" }
        ],
      };
    },
};
</script>

<style>


</style>