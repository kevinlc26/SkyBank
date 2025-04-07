<template>
  <table class="tabla">
    <thead>
      <tr>
        <th v-for="(header, index) in headers" :key="index">{{ header }}</th>
        <th>Opciones</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="(row, rowIndex) in rows" :key="rowIndex">

        <!-- DATOS Y CAMPOS -->
        <td v-for="(value, colIndex) in Object.values(row)" :key="colIndex">
           <router-link
              v-if="(tableName === 'cuentas' && (colIndex === 0 || colIndex === 1)) ||
                    ((tableName === 'clientes' || tableName === 'empleados') && colIndex === 1) ||
                    (tableName === 'tarjetas' && (colIndex === 0 || colIndex === 1 || colIndex === 2)) ||
                    (tableName === 'transferencias' && (colIndex === 1 || colIndex === 2)) ||
                    ((tableName === 'movimientos' || tableName === 'detalleCliente') && (colIndex === 1 || (colIndex === 2 && row[Object.keys(row)[colIndex]] !== null)))"
              :to="{
                path: '/detalle-empleado',
                query: {
                  identificador: row[Object.keys(row)[colIndex]],
                  tableName,
                  datos: JSON.stringify(row)
                }
              }"
            >
            {{ value || "-" }}
          </router-link>

          <span v-else>{{ value || "-" }}</span>
        </td>

        <!-- OPCIONES -->
        <td>
          <!-- EDIT -->
          <button style="all: unset" @click="openEditModal(getId(row))">
            <img src="../../assets/icons/edit.svg" alt="edit" width="24" height="24"/>
          </button>
          <!-- BLOQUEAR -->
          <button v-if="bloqueo(tableName, row)" style="all: unset">
            <img src="../../assets/icons/desbloquear.svg" alt="desbloquear" width="24" height="24"/>
          </button>
          <button v-else style="all: unset">
            <img src="../../assets/icons/bloqueado.svg" alt="bloquear" width="24" height="24"/>
          </button>
          <!-- ACTIVAR/DELETE -->
          <a v-if="inactivar(row)" @click.prevent="openConfirmModal(getId(row))">
            <img src="../../assets/icons/activar_icon.svg" alt="activar" width="24" height="24"/>
          </a>
          <a v-else @click.prevent="openConfirmModal(getId(row))">
            <img src="../../assets/icons/delete.svg" alt="delete" width="24" height="24"/>
          </a>
        </td>

      </tr>
    </tbody>
  </table>

  <EditForm v-if="editVisible" :id="editId" :tableName="tableName" @close="editVisible = false"/>

  <ConfirmDelete :showModal="showModal" @confirm="confirmDelete" :tableName="tableName" :idToDelete="idToDelete"  @cancel="cancelDelete"/>
</template>

<script setup>
import { ref, computed } from 'vue';
import EditForm from "../../views/Empleado/EditForm.vue";
import ConfirmDelete from "./ConfirmDelete.vue";

const props = defineProps({
  headers: Array,
  rows: Array,
  tableName: String,
});

// DETERMINAR ID
const getId = (row) => {
  const keys = Object.keys(row);

  switch (props.tableName) {
    case "clientes":
      return row[keys[1]];
    case "cuentas": 
      return row[keys[0]];
    case "tarjetas":
      return row[keys[0]];
    case "transferencias":
      return row[keys[1]];
    case "movimientos":
      return row[keys[1]];
    case "default":
      return row[keys[0]];
  }
}

// DETERMINAR BLOQUEO O INACTIVAR
const bloqueo = (tableName, row) => {
  return (tableName === 'tarjetas' || tableName === 'cuentas') &&
    (row.Estado_tarjeta === 'Bloqueada' || row.Estado_cuenta === 'Bloqueada');
};

const inactivar = (row) => {
  return (row.Estado_tarjeta === 'Inactiva' || row.Estado_cuenta === 'Inactiva' || row.Estado_cliente === 'Inactivo' || row.Estado_empleado === 'Inactivo');
};

// EDIT
const editVisible = ref(false);
const editId = ref(null);

const openEditModal = (id) => {
  editId.value = id;
  editVisible.value = true;
};



// DELETE
const showModal = ref(false);
const idToDelete = ref('');

const openConfirmModal = (id) => {
  idToDelete.value = id;
  showModal.value = true;
};

const confirmDelete = () => {
  console.log(`Cuenta con ID ${idToDelete.value} eliminada.`);
  showModal.value = false;
};

const cancelDelete = () => {
  console.log("Operación de eliminación cancelada.");
  showModal.value = false;
};
</script>

<style scoped>
.tabla {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.tabla th,
.tabla td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.tabla th {
  background-color: #9dac7b;
  color: white;
}

.tabla tbody tr:nth-child(even) {
  background-color: #e4ded5;
}
.tabla tbody tr:nth-child(odd) {
  background-color: #eee9e0;
}

.tabla tbody tr:hover {
  background-color: #f1f1f1;
}

a {
  color: black;
  text-decoration: none;
  font-size: 16px;
}

a:hover {
  color: #e88924;
}
</style>
