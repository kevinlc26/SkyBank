<template>
  <header class="header">
    <div class="logo">
      <img src="../../public/SkyBank-Logo.svg" alt="logo" class="logo" />
    </div>
    <nav class="nav">
      <div class="desktop-menu">
        <router-link to="/login-cliente">Iniciar sesión</router-link>
        <router-link to="/register-cliente">Hazte cliente</router-link>
      </div>
      <div class="menu-hamburguesa" @click="toggleMenu">
        &#9776;
      </div>
    </nav>
    <div v-if="menuAbierto" class="mobile-menu">
      <router-link to="/login-cliente">Iniciar sesión</router-link>
      <router-link to="/register-cliente">Hazte cliente</router-link>
      <router-link to="/login-empleado">Área empleado</router-link>
    </div>
  </header>
</template>

<script>
export default {
  data() {
    return {
      menuAbierto: false
    };
  },
  methods: {
    toggleMenu() {
      this.menuAbierto = !this.menuAbierto;
    }
  }
};
</script>

<style scoped>
.header {
  background-color: #263E33;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1000;
}

.logo a {
  color: white;
  text-decoration: none;
  font-size: 1.5em;
}

.nav {
  display: flex;
  align-items: center;
  gap: 20px;
}

.desktop-menu {
  display: flex;
  gap: 15px;
}

.desktop-menu a {
  color: white;
  text-decoration: none;
}

.menu-hamburguesa {
  display: block;
  font-size: 1.8em;
  cursor: pointer;
  color: white;
  margin-right: 70px;
}

.mobile-menu {
  position: absolute;
  top: 60px;
  right: 20px;
  background-color: #263E33;
  padding: 10px;
  border-radius: 5px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 200px;
}

.mobile-menu a {
  color: white;
  text-decoration: none;
  padding: 10px;
  display: block;
}

@media (max-width: 768px) {
  .desktop-menu {
    display: none;
  }
}
</style>