<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloCuenta }}</h1>
      <br />

      <div class="contenedorT">
        <menuCuenta />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloAhorro }}</h3>
          <button class="btn-orange">
            <router-link to="/traspaso">{{ textos.btnAportacion }}</router-link>
          </button>
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
              <tr v-for="(traspaso, index) in traspasos" :key="index">
                <td>{{ traspaso.Fecha_movimiento }}</td>
                <td>{{ traspaso.Concepto }}</td>
                <td>{{ traspaso.Importe }}€</td>
                <td>{{ traspaso.Saldo_nuevo }}€</td>
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
import { gestionarTextos } from "../../utils/traductor.js"; 

const selectedLang = inject("selectedLang");

const idCuenta = ref(null);
const traspasos = ref([]);

const textos = ref({
  tituloCuenta: "Cuenta Online Skybank",
  tituloAhorro: "Ahorro de la cuenta",
  btnAportacion: "Realizar aportación",
  columnaFecha: "Fecha",
  columnaConcepto: "Concepto",
  columnaImporte: "Importe",
  columnaSaldo: "Saldo"
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

// Obtener movimientos desde la API
const obtenerMovimientos = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/movimientos?ID_cuenta-Ahorro=${idCuenta.value}`);
    const data = await response.json();
    if (response.ok) {
      traspasos.value = data;
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
  </style>