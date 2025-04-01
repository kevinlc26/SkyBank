<template>
  <HeaderEmpleado />
  <div class="main">
    <p>IGUAL FALTARIA AÑADIR TITULAR (NOMBRE Y DNI)!!!!!!!!!!!!!</p>

    <h1 style="display: inline">movimientos</h1>

    <FiltroEmpleado :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado :headers="tableHeaders" :rows="filteredRows" :tableName="'movimientos'"
    />
  </div>
  <FooterEmpleado />
</template>

<script setup>
import { ref, computed } from "vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";

const tableHeaders = [ "ID", "Número de cuenta", "Número Tarjeta", "Tipo", "Importe", "Fecha", "Concepto"];

const tableRows = ref([
  {
    ID: 1,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1332",
    "Número Tarjeta": "1234 5678 9012 3456",
    Tipo: "Pago",
    Importe: 150,
    Fecha: "2025-02-18",
    Concepto: "Compra en supermercado",
  },
  {
    ID: 2,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1333",
    "Número Tarjeta": "2345 6789 0123 4567",
    Tipo: "Ingreso",
    Importe: 500,
    Fecha: "2025-02-17",
    Concepto: "Depósito en efectivo",
  },
  {
    ID: 3,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1334",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 200,
    Fecha: "2025-02-16",
    Concepto: "Transferencia a cuenta corriente",
  },
  {
    ID: 4,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1335",
    "Número Tarjeta": "3456 7890 1234 5678",
    Tipo: "Pago",
    Importe: 75,
    Fecha: "2025-02-15",
    Concepto: "Compra en tienda online",
  },
  {
    ID: 5,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1336",
    "Número Tarjeta": null,
    Tipo: "Ingreso",
    Importe: 300,
    Fecha: "2025-02-14",
    Concepto: "Ingreso por transferencia",
  },
  {
    ID: 6,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1337",
    "Número Tarjeta": "4567 8901 2345 6789",
    Tipo: "Pago",
    Importe: 450,
    Fecha: "2025-02-13",
    Concepto: "Compra en restaurante",
  },
  {
    ID: 7,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1338",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 1500,
    Fecha: "2025-02-12",
    Concepto: "Pago de deuda",
  },
  {
    ID: 8,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1339",
    "Número Tarjeta": "5678 9012 3456 7890",
    Tipo: "Ingreso",
    Importe: 350,
    Fecha: "2025-02-11",
    Concepto: "Ingreso por venta",
  },
  {
    ID: 9,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1340",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 1000,
    Fecha: "2025-02-10",
    Concepto: "Transferencia de pago",
  },
  {
    ID: 10,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1341",
    "Número Tarjeta": "6789 0123 4567 8901",
    Tipo: "Ingreso",
    Importe: 200,
    Fecha: "2025-02-09",
    Concepto: "Ingreso por venta online",
  },
  {
    ID: 11,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1342",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 350,
    Fecha: "2025-02-08",
    Concepto: "Pago en efectivo",
  },
  {
    ID: 12,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1343",
    "Número Tarjeta": "7890 1234 5678 9012",
    Tipo: "Pago",
    Importe: 250,
    Fecha: "2025-02-07",
    Concepto: "Compra en tienda física",
  },
  {
    ID: 13,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1344",
    "Número Tarjeta": null,
    Tipo: "Ingreso",
    Importe: 450,
    Fecha: "2025-02-06",
    Concepto: "Ingreso por transferencia",
  },
  {
    ID: 14,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1345",
    "Número Tarjeta": "8901 2345 6789 0123",
    Tipo: "Pago",
    Importe: 500,
    Fecha: "2025-02-05",
    Concepto: "Compra en línea",
  },
  {
    ID: 15,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1346",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 800,
    Fecha: "2025-02-04",
    Concepto: "Pago a proveedor",
  },
  {
    ID: 16,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1347",
    "Número Tarjeta": "9012 3456 7890 1234",
    Tipo: "Ingreso",
    Importe: 1200,
    Fecha: "2025-02-03",
    Concepto: "Ingreso de salario",
  },
  {
    ID: 17,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1348",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 650,
    Fecha: "2025-02-02",
    Concepto: "Pago de alquiler",
  },
  {
    ID: 18,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1349",
    "Número Tarjeta": "0123 4567 8901 2345",
    Tipo: "Ingreso",
    Importe: 1000,
    Fecha: "2025-02-01",
    Concepto: "Ingreso por trabajo freelance",
  },
  {
    ID: 19,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1350",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 1200,
    Fecha: "2025-01-31",
    Concepto: "Transferencia interna",
  },
  {
    ID: 20,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1351",
    "Número Tarjeta": "1234 5678 9012 3456",
    Tipo: "Pago",
    Importe: 400,
    Fecha: "2025-01-30",
    Concepto: "Compra en tienda online",
  },
  {
    ID: 21,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1352",
    "Número Tarjeta": null,
    Tipo: "Ingreso",
    Importe: 250,
    Fecha: "2025-01-29",
    Concepto: "Ingreso por pago de cliente",
  },
  {
    ID: 22,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1353",
    "Número Tarjeta": "2345 6789 0123 4567",
    Tipo: "Pago",
    Importe: 180,
    Fecha: "2025-01-28",
    Concepto: "Compra de tecnología",
  },
  {
    ID: 23,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1354",
    "Número Tarjeta": null,
    Tipo: "Ingreso",
    Importe: 500,
    Fecha: "2025-01-27",
    Concepto: "Transferencia bancaria",
  },
  {
    ID: 24,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1355",
    "Número Tarjeta": "3456 7890 1234 5678",
    Tipo: "Pago",
    Importe: 750,
    Fecha: "2025-01-26",
    Concepto: "Compra en restaurante",
  },
  {
    ID: 25,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1356",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 950,
    Fecha: "2025-01-25",
    Concepto: "Pago de servicios públicos",
  },
  {
    ID: 26,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1357",
    "Número Tarjeta": "4567 8901 2345 6789",
    Tipo: "Ingreso",
    Importe: 300,
    Fecha: "2025-01-24",
    Concepto: "Ingreso por transferencia",
  },
  {
    ID: 27,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1358",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 600,
    Fecha: "2025-01-23",
    Concepto: "Pago a proveedores",
  },
  {
    ID: 28,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1359",
    "Número Tarjeta": "5678 9012 3456 7890",
    Tipo: "Ingreso",
    Importe: 1000,
    Fecha: "2025-01-22",
    Concepto: "Ingreso de cliente",
  },
  {
    ID: 29,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1360",
    "Número Tarjeta": null,
    Tipo: "Pago",
    Importe: 500,
    Fecha: "2025-01-21",
    Concepto: "Pago por transferencia",
  },
  {
    ID: 30,
    "Número de cuenta": "ES91 2100 0418 4502 0005 1361",
    "Número Tarjeta": "6789 0123 4567 8901",
    Tipo: "Pago",
    Importe: 200,
    Fecha: "2025-01-20",
    Concepto: "Compra en librería",
  },
]);

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
  return tableRows.value.filter((row) => {
    return Object.keys(filtroActivo.value).every((key) => {
      const filtroValor = filtroActivo.value[key]; // Valor ingresado en el filtro
      const rowValor = row[key]; // Valor en la tabla

      if (!filtroValor) return true; // Si no hay filtro, no aplicar

      if (rowValor === undefined || rowValor === null) return false; // Si el valor en la tabla es null/undefined, descartar

      const filtroStr = filtroValor.toString().trim().toLowerCase();
      
      // Comparar números
      if (typeof rowValor === "number") {
        return rowValor === Number(filtroValor);
      }

      // Comparar fechas
      if (key.includes("Fecha") || key.includes("fecha") || rowValor instanceof Date) {
        const rowDate = new Date(rowValor).toISOString().split("T")[0]; // Convertir a YYYY-MM-DD
        return rowDate === filtroStr;
      }

      // Comparar booleanos (checkbox)
      if (typeof rowValor === "boolean") {
        return rowValor === (filtroValor === "true");
      }

      // Comparar texto
      return rowValor.toString().toLowerCase().includes(filtroStr);
    });
  });
});

// Recibir datos del filtro y actualizar `filtroActivo`
const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

</script>
