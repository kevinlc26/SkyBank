<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">movimientos</h1>

    <FiltroEmpleado :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado :headers="tableHeaders" :rows="filteredRows" :tableName="'movimientos'"
    />
  </div>
  <FooterEmpleado />
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";

// CABECERA
const tableHeaders = [ "ID", "Cuenta Emisor", "Titular Emisor", "Cuenta beneficiario", "Titular beneficiario", "Número Tarjeta", "Tipo", "Importe", "Estado","Fecha de realización", "Concepto"];

//DATOS
const movimientos = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/movimientos');
    if (response.ok) {
      const data = await response.json();
      movimientos.value = data;  
    } else {
      console.error('Error al obtener los datos:', response.status);
    }
  } catch (error) {
    console.error('Error en la petición:', error);
  }
});

const filtro = [
    { COLUMN_NAME: "ID_movimiento", DATA_TYPE: "int", TITULO: "ID: " },
    { COLUMN_NAME: "ID_cuenta_emisor", DATA_TYPE: "varchar", TITULO: "Emisor: " },
    { COLUMN_NAME: "ID_cuenta_beneficiario", DATA_TYPE: "varchar", TITULO: "Beneficiario: " },
    { COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar", TITULO: "Núm Tarjeta: " },
    { COLUMN_NAME: "Tipo_movimiento", DATA_TYPE: "enum", TITULO: "Tipo: " , OPTIONS: ["transferencia", "cobro", "pago", "ingreso"]},
    { COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe desde: " },
    { COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe hasta: " },
    { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha desde: " },
    { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha hasta: " },
];

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return movimientos.value.filter((row) => {
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
