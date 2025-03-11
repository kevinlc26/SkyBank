<template>
  <HeaderCliente/>
  <div class="main">
    <div class="contenedorGrande">
      <div class="recuadro-thin verde">
      <b><p>Cuenta Online Skybank</p></b>
      </div>
      <br />
      
      <div class="contenedorT">
        <menuCuenta/>
        <div class="recuadro-central gris">
          <h1>Detalles de la cuenta</h1>
          <table class="tabla">
            <thead>
              <tr>
                <th>Nombre del Titular</th>
                <th>Número de Cuenta</th>
                <th>Divisa</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Juan Pérez</td>
                <td>123456789</td>
                <td>USD</td>
                <td>$5,000.00</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <FooterInicio/>
</template>

<script>
import HeaderCliente from '../../components/HeaderCliente.vue';
import FooterInicio from '../../components/FooterInicio.vue';
import menuCuenta from '../../components/menuCuenta.vue';

export default {
components: {
  HeaderCliente,
  FooterInicio,
  menuCuenta
},
data() {
  return {
    cuentas: [
      "Cuenta Online Skybank",
      "Cuenta Ahorro Skybank"
    ]
  };
}
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