<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>Tarjeta {{ ID_tarjeta }}</h1>
      <br />

      <div class="contenedorT">
        <MenuTarjeta />
        <div class="recuadro-central gris">
          <h3>Indica el motivo de bloqueo</h3> <br />

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
            <button @click.prevent="openConfirmModal" class="btn-orange">Bloquear</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br />

  <ConfirmDelete 
    :showModal="showModal" 
    :tableName="'tarjetas'" 
    :accion="'bloquear'" 
    :idToDelete="ID_tarjeta"
    @confirm="confirmDelete" 
    @cancel="cancelDelete"
  />

  <FooterInicio />
</template>

<script setup>
import { ref } from "vue";
import { getCookie } from "../../utils/cookies";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTarjeta from "../../components/Cliente/menuTarjeta.vue";
import ConfirmDelete from "../../components/Empleado/ConfirmDelete.vue";

const motivos = ref([]);
const showModal = ref(false);
const ID_tarjeta = getCookie("ID_tarjeta");

// Abrir modal
const openConfirmModal = () => {
  showModal.value = true;
};

// Confirmar acción
const confirmDelete = (id) => {
  console.log(`Tarjeta con ID ${id} bloqueada por motivo(s):`, motivos.value);
  showModal.value = false;
};

// Cancelar acción
const cancelDelete = () => {
  console.log("Operación de bloqueo cancelada.");
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
  