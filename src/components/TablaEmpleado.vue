<template>
  <table class="tabla">
    <thead>
      <tr>
        <th v-for="(header, index) in headers" :key="index">{{ header }}</th>
        <th>Opciones</th>
      </tr>
    </thead>

    <tbody>
      <!-- DATOS Y CAMPOS -->
      <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
        <td v-for="(column, colIndex) in headers" :key="colIndex">
           <!-- Verifica si la tabla es 'cuentas' y si la columna es la 1 o 2 -->
           <router-link
            v-if="(tableName === 'cuentas' && (colIndex === 0 || colIndex === 1)) ||
                  (tableName === 'clientes' && colIndex === 1) ||
                  (tableName === 'tarjetas' && (colIndex === 0 || colIndex === 1 || colIndex === 2)) ||
                  (tableName === 'transferencias' && (colIndex === 1 || colIndex === 2)) ||
                  (tableName === 'movimientos' && (colIndex === 1 || (colIndex === 2 && row[column] !== null)) )"
            :to="{
              path: '/detalle-empleado',
              query: {
                identificador: row[column],
                tableName,
                datos: JSON.stringify(row)
              }
            }"
          >
            {{ row[column] || "-" }}
          </router-link>

          <!-- Si no es una columna con RouterLink, solo muestra el dato -->
          <span v-else>{{ row[column] || "-" }}</span>
        </td>
        <td>
          <!-- OPCIONES -->
          <button style="all: unset" @click="openEditModal(Object.values(row)[0])">
            <img src="../assets/icons/edit.svg" alt="edit" width="24" height="24"/>
          </button>
          <a @click.prevent="openConfirmModal(Object.values(row)[0])">
            <img src="../assets/icons/delete.svg" alt="delete" width="24" height="24"/>
          </a>
        </td>
      </tr>
    </tbody>
  </table>

  <EditForm v-if="editVisible" :id="editId" :datos="editRow" :tableName="tableName" @close="editVisible = false"/>

  <ConfirmDelete :showModal="showModal" @confirm="confirmDelete" @cancel="cancelDelete"/>
</template>

<script setup>
import { ref, computed } from 'vue';
import EditForm from "../views/EditForm.vue";
import ConfirmDelete from "./ConfirmDelete.vue";

const props = defineProps({
  headers: Array,
  rows: Array,
  tableName: String,
});

const showModal = ref(false);
const idToDelete = ref(null);
const editVisible = ref(false);
const editId = ref(null);

const openEditModal = (id) => {
  editId.value = id;
  editVisible.value = true;
};

const editRow = computed(() => {
  return props.rows.find((row) => Object.values(row)[0] === editId.value) || {};
});

console.log("datos: ",  JSON.stringify(editRow.value, null, 2));
console.log(editId.value);

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
</style>
