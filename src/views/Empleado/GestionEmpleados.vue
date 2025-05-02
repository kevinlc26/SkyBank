<template>
    <HeaderEmpleado />
    <div class="main">
        <h1 style="display: inline">Gestión de Empleados</h1>
        <button style="all: unset" @click="openAddModal">
        <img src="../../assets/icons/add.svg" alt="add" width="24" height="24" title="Añadir"/>
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
const tableHeaders = ["ID","Número Identificación","Nombre","Apellido/s", "Nacionalidad","Fecha de Nacimiento","Teléfono","Email","Dirección","Rol","Número de la Seguridad Social","Fecha de Contratación", "Estado"];


// API GET
const empleados = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/empleados');
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

// DATOS DE FILTRO
const filtro = [  
  { KEY: "ID_empleado", COLUMN_NAME: "ID_empleado", DATA_TYPE: "int", TITULO: "ID: " },  
  { KEY: "Num_ident", COLUMN_NAME: "Num_ident", DATA_TYPE: "varchar", TITULO: "Número identificación: " },  
  { KEY: "Nombre", COLUMN_NAME: "Nombre", DATA_TYPE: "varchar", TITULO: "Nombre: " },  
  { KEY: "Apellidos", COLUMN_NAME: "Apellidos", DATA_TYPE: "varchar", TITULO: "Apellido/s: " },  
  { KEY: "Estado_empleado", COLUMN_NAME: "Estado_empleado", DATA_TYPE: "enum", TITULO: "Estado: ", OPTIONS: ["Activo", "Inactivo"] },  
  { KEY: "Nacionalidad", COLUMN_NAME: "Nacionalidad", DATA_TYPE: "varchar", TITULO: "Nacionalidad: " },  
  { KEY: "FechaNacimientoDesde", COLUMN_NAME: "Fecha_nacimiento", DATA_TYPE: "date", TITULO: "Fecha de nacimiento desde: " },  
  { KEY: "FechaNacimientoHasta", COLUMN_NAME: "Fecha_nacimiento", DATA_TYPE: "date", TITULO: "Fecha de nacimiento hasta: " },  
  { KEY: "Telefono", COLUMN_NAME: "Telefono", DATA_TYPE: "varchar", TITULO: "Teléfono: " },  
  { KEY: "Email", COLUMN_NAME: "Email", DATA_TYPE: "varchar", TITULO: "Email: " },  
  { KEY: "Direccion", COLUMN_NAME: "Direccion", DATA_TYPE: "varchar", TITULO: "Dirección: " },  
  { KEY: "Rol", COLUMN_NAME: "Rol", DATA_TYPE: "enum", TITULO: "Rol: ", OPTIONS: ["Administrador", "Gestor"] },  
  { KEY: "Num_SS", COLUMN_NAME: "Num_SS", DATA_TYPE: "varchar", TITULO: "Número SS: " },  
  { KEY: "FechaContratacionDesde", COLUMN_NAME: "Fecha_contratacion", DATA_TYPE: "date", TITULO: "Fecha de contratación desde: " },  
  { KEY: "FechaContratacionHasta", COLUMN_NAME: "Fecha_contratacion", DATA_TYPE: "date", TITULO: "Fecha de contratación hasta: " }  
];


//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return empleados.value.filter((row) => {
    return Object.entries(filtroActivo.value).every(([key, filtroValor]) => {
      if (!filtroValor) return true;

      // RANGOS DE FECHAS
      if (key === "FechaNacimientoDesde") {
        return new Date(row.Fecha_nacimiento) >= new Date(filtroValor);
      }
      if (key === "FechaNacimientoHasta") {
        return new Date(row.Fecha_nacimiento) <= new Date(filtroValor);
      }
      if (key === "FechaContratacionDesde") {
        return new Date(row.Fecha_contratacion) >= new Date(filtroValor);
      }
      if (key === "FechaContratacionHasta") {
        return new Date(row.Fecha_contratacion) <= new Date(filtroValor);
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
