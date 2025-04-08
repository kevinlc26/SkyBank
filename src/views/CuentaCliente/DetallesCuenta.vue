<template>
  <HeaderCliente/>
  <div class="main">
    <div class="contenedorGrande">
      <h1>Cuenta Online Skybank</h1>
      <br />
      
      <div class="contenedorT">
        <menuCuenta/>
        <div class="recuadro-central gris">
          <h3>Detalles de la cuenta</h3>
          <table class="tabla">
            <thead>
              <tr >
                <th>Nombre del Titular</th>
                <th>Número de Cuenta</th>
                <th>Divisa</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(detalle, index) in detalles" :key="index">
                <td>{{ detalle.Nombre_Cliente }} {{ detalle.Apellidos_Cliente }}</td>
                <td>{{ detalle.ID_cuenta }}</td>
                <td>EUR</td>
                <td>{{ detalle.Saldo_Cuenta }}€</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <FooterInicio/>
</template>

<script setup>
  import HeaderCliente from '../../components/Cliente/HeaderCliente.vue';
  import FooterInicio from '../../components/Cliente/FooterInicio.vue';
  import menuCuenta from '../../components/Cliente/menuCuenta.vue';

  import { ref, onMounted } from 'vue';
  const idCuenta = ref(null);
  const detalles = ref(null);
  const obtenerCookie = (nombre) => {
    const name = `${nombre}=`;
    const decodedCookie = decodeURIComponent(document.cookie);
    const ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i].trim();
      if (c.indexOf(name) === 0) {
        return c.substring(name.length, c.length);
      }
    }
    return null;
  };

  onMounted(() => {
    idCuenta.value = obtenerCookie('ID_cuenta');
    if (idCuenta.value) {
      obtenerDetalles();
    } else {
      console.error('No se encontró ID_cuenta ni en la URL ni en las cookies.');
    }
  });
  const obtenerDetalles = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/verCuenta?ID_cuentaDetalle=${idCuenta.value}`);
    const data = await response.json();
    if (response.ok) {
      detalles.value = data;
    } else {
      console.error("Error en API:", data.error);
    }
  } catch (error) {
    console.error("Error al obtener movimientos:", error);
  }
};

  const cuentas = ["Cuenta Online Skybank", "Cuenta Ahorro Skybank"];
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