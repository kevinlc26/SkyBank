<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloCuenta }}</h1>
      <br />

      <div class="contenedorT">
        <menuCuenta />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloDetalles }}</h3>
          <table class="tabla">
            <thead>
              <tr>
                <th>{{ textos.columnaTitular }}</th>
                <th>{{ textos.columnaNumeroCuenta }}</th>
                <th>{{ textos.columnaDivisa }}</th>
                <th>{{ textos.columnaSaldo }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(detalle, index) in detalles" :key="index">
                <td>{{ detalle.Nombre_Cliente }} {{ detalle.Apellidos_Cliente }}</td>
                <td>{{ detalle.ID_cuenta }}</td>
                <td>{{ textos.divisa }}</td>
                <td>{{ detalle.Saldo_Cuenta }}€</td>
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
import menuCuenta from "../../components/Cliente/menuCuenta.vue";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const idCuenta = ref(null);
const detalles = ref([]);

const textos = ref({
  tituloCuenta: "Cuenta Online Skybank",
  tituloDetalles: "Detalles de la cuenta",
  columnaTitular: "Nombre del Titular",
  columnaNumeroCuenta: "Número de Cuenta",
  columnaDivisa: "Divisa",
  columnaSaldo: "Saldo",
  divisa: "EUR"
});

// Función para obtener la cookie
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

// Obtener detalles desde la API
const obtenerDetalles = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/cuentas?ID_cuentaDetalle=${idCuenta.value}`);
    const data = await response.json();
    if (response.ok) {
      detalles.value = data;
    } else {
      console.error("Error en API:", data.error);
    }
  } catch (error) {
    console.error("Error al obtener detalles:", error);
  }
};

// Traducir textos dinámicamente
onMounted(async () => {
  idCuenta.value = obtenerCookie("ID_cuenta");
  if (idCuenta.value) {
    obtenerDetalles();
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
</style>