<template>
    <HeaderCliente/>
    <div class="main">
      <div class="contenedorGrande">
        <div class="recuadro-thin verde">
        <b><p>Lorem Ipsum</p></b>
      </div>
      <br />
      
      <div class="contenedorT">
        <menuPerfil/>
        <div class="recuadro-central gris">
          <h1>Listado de contratos</h1>
          <table class="tabla">
            <thead>
                <tr>
                <th>Nombre de contrato</th>
                <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>Contrato A</td>
                <td>2023-11-20</td>
                </tr>
                <tr>
                <td>Contrato B</td>
                <td>2023-11-21</td>
                </tr>
                <tr>
                <td>Contrato C</td>
                <td>2023-11-22</td>
                </tr>
            </tbody>
            </table>
        </div>
      </div>
      </div>
    </div>
    <FooterInicio/>
</template>
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
<script>
import { ref } from "vue";
import HeaderCliente from "../components/HeaderCliente.vue";
import FooterInicio from "../components/FooterInicio.vue";
import menuPerfil from "../components/MenuPerfil.vue";

export default {
  components: {
    HeaderCliente,
    FooterInicio,
    menuPerfil
  },
  setup() {
    const motivos = ref([]);
    return { motivos };
  },
};
</script>
