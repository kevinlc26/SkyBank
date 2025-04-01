<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">tarjetas </h1>
    <button style="all: unset" @click="openAddModal">
      <img src="../../assets/icons/add.svg" alt="add" width="24" height="24" />
    </button>

    <FiltroEmpleado :tableName="`tarjetas`" :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado
      :headers="tableHeaders"
      :rows="filteredRows"
      :tableName="'tarjetas'"
    />
  </div>

  <AddForm
    v-if="addVisible"
    :tableName="'tarjetas'"
    @close="addVisible = false"
  />
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

// Definir las cabeceras de la tabla
const tableHeaders = ref(["Número","Número de cuenta","Titular","Tipo","Estado","Fecha de caducidad","Límite operativo"]);
const tarjetas = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/tarjetas');
    if (response.ok) {
      const data = await response.json();
      tarjetas.value = data;  
    } else {
      console.error('Error al obtener los datos:', response.status);
    }
  } catch (error) {
    console.error('Error en la petición:', error);
  }
});


const filtro = [
    { COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar", TITULO: "Núm Tarjeta: " },
    { COLUMN_NAME: "ID_cuenta", DATA_TYPE: "varchar", TITULO: "Núm cuenta: " },
    { COLUMN_NAME: "Tipo_tarjeta", DATA_TYPE: "enum", TITULO: "Tipo: ", TITULO: "Tipo: " , OPTIONS: ["online", "ahorro", "corriente"]},
    { COLUMN_NAME: "Estado_tarjeta", DATA_TYPE: "enum", TITULO: "Estado: ", TITULO: "Estado: " , OPTIONS: ["activo", "inactivo", "bloqueada"] },
    { COLUMN_NAME: "Fecha_caducidad", DATA_TYPE: "date", TITULO: "Caducidad desde: " },
    { COLUMN_NAME: "Fecha_caducidad", DATA_TYPE: "date", TITULO: "Caducidad hasta: " },
    { COLUMN_NAME: "Limite_operativo", DATA_TYPE: "int", TITULO: "Límite desde: " },
    { COLUMN_NAME: "Limite_operativo", DATA_TYPE: "int", TITULO: "Límite hasta: " },
];

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return tarjetas.value.filter((row) => {
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
