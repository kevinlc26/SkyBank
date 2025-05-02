<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">tarjetas </h1>
    <button style="all: unset" @click="openAddModal">
      <img src="../../assets/icons/add.svg" alt="add" width="24" height="24" />
    </button>

    <FiltroEmpleado :tableName="`tarjetas`" :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado :headers="tableHeaders" :rows="filteredRows" :tableName="'tarjetas'" />
  </div>

  <AddForm
    v-if="addVisible" :tableName="'tarjetas'" @close="addVisible = false"/>
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
const tableHeaders = ref(["Número de Tarjeta","Número de Cuenta","Titular","Tipo","Fecha de Caducidad","Límite Operativo(€)","Estado"]);
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
  { KEY: "ID_tarjeta", COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar", TITULO: "Núm Tarjeta: " },
  { KEY: "Titular", COLUMN_NAME: "Titular", DATA_TYPE: "varchar", TITULO: "Titular: " },
  { KEY: "ID_cuenta", COLUMN_NAME: "ID_cuenta", DATA_TYPE: "varchar", TITULO: "Núm cuenta: " },
  { KEY: "Tipo_tarjeta", COLUMN_NAME: "Tipo_tarjeta", DATA_TYPE: "enum", TITULO: "Tipo: ", OPTIONS: ["Skydebit", "Skycredit", "Skypre"] },
  { KEY: "Estado_tarjeta", COLUMN_NAME: "Estado_tarjeta", DATA_TYPE: "enum", TITULO: "Estado: ", OPTIONS: ["Activa", "Inactiva", "Bloqueada"] },
  { KEY: "CaducidadDesde", COLUMN_NAME: "Fecha_caducidad", DATA_TYPE: "date", TITULO: "Caducidad desde: " },
  { KEY: "CaducidadHasta", COLUMN_NAME: "Fecha_caducidad", DATA_TYPE: "date", TITULO: "Caducidad hasta: " },
  { KEY: "LimiteDesde", COLUMN_NAME: "Limite_operativo", DATA_TYPE: "int", TITULO: "Límite desde: " },
  { KEY: "LimiteHasta", COLUMN_NAME: "Limite_operativo", DATA_TYPE: "int", TITULO: "Límite hasta: " },
];


//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return tarjetas.value.filter((row) => {
    return Object.entries(filtroActivo.value).every(([key, filtroValor]) => {
      if (!filtroValor) return true;

      // RANGOS DE FECHAS
      if (key === "CaducidadDesde") {
        return new Date(row.Fecha_caducidad) >= new Date(filtroValor);
      }
      if (key === "CaducidadHasta") {
        return new Date(row.Fecha_caducidad) <= new Date(filtroValor);
      }

      // RANGOS NUMÉRICOS
      if (key === "LimiteDesde") {
        return Number(row.Limite_operativo) >= Number(filtroValor);
      }
      if (key === "LimiteHasta") {
        return Number(row.Limite_operativo) <= Number(filtroValor);
      }

      // OBTENER NOMBRE REAL DE LA COLUMNA
      const columna = filtro.find(f => f.KEY === key)?.COLUMN_NAME || key;
      const rowValor = row[columna];

      if (rowValor === undefined || rowValor === null) return false;

      const rowStr = rowValor.toString().toLowerCase();
      const filtroStr = filtroValor.toString().trim().toLowerCase();

      return rowStr.includes(filtroStr);
    });
  });
});


const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>
