<template>
  <HeaderEmpleado />
  <div class="main">
    <h1>clientes</h1>

    <FiltroEmpleado />
    <TablaEmpleado
      :headers="tableHeaders"
      :rows="tableRows"
      :tableName="'clientes'"
    />
  </div>
  <FooterEmpleado />
</template>

<script setup>
import FooterEmpleado from "../components/FooterEmpleado.vue";
import HeaderEmpleado from "../components/HeaderEmpleado.vue";
import FiltroEmpleado from "../components/FiltroEmpleado.vue";
import TablaEmpleado from "../components/TablaEmpleado.vue";
import { ref } from "vue";

// Definir las cabeceras de la tabla
const tableHeaders = ref([
  "id",
  "DNI/NIE",
  "Nombre",
  "Apellidos",
  "Nacionalidad",
  "Fecha nacimiento",
  "Teléfono",
  "Email",
  "Dirección",
]);

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
</script>

<style></style>
