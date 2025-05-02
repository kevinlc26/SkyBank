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
const tableHeaders = [ "ID", "Cuenta Emisor", "Titular Emisor", "Cuenta Beneficiario", "Titular Beneficiario", "Número de Tarjeta", "Tipo", "Importe(€)","Fecha de Realización", "Concepto", "Estado"];

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
  { KEY: "ID_movimiento", COLUMN_NAME: "ID_movimiento", DATA_TYPE: "int", TITULO: "ID: " },
  { KEY: "ID_cuenta_emisor", COLUMN_NAME: "ID_cuenta_emisor", DATA_TYPE: "varchar", TITULO: "Cuenta Emisor: " },
  { KEY: "Titular_Emisor", COLUMN_NAME: "Titular_Emisor", DATA_TYPE: "varchar", TITULO: "Titular Emisor: " },
  { KEY: "ID_cuenta_beneficiario", COLUMN_NAME: "ID_cuenta_beneficiario", DATA_TYPE: "varchar", TITULO: "Cuenta Beneficiario: " },
  { KEY: "Titular_Beneficiario", COLUMN_NAME: "Titular_Beneficiario", DATA_TYPE: "varchar", TITULO: "Titular Beneficiario: " },
  { KEY: "ID_tarjeta", COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar", TITULO: "Núm Tarjeta: " },
  { KEY: "Tipo_movimiento", COLUMN_NAME: "Tipo_movimiento", DATA_TYPE: "enum", TITULO: "Tipo: ", OPTIONS: ["Cobro", "Comisión", "Recibo", "Traspaso", "Pago", "Ingreso"] },
  { KEY: "Estado", COLUMN_NAME: "Estado", DATA_TYPE: "enum", TITULO: "Estado: ", OPTIONS: ["Activo", "Bloqueado"] },
  { KEY: "ImporteDesde", COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe desde: " },
  { KEY: "ImporteHasta", COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe hasta: " },
  { KEY: "FechaDesde", COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha desde: " },
  { KEY: "FechaHasta", COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha hasta: " },
];


//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return movimientos.value.filter((row) => {
    return Object.entries(filtroActivo.value).every(([key, filtroValor]) => {
      if (!filtroValor) return true;

      // Rango de fechas
      if (key === "FechaDesde") {
        return new Date(row.Fecha_movimiento) >= new Date(filtroValor);
      }
      if (key === "FechaHasta") {
        return new Date(row.Fecha_movimiento) <= new Date(filtroValor);
      }

      // Rango de importe
      if (key === "ImporteDesde") {
        return Number(row.Importe) >= Number(filtroValor);
      }
      if (key === "ImporteHasta") {
        return Number(row.Importe) <= Number(filtroValor);
      }

      // Resto de filtros
      const columna = filtro.find(f => f.KEY === key)?.COLUMN_NAME || key;
      const rowValor = row[columna];

      if (rowValor === undefined || rowValor === null) return false;

      const rowStr = rowValor.toString().toLowerCase();
      const filtroStr = filtroValor.toString().trim().toLowerCase();

      return rowStr.includes(filtroStr);
    });
  });
});



// Recibir datos del filtro y actualizar `filtroActivo`
const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>
