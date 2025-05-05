<template>
  <HeaderCliente/>
  <div class="main">
      <h1 class="titulo">Mis Tarjetas</h1>
      <div  class="contenedor-cuentas">
        <div v-if="errorMessage" class="error-message">
          <p>{{ errorMessage }}</p>
      </div>
          <div v-else class="tarjeta-cuenta" v-for="tarjeta in tarjetas" :key="tarjeta.ID_tarjeta">
              <router-link to="/miTarjeta" @click.native="guardarID_Tarjeta(tarjeta.ID_tarjeta)">
                  <p class="nombre-cuenta">Tarjeta {{ tarjeta.tipo_tarjeta }} {{ tarjeta.ID_tarjeta }}</p>
              </router-link>
          </div>
          <hr><br>
          <router-link to="/nuevaTarjeta">
              <button class="btn-orange">
                  Contratar nueva
              </button>
          </router-link>
      </div>
  </div>

  <FooterInicio/>
</template>

<script >
import HeaderCliente from '../../components/Cliente/HeaderCliente.vue';
import FooterInicio from '../../components/Cliente/FooterInicio.vue';
import { setCookie, getCookie } from '../../utils/cookies.js';

export default {
components: {
  HeaderCliente,
  FooterInicio
},
data() {
  return {
    tarjetas: [],
    errorMessage: ""
  };
},
methods: {
  async obtenerTarjetas() {
    const ID_cliente = getCookie("ID_cliente");

    if (!ID_cliente) {
      this.errorMessage = "No se ha encontrado el ID del cliente en las cookies.";
      return;
    }

    try {
      const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/tarjetas?ID_cliente=${ID_cliente}`);
      const data = await response.json();

      if (response.ok) {
        if (data.mensaje) {
          this.errorMessage = data.mensaje;
        } else {
          this.tarjetas = data;
        }
      } else {
        this.errorMessage = data.error || "Error al obtener las tarjetas.";
      }
    } catch (error) {
      this.errorMessage = "Error al conectar con el servidor.";
      console.error(error);
    }
  },
  guardarID_Tarjeta(ID_tarjeta) {
    setCookie("ID_tarjeta", ID_tarjeta, 1);
  }
},
mounted() {
  this.obtenerTarjetas();
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
</style>