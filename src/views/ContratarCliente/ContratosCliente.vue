<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloPerfil }}</h1>
      <br />

      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloListadoContratos }}</h3>
          <table class="tabla">
            <thead>
              <tr>
                <th>{{ textos.columnaNombreContrato }}</th>
                <th>{{ textos.columnaFecha }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(contrato, index) in contratos" :key="index">
                <td>{{ textos.textoCuenta }} {{ contrato.Tipo_cuenta }}</td>
                <td>{{ contrato.Fecha_creacion }}</td>
              </tr>
              <tr v-if="contratos.length === 0">
                <td colspan="2">{{ textos.mensajeSinContratos }}</td>
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
import menuPerfil from "../../components/Cliente/MenuPerfil.vue";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const ID_cliente = getCookie("ID_cliente");
const contratos = ref([]);
const selectedLang = inject("selectedLang");

const textos = ref({
  tituloPerfil: "Perfil",
  tituloListadoContratos: "Listado de contratos",
  columnaNombreContrato: "Nombre de contrato",
  columnaFecha: "Fecha",
  textoCuenta: "Cuenta",
  mensajeSinContratos: "No se encontraron contratos."
});

// Traducir textos dinámicamente según el idioma seleccionado
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
  cargarContratos();
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

const cargarContratos = async () => {
  try {
    const res = await fetch(`http://localhost/SkyBank/backend/public/api.php/cuentas?ID_clienteContratos=${ID_cliente}`);
    const data = await res.json();
    if (res.ok && Array.isArray(data)) {
      contratos.value = data;
    } else {
      console.error("Error al cargar contratos:", data?.error || data?.mensaje || "Respuesta no válida");
    }
  } catch (err) {
    console.error("Error de red al obtener contratos:", err);
  }
};
</script>


<style>
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