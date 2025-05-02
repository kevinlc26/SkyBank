<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">cuentas </h1>
    <button style="all: unset" @click="openAddModal">
      <img src="../../assets/icons/add.svg" alt="add" width="24" height="24" title="Añadir"/>
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
const tableHeaders = ["Número de Cuenta","Titulares","Tipo","Saldo(€)","Fecha Apertura","Estado"]; // Cabecera

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
  { COLUMN_NAME: "ID_cuenta", DATA_TYPE: "varchar", TITULO: "Núm cuenta: ", KEY: "ID_cuenta" },
  { COLUMN_NAME: "Titular", DATA_TYPE: "varchar", TITULO: "Titulares: ", KEY: "Titular" },
  { COLUMN_NAME: "Tipo_cuenta", DATA_TYPE: "enum", TITULO: "Tipo: ", OPTIONS: ["online", "ahorro", "corriente"], KEY: "Tipo_cuenta" },
  { COLUMN_NAME: "Estado_cuenta", DATA_TYPE: "enum", TITULO: "Estado: ", OPTIONS: ["Activa", "Inactiva", "Bloqueada"], KEY: "Estado_cuenta" },
  { COLUMN_NAME: "Saldo", DATA_TYPE: "int", TITULO: "Saldo desde: ", KEY: "SaldoDesde" },
  { COLUMN_NAME: "Saldo", DATA_TYPE: "int", TITULO: "Saldo hasta: ", KEY: "SaldoHasta" },
  { COLUMN_NAME: "Fecha_creacion", DATA_TYPE: "date", TITULO: "Fecha apertura desde: ", KEY: "FechaAperturaDesde" },
  { COLUMN_NAME: "Fecha_creacion", DATA_TYPE: "date", TITULO: "Fecha apertura hasta: ", KEY: "FechaAperturaHasta" },
];

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return cuentas.value.filter((row) => {
    const filtros = filtroActivo.value;

    return Object.keys(filtros).every((key) => {
      const valorFiltro = filtros[key];

      if (valorFiltro === null || valorFiltro === undefined || valorFiltro === "") return true;

      // SALDO RANGO
      if (key === "SaldoDesde") return Number(row.Saldo) >= Number(valorFiltro);
      if (key === "SaldoHasta") return Number(row.Saldo) <= Number(valorFiltro);

      // FECHA RANGO
      if (key === "FechaAperturaDesde") return new Date(row.Fecha_creacion) >= new Date(valorFiltro);
      if (key === "FechaAperturaHasta") return new Date(row.Fecha_creacion) <= new Date(valorFiltro);

      // OTROS TIPOS
      const rowValor = row[key] ?? row[key.replace("Desde", "").replace("Hasta", "")]; // fallback por si el campo base es el mismo

      if (rowValor === undefined || rowValor === null) return false;

      const rowStr = rowValor.toString().toLowerCase();
      const filtroStr = valorFiltro.toString().trim().toLowerCase();

      return rowStr.includes(filtroStr);
    });
  });
});


const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>
