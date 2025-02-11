<template>
  <HeaderInicio />
  <h1>ACCESO A LA BANCA ONLINE</h1>
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
          <button type="submit">Entrar</button>
        </form><br><hr><br>
        <a href="#">¿Olvidaste tu clave de acceso?</a>
        
      </div>
      <div class="rectangulo2">
        <p id="p1"><strong>DESDE SKYBANK TE DAMOS LA BIENVENIDA A LA BANCA ONLINE</strong></p>
      </div>
    </div>
  </template>
  
  <script>
  import HeaderInicio from "../components/HeaderInicio.vue";
  export default {
    components: {
    HeaderInicio,

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
    min-height: 100vh;
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
  </style>
  

