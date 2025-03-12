<template>
  <HeaderEmpleado />
  <div class="main">
    <div class="perfil-header">
      <h1>Perfil del Empleado</h1>
      <button class="edit-btn" @click="openEditModal">
        <img src="../../assets/icons/edit.svg" alt="edit" width="24" height="24" />
      </button>
    </div>

    <div class="perfil-container">
      <div class="perfil-flex">
        <!-- Imagen de perfil a la izquierda -->
        <div class="imagen-perfil">
          <img src="../../assets/imagenes_perfil/1.png" alt="Imagen de Perfil" class="perfil-img" />
        </div>

        <!-- Datos del empleado a la derecha -->
        <div class="info-card">
          <h2>{{ empleado.nombre }}</h2> <br>
          <div class="info-grid">
            <div>
              <label class="label-perfil" style="display: inline;">ID: </label> 
              <span>{{ empleado.id }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Correo: </label> 
              <span>{{ empleado.correo }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Teléfono: </label> 
              <span>{{ empleado.telefono }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Fecha de Ingreso: </label> 
              <span>{{ empleado.fechaIngreso }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Jefe Directo: </label> 
              <span>{{ empleado.jefeDirecto }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Último Inicio de Sesión: </label> 
              <span>{{ empleado.ultimoInicio }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Secciones en dos columnas -->
      <div class="secciones-grid">
        <div class="seccion">
          <h3>Documentos Importantes</h3> <br>
          <table class="styled-table">
            <thead>
              <tr>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(doc, index) in empleado.documentos" :key="index">
                <td><a class="a-docs" :href="doc.url" target="_blank">{{ doc.nombre }}</a></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="seccion">
          <h3>Registro de Actividad</h3> <br>
          <table class="styled-table">
            <thead>
              <tr>
                <th>Actividad</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(registro, index) in empleado.registroActividad" :key="index">
                <td>{{ registro.actividad }}</td>
                <td>{{ registro.fecha }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <EditForm v-if="editVisible" :tableName="'perfil'" :id="id" :datos="datos" @close="editVisible = false"/>
  <FooterEmpleado />
</template>

<script setup>
import { ref } from "vue";
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import EditForm from "./EditForm.vue";

const empleado = ref({
  nombre: "Juan Pérez",
  correo: "juan.perez@skybank.com",
  telefono: "+34 600 123 456",
  id: id,
  imagenPerfil: "../assets/imagenes_perfil/1.png",
  fechaIngreso: "15/03/2020",
  jefeDirecto: "Ana Rodríguez",
  ultimoInicio: "05/03/2025 14:32",
  documentos: [
    { nombre: "Contrato Laboral", url: "#" },
    { nombre: "Código de Ética", url: "#" },
  ],
  registroActividad: [
    { actividad: "Modificó información de un cliente", fecha: "04/03/2025"},
    { actividad: "Aprobó una solicitud de crédito", fecha: "03/03/2025"},
    { actividad: "Cerró sesión", fecha: "02/03/2025"},
  ],
});
const id = "12354";

const datos = [
    { COLUMN_NAME: "Nombre", VALUE: "Juan" },
    { COLUMN_NAME: "Apellidos", VALUE: "Pérez" },
    { COLUMN_NAME: "Telefono", VALUE: "+34 600 123 456" },
    { COLUMN_NAME: "Email", VALUE: "juan.perez@skybank.com" },
    { COLUMN_NAME: "Fecha_contratacion", VALUE: "2025-03-15"},
    { COLUMN_NAME: "Superior", VALUE: "Maria Gómez" },
    { COLUMN_NAME: "Documentos", VALUE: ""},
    { COLUMN_NAME: "foto_empleado", VALUE: "1.png"},
];

//MODAL
const editVisible = ref(false);
const openEditModal = () => {
  (editVisible.value = true), (editId = id);
  console.log("Modal abierto");
};
</script>

<style scoped>

/* HEADER */
.perfil-container {
  max-width: 900px;
  background: #eee9e0;
  padding: 25px;
  border-radius: 10px;
  margin: 20px auto;
  border: 2px solid #780000;
}

.perfil-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 900px;
  margin: 0 auto 20px;
}

.edit-btn {
  background-color: transparent;
  border: none;
  cursor: pointer;
  padding: 5px;
  transition: transform 0.2s ease-in-out;
}

/* FOTO PERFIL */

.perfil-flex {
  display: flex;
  align-items: center;
  gap: 30px;
  margin-bottom: 20px;
}

.imagen-perfil {
  flex-shrink: 0;
  text-align: center;
}

.perfil-img {
  width: 140px;
  height: 140px;
  object-fit: cover;
}

/* DATOS DE PERFIL */

.info-card {
  flex-grow: 1;
  padding: 20px;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 22px;
}

h2, h3 {
  color: #780000;
  text-transform: uppercase;
}

.label-perfil {
  font-weight: 600;
}

.secciones-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-top: 20px;
}

/* TABLA */

.styled-table {
  width: 100%;
  border-collapse: collapse;
}

.styled-table th, .styled-table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ccc;
}

.styled-table thead {
  background-color: #9dac7b;
  color: white;
}

.styled-table tbody tr:nth-child(odd) {
  background-color: #eee9e0;
}

.styled-table tbody tr:nth-child(even) {
  background-color: #e4ded5;
}

.a-docs {
  color: black;
  text-decoration: none;
  font-size: 16px;

}

.a-docs:hover {
  color: #e88924;
  text-decoration: underline;
}
</style>