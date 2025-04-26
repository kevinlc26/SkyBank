<template>
    <HeaderCliente/>
    <div class="main">
      <div class="contenedorGrande">
        <h1>Cuenta Online Skybank</h1>
        <br/>
        
        <div class="contenedorT">
          <menuCuenta/>
          <div class="recuadro-central gris">
            <h3>Ahorro de la cuenta</h3>
            <button class="btn-orange"><router-link to="/traspaso">Realizar aportación</router-link></button>
            <table class="tabla">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Concepto</th>
                  <th>Importe</th>
                  <th>Saldo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(traspaso, index) in traspasos" :key="index" >
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
    <FooterInicio/>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import HeaderCliente from '../../components/Cliente/HeaderCliente.vue';
  import FooterInicio from '../../components/Cliente/FooterInicio.vue';
  import menuCuenta from '../../components/Cliente/menuCuenta.vue';
  import { RouterLink } from 'vue-router';

  // Reactive variables
  const cuentas = ref([
    "Cuenta Online Skybank",
    "Cuenta Ahorro Skybank"
  ]);

  const idCuenta = ref(null);
  const traspasos = ref([]);
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
      obtenerMovimientos();
    } else {
      console.error('No se encontró ID_cuenta ni en la URL ni en las cookies.');
    }
  });
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