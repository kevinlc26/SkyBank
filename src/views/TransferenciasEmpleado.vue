<template>
     <HeaderEmpleado /> 
        <p>Este es transferencias empleado</p>

        

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