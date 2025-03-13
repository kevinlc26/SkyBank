<template>
  <HeaderInicio />
  <div class="main">
    <h1 class="titulo">ACCESO A LA BANCA ONLINE</h1>
    <div class="login-container">
      
      <div class="recuadro verde">
        <h3>Iniciar Sesión</h3> <br>
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
            <button class="btn-orange" type="button">Entrar</button>
          </router-link>
        </form><br><hr><br>
        <a href="#">¿Olvidaste tu clave de acceso?</a>
        
      </div>
      <div class="rectangulo2">
        <p id="p1"><strong>DESDE SKYBANK TE DAMOS LA BIENVENIDA A LA BANCA ONLINE</strong></p>
      </div>
    </div>
    <div class="recuadro3">
        <h2>¿AÚN NO ERES CLIENTE?</h2>
        <div class="noclienteimg">
          <p><strong>DESCUBRE TODOS NUESTROS PRODUCTOS</strong></p>
        </div>
    </div>
  </div>

    <FooterInicio/>
  </template>
  
  <script setup>
  import { ref } from "vue";
  import FooterInicio from "../../components/Cliente/FooterInicio.vue";
  import HeaderInicio from "../../components/Cliente/HeaderInicio.vue";
  
  // Variables reactivas para los inputs
  const email = ref("");
  const password = ref("");
  
  // Función para manejar el login
  const login = () => {
    console.log("Iniciando sesión con:", email.value, password.value);
  };
  </script>
  
  
  <style scoped>
  .main {
    margin-bottom: 200px;
    margin-top: 200px;
  }
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

  h3, h2 {
  color: #780000;
  text-transform: uppercase;
  font-family: Raleway, sans-serif;
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

  .recuadro3 {
    background-color: #e4ded5;
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
    background-image: url('../assets/tarjetaDebito.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    border-radius: 15px;
  }
  h2{
    text-align: center;
    margin-bottom: 3%;
    
  }
  @media (max-width: 768px) {
  .titulo {
    font-size: 18px;
  }
  .login-container{
    margin-top: 50px;
    width: 90%;
  }
  .recuadro.verde{
    width: 100%;
  }
  button {
    width: 100%;
  }

  .rectangulo2 {
    display: none;
  }
  .recuadro3 {
    width: 90%;
    
  }
  .noclienteimg{
    width: 80%;
  }
  #usuario, #password{
    width: 90%;
  }
}
  </style>
  

