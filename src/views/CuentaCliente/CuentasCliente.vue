<template>
  <HeaderCliente />
  <div class="main">
    <h1 class="titulo">Mis Cuentas</h1>
    <div class="contenedor-cuentas">
      <router-link 
        v-for="(cuenta, index) in cuentas" 
        :key="index" 
        class="tarjeta-cuenta"
        @click="seleccionarCuenta(cuenta.ID_cuenta)"
      >
        <p class="nombre-cuenta"> Cuenta {{ cuenta.Tipo_cuenta}} {{ cuenta.ID_cuenta }} | {{ cuenta.Saldo }}€</p>
      </router-link>

      <br /><hr /><br />
      <router-link to="/nuevaCuenta">
          <button class="btn-orange">Nueva cuenta</button>
      </router-link>
    </div>
  </div>
  <FooterInicio />
</template>

<script>
import HeaderCliente from '../../components/Cliente/HeaderCliente.vue';
import FooterInicio from '../../components/Cliente/FooterInicio.vue';
import { getCookie, setCookie, deleteCookie } from '../../utils/cookies';

export default {
  components: {
    HeaderCliente,
    FooterInicio
  },
  data() {
    return {
      cuentas: []
    };
  },
  mounted() {
    deleteCookie("ID_cuenta");
    const ID_cliente = getCookie("ID_cliente");

    if (!ID_cliente) {
      console.error("No se encontró ID_cliente en las cookies.");
      return;
    }

    this.obtenerCuentas(ID_cliente);
  },
  methods: {
    async obtenerCuentas(ID_cliente) {
      try {
        const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/cuentas?ID_cliente_cuentas=${ID_cliente}`);
        const data = await response.json();

        if (response.ok) {
          console.log("Cuentas recibidas:", data);
          this.cuentas = data;
        } else {
          console.error("Error desde API:", data.error || data);
        }
      } catch (error) {
        console.error("Error en la petición fetch:", error);
      }
    },
    seleccionarCuenta(ID_cuenta) {
      setCookie("ID_cuenta", ID_cuenta, 1);
      this.$router.push('/verCuenta');
    }
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
background-color: #eee9e0;
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
background-color: #e2ddd3;
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