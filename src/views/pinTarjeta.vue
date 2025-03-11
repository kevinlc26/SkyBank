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
          <h1>Tarjeta: 234******55</h1><br>
          <div class="limites">
            <label for="limitediario">Mostrar el PIN</label><br>
            <input type="number" name="limitediario" id="" value="5555" readonly>
          </div>
          <div class="limites">
            <label for="nuevoPin">Introduce un nuevo pin para modificarlo</label>
            <input type="number" name="nuevoPin" id="">
          </div><br>
          
          <p>Confirmar cambios</p>
          <!-- Botones -->
          <div class="botones">
            <button class="btn-orange">Aceptar</button>
            <button class="btn-blanco">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <br />
    <FooterInicio />
  </template>
<style>
.recuadro-thin.verde p{
    color: #780000;
    margin-left: 20%;
    padding-top: 20px;
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