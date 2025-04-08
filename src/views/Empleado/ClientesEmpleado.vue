<template>
  <HeaderEmpleado />
  <div class="main">
    <h1 style="display: inline">clientes</h1>

    <FiltroEmpleado :filtro="filtro" @filtrarDatos="aplicarFiltro"/>
    <TablaEmpleado :headers="tableHeaders" :rows="filteredRows" :tableName="'clientes'"/>
  </div>
  <FooterEmpleado />
</template>

<script setup>
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";
import { ref, computed } from "vue";

// Definir las cabeceras de la tabla
const tableHeaders = ref(["id", "Número de Identificación", "Nombre", "Apellidos", "Nacionalidad", "Fecha nacimiento", "Teléfono", "Email", "Dirección"]);

// Definir las filas de la tabla
const tableRows = ref([
  {
    id: 1,
    "DNI/NIE": "X1234567A",
    Nombre: "Juan",
    Apellidos: "Pérez",
    Nacionalidad: "Española",
    "Fecha nacimiento": "1990-05-15",
    Teléfono: "600123456",
    Email: "juan.perez@example.com",
    Dirección: "Calle Ficticia 1, Madrid",
  },
  {
    id: 2,
    "DNI/NIE": "X2345678B",
    Nombre: "Sara",
    Apellidos: "Smith",
    Nacionalidad: "Británica",
    "Fecha nacimiento": "1987-02-20",
    Teléfono: "600234567",
    Email: "sara.smith@example.com",
    Dirección: "Baker Street 221B, Londres",
  },
  {
    id: 3,
    "DNI/NIE": "X3456789C",
    Nombre: "Carlos",
    Apellidos: "López",
    Nacionalidad: "Argentina",
    "Fecha nacimiento": "1992-07-30",
    Teléfono: "600345678",
    Email: "carlos.lopez@example.com",
    Dirección: "Calle Sol 3, Valencia",
  },
  {
    id: 4,
    "DNI/NIE": "X4567890D",
    Nombre: "Yuki",
    Apellidos: "Tanaka",
    Nacionalidad: "Japonesa",
    "Fecha nacimiento": "1994-11-13",
    Teléfono: "600456789",
    Email: "yuki.tanaka@example.com",
    Dirección: "Shibuya, Tokio",
  },
  {
    id: 5,
    "DNI/NIE": "X5678901E",
    Nombre: "Pedro",
    Apellidos: "Jiménez",
    Nacionalidad: "Chilena",
    "Fecha nacimiento": "1980-01-25",
    Teléfono: "600567890",
    Email: "pedro.jimenez@example.com",
    Dirección: "Calle Mar 5, Bilbao",
  },
  {
    id: 6,
    "DNI/NIE": "X6789012F",
    Nombre: "Amina",
    Apellidos: "Khan",
    Nacionalidad: "Paquistaní",
    "Fecha nacimiento": "1985-06-10",
    Teléfono: "600678901",
    Email: "amina.khan@example.com",
    Dirección: "Karachi, Pakistán",
  },
  {
    id: 7,
    "DNI/NIE": "X7890123G",
    Nombre: "Alex",
    Apellidos: "Johnson",
    Nacionalidad: "Estadounidense",
    "Fecha nacimiento": "1982-04-04",
    Teléfono: "600789012",
    Email: "alex.johnson@example.com",
    Dirección: "Fifth Avenue 10, Nueva York",
  },
  {
    id: 8,
    "DNI/NIE": "X8901234H",
    Nombre: "Ming",
    Apellidos: "Wang",
    Nacionalidad: "China",
    "Fecha nacimiento": "1995-12-30",
    Teléfono: "600890123",
    Email: "ming.wang@example.com",
    Dirección: "Beijing, China",
  },
  {
    id: 9,
    "DNI/NIE": "X9012345I",
    Nombre: "Olga",
    Apellidos: "Petrova",
    Nacionalidad: "Rusa",
    "Fecha nacimiento": "1989-08-22",
    Teléfono: "600901234",
    Email: "olga.petrova@example.com",
    Dirección: "Moscú, Rusia",
  },
  {
    id: 10,
    "DNI/NIE": "X0123456J",
    Nombre: "Ahmed",
    Apellidos: "Hassan",
    Nacionalidad: "Egipcia",
    "Fecha nacimiento": "1990-12-15",
    Teléfono: "600012345",
    Email: "ahmed.hassan@example.com",
    Dirección: "Cairo, Egipto",
  },
  {
    id: 11,
    "DNI/NIE": "X1234567K",
    Nombre: "Emma",
    Apellidos: "Miller",
    Nacionalidad: "Alemana",
    "Fecha nacimiento": "1987-09-05",
    Teléfono: "600123456",
    Email: "emma.miller@example.com",
    Dirección: "Berlin, Alemania",
  },
  {
    id: 12,
    "DNI/NIE": "X2345678L",
    Nombre: "Nia",
    Apellidos: "Oliviera",
    Nacionalidad: "Brasileña",
    "Fecha nacimiento": "1993-03-14",
    Teléfono: "600234567",
    Email: "nia.oliveira@example.com",
    Dirección: "Sao Paulo, Brasil",
  },
  {
    id: 13,
    "DNI/NIE": "X3456789M",
    Nombre: "David",
    Apellidos: "Lee",
    Nacionalidad: "Canadiense",
    "Fecha nacimiento": "1980-10-01",
    Teléfono: "600345678",
    Email: "david.lee@example.com",
    Dirección: "Toronto, Canadá",
  },
  {
    id: 14,
    "DNI/NIE": "X4567890N",
    Nombre: "Marie",
    Apellidos: "Lemoine",
    Nacionalidad: "Francesa",
    "Fecha nacimiento": "1991-02-17",
    Teléfono: "600456789",
    Email: "marie.lemoine@example.com",
    Dirección: "París, Francia",
  },
  {
    id: 15,
    "DNI/NIE": "X5678901O",
    Nombre: "Mohammed",
    Apellidos: "Al-Mansoor",
    Nacionalidad: "Emiratos Árabes Unidos",
    "Fecha nacimiento": "1994-11-21",
    Teléfono: "600567890",
    Email: "mohammed.almansoor@example.com",
    Dirección: "Dubái, Emiratos Árabes Unidos",
  },
]);

const filtro = [
    { COLUMN_NAME: "ID_cliente", DATA_TYPE: "int", TITULO: "ID: " },
    { COLUMN_NAME: "Num_ident", DATA_TYPE: "varchar", TITULO: "DNI/NIE: " },
    { COLUMN_NAME: "Nombre", DATA_TYPE: "varchar", TITULO: "Nombre: " },
    { COLUMN_NAME: "Apellido", DATA_TYPE: "varchar", TITULO: "Apellido/s: " },
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

<style></style>
