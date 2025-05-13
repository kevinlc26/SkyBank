<template>
  <header>
    <img src="../../../public/SkyBank-Logo.svg" :alt="textos.altLogo" class="logo" />
    <nav>

      <!-- Botón Menú Hamburguesa -->
      <button @click="toggleMenu" class="menu-button">☰</button>

      <!-- Menú desplegable desde la derecha -->
      <div :class="['mobile-menu', { 'open': menuOpen }]">
        <button @click="toggleMenu" class="close-button">✖</button>
        <router-link to="/inicio-cliente" @click="toggleMenu">{{ textos.menuInicio }}</router-link>
        <router-link to="/cuentas-cliente" @click="toggleMenu">{{ textos.menuCuentas }}</router-link>
        <router-link to="/tarjetas-cliente" @click="toggleMenu">{{ textos.menuTarjetas }}</router-link>
        <router-link to="/transferencias-cliente" @click="toggleMenu">{{ textos.menuTransferencias }}</router-link>
        <router-link to="/contratar-cliente" @click="toggleMenu">{{ textos.menuContratar }}</router-link>
        <router-link to="/perfil-cliente" @click="toggleMenu">{{ textos.menuPerfil }}</router-link>
        <router-link to="/" @click="cerrarSesion">{{ textos.menuCerrarSesion }}</router-link>
      </div>
    </nav>
  </header>
</template>

<script>
import { ref, onMounted, watch, inject } from "vue";
import {getCookie, deleteCookie} from "../../utils/cookies.js";
import { gestionarTextos } from "../../utils/traductor.js";

const dni_Cliente = getCookie('DNI');

export default {
  setup() {
    const menuOpen = ref(false);
    const selectedLang = inject("selectedLang");

    const toggleMenu = () => {
      menuOpen.value = !menuOpen.value;
    };
    const cerrarSesion = () => {
      deleteCookie("DNI");
      toggleMenu();
    };

    const textos = ref({
      altLogo: "logo",
      menuInicio: "Inicio",
      menuCuentas: "Cuentas",
      menuTarjetas: "Tarjetas",
      menuTransferencias: "Transferencias",
      menuContratar: "Contratar",
      menuPerfil: "Perfil",
      menuCerrarSesion: "Cerrar Sesión"
    });

    onMounted(() => {
      gestionarTextos(textos, selectedLang.value);
    });

    watch(selectedLang, async () => {
      await gestionarTextos(textos, selectedLang.value);
    });

    return { menuOpen, toggleMenu, cerrarSesion, textos };
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
  background: #263E33; /* Color de fondo */
  color: white;
}
.logo {
  width: 100px;
  height: auto;
  filter: invert(100%);
  margin-right: auto;
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
  right: 0;
  width: 100%;
  max-width: 300px;
  height: 100vh;
  background: #263E33;
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: start; /* Mantener elementos alineados a la izquierda */
  gap: 20px;
  transform: translateX(100%);
  transition: transform 0.3s ease-in-out;
  box-shadow: -4px 0 10px rgba(0, 0, 0, 0.5);
  overflow-y: auto; /* Permitir desplazamiento si es necesario */
}

/* 🔹 Menú abierto */
.mobile-menu.open {
  transform: translateX(0);
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
  text-align: left; /* Alinear texto a la izquierda */
  padding-left: 20px; /* Agregar margen izquierdo */
}

.mobile-menu a:hover {
  background: #3A5A40;
  padding-left: 30px; /* Aumentar margen en hover para efecto visual */
}

/* 🔹 Responsividad */
@media (max-width: 768px) {
  .mobile-menu {
    width: 100%;
    max-width: 100%;
  }
  .mobile-menu a {
    padding-left: 10%;
  }
}
</style>
