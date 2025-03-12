<template>
    <HeaderCliente />
    <div class="main">
      <div class="contenedorGrande">
        <h1>MI TARJETA 234******55</h1>
      <br />
      
      <div class="contenedorT">
        <MenuTarjeta/>
        <div class="recuadro-central gris">
          <h3>Indica el motivo de bloqueo</h3> <br>
          
          <div class="opciones">
            <label>
            <input type="checkbox" v-model="motivos" value="robo" />
            Robo
          </label>
          <label>
            <input type="checkbox" v-model="motivos" value="perdida" />
            Pérdida
          </label>
          <label>
            <input type="checkbox" v-model="motivos" value="deterioro" />
            Deterioro
          </label>
          </div>
          
          <!-- Botones -->
          <div class="botones">
            <button @click.prevent="openConfirmModal()" class="btn-orange">Bloquear</button>
          </div>
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

<style scoped>
/* Estilo del recuadro superior */
.recuadro-thin.verde {
  height: 59px;
  width: 80%;
  margin-left: 8%;
  margin-top: 1%;
}
h1{
    text-align: center;
    padding-left: 0%    ;
}

/* Estilo del contenedor de botones (espacio entre ellos) */
.botones {
  display: flex;
  gap: 20px; /* Espacio entre los botones */
  margin-top: 20px;
  justify-content: center;
}
/* Estilo para alinear los checkboxes a la izquierda */
label {
display: flex;
align-items: center;
gap: 10px; /* Espacio entre el checkbox y el texto */
font-size: 16px;
cursor: pointer;
}

/* Ajusta el tamaño del checkbox si es necesario */
input[type="checkbox"] {
width: 18px;
height: 18px;
}

/* Responsividad para pantallas pequeñas */
@media (max-width: 768px) {
  .contenedorT {
    flex-direction: column; /* En móviles, los recuadros se apilan */
    align-items: center;
  }

  .recuadro-lateral,
  .recuadro-central {
    width: 90%;
  }
}
</style>
  