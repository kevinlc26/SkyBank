<template>
    <HeaderEmpleado />
    <div class="main">
        <h1 style="display: inline">Gestión de Empleados</h1>
        <button style="all: unset" @click="openAddModal">
        <img src="../../assets/icons/add.svg" alt="add" width="24" height="24" />
        </button>

    <FiltroEmpleado :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado :headers="tableHeaders" :rows="filteredRows" :tableName="'empleados'"/>
  </div>

  <AddForm
    v-if="addVisible"
    :tableName="'empleados'"
    @close="addVisible = false"
  />

  <FooterEmpleado />
</template>

<script setup>
import { ref, computed } from "vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";
import AddForm from "./AddForm.vue";


//MODAL
const addVisible = ref(false);
const openAddModal = () => {
  addVisible.value = true;
  console.log("Modal abierto");
};

const tableHeaders = [
  "ID",
  "Número identificación",
  "Nombre",
  "Apellidos",
  "Nacionalidad",
  "Fecha de nacimiento",
  "Teléfono",
  "Email",
  "Dirección",
  "Rol",
  "Número de la Seguridad Social",
  "Fecha de contratación",
];

fetch('/skybank/backend/public/api.php/empleados', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
    }
}) 
.then(response => response.json())
.then(data => {
    console.log(data);
})
.catch(error => {
    console.error('Error: ', error);
});

const tableRows = ref([  
  { ID: 1, 'Número identificación': "12345678A", Nombre: "Carlos", Apellidos: "Pérez", Nacionalidad: "Española", "Fecha de nacimiento": "1990-05-15", Teléfono: "600123456", Email: "carlos.perez@example.com", Dirección: "Calle Mayor 10", Rol: "Gerente", "Número de la Seguridad Social": "123456789", "Fecha de contratación": "2023-01-01" },  
  { ID: 2, 'Número identificación': "87654321B", Nombre: "Laura", Apellidos: "Gómez", Nacionalidad: "Argentina", "Fecha de nacimiento": "1985-10-22", Teléfono: "699987654", Email: "laura.gomez@example.com", Dirección: "Avenida Central 45", Rol: "Contadora", "Número de la Seguridad Social": "987654321", "Fecha de contratación": "2022-06-15" },  
  { ID: 3, 'Número identificación': "11223344C", Nombre: "Mario", Apellidos: "Fernández", Nacionalidad: "Mexicana", "Fecha de nacimiento": "1995-03-08", Teléfono: "655223344", Email: "mario.fernandez@example.com", Dirección: "Calle del Sol 7", Rol: "Ingeniero", "Número de la Seguridad Social": "112233445", "Fecha de contratación": "2021-09-10" }  
]); 

const filtro = [  
    { COLUMN_NAME: "ID_empleado", DATA_TYPE: "int", TITULO: "ID: " },  
    { COLUMN_NAME: "Número identificación", DATA_TYPE: "varchar", TITULO: "Número identificación: " },  
    { COLUMN_NAME: "Nombre", DATA_TYPE: "varchar", TITULO: "Nombre: " },  
    { COLUMN_NAME: "Apellidos", DATA_TYPE: "varchar", TITULO: "Apellidos: " },  
    { COLUMN_NAME: "Nacionalidad", DATA_TYPE: "varchar", TITULO: "Nacionalidad: " },  
    { COLUMN_NAME: "Fecha de nacimiento", DATA_TYPE: "date", TITULO: "Fecha de nacimiento desde: " },  
    { COLUMN_NAME: "Fecha de nacimiento", DATA_TYPE: "date", TITULO: "Fecha de nacimiento hasta: " },  
    { COLUMN_NAME: "Teléfono", DATA_TYPE: "varchar", TITULO: "Teléfono: " },  
    { COLUMN_NAME: "Email", DATA_TYPE: "varchar", TITULO: "Email: " },  
    { COLUMN_NAME: "Dirección", DATA_TYPE: "varchar", TITULO: "Dirección: " },  
    { COLUMN_NAME: "Rol", DATA_TYPE: "enum", TITULO: "Rol: ", OPTIONS: ["Gerente", "Contador", "Ingeniero", "Administrativo"] },  
    { COLUMN_NAME: "Número de la Seguridad Social", DATA_TYPE: "varchar", TITULO: "Número SS: " },  
    { COLUMN_NAME: "Fecha de contratación", DATA_TYPE: "date", TITULO: "Fecha de contratación desde: " },  
    { COLUMN_NAME: "Fecha de contratación", DATA_TYPE: "date", TITULO: "Fecha de contratación hasta: " }  
];  

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return tableRows.value.filter((row) => {
    return Object.keys(filtroActivo.value).every((key) => {
      const filtroValor = filtroActivo.value[key]; // Valor ingresado en el filtro
      const rowValor = row[key]; // Valor en la tabla

      if (!filtroValor) return true; // Si no hay filtro, no aplicar

      if (rowValor === undefined || rowValor === null) return false; // Si el valor en la tabla es null/undefined, descartar

      const filtroStr = filtroValor.toString().trim().toLowerCase();
      
      // Comparar números
      if (typeof rowValor === "number") {
        return rowValor === Number(filtroValor);
      }

      // Comparar fechas
      if (key.includes("Fecha") || key.includes("fecha") || rowValor instanceof Date) {
        const rowDate = new Date(rowValor).toISOString().split("T")[0]; // Convertir a YYYY-MM-DD
        return rowDate === filtroStr;
      }

      // Comparar booleanos (checkbox)
      if (typeof rowValor === "boolean") {
        return rowValor === (filtroValor === "true");
      }

      // Comparar texto
      return rowValor.toString().toLowerCase().includes(filtroStr);
    });
  });
});

// Recibir datos del filtro y actualizar `filtroActivo`
const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>
