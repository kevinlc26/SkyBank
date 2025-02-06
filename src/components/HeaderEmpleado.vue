<template>
  <header>
    <!-- <img src="@/assets/logo.jpg" alt="logo" class="logo" /> -->

    <nav class="nav-menu">
      <router-link to="/inicio-empleado">Inicio</router-link>
      <router-link to="/perfil-empleado">Perfil</router-link>
      <router-link to="/login-empleado">Salir</router-link>

      <div class="menu-container">
        <button @click="toggleMenu" class="menu-button">
          ☰
        </button>
        <div v-if="menuOpen" class="dropdown-menu">
          <router-link to="/cuentas-empleado">Cuentas</router-link>
          <router-link to="/clientes-empleado">Clientes</router-link>
          <router-link to="/tarjetas-empleado">Tarjetas</router-link>
          <router-link to="/transferencias-empleado">Transferencias</router-link>
          <router-link to="/movimientos-empleado">Movimientos</router-link>
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
  justify-content: right;
  align-items: center;
  padding: 15px 20px;
  background: #263E33;
  color: white;
}

.logo {
  width: 50px;
  height: auto;
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
  top: 49px;
  right: 0;
  margin: 0;
  width: 100vw;
  background: #263E33;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;

}

.dropdown-menu a {
  color: white;
  text-decoration: none;
  padding: 5px 10px;
}
a{
  color: white;
}
@media (max-width: 768px) {
  .nav-menu {
    gap: 10px;
  }
}
</style>