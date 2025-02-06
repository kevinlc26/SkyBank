<template>
    <div class="login-container">
      <div class="login-box">
        <h1>Iniciar Sesión</h1>
        <form @submit.prevent="login">
          <div class="input-group">
            <label for="email">Correo electrónico</label>
            <input type="email" id="email" v-model="email" required />
          </div>
          <div class="input-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" v-model="password" required />
          </div>
          <button type="submit">Ingresar</button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
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
    padding: 30px;
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
    margin-bottom: 5px;
  }
  
  input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  /* Botón de inicio de sesión */
  button {
    background-color: #e09f5a;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
    font-size: 16px;
  }
  
  button:hover {
    background-color: #d0884a;
  }
  </style>
  