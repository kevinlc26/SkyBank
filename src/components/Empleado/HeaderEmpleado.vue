<template>
  <header>
    <img src="../../../public/SkyBank-Logo.svg" alt="logo" class="logo" />

    <nav class="nav-menu">
      <p v-if="true" class="user-text" style="color: #e88924">Usuario: {{ username }}</p>
      <router-link to="/">SkyBank.com</router-link>
      <router-link to="/inicio-empleado">Inicio</router-link>
      <router-link to="/perfil-empleado">Perfil</router-link>
      <router-link to="/login-empleado">Salir</router-link>

      <div class="menu-container">
        <button @click="toggleMenu" class="menu-button">☰</button>
        <div v-if="menuOpen" class="dropdown-menu">
          <router-link to="/">SkyBank.com</router-link>
          <router-link to="/perfil-empleado">Perfil</router-link>
          <router-link to="/cuentas-empleado">Cuentas</router-link>
          <router-link to="/clientes-empleado">Clientes</router-link>
          <router-link to="/tarjetas-empleado">Tarjetas</router-link>
          <router-link to="/transferencias-empleado">Transferencias</router-link>
          <router-link to="/movimientos-empleado">Movimientos</router-link>
          <router-link to="/gestion-empleado">Gestión Empleados</router-link>
          <router-link to="/login-empleado" class="logout">Salir</router-link>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref } from "vue";

const username = 'Juan Pérez';
const menuOpen = ref(false);

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value;
};
</script>

<style scoped>
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0px 20px;
  background: #263e33;
  color: white !important;
}

.logo {
  width: 100px;
  height: auto;
  filter: invert(100%);
  margin-right: auto;
}

.nav-menu {
  display: flex;
  align-items: center;
  gap: 20px;
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

.dropdown-menu {
  position: absolute;
  top: 134px;
  right: 0;
  margin: 0;
  width: 96vw;
  background: #263e33;
  padding: 10px;
  display: flex;
  justify-content: space-around;
  align-items: center;
}

.dropdown-menu a {
  color: white;
  text-decoration: none;
  padding: 5px 10px;
}

a {
  color: white;
  text-decoration: none;
  font-weight: normal;
}

/* PANTALLA PEQUEÑA */
@media (max-width: 768px) {
  .nav-menu > *:not(.menu-container) {
    display: none;
  }

  .menu-container {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .dropdown-menu {
    position: absolute;
    top: 120px;
    width: 70vw;
    right: 125%;
    transform: translateX(50%);
    flex-direction: column;
    text-align: center;
    gap: 10px;
    padding: 20px;
    border-radius: 10px;
  }

  .dropdown-menu a {
    padding: 15px;
    font-size: 18px;
    display: block;
  }
}
</style>
