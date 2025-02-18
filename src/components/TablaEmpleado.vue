<template>
    <table class="tabla">
      <!-- Cabecera de la tabla -->
      <thead>
        <tr>
          <th v-for="(header, index) in headers" :key="index">{{ header }}</th>
          <th>Opciones</th>
        </tr>
      </thead>
  
      <!-- Cuerpo de la tabla -->
      <tbody>
        <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
          <td v-for="(column, colIndex) in headers" :key="colIndex">
            {{ row[column] || '-' }}
          </td>
          <td>
            <router-link :to="'/detalle/' + row.ID">
              <img src="../assets/icons/edit.svg" alt="edit" width="24" height="24">
            </router-link>
            <a @click.prevent="openConfirmModal(row.ID)">
              <img src="../assets/icons/delete.svg" alt="delete" width="24" height="24">
            </a>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Confimación delete -->
    <ConfirmDelete :showModal="showModal" :idToDelete="idToDelete" @confirm="confirmDelete" @cancel="cancelDelete" />
</template>
  
  <script>
    import ConfirmDelete from './ConfirmDelete.vue'; 
  export default {
    props: {
      headers: Array, // Array de cabeceras
      rows: Array,    // Array de filas de datos
    },

    data() {
      return {
        showModal: false,  // Controla si el modal es visible
        idToDelete: null,  // Guarda el ID del registro a eliminar
      };
    },
    methods: {
      // Abre el modal de confirmación
      openConfirmModal(id) {
        this.idToDelete = id;
        this.showModal = true;
      },
      // Confirmación de la eliminación
      confirmDelete(id) {
        console.log(`Cuenta con ID ${id} eliminada.`);
        // Lógica para eliminar la cuenta, ya sea del array local o enviando petición HTTP
        this.showModal = false;  // Cierra el modal
      },
      // Cancelar la eliminación
      cancelDelete() {
        console.log('Operación de eliminación cancelada.');
        this.showModal = false;  // Cierra el modal
      },
    },
    components: {
      ConfirmDelete,  // Registra el componente del modal
    },
  };
  </script>
  
  <style scoped>
  /* Estilos de la tabla y otros elementos */
.tabla {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.tabla th, .tabla td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.tabla th {
  background-color: #263E33;
  color: white;
}

.tabla tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

.tabla tbody tr:hover {
  background-color: #f1f1f1;
}
  </style>
  