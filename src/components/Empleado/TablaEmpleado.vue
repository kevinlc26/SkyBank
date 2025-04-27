<template>
  <table class="tabla">
    <thead>
      <tr>
        <th v-for="(header, index) in headers" :key="index">{{ header }}</th>
        <th>Opciones</th>
      </tr>
    </thead>

    <tbody>
      <tr v-for="(row, rowIndex) in filteredRows" :key="rowIndex">

        <!-- DATOS Y CAMPOS -->
        <td v-for="(value, colIndex) in Object.values(row)" :key="colIndex">
          <router-link
            v-if="(tableName === 'cuentas' && (colIndex === 0 || colIndex === 1)) ||
                  (tableName === 'clientes' && colIndex === 1) ||
                  (tableName === 'empleados' && colIndex === 1) ||
                  (tableName === 'tarjetas' && (colIndex === 0 || colIndex === 1 || colIndex === 2)) ||
                  (tableName === 'transferencias' && (colIndex === 1 || colIndex === 2)) ||
                  ((tableName === 'movimientos' || tableName === 'detalleCliente') && (colIndex === 1 || (colIndex === 2 && row[Object.keys(row)[colIndex]] !== null)))"
            :to="{
              path: (tableName === 'empleados' && colIndex === 1) ? '/perfil-empleado' : '/detalle-empleado',
              query: {
                identificador: row[Object.keys(row)[colIndex]],
                tableName
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
          <span v-if="tableName !== 'movimientos' && tableName !== 'transferencias'">
            <button style="all: unset" @click="openEditModal(Object.values(row)[0])">
              <img src="../../assets/icons/edit.svg" alt="edit" width="24" height="24"/>
            </button>
          </span>
          <!-- BLOQUEAR -->
          <span v-if="tableName === 'tarjetas' || tableName === 'cuentas' || tableName === 'movimientos' || tableName === 'transferencias'">
            <a v-if="bloqueo(row)" @click.prevent="openConfirmModal(Object.values(row)[0], 'desbloquear')">
              <img src="../../assets/icons/desbloquear.svg" alt="desbloquear" width="24" height="24"/>
            </a>
            <a v-else @click.prevent="openConfirmModal(Object.values(row)[0], 'bloquear')">
              <img src="../../assets/icons/bloqueado.svg" alt="bloquear" width="24" height="24"/>
            </a>
          </span>
          <!-- ACTIVAR/DELETE -->
          <span v-if="tableName !== 'movimientos' && tableName !== 'transferencias'">
            <a v-if="inactivar(row)" @click.prevent="openConfirmModal(Object.values(row)[0], 'activar')">
              <img src="../../assets/icons/activar_icon.svg" alt="activar" width="24" height="24"/>
            </a>
            <a v-else @click.prevent="openConfirmModal(Object.values(row)[0], 'delete')">
              <img src="../../assets/icons/delete.svg" alt="delete" width="24" height="24"/>
            </a>
          </span>
          
        </td>

      </tr>
    </tbody>
  </table>

  <EditForm v-if="editVisible" :id="editId" :tableName="tableName" @close="editVisible = false"/>
  <ConfirmDelete :showModal="showModal" @confirm="confirmDelete" :tableName="tableName" :idToDelete="idToDelete" :accion="accion" @cancel="cancelDelete"/>
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

// ACCEDER A LA COOKIE
const dni = ref(getCookie('DNI_empleado'))
function getCookie(nombre) {
  const valor = `; ${document.cookie}`;
  const partes = valor.split(`; ${nombre}=`);
  if (partes.length === 2) return partes.pop().split(';').shift();
}

// ELIMINAR SU PROPIO REGISTRO DE GESTION EMPLEADOS
const filteredRows = computed(() => {
  if (props.tableName === 'empleados') {
    return props.rows.filter(row =>
      !Object.values(row).some(val =>
        String(val).trim().toLowerCase() === String(dni.value).trim().toLowerCase()
      )
    );
  }
  return props.rows;
});

// DETERMINAR BLOQUEO
const bloqueo = (row) => {
  return (row.Estado_tarjeta === 'Bloqueada' || row.Estado_cuenta === 'Bloqueada' || row.Estado === 'Bloqueado');
};

// DETERMINAR INACTIVAR
const inactivar = (row) => {
  return (row.Estado_tarjeta === 'Inactiva' || row.Estado_cuenta === 'Inactiva' || row.Estado_Clientes === 'Inactivo' || row.Estado_empleado === 'Inactivo');
};

// EDIT MODAL
const editVisible = ref(false);
const editId = ref(null);

const openEditModal = (id) => {
  editId.value = id;
  editVisible.value = true;
};

// DELETE MODAL
const showModal = ref(false);
const idToDelete = ref('');
let accion = ref('');

const openConfirmModal = (id, action) => {
  idToDelete.value = id;
  accion.value = action;
  showModal.value = true;
};

const confirmDelete = () => {
  console.log(`Registro con ID ${idToDelete.value} eliminado.`);
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
