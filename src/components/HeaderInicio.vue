<template>
    <header>
      <!-- <img src="@/assets/logo.jpg" alt="logo" /> -->
      <nav>
        <h1>SkyBank</h1>
  
        <!-- Opciones SIEMPRE visibles -->
        <div class="menu">
          <router-link to="/">Inicio</router-link> |
          <router-link to="/login-cliente">Iniciar sesión</router-link> |
          <router-link to="/register-cliente">Hazte cliente</router-link> |
        </div>
  
        <!-- Menú Hamburguesa SIEMPRE visible -->
        <div class="menu-container">
          <button @click="toggleMenu" class="menu-button">
            ☰
          </button>
          <div v-if="menuOpen" class="mobile-menu">
            <router-link to="/login-empleado">Área empleado</router-link>
          </div>
        </div>
      </nav>
    </header>
  </template>
  
  <script>
  import { ref } from "vue";
  
  export default {
    setup() {
      const menuOpen = ref(false);
  
      const toggleMenu = () => {
        menuOpen.value = !menuOpen.value;
      };
  
      return { menuOpen, toggleMenu };
    },
  };
  </script>
  
  <style scoped>
  header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
    background: #333;
    color: white;
  }
  
  img {
    width: 50px;
    height: auto;
  }
  
  nav {
    display: flex;
    align-items: center;
    gap: 20px;
  }
  

  .menu {
    display: flex;
    gap: 15px;
  }
  
  .menu-container {
    position: relative;
  }
  
  .menu-button {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: white;
  }
  
  .mobile-menu {
    position: absolute;
    top: 40px;
    right: 0;
    background: #222;
    padding: 10px;
    border-radius: 5px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  
  .mobile-menu a {
    color: white;
    text-decoration: none;
  }

  a{
    color: white;
  }
  </style>
  