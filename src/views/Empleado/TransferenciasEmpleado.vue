<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">transferencias</h1>
    <FiltroEmpleado :tableName="`transferencias`" :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado :headers="tableHeaders" :rows="filteredRows" :tableName="'transferencias'"/>
  </div>

  <FooterEmpleado />
</template>

<script setup>
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";
import { ref, computed, onMounted } from "vue";

// CABECERA
const tableHeaders = ["ID","Cuenta Emisor","Cuenta Beneficiario","Importe","Estado","Fecha","Concepto"];

// DATOS
const transferencias = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/transferencias');
    if (response.ok) {
      const data = await response.json();
      transferencias.value = data;  
    } else {
      console.error('Error al obtener los datos:', response.status);
    }
  } catch (error) {
    console.error('Error en la petición:', error);
  }
});

/*const tableRows = ref([
  {
    ID: 1,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1332",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1333",
    Importe: 500,
    Estado: "Completada",
    Fecha: "2025-02-18",
    Concepto: "Pago de factura",
  },
  {
    ID: 2,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1333",
    "Cuenta Beneficiario": "GB29 XABC 1016 1338 8765 43",
    Importe: 1200,
    Estado: "Completada",
    Fecha: "2025-02-17",
    Concepto: "Pago internacional a proveedor",
  },
  {
    ID: 3,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1334",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1335",
    Importe: 250,
    Estado: "Pendiente",
    Fecha: "2025-02-16",
    Concepto: "Transferencia interna",
  },
  {
    ID: 4,
    "Cuenta Emisor": "US64 1234 5678 9876 5432 10",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1336",
    Importe: 3000,
    Estado: "Completada",
    Fecha: "2025-02-15",
    Concepto: "Transferencia internacional",
  },
  {
    ID: 5,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1337",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1338",
    Importe: 850,
    Estado: "Completada",
    Fecha: "2025-02-14",
    Concepto: "Pago de préstamo personal",
  },
  {
    ID: 6,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1338",
    "Cuenta Beneficiario": "DE44 1234 5678 9012 3456 78",
    Importe: 600,
    Estado: "Pendiente",
    Fecha: "2025-02-13",
    Concepto: "Pago de servicios a proveedor internacional",
  },
  {
    ID: 7,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1339",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1340",
    Importe: 1200,
    Estado: "Completada",
    Fecha: "2025-02-12",
    Concepto: "Transferencia interna",
  },
  {
    ID: 8,
    "Cuenta Emisor": "GB29 XABC 1016 1338 8765 43",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1341",
    Importe: 450,
    Estado: "Completada",
    Fecha: "2025-02-11",
    Concepto: "Pago internacional de factura",
  },
  {
    ID: 9,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1342",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1343",
    Importe: 1500,
    Estado: "Pendiente",
    Fecha: "2025-02-10",
    Concepto: "Pago de servicio",
  },
  {
    ID: 10,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1344",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1345",
    Importe: 400,
    Estado: "Completada",
    Fecha: "2025-02-09",
    Concepto: "Transferencia interna entre cuentas",
  },
  {
    ID: 11,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1345",
    "Cuenta Beneficiario": "FR76 3000 4001 0200 0001 89",
    Importe: 2000,
    Estado: "Pendiente",
    Fecha: "2025-02-08",
    Concepto: "Pago de proveedor",
  },
  {
    ID: 12,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1346",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1347",
    Importe: 750,
    Estado: "Completada",
    Fecha: "2025-02-07",
    Concepto: "Transferencia interna",
  },
  {
    ID: 13,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1348",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1349",
    Importe: 600,
    Estado: "Completada",
    Fecha: "2025-02-06",
    Concepto: "Pago de deuda",
  },
  {
    ID: 14,
    "Cuenta Emisor": "US64 1234 5678 9876 5432 10",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1350",
    Importe: 2200,
    Estado: "Pendiente",
    Fecha: "2025-02-05",
    Concepto: "Pago a proveedor extranjero",
  },
  {
    ID: 15,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1351",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1352",
    Importe: 1000,
    Estado: "Completada",
    Fecha: "2025-02-04",
    Concepto: "Transferencia interna",
  },
  {
    ID: 16,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1353",
    "Cuenta Beneficiario": "GB29 XABC 1016 1338 8765 43",
    Importe: 1500,
    Estado: "Pendiente",
    Fecha: "2025-02-03",
    Concepto: "Pago internacional a proveedor",
  },
  {
    ID: 17,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1354",
    "Cuenta Beneficiario": "FR76 3000 4001 0200 0001 89",
    Importe: 800,
    Estado: "Completada",
    Fecha: "2025-02-02",
    Concepto: "Pago de servicio internacional",
  },
  {
    ID: 18,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1355",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1356",
    Importe: 500,
    Estado: "Pendiente",
    Fecha: "2025-02-01",
    Concepto: "Transferencia interna",
  },
  {
    ID: 19,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1357",
    "Cuenta Beneficiario": "ES91 2100 0418 4502 0005 1358",
    Importe: 2000,
    Estado: "Completada",
    Fecha: "2025-01-31",
    Concepto: "Pago de factura",
  },
  {
    ID: 20,
    "Cuenta Emisor": "ES91 2100 0418 4502 0005 1359",
    "Cuenta Beneficiario": "DE44 1234 5678 9012 3456 78",
    Importe: 1200,
    Estado: "Completada",
    Fecha: "2025-01-30",
    Concepto: "Transferencia internacional a proveedor",
  },
]);*/

const filtro = [
    { COLUMN_NAME: "ID_movimiento", DATA_TYPE: "int", TITULO: "ID: " },
    { COLUMN_NAME: "ID_cuenta_emisor", DATA_TYPE: "varchar", TITULO: "Emisor: " },
    { COLUMN_NAME: "ID_cuenta_beneficiario", DATA_TYPE: "varchar", TITULO: "Beneficiario: " },
    { COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar", TITULO: "Núm Tarjeta: " },
    { COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe desde: " },
    { COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe hasta: " },
    { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha desde: " },
    { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha hasta: " },
];

//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return transferencias.value.filter((row) => {
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
