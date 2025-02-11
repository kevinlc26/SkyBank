<template>
     <HeaderEmpleado /> 

        <div><router-link to="/cuentas-empleado">Cuentas</router-link></div>
        <div><router-link to="/clientes-empleado">Clientes</router-link></div>
        <div><router-link to="/tarjetas-empleado">Tarjetas</router-link></div>
        <div><router-link to="/transferencias-empleado">Transferencias</router-link></div>
        <div><router-link to="/movimientos-empleado">Movimientos</router-link></div>
        

     <FooterEmpleado /> 
</template>

<script>
import FooterEmpleado from '../components/FooterEmpleado.vue'
import HeaderEmpleado from '../components/HeaderEmpleado.vue'

export default {
  components: {
    HeaderEmpleado,
    FooterEmpleado,
  }
}
</script>

<style>
  
</style>