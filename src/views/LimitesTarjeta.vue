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
            <label for="limitediario">Limite de uso diario</label><br>
            <input type="number" name="limitediario" id="" placeholder="200€">
          </div>
          <div class="limites">
            <label for="limiteMensual">Limites de uso mensual</label><br>
            <input type="number" name="limiteMensual" placeholder="900€">
          </div>
          <div class="permisos">
            <input type="checkbox" name="comprasInternacial" id=""> Compras en el extranjero <br>
            <input type="checkbox" name="comprasOnline" id=""> Compras Online <br>
          </div><br>
          <p>Confirmar cambios</p><br>
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
.limites{
    width: 52%;
}
input{
    background-color: #263E33;
    border-radius: 5px;
    width: 100%;
    height: 30px;
    color: aliceblue;
    border: 1px solid black;
}
h1{
    text-align: center;
    padding-left: 0%    ;
}
.permisos{
    display: flex;
    margin-top: 2%;
}
.permisos input[type="checkbox"] {
  display: inline-block; /* Mostrar los checkboxes como elementos en línea */
  margin-right: 5px; /* Agregar un pequeño margen a la derecha */
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