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
        tableHeaders: ["Número", "Número de cuenta", "Titular", "Tipo", "Estado", "Fecha de caducidad", "Límite operativo", "Opciones"], // Cabecera dinámica
        tableRows: [ 
        { Número: "1234 5678 9012 3456", "Número de cuenta": "ES91 2100 0418 4502 0005 1332", Titular: "X1234567Y", Tipo: "Visa", Estado: "Activo", "Fecha de caducidad": "12/26", "Límite operativo": 5000 },
        { Número: "1234 5678 9012 3457", "Número de cuenta": "ES91 2100 0418 4502 0005 1333", Titular: "X1234568Y", Tipo: "MasterCard", Estado: "Inactivo", "Fecha de caducidad": "11/25", "Límite operativo": 3200 },
        { Número: "1234 5678 9012 3458", "Número de cuenta": "ES91 2100 0418 4502 0005 1334", Titular: "X1234569Y", Tipo: "Visa", Estado: "Activo", "Fecha de caducidad": "08/25", "Límite operativo": 4500 },
        { Número: "1234 5678 9012 3459", "Número de cuenta": "ES91 2100 0418 4502 0005 1335", Titular: "X1234570Y", Tipo: "MasterCard", Estado: "Activo", "Fecha de caducidad": "05/24", "Límite operativo": 3000 },
        { Número: "1234 5678 9012 3460", "Número de cuenta": "ES91 2100 0418 4502 0005 1336", Titular: "X1234571Y", Tipo: "Visa", Estado: "Inactivo", "Fecha de caducidad": "03/26", "Límite operativo": 6000 },
        { Número: "1234 5678 9012 3461", "Número de cuenta": "ES91 2100 0418 4502 0005 1337", Titular: "X1234572Y", Tipo: "MasterCard", Estado: "Activo", "Fecha de caducidad": "01/25", "Límite operativo": 4200 },
        { Número: "1234 5678 9012 3462", "Número de cuenta": "ES91 2100 0418 4502 0005 1338", Titular: "X1234573Y", Tipo: "Visa", Estado: "Activo", "Fecha de caducidad": "02/24", "Límite operativo": 3800 },
        { Número: "1234 5678 9012 3463", "Número de cuenta": "ES91 2100 0418 4502 0005 1339", Titular: "X1234574Y", Tipo: "MasterCard", Estado: "Inactivo", "Fecha de caducidad": "09/25", "Límite operativo": 2000 },
        { Número: "1234 5678 9012 3464", "Número de cuenta": "ES91 2100 0418 4502 0005 1340", Titular: "X1234575Y", Tipo: "Visa", Estado: "Activo", "Fecha de caducidad": "07/23", "Límite operativo": 2700 },
        { Número: "1234 5678 9012 3465", "Número de cuenta": "ES91 2100 0418 4502 0005 1341", Titular: "X1234576Y", Tipo: "MasterCard", Estado: "Activo", "Fecha de caducidad": "06/24", "Límite operativo": 5000 }
        ],
      };
    },
};
</script>

<style>


</style>