<template>
  <header>
    <nav>
      <h1>SkyBank</h1>

      <!-- Botón Menú Hamburguesa -->
      <button @click="toggleMenu" class="menu-button">☰</button>

      <!-- Menú desplegable desde la derecha -->
      <div :class="['mobile-menu', { 'open': menuOpen }]">
        <button @click="toggleMenu" class="close-button">✖</button>
        <router-link to="/inicio-cliente" @click="toggleMenu">Inicio</router-link>
        <router-link to="/tarjetas-cliente" @click="toggleMenu">Tarjetas</router-link>
        <router-link to="/transferencias-cliente" @click="toggleMenu">Transferencias</router-link>
        <router-link to="/contratar-cliente" @click="toggleMenu">Contratar</router-link>
        <router-link to="/perfil-cliente" @click="toggleMenu">Perfil</router-link>
        <router-link to="/cuentas-cliente" @click="toggleMenu">Cuentas</router-link>
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
/* 🔹 Estilos del Header */
header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px;
  background: #263E33; /* Nuevo color de fondo */
  color: white;
}

/* 🔹 Botón Menú Hamburguesa */
.menu-button {
  background: none;
  border: none;
  font-size: 28px;
  cursor: pointer;
  color: white;
}

/* 🔹 Menú deslizable desde la derecha */
.mobile-menu {
  position: fixed;
  top: 0;
  right: -495px; /* Oculto fuera de la pantalla */
  width: 495px;
  height: 100%;
  background: #263E33; /* Nuevo color de fondo */
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: start;
  gap: 20px;
  transition: right 0.3s ease-in-out;
  box-shadow: -4px 0 10px rgba(0, 0, 0, 0.5);
}

/* 🔹 Menú abierto */
.mobile-menu.open {
  right: 0;
}

/* 🔹 Botón cerrar menú */
.close-button {
  align-self: flex-end;
  background: none;
  border: none;
  font-size: 24px;
  color: white;
  cursor: pointer;
}

/* 🔹 Estilos de los links */
.mobile-menu a {
  color: white;
  text-decoration: none;
  font-size: 18px;
  padding: 10px 0;
  width: 100%;
  text-align: left;
}

.mobile-menu a:hover {
  background: #3A5A40; /* Efecto hover */
  padding-left: 10px;
}

/* 🔹 Responsividad */
@media (max-width: 768px) {
  .mobile-menu {
    width: 100%; /* Ocupa toda la pantalla en móviles */
  }
}
</style>
