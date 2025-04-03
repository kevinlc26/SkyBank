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
import { ref, computed, onMounted } from "vue";
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

// CABECERAS TABLA
const tableHeaders = ["ID","Número identificación","Nombre","Apellidos","Nacionalidad","Fecha de nacimiento","Teléfono","Email","Dirección","Rol","Número de la Seguridad Social","Fecha de contratación"];


// API GET
const empleados = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/empleados?Estado_empleado=Activo');
    if (response.ok) {
      const data = await response.json();
      empleados.value = data;  
    } else {
      console.error('Error al obtener los datos:', response.status);
    }
  } catch (error) {
    console.error('Error en la petición:', error);
  }
});


const filtro = [  
    { COLUMN_NAME: "ID_empleado", DATA_TYPE: "int", TITULO: "ID: " },  
    { COLUMN_NAME: "Num_ident", DATA_TYPE: "varchar", TITULO: "Número identificación: " },  
    { COLUMN_NAME: "Nombre", DATA_TYPE: "varchar", TITULO: "Nombre: " },  
    { COLUMN_NAME: "Apellidos", DATA_TYPE: "varchar", TITULO: "Apellidos: " },  
    { COLUMN_NAME: "Nacionalidad", DATA_TYPE: "varchar", TITULO: "Nacionalidad: " },  
    { COLUMN_NAME: "Fecha de nacimiento", DATA_TYPE: "date", TITULO: "Fecha de nacimiento desde: " },  
    { COLUMN_NAME: "Fecha de nacimiento", DATA_TYPE: "date", TITULO: "Fecha de nacimiento hasta: " },  
    { COLUMN_NAME: "Teléfono", DATA_TYPE: "varchar", TITULO: "Teléfono: " },  
    { COLUMN_NAME: "Email", DATA_TYPE: "varchar", TITULO: "Email: " },  
    { COLUMN_NAME: "Dirección", DATA_TYPE: "varchar", TITULO: "Dirección: " },  
    { COLUMN_NAME: "Rol", DATA_TYPE: "enum", TITULO: "Rol: ", OPTIONS: ["Administrador", "Gestor"] },  
    { COLUMN_NAME: "Número de la Seguridad Social", DATA_TYPE: "varchar", TITULO: "Número SS: " },  
    { COLUMN_NAME: "Fecha de contratación", DATA_TYPE: "date", TITULO: "Fecha de contratación desde: " },  
    { COLUMN_NAME: "Fecha de contratación", DATA_TYPE: "date", TITULO: "Fecha de contratación hasta: " }  
];  

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return empleados.value.filter((row) => {
    return Object.keys(filtroActivo.value).every((key) => {
      const filtroValor = filtroActivo.value[key]; // Valor ingresado en el filtro
      const rowValor = row[key]; // Valor en la tabla

      if (!filtroValor) return true; // Si no hay filtro, no aplicar

      if (rowValor === undefined || rowValor === null) return false; // Si el valor en la tabla es null/undefined, descartar

      const filtroStr = filtroValor.toString().trim().toLowerCase();
      
      if (typeof rowValor === "number") {
        return rowValor === Number(filtroValor);
      }

      if (key.includes("Fecha") || key.includes("fecha") || rowValor instanceof Date) {
        const rowDate = new Date(rowValor).toISOString().split("T")[0]; // Convertir a YYYY-MM-DD
        return rowDate === filtroStr;
      }

      if (typeof rowValor === "boolean") {
        return rowValor === (filtroValor === "true");
      }

      return rowValor.toString().toLowerCase().includes(filtroStr);
    });
  });
});

const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>
