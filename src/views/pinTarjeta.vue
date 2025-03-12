<template>
    <HeaderCliente />
    <div class="main">
      <h1>MI TARJETA 234******55</h1>
      <br />
      
      <div class="contenedorT">
        <MenuTarjeta/>
        <div class="recuadro-central gris">
          <h3>Consultar PIN</h3><br>
          <div class="pin">
            <label for="limitediario">PIN actual: </label>
            <span>5555</span>
          </div>

          <div class="pin">
            <label for="nuevoPin">Nuevo PIN: </label>
            <input type="number" name="nuevoPin" id="">
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

.pin {
  width: 80%;
  display: flex;
  flex-direction: row;
}
</style>