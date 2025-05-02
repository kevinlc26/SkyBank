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
  { KEY: "ID_cliente", COLUMN_NAME: "ID_cliente", DATA_TYPE: "int", TITULO: "ID: " },
  { KEY: "Num_ident", COLUMN_NAME: "Num_ident", DATA_TYPE: "varchar", TITULO: "DNI/NIE: " },
  { KEY: "Nombre", COLUMN_NAME: "Nombre", DATA_TYPE: "varchar", TITULO: "Nombre: " },
  { KEY: "Apellido", COLUMN_NAME: "Apellido", DATA_TYPE: "varchar", TITULO: "Apellido/s: " },
  { KEY: "Estado_Clientes", COLUMN_NAME: "Estado_Clientes", DATA_TYPE: "varchar", TITULO: "Estado: " },
  { KEY: "Nacionalidad", COLUMN_NAME: "Nacionalidad", DATA_TYPE: "varchar", TITULO: "Nacionalidad: " },
  { KEY: "FechaNacimientoDesde", COLUMN_NAME: "Fecha_nacimiento", DATA_TYPE: "date", TITULO: "Fecha nacimiento desde: " },
  { KEY: "FechaNacimientoHasta", COLUMN_NAME: "Fecha_nacimiento", DATA_TYPE: "date", TITULO: "Fecha nacimiento hasta: " },
  { KEY: "Telefono", COLUMN_NAME: "Telefono", DATA_TYPE: "telf", TITULO: "Teléfono: " },
  { KEY: "Email", COLUMN_NAME: "Email", DATA_TYPE: "email", TITULO: "Email: " },
  { KEY: "Direccion", COLUMN_NAME: "Direccion", DATA_TYPE: "varchar", TITULO: "Dirección: " },
];

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return clientes.value.filter((row) => {
    const filtros = filtroActivo.value;

    return Object.keys(filtros).every((key) => {
      const valorFiltro = filtros[key];
      if (valorFiltro === null || valorFiltro === undefined || valorFiltro === "") return true;

      // FILTROS DE RANGO
      if (key === "FechaNacimientoDesde") {
        return new Date(row.Fecha_nacimiento) >= new Date(valorFiltro);
      }
      if (key === "FechaNacimientoHasta") {
        return new Date(row.Fecha_nacimiento) <= new Date(valorFiltro);
      }

      // BUSCAR CAMPO EQUIVALENTE EN EL ROW
      const rowValor = row[key] ?? row[
        filtro.find(f => f.KEY === key)?.COLUMN_NAME
      ];

      if (rowValor === undefined || rowValor === null) return false;

      const rowStr = rowValor.toString().toLowerCase();
      const filtroStr = valorFiltro.toString().trim().toLowerCase();

      return rowStr.includes(filtroStr);
    });
  });
});



// Recibir datos del filtro y actualizar `filtroActivo`
const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>

<style></style>
