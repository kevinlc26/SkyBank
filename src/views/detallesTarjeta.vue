<template>
    <HeaderCliente />
    <div class="main">
      <div class="recuadro-thin verde">
        <b><p>MI TARJETA 234******55</p></b>
      </div>
      <br />
      
      <div class="contenedorT">
        <MenuTarjeta/>
        <div class="recuadro-central gris">
          <h1>Tarjeta 234******55</h1><br>
          <div class="limites">
            <b><p class="detalle">Detalles tarjeta</p></b><br>
            <hr><br>
            <p>Numero de tarjeta: 23456478955</p><br>
            <p>Fecha de caducidad: 07/28</p><br>
            <p>CVV: 369</p><br>
            <p>Cuenta vinculada: 21316354544635132131223</p>
          </div><br>
        </div>
      </div>
    </div>
    <br />
    <FooterInicio />
</template>
<style>
.detalle{
    text-align: center;
}
</style>
<script>
    import { ref } from "vue";
    import HeaderCliente from "../components/HeaderCliente.vue";
    import FooterInicio from "../components/FooterInicio.vue";
    import MenuTarjeta from "../components/menuTarjeta.vue";
    
    export default {
      components: {
        HeaderCliente,
        FooterInicio,
        MenuTarjeta
      },
      setup() {
        const motivos = ref([]);
        return { motivos };
      },
    };
</script>