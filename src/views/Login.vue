<template>
  <HeaderInicio />
  <h1 class="titulo">ACCESO A LA BANCA ONLINE</h1>
    <div class="login-container">
      
      <div class="recuadro verde">
        <h1>Iniciar Sesión</h1>
        <form @submit.prevent="login">
          <div class=".recuadro verde">
            <label for="text">Introduce tu usuario</label>
            <input type="text" id="usuario" v-model="email" required />
          </div>
          <div class="input-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" v-model="password" required />
          </div>
          <router-link to="/inicio-cliente">
            <button type="button">Entrar</button>
          </router-link>
        </form><br><hr><br>
        <a href="#">¿Olvidaste tu clave de acceso?</a>
        
      </div>
      <div class="rectangulo2">
        <p id="p1"><strong>DESDE SKYBANK TE DAMOS LA BIENVENIDA A LA BANCA ONLINE</strong></p>
      </div>
    </div>
    <div class="recuadro3">
        <h2><strong>¿AÚN NO ERES CLIENTE?</strong></h2>
        <div class="noclienteimg">
          <p><strong>DESCUBRE TODOS NUESTROS PRODUCTOS</strong></p>
        </div>
    </div>
    <FooterInicio/>
  </template>
  
  <script>
  import FooterInicio from "../components/FooterInicio.vue";
import HeaderInicio from "../components/HeaderInicio.vue";
  export default {
    components: {
    HeaderInicio,
    FooterInicio,

  },
    data() {
      return {
        email: "",
        password: "",
      };
    },
    methods: {
      login() {
        // Aquí puedes agregar la lógica de autenticación
        console.log("Iniciando sesión con:", this.email, this.password);
      },
    },
  };
  </script>
  
  <style scoped>
  /* Fondo general */
  .login-container {
    background-color: #efe7da;
    min-height: 10vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  /* Caja del login */
  .login-box {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
  }
  
  /* Grupo de inputs */
  .input-group {
    margin-bottom: 15px;
    text-align: left;
  }
  
  label {
    font-weight: bold;
    display: block;
    margin: 5px;
  }
  
  input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  /* Botón de inicio de sesión */
  button {
    background-color: #E88924;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    width: 40%;
    cursor: pointer;
    font-size: 16px;
  }
  
  button:hover {
    background-color: #d0884a;
  }
  .rectangulo2{
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    margin-left: 5%;
    padding-left: 5%;
    padding-right: 5%;
    width: 370px;
    height: 250px;
    background-image: url('../assets/nubes.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 15px;
  }
  #p1{
    text-align: center;
    color: #780000;
  }
  .recuadro.verde{
    width: 35%;
    margin: 5%;
    padding: 2%;
  }
.titulo{
  font-size: 25px;
  color: #780000;
  text-transform: uppercase;
  margin-left: 20%;
  margin-top: 1%;
  }
  .recuadro3 {
    background-color: #D9D9D9;
    width: 60%;
    border-radius: 15px;
    padding: 2%;
    margin: 20px auto; /* Centra el div horizontalmente */
    display: flex;
    flex-direction: column;
    align-items: center; /* Asegura que los elementos dentro del div estén centrados */
    text-align: center;
}

  .noclienteimg{
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    margin-left: 5%;
    padding-left: 5%;
    padding-right: 5%;
    width: 370px;
    height: 250px;
    background-image: url('../assets/nubes.webp');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 15px;
  }
  h2{
    text-align: center;
    margin-bottom: 3%;
    
  }
  </style>
  

