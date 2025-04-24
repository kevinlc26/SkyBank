<template>
  <header>
    <img src="../../../public/SkyBank-Logo.svg" alt="logo" class="logo" />

    <nav class="nav-menu">
      <p v-if="true && username !== ''" class="user-text" style="color: #e88924">Usuario: {{ username }}</p>
      <router-link to="/">SkyBank.com</router-link>
      <router-link v-if="username != ''" to="/inicio-empleado">Inicio</router-link>
      <router-link v-if="username != ''" to="/perfil-empleado">Perfil</router-link>
      <router-link v-if="username != ''" to="/login-empleado" @click.native.prevent="logoutEmpleado">Salir</router-link>
      <router-link v-if="username === ''" to="/login-empleado">Iniciar sesión</router-link>

      <div class="menu-container">
        <button @click="toggleMenu" class="menu-button">☰</button>
        <div v-if="menuOpen" class="dropdown-menu">
          <router-link to="/">SkyBank.com</router-link>
          <router-link v-if="username != ''" to="/perfil-empleado">Perfil</router-link>
          <router-link v-if="username != ''" to="/cuentas-empleado">Cuentas</router-link>
          <router-link v-if="username != ''" to="/clientes-empleado">Clientes</router-link>
          <router-link v-if="username != ''" to="/tarjetas-empleado">Tarjetas</router-link>
          <router-link v-if="username != ''" to="/transferencias-empleado">Transferencias</router-link>
          <router-link v-if="username != ''" to="/movimientos-empleado">Movimientos</router-link>
          <router-link v-if="rol === 'Administrador'" to="/gestion-empleado">Gestión Empleados</router-link>
          <router-link v-if="username != ''" to="/login-empleado" class="logout" @click.native.prevent="logoutEmpleado">Salir</router-link>
          <router-link v-if="username === ''" to="/login-empleado">Iniciar sesión</router-link>
        </div>
      </div>
    </nav>
  </header>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from 'vue-router';

let username = ref("");
let rol = ref("");
const menuOpen = ref(false);

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value;
};

// ACCEDER A LA COOKIE
function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

// LLAMADA A LA FUNCION 
const dni = getCookie("DNI_empleado");

const empleado = ref([]);

// GET NOMBRE Y ROL SEGUN NUM IDENT DE LOGIN
onMounted(async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/empleados?Num_ident=${dni}`);
    if (response.ok) {
      const data = await response.json();
      empleado.value = data;  

      if (empleado.value && empleado.value.Nombre && empleado.value.Apellidos) {
        username.value = empleado.value.Nombre + ' ' + empleado.value.Apellidos;
      } else {
        console.warn("Datos incompletos del empleado:", empleado.value);
      }

      if (empleado.value && empleado.value.Rol) {
        rol.value = empleado.value.Rol;
      } else {
        console.warn("Datos incompletos del empleado:", empleado.value);
      }
      
    } else {
      console.error('Error al obtener los datos:', response.status);
    }
  } catch (error) {
    console.error('Error en la petición:', error);
  }
});

// LOGOUT
const router = useRouter();

const logoutEmpleado = () => {
  document.cookie = "DNI_empleado=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  document.cookie = "Rol=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  localStorage.clear();

  alert("Sesión cerrada correctamente");
  router.push("/login-empleado");
  window.location.reload();
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
