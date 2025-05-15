<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloTransferencias }}</h1>
      <div class="contenedorT">
        <MenuTransferencias />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloConsultarTransferencias }}</h3>
          <br>
          <label for="cuentaOrigen">{{ textos.labelSeleccionarCuenta }}</label>
          <select v-model="cuentaSeleccionada" required>
            <option disabled value="">{{ textos.opcionSeleccionaCuenta }}</option>
            <option
              v-for="cuenta in cuentas"
              :key="cuenta.ID_cuenta"
              :value="cuenta.ID_cuenta"
            >
              {{ textos.textoCuenta }} {{ cuenta.Tipo_cuenta }} ({{ textos.textoSaldo }} {{ cuenta.Saldo }}€)
            </option>
          </select><br>

          <div class="filtros">
            <input type="text" v-model="busqueda" :placeholder="textos.placeholderBusqueda" />
            <input type="number" v-model="cantidad" :placeholder="textos.placeholderCantidad" />
            <input type="date" v-model="fecha" :placeholder="textos.placeholderFecha" />
            <select v-model="tipo">
              <option value="">{{ textos.opcionTodos }}</option>
              <option value="recibido">{{ textos.opcionRecibidos }}</option>
              <option value="enviado">{{ textos.opcionEnviados }}</option>
            </select>
            <button class="btn-orange" @click="aplicarFiltros">{{ textos.btnBuscar }}</button>
          </div>

          <!-- Nueva tabla para listar transferencias -->
          <table class="styled-table">
            <thead>
              <tr>
                <th>{{ textos.columnaConcepto }}</th>
                <th>{{ textos.columnaCantidad }}</th>
                <th>{{ textos.columnaFecha }}</th>
                <th>{{ textos.columnaTipo }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="transferencia in transferenciasMostradas" :key="transferencia.id">
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
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTransferencias from "../../components/Cliente/MenuTransferencia.vue";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js";

const selectedLang = inject("selectedLang");

const ID_cliente = getCookie("ID_cliente");
const busqueda = ref("");
const cantidad = ref(null);
const fecha = ref("");
const tipo = ref("");
const cuentas = ref([]);
const cuentaSeleccionada = ref("");
const transferencias = ref([]);
const transferenciasMostradas = ref([]);

const textos = ref({
  tituloTransferencias: "TRANSFERENCIAS",
  tituloConsultarTransferencias: "Consultar Transferencias",
  labelSeleccionarCuenta: "Seleccionar cuenta:",
  opcionSeleccionaCuenta: "-- Selecciona una cuenta --",
  textoCuenta: "Cuenta SkyBank",
  textoSaldo: "Saldo:",
  placeholderBusqueda: "Buscar por concepto...",
  placeholderCantidad: "Cantidad mínima",
  placeholderFecha: "Fecha",
  opcionTodos: "Todos",
  opcionRecibidos: "Recibidos",
  opcionEnviados: "Enviados",
  btnBuscar: "Buscar",
  columnaConcepto: "Concepto",
  columnaCantidad: "Cantidad (€)",
  columnaFecha: "Fecha",
  columnaTipo: "Tipo"
});

// Obtener cuentas
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

// Obtener transferencias
const obtenerTransferencias = async (cuentaId) => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/movimientos?cuenta_ID-Traspasos=${cuentaId}`);
    const data = await response.json();
    console.log("Transferencias devueltas:", data);
    if (response.ok) {
      transferencias.value = data.map(t => {
        const esRecibida = t.ID_cuenta_beneficiario === cuentaId;
        return {
          id: t.ID_movimiento,
          concepto: t.Concepto,
          cantidad: parseFloat(t.Importe),
          fecha: t.Fecha_movimiento,
          tipo: esRecibida ? textos.value.opcionRecibidos : textos.value.opcionEnviados,
        };
      });
      transferenciasMostradas.value = transferencias.value; // Mostrar todas inicialmente
    } else {
      console.error("Error en API:", data.error);
    }
  } catch (error) {
    console.error("Error al obtener transferencias:", error);
  }
};

// Aplicar filtros manualmente
const aplicarFiltros = () => {
  transferenciasMostradas.value = transferencias.value.filter(t => {
    return (
      (!busqueda.value || t.concepto.toLowerCase().includes(busqueda.value.toLowerCase())) &&
      (!cantidad.value || t.cantidad >= cantidad.value) &&
      (!fecha.value || t.fecha === fecha.value) &&
      (!tipo.value || t.tipo === tipo.value)
    );
  });
};

// Cargar cuentas al iniciar
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
  obtenerCuentas();
});

// Cargar transferencias al cambiar cuenta seleccionada
watch(cuentaSeleccionada, (nuevaCuentaId) => {
  if (nuevaCuentaId) {
    obtenerTransferencias(nuevaCuentaId);
  }
});

// Traducir textos dinámicamente
watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
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
