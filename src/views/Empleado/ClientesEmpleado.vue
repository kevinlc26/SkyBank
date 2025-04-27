<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">clientes</h1>

    <FiltroEmpleado :tableName="`clientes`" :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado :headers="tableHeaders" :rows="filteredRows" :tableName="'clientes'"/>
  </div>
  <FooterEmpleado />
</template>

<script setup>
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";
import { ref, computed, onMounted } from "vue";

// DATOS
const tableHeaders = ref(["ID", "Número de Identidad", "Nombre", "Apellido/s", "Nacionalidad", "Fecha de Nacimiento", "Teléfono", "Email", "Dirección", "Estado"]);

const clientes = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/clientes');
    if (response.ok) {
      const data = await response.json();
      clientes.value = data;  
    } else {
      console.error('Error al obtener los datos:', response.status);
    }
  } catch (error) {
    console.error('Error en la petición:', error);
  }
});

// VARIABLES DE FILTRO
const filtro = [
    { COLUMN_NAME: "ID_cliente", DATA_TYPE: "int", TITULO: "ID: " },
    { COLUMN_NAME: "Num_ident", DATA_TYPE: "varchar", TITULO: "DNI/NIE: " },
    { COLUMN_NAME: "Nombre", DATA_TYPE: "varchar", TITULO: "Nombre: " },
    { COLUMN_NAME: "Apellido", DATA_TYPE: "varchar", TITULO: "Apellido/s: " },
    { COLUMN_NAME: "Estado_Clientes", DATA_TYPE: "varchar", TITULO: "Estado: " },
    { COLUMN_NAME: "Nacionalidad", DATA_TYPE: "varchar", TITULO: "Nacionalidad: " },
    { COLUMN_NAME: "Fecha_nacimiento", DATA_TYPE: "date", TITULO: "Fecha nacimiento desde: " },
    { COLUMN_NAME: "Fecha_nacimiento", DATA_TYPE: "date", TITULO: "Fecha nacimiento hasta: " },
    { COLUMN_NAME: "Telefono", DATA_TYPE: "telf", TITULO: "Teléfono: " },
    { COLUMN_NAME: "Email", DATA_TYPE: "email", TITULO: "Email: " },
    { COLUMN_NAME: "Direccion", DATA_TYPE: "varchar", TITULO: "Direccion: " },
];

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return clientes.value.filter((row) => {
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


// Recibir datos del filtro y actualizar `filtroActivo`
const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>

<style></style>
