<template>
    <HeaderCliente />
    <div class="main">

      <h1>MI TARJETA 234******55</h1>

      <br/>
      
      <div class="contenedorT">
        <MenuTarjeta/>
        <div class="recuadro-central gris">
          <h3>Detalles tarjeta</h3><br>
          <div class="detalles">
            <label for="num">Numero de tarjeta:</label>
            <span>23456478955</span> <br> <br>
            <label for="caducidad">Fecha de caducidad:</label>
            <span>07/28</span> <br> <br>
            <label for="ccv">CCV:</label>
            <span>369</span> <br> <br>
            <label for="cuenta">Cuenta vinculada:</label>
            <span>ES16354544635132131223</span>

          </div><br>
        </div>
      </div>
    </div>
    <br />
    <FooterInicio />
</template>

<script setup>
import { ref } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTarjeta from "../../components/Cliente/menuTarjeta.vue";

const motivos = ref([]);
</script>

<style>
.detalle{
    text-align: center;
}
h1{
    text-align: center;
    padding-left: 0%    ;
}
</style>