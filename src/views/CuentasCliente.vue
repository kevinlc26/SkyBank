<template>
  <HeaderCliente />
  <div class="main">
    <h1 class="titulo">Mis Cuentas</h1>
    <div class="contenedor-cuentas">
      <router-link 
        v-for="(cuenta, index) in cuentas" 
        :key="index" 
        :to="{ path: '/verCuenta', query: { nombre: cuenta } }"
        class="tarjeta-cuenta"
      >
        <p class="nombre-cuenta">{{ cuenta }}</p>
      </router-link>

      <br /><hr /><br />
      <router-link to="/nuevaCuenta">
          <button class="boton-agregar">Añadir nueva cuenta</button>
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
.titulo {
color: #780000;
text-align: center;
padding-top: 5%;
}

.contenedor-cuentas {
background-color: #9dac7b;
margin: 20px;
padding: 20px;
border-radius: 10px;
text-align: center;
}

.tarjeta-cuenta {
background-color: #d9d9d9;
width: 90%;
margin: 10px auto;
padding: 10px;
text-align: center;
text-decoration: none;
display: block;
border-radius: 5px;
transition: background-color 0.2s ease-in-out;
cursor: pointer;
}

.tarjeta-cuenta:hover {
background-color: #bfbfbf;
}

.nombre-cuenta {
color: #780000;
font-weight: bold;
}

.boton-agregar {
background-color: #e88924;
color: white;
height: 42px;
width: 446px;
display: flex;
justify-content: center;
align-items: center;
border: none;
border-radius: 5px;
cursor: pointer;
font-size: 16px;
font-weight: bold;
}

.boton-agregar:hover {
background-color: #cc751f;
}
@media (max-width: 768px) {
  .boton-agregar{
    width: 70%;
  }
}
</style>