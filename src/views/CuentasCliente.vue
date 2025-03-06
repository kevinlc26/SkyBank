<template>
    <HeaderCliente />
    <div class="main">
      <h1>Mis Cuentas</h1>
      <div class="recuadro verde">
        <p class="subtitulo">Mis cuentas</p>
  
        <router-link 
          v-for="(cuenta, index) in cuentas" 
          :key="index" 
          :to="{ path: '/verCuenta', query: { nombre: cuenta } }"
          class="recuadro-thin gris cuenta-link"
        >
          <p class="nombreCuenta">{{ cuenta }}</p>
        </router-link>
  
        <br /><hr /><br />
        <router-link to="/nuevaCuenta">
            <button class="btn-orange">Añadir nueva cuenta</button>
        </router-link>
      </div>
    </div>
    <FooterInicio />
  </template>
  
  <script>
  import HeaderCliente from '../components/HeaderCliente.vue';
  import FooterInicio from '../components/FooterInicio.vue';
  
  export default {
    components: {
      HeaderCliente,
      FooterInicio
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
  h1 {
    padding-left: 22%;
    padding-top: 5%;
  }
  .recuadro-thin.gris {
    width: 90%;
    margin-top: 10px;
    padding: 10px;
    text-align: center;
    transition: background-color 0.2s ease-in-out;
  }
  .cuenta-link {
    text-decoration: none;
    display: block;
    cursor: pointer;
  }
  .cuenta-link:hover {
    background-color: #e0e0e0;
  }
  .subtitulo {
    color: #EFE7DA;
  }
  .nombreCuenta {
    color: #780000;
  }
  .recuadro.verde {
    margin: 20px;
    align-items: center;
  }
  .btn-orange {
    height: 42px;
    width: 446px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  </style>
  