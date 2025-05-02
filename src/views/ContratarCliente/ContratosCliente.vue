<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>Perfil</h1>
      <br />

      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>Listado de contratos</h3>
          <table class="tabla">
            <thead>
              <tr>
                <th>Nombre de contrato</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(contrato, index) in contratos" :key="index">
                <td>Cuenta {{ contrato.Tipo_cuenta }}</td>
                <td>{{ contrato.Fecha_creacion }}</td>
              </tr>
              <tr v-if="contratos.length === 0">
                <td colspan="2">No se encontraron contratos.</td>
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
import { ref, onMounted } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import menuPerfil from "../../components/Cliente/MenuPerfil.vue";
import { getCookie } from "../../utils/cookies";

const ID_cliente = getCookie("ID_cliente");
const contratos = ref([]);

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

onMounted(() => {
  cargarContratos();
});
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