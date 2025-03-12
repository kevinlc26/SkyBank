<template>
    <HeaderCliente />
    <div class="main">
      <div class="contenedorGrande">
        <h1>perfil</h1>
      <br />
      
      <div class="contenedorT">
        <menuPerfil/>
        <div class="recuadro-central gris">
          <h3>Datos personales</h3>
          <label for="Nombre">Nombre y apellidos</label>
          <input type="text" value="Lorem ipsum" readonly>
          <label for="Telefono">Teléfono</label>
          <input type="tel" value="674365211" readonly>
          <label for="mail">Mail</label>
          <input type="email" value="lorem@ejemplo.com" readonly>
          <label for="Direccioin">Dirección postal</label>
          <input type="text" name="direccion" value="c/ Santa Carolina 1, 1" readonly>
          <label for="documentacion">Documentación</label>
          <input type="text" value="46322055L" readonly>
          <!-- Botones -->
          <div class="botones">
            <button class="btn-orange">Editar</button>
          </div>
        </div>
      </div>
      </div>
    </div>
    <FooterInicio/>
</template>

<script setup>
  import { ref } from "vue";
  import HeaderCliente from "../components/HeaderCliente.vue";
  import FooterInicio from "../components/FooterInicio.vue";
  import menuPerfil from "../components/MenuPerfil.vue";

  // Reactive variable
  const motivos = ref([]);
</script>



<style>


</style>
