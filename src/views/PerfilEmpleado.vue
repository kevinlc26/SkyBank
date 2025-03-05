<template>
  <HeaderEmpleado />
  <div class="main">
    <h1>Perfil del Empleado</h1>

    <div class="perfil-container">
      <h2>{{ empleado.nombre }}</h2>

      <div class="info-grid">
        <div><strong>ID:</strong> {{ empleado.id }}</div>
        <div><strong>Correo:</strong> {{ empleado.correo }}</div>
        <div><strong>Teléfono:</strong> {{ empleado.telefono }}</div>
        <div>
          <strong>Fecha de Ingreso:</strong> {{ empleado.fechaIngreso }}
        </div>
        <div><strong>Jefe Directo:</strong> {{ empleado.jefeDirecto }}</div>
        <div>
          <strong>Último Inicio de Sesión:</strong> {{ empleado.ultimoInicio }}
        </div>
      </div>

      <div class="seccion">
        <h3>Documentos Importantes</h3>
        <ul>
          <li v-for="(doc, index) in empleado.documentos" :key="index">
            <a :href="doc.url" target="_blank">{{ doc.nombre }}</a>
          </li>
        </ul>
      </div>

      <div class="seccion">
        <h3>Registro de Actividad</h3>
        <ul>
          <li
            v-for="(actividad, index) in empleado.registroActividad"
            :key="index"
          >
            {{ actividad }}
          </li>
        </ul>
      </div>
    </div>
  </div>

  <FooterEmpleado />
</template>

<script setup>
import { ref } from "vue";
import FooterEmpleado from "../components/FooterEmpleado.vue";
import HeaderEmpleado from "../components/HeaderEmpleado.vue";

const empleado = ref({
  nombre: "Juan Pérez",
  correo: "juan.perez@skybank.com",
  telefono: "+34 600 123 456",
  id: "12345",
  fechaIngreso: "15/03/2020",
  jefeDirecto: "Ana Rodríguez",
  ultimoInicio: "05/03/2025 14:32",
  documentos: [
    { nombre: "Contrato Laboral", url: "#" },
    { nombre: "Código de Ética", url: "#" },
  ],
  registroActividad: [
    "Modificó información de un cliente - 04/03/2025",
    "Aprobó una solicitud de crédito - 03/03/2025",
    "Cerró sesión - 02/03/2025",
  ],
});
</script>

<style>
.perfil-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  background: #fff;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  text-align: left;
}

h2 {
  font-size: 20px;
  margin-bottom: 15px;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 20px;
}

.seccion {
  margin-top: 20px;
}

.seccion h3 {
  font-size: 18px;
  margin-bottom: 10px;
}

ul {
  list-style-type: disc;
  padding-left: 20px;
}

a {
  color: blue;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>
