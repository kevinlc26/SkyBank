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
          <label for="cuentaOrigen">Seleccionar cuenta:</label>
          <select v-model="cuentaSeleccionada" required>
            <option disabled value="">-- Selecciona una cuenta --</option>
            <option v-for="cuenta in cuentas" :key="cuenta.ID_cuenta" :value="cuenta.ID_cuenta">
              Cuenta SkyBank {{ cuenta.Tipo_cuenta }} (Saldo: {{ cuenta.Saldo }}€)
            </option>
          </select><br>
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
import { ref, computed, onMounted, watch } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTransferencias from "../../components/Cliente/MenuTransferencia.vue";
import { getCookie } from "../../utils/cookies";

// Reactive state
const ID_cliente = getCookie("ID_cliente");
const busqueda = ref("");
const cantidad = ref(null);
const fecha = ref("");
const tipo = ref("");
const cuentas = ref([]);
const cuentaSeleccionada = ref("");
const transferencias = ref([]);

onMounted(() => {
  obtenerCuentas();
});

watch(cuentaSeleccionada, (nuevaCuentaId) => {
  if (nuevaCuentaId) {
    obtenerTransferencias(nuevaCuentaId);
  }
});

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

const obtenerCuentas = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/cuentas?ID_cliente_cuentas=${ID_cliente}`);
    const data = await response.json();
    if (response.ok) {
      cuentas.value = data.map(cuenta => ({
        ID_cuenta: cuenta.ID_cuenta,
        Tipo_cuenta: cuenta.Tipo_cuenta,
        Saldo: parseFloat(cuenta.Saldo),
      }));
    } else {
      console.error("Error en API:", data.error);
    }
  } catch (error) {
    console.error("Error al obtener cuentas:", error);
  }
};

const obtenerTransferencias = async (cuentaId) => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/movimientos?cuenta_ID-Traspasos=${cuentaId}`);
    const data = await response.json();
    console.log("Transferencias devueltas:", data);
    if (response.ok) {
      transferencias.value = data.map(t => ({
        id: t.ID_movimiento,
        concepto: t.Concepto,
        cantidad: parseFloat(t.Importe),
        fecha: t.Fecha_movimiento,
        tipo: t.Tipo_movimiento.includes("enviada") ? "enviado" : "recibido",
      }));
    } else {
      console.error("Error en API:", data.error);
    }
  } catch (error) {
    console.error("Error al obtener transferencias:", error);
  }
};
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
