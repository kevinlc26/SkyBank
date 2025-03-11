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
import { ref } from "vue";
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
const tableHeaders = ref([
  "Número",
  "Número de cuenta",
  "Titular",
  "Tipo",
  "Estado",
  "Fecha de caducidad",
  "Límite operativo",
]);

// Definir las filas de la tabla
const tableRows = ref([
  {
    Número: "1234 5678 9012 3456",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1332",
    Titular: "X1234567Y",
    Tipo: "Visa",
    Estado: "Activo",
    "Fecha de caducidad": "12/26",
    "Límite operativo": 5000,
  },
  {
    Número: "1234 5678 9012 3457",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1333",
    Titular: "X1234568Y",
    Tipo: "MasterCard",
    Estado: "Inactivo",
    "Fecha de caducidad": "11/25",
    "Límite operativo": 3200,
  },
  {
    Número: "1234 5678 9012 3458",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1334",
    Titular: "X1234569Y",
    Tipo: "Visa",
    Estado: "Activo",
    "Fecha de caducidad": "08/25",
    "Límite operativo": 4500,
  },
  {
    Número: "1234 5678 9012 3459",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1335",
    Titular: "X1234570Y",
    Tipo: "MasterCard",
    Estado: "Activo",
    "Fecha de caducidad": "05/24",
    "Límite operativo": 3000,
  },
  {
    Número: "1234 5678 9012 3460",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1336",
    Titular: "X1234571Y",
    Tipo: "Visa",
    Estado: "Inactivo",
    "Fecha de caducidad": "03/26",
    "Límite operativo": 6000,
  },
  {
    Número: "1234 5678 9012 3461",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1337",
    Titular: "X1234572Y",
    Tipo: "MasterCard",
    Estado: "Activo",
    "Fecha de caducidad": "01/25",
    "Límite operativo": 4200,
  },
  {
    Número: "1234 5678 9012 3462",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1338",
    Titular: "X1234573Y",
    Tipo: "Visa",
    Estado: "Activo",
    "Fecha de caducidad": "02/24",
    "Límite operativo": 3800,
  },
  {
    Número: "1234 5678 9012 3463",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1339",
    Titular: "X1234574Y",
    Tipo: "MasterCard",
    Estado: "Inactivo",
    "Fecha de caducidad": "09/25",
    "Límite operativo": 2000,
  },
  {
    Número: "1234 5678 9012 3464",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1340",
    Titular: "X1234575Y",
    Tipo: "Visa",
    Estado: "Activo",
    "Fecha de caducidad": "07/23",
    "Límite operativo": 2700,
  },
  {
    Número: "1234 5678 9012 3465",
    "Número de cuenta": "ES91 2100 0418 4502 0005 1341",
    Titular: "X1234576Y",
    Tipo: "MasterCard",
    Estado: "Activo",
    "Fecha de caducidad": "06/24",
    "Límite operativo": 5000,
  },
]);

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
  return tableRows.value.filter((row) => {
    return Object.keys(filtroActivo.value).every((key) => {
      const filtroValor = filtroActivo.value[key]; // Valor ingresado en el filtro
      const rowValor = row[key]; // Valor en la tabla

      if (!filtroValor) return true; // Si no hay filtro, no aplicar

      if (rowValor === undefined || rowValor === null) return false; // Si el valor en la tabla es null/undefined, descartar

      const filtroStr = filtroValor.toString().trim().toLowerCase();
      
      // 🔹 Comparar números
      if (typeof rowValor === "number") {
        return rowValor === Number(filtroValor);
      }

      // 🔹 Comparar fechas
      if (key.includes("Fecha") || key.includes("fecha") || rowValor instanceof Date) {
        const rowDate = new Date(rowValor).toISOString().split("T")[0]; // Convertir a YYYY-MM-DD
        return rowDate === filtroStr;
      }

      // 🔹 Comparar booleanos (checkbox)
      if (typeof rowValor === "boolean") {
        return rowValor === (filtroValor === "true");
      }

      // 🔹 Comparar texto
      return rowValor.toString().toLowerCase().includes(filtroStr);
    });
  });
});

// Recibir datos del filtro y actualizar `filtroActivo`
const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>
