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
        <td v-for="(column, colIndex) in headers" :key="colIndex">
          {{ row[column] || "-" }}
        </td>
        <td>
          <button
            style="all: unset"
            @click="openEditModal(Object.values(row)[0])"
          >
            <img
              src="../assets/icons/edit.svg"
              alt="edit"
              width="24"
              height="24"
            />
          </button>
          <a @click.prevent="openConfirmModal(row.ID)">
            <img
              src="../assets/icons/delete.svg"
              alt="delete"
              width="24"
              height="24"
            />
          </a>
        </td>
      </tr>
    </tbody>
  </table>

  <EditForm
    v-if="editVisible"
    :id="editId"
    :tableName="tableName"
    @close="editVisible = false"
  />

  <ConfirmDelete
    :showModal="showModal"
    :idToDelete="idToDelete"
    @confirm="confirmDelete"
    @cancel="cancelDelete"
  />
</template>

<script>
import ConfirmDelete from "./ConfirmDelete.vue";
import EditForm from "../views/EditForm.vue";

export default {
  props: {
    headers: Array,
    rows: Array,
    tableName: String,
  },

  data() {
    return {
      showModal: false,
      idToDelete: null,
      editVisible: false,
      editId: null,
    };
  },
  methods: {
    openEditModal(id) {
      this.editId = id;
      this.editVisible = true;
    },
    openConfirmModal(id) {
      this.idToDelete = id;
      this.showModal = true;
    },
    confirmDelete(id) {
      console.log(`Cuenta con ID ${id} eliminada.`);
      this.showModal = false;
    },
    cancelDelete() {
      console.log("Operación de eliminación cancelada.");
      this.showModal = false;
    },
  },
  components: {
    EditForm,
    ConfirmDelete,
  },
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
