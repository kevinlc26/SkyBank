<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">cuentas </h1>
    <button style="all: unset" @click="openAddModal">
      <img src="../../assets/icons/add.svg" alt="add" width="24" height="24" />
    </button>
    
    <FiltroEmpleado :tableName="`cuentas`" :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado :headers="tableHeaders" :rows="filteredRows" :tableName="'cuentas'"/>
  </div>

  <AddForm v-if="addVisible" :tableName="'cuentas'" @close="addVisible = false"/>

  <FooterEmpleado />
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";
import AddForm from "./AddForm.vue";

//MODAL
const addVisible = ref(false);
const openAddModal = () => {
  addVisible.value = true;
  console.log("Modal abierto");
};

//DATOS
const tableHeaders = ["ID","Titulares","Tipo","Saldo","Estado","Fecha"]; // Cabecera

const cuentas = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/cuentas');
    if (response.ok) {
      const data = await response.json();
      cuentas.value = data;  
    } else {
      console.error('Error al obtener los datos:', response.status);
    }
  } catch (error) {
    console.error('Error en la petición:', error);
  }
});

const filtro = [
    { COLUMN_NAME: "ID_cuenta", DATA_TYPE: "varchar", TITULO: "Núm cuenta: " },
    { COLUMN_NAME: "Titulares", DATA_TYPE: "varchar", TITULO: "Titulares: " },
    { COLUMN_NAME: "Tipo_cuenta", DATA_TYPE: "enum", TITULO: "Tipo: " , OPTIONS: ["online", "ahorro", "corriente"]},
    { COLUMN_NAME: "Estado_cuenta", DATA_TYPE: "enum", TITULO: "Estado: " , OPTIONS: ["activo", "inactivo", "bloqueada"]},
    { COLUMN_NAME: "Saldo", DATA_TYPE: "int", TITULO: "Saldo desde: " },
    { COLUMN_NAME: "Saldo", DATA_TYPE: "int", TITULO: "Saldo hasta: " },
    { COLUMN_NAME: "Fecha_creacion", DATA_TYPE: "date", TITULO: "Fecha desde: " },
    { COLUMN_NAME: "Fecha_creacion", DATA_TYPE: "date", TITULO: "Fecha hasta: " },
];

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return cuentas.value.filter((row) => {
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
