<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
        <h1>TRANSFERENCIAS</h1>
      <div class="contenedorT">
        <MenuTransferencias />
        <div class="recuadro-central gris">
          <h3>Consultar Transferencias</h3>
          <br>
          <div class="filtros">
            <input type="text" v-model="busqueda" placeholder="Buscar por concepto..." />
            <input type="number" v-model="cantidad" placeholder="Cantidad mínima" />
            <input type="date" v-model="fecha" placeholder="Fecha" />
            <select v-model="tipo">
              <option value="">Todos</option>
              <option value="recibido">Recibidos</option>
              <option value="enviado">Enviados</option>
            </select>
            <button class="btn-orange">Buscar</button>
          </div>

          <!-- Nueva tabla para listar transferencias -->
          <table class="styled-table">
            <thead>
              <tr>
                <th>Concepto</th>
                <th>Cantidad (€)</th>
                <th>Fecha</th>
                <th>Tipo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="transferencia in transferenciasFiltradas" :key="transferencia.id">
                <td>{{ transferencia.concepto }}</td>
                <td>{{ transferencia.cantidad }}</td>
                <td>{{ transferencia.fecha }}</td>
                <td>{{ transferencia.tipo }}</td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
  <FooterInicio />
</template>

<script setup>
import { ref, computed } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTransferencias from "../../components/Cliente/MenuTransferencia.vue";

// Reactive state
const busqueda = ref("");
const cantidad = ref(null);
const fecha = ref("");
const tipo = ref("");
const transferencias = ref([
{ id: 1, concepto: "Pago de servicio", cantidad: 500, fecha: "2024-03-01", tipo: "enviado" },
{ id: 2, concepto: "Salario", cantidad: 1500, fecha: "2024-02-25", tipo: "recibido" },
{ id: 3, concepto: "Renta", cantidad: 1000, fecha: "2024-02-20", tipo: "enviado" },
]);

// Computed property for filtered transfers
const transferenciasFiltradas = computed(() => {
return transferencias.value.filter(t => {
  return (
    (!busqueda.value || t.concepto.toLowerCase().includes(busqueda.value.toLowerCase())) &&
    (!cantidad.value || t.cantidad >= cantidad.value) &&
    (!fecha.value || t.fecha === fecha.value) &&
    (!tipo.value || t.tipo === tipo.value)
  );
});
});
</script>

<style scoped>
.filtros {
display: flex;
gap: 10px;
margin-bottom: 20px;
}

.styled-table {
width: 100%;
border-collapse: collapse;
margin-top: 20px;
}

.styled-table th,
.styled-table td {
border: 1px solid #ddd;
padding: 8px;
text-align: left;
}
input, select{
  height: fit-content;
}
.styled-table th {
background-color: #9dac7b;
color: white;
}

.styled-table tbody tr:nth-child(even) {
background-color: #e4ded5;
}

.styled-table tbody tr:nth-child(odd) {
background-color: #eee9e0;
}

.styled-table tbody tr:hover {
background-color: #f1f1f1;
}

</style>
