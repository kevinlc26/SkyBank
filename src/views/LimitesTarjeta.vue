<template>
    <HeaderCliente />
    <div class="main">
        <h1>MI TARJETA 234******55</h1>
      <br />
      
      <div class="contenedorT">
        <MenuTarjeta/>
        <div class="recuadro-central gris">
          <h3>Modificar límite</h3><br>
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
          <!-- Botones -->
          <div class="botones">
            <button @click.prevent="openConfirmModal()" class="btn-orange">Confirmar</button>
          </div>
        </div>
      </div>
    </div>
    <br />
    <ConfirmDelete :showModal="showModal" @confirm="confirmDelete" @cancel="cancelDelete"/>
    <FooterInicio />
  </template>

<script setup>
import { ref } from "vue";
import HeaderCliente from "../components/HeaderCliente.vue";
import FooterInicio from "../components/FooterInicio.vue";
import MenuTarjeta from "../components/menuTarjeta.vue";
import ConfirmDelete from "../components/Empleado/ConfirmDelete.vue";

const motivos = ref([]);
const showModal = ref(false);
const idToDelete = ref(1);

const openConfirmModal = (id) => {
idToDelete.value = id;
showModal.value = true;
};

const confirmDelete = (id) => {
  console.log(`Cuenta con ID ${id} eliminada.`);
  showModal.value = false;
};

const cancelDelete = () => {
  console.log("Operación de eliminación cancelada.");
  showModal.value = false;
};

</script>


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