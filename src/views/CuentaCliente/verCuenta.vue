<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloCuenta }}</h1>
      <br />

      <div class="contenedorT">
        <menuCuenta />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloMovimientos }}</h3><br>

          <!-- Filtros -->
          <div class="filtros">
            <input type="text" v-model="filtroConcepto" :placeholder="textos.filtroConcepto" />
            <input type="date" v-model="filtroFecha" :placeholder="textos.filtroFecha" />
            <select v-model="filtroTipo">
              <option value="">{{ textos.opcionTodos }}</option>
              <option value="ingreso">{{ textos.opcionIngresos }}</option>
              <option value="gasto">{{ textos.opcionGastos }}</option>
            </select>
            <input type="number" v-model="filtroImporte" :placeholder="textos.filtroImporte" />
          </div>

          <table class="tabla">
            <thead>
              <tr>
                <th>{{ textos.columnaFecha }}</th>
                <th>{{ textos.columnaConcepto }}</th>
                <th>{{ textos.columnaImporte }}</th>
                <th>{{ textos.columnaSaldo }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(movimiento, index) in movimientosFiltrados" :key="index">
                <td>{{ movimiento.Fecha_movimiento }}</td> 
                <td>{{ movimiento.Concepto }}</td>
                <td>{{ movimiento.Importe }}€</td>
                <td>{{ movimiento.Saldo_Post_Movimiento }}€</td> 
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
import { ref, computed, onMounted, watch, inject } from "vue";
import { useRoute } from "vue-router";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import menuCuenta from "../../components/Cliente/menuCuenta.vue";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const idCuenta = ref(null);
const movimientos = ref([]);

const textos = ref({
  tituloCuenta: "Cuenta Online Skybank",
  tituloMovimientos: "Movimientos de la cuenta",
  filtroConcepto: "Filtrar por concepto",
  filtroFecha: "Filtrar por fecha",
  filtroImporte: "Filtrar por importe",
  opcionTodos: "Todos",
  opcionIngresos: "Ingresos",
  opcionGastos: "Gastos",
  columnaFecha: "Fecha y hora",
  columnaConcepto: "Concepto",
  columnaImporte: "Importe",
  columnaSaldo: "Saldo"
});

const filtroConcepto = ref("");
const filtroFecha = ref("");
const filtroTipo = ref("");
const filtroImporte = ref("");

const movimientosFiltrados = computed(() => {
  return movimientos.value.filter(movimiento => {
    return (
      (!filtroConcepto.value || movimiento.Concepto.toLowerCase().includes(filtroConcepto.value.toLowerCase())) &&
      (!filtroFecha.value || movimiento.Fecha_movimiento.startsWith(filtroFecha.value)) &&
      (!filtroImporte.value || movimiento.Importe == filtroImporte.value) &&
      (!filtroTipo.value || 
        (filtroTipo.value === "ingreso" && movimiento.Importe > 0) || 
        (filtroTipo.value === "gasto" && movimiento.Importe < 0))
    );
  });
});

const obtenerCookie = (nombre) => {
  const name = `${nombre}=`;
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i].trim();
    if (c.indexOf(name) === 0) {
      return c.substring(name.length, c.length);
    }
  }
  return null;
};

const obtenerMovimientos = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/movimientos?ID_cuenta=${idCuenta.value}`);
    const data = await response.json();
    if (response.ok) {
      movimientos.value = data;
    } else {
      console.error("Error en API:", data.error);
    }
  } catch (error) {
    console.error("Error al obtener movimientos:", error);
  }
};

// Traducir textos dinámicamente
onMounted(async () => {
  idCuenta.value = obtenerCookie("ID_cuenta");
  if (idCuenta.value) {
    obtenerMovimientos();
  } else {
    console.error("No se encontró ID_cuenta ni en la URL ni en las cookies.");
  }
  
  await gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});
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
background-color: #9DAC7B;
color: white;
}

.tabla tbody tr:nth-child(even) {
background-color: #e4ded5;
}

.tabla tbody tr:nth-child(odd) {
background-color: #eee9e0;
}

.tabla tbody tr:hover {
background-color: #f1f1f1;
}

.filtros {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

.filtros input, .filtros select {
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
