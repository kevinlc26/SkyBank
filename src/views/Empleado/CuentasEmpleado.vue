<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">cuentas </h1>
    <button style="all: unset" @click="openAddModal">
      <img src="../../assets/icons/add.svg" alt="add" width="24" height="24" />
    </button>
    
    <FiltroEmpleado :filtro="filtro"/>
    <TablaEmpleado
      :headers="tableHeaders"
      :rows="tableRows"
      :tableName="'cuentas'"
    />
  </div>

  <AddForm
    v-if="addVisible"
    :tableName="'cuentas'"
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

//DATOS
const tableHeaders = [
  "ID",
  "Titulares",
  "Tipo",
  "Saldo",
  "Estado",
  "Saldo",
  "Fecha",
]; // Cabecera dinámica
const tableRows = [
  {
    ID: "ES91 2100 0418 4502 0005 1332",
    Titulares: "Juan Pérez",
    Tipo: "online",
    Saldo: 5000,
    Estado: "Activo",
    Fecha: "2025-02-18",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1333",
    Titulares: "Ana García",
    Tipo: "online",
    Saldo: 3200,
    Estado: "Inactivo",
    Fecha: "2025-02-17",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1334",
    Titulares: "Carlos López",
    Tipo: "estándar",
    Saldo: 4500,
    Estado: "Activo",
    Fecha: "2025-02-15",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1335",
    Titulares: "Marta Ruiz",
    Tipo: "estándar",
    Saldo: 2700,
    Estado: "Activo",
    Fecha: "2025-02-14",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1336",
    Titulares: "Pedro Jiménez",
    Tipo: "online",
    Saldo: 5900,
    Estado: "Activo",
    Fecha: "2025-02-13",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1337",
    Titulares: "Laura García",
    Tipo: "estándar",
    Saldo: 1200,
    Estado: "Inactivo",
    Fecha: "2025-02-12",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1338",
    Titulares: "Sergio Fernández",
    Tipo: "online",
    Saldo: 4200,
    Estado: "Activo",
    Fecha: "2025-02-11",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1339",
    Titulares: "Julia López",
    Tipo: "online",
    Saldo: 2500,
    Estado: "Activo",
    Fecha: "2025-02-10",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1340",
    Titulares: "Raúl Sánchez",
    Tipo: "estándar",
    Saldo: 8000,
    Estado: "Activo",
    Fecha: "2025-02-09",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1341",
    Titulares: "Elena Morales",
    Tipo: "online",
    Saldo: 3600,
    Estado: "Inactivo",
    Fecha: "2025-02-08",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1342",
    Titulares: "Víctor Rodríguez",
    Tipo: "estándar",
    Saldo: 4800,
    Estado: "Activo",
    Fecha: "2025-02-07",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1343",
    Titulares: "Beatriz Martínez",
    Tipo: "online",
    Saldo: 3100,
    Estado: "Inactivo",
    Fecha: "2025-02-06",
  },
  {
    ID: "ES91 2100 0418 4502 0005 1344",
    Titulares: "Javier Pérez",
    Tipo: "estándar",
    Saldo: 6500,
    Estado: "Activo",
    Fecha: "2025-02-05",
  },
];

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
</script>
