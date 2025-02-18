<template>
    <table class="tabla">
      <!-- Cabecera de la tabla -->
      <thead>
        <tr>
          <th v-for="(header, index) in headers" :key="index">{{ header }}</th>
        </tr>
      </thead>
  
      <!-- Cuerpo de la tabla -->
      <tbody>
        <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
          <td v-for="(column, colIndex) in headers" :key="colIndex">
            {{ row[column] || '-' }}
          </td>
        </tr>
      </tbody>
    </table>
  </template>
  
  <script>
  export default {
    props: {
      headers: Array, // Array de cabeceras
      rows: Array,    // Array de filas de datos
    },
  };
  </script>
  
  <style scoped>
  .tabla {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  
  .tabla th, .tabla td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }
  
  .tabla th {
    background-color: #263E33;
    color: white;
  }
  
  .tabla tbody tr:nth-child(even) {
    background-color: #f9f9f9;
  }
  
  .tabla tbody tr:hover {
    background-color: #f1f1f1;
  }
  </style>
  