<template>
  <HeaderEmpleado />

  <div class="login-container">
    <br /><br /><br /><br /><br />
    <h1>Acceso al portal del empleado</h1>
    <br /><br />
    <div class="recuadro verde">
      <br /><br />

      <form @submit.prevent="login">
        <div class="input-group">
          <label class="label-form" for="user">Número de identificación</label>
          <input class="input-form" id="user" type="text" v-model="user" placeholder="Introduce tu usuario"required />
        </div>
        <div class="input-group">
          <label class="label-form" for="password">Password</label>
          <input class="input-form" id="password" type="password" v-model="password" placeholder="Introduce tu password" required
          />
        </div>
        <button type="submit" class="btn-orange">Iniciar sesión</button>
        <p v-if="errorMessage" class="error-text">{{ errorMessage }}</p>

      </form>
    </div>
  </div>

  <FooterEmpleado />
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";

const user = ref("");
const password = ref("");
const errorMessage = ref("");
const loading = ref(false);

const router = useRouter();

// LOGIN 
const login = async () => {
  errorMessage.value = "";
  loading.value = true;

  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/empleados", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        Num_ident: user.value,
        PIN: password.value
      })
    });

    let data;

    try {
      data = await response.json();
    } catch (jsonError) {
      throw new Error("La respuesta del servidor no es JSON válido.");
    }

    if (response.ok && data.mensaje === "Login Correcto") {
      setDniCookie(data.DNI, 30);
      setRolCookie(data.Rol, 30);
      alert("Login Correcto");
      router.push("/inicio-empleado");
    } else {
      errorMessage.value = data.error || "Error al iniciar sesión.";
    }
  } catch (error) {
    console.error("Error en login:", error);
    errorMessage.value = error.message || "No se pudo conectar con el servidor.";
  } finally {
    loading.value = false;
  }

};

// SET COOKIE DNI
function setDniCookie(dni, minutes) {
  const d = new Date();
  d.setTime(d.getTime() + (minutes * 60 * 1000)); 
  const expires = "expires=" + d.toUTCString();
  document.cookie = `DNI_empleado=${dni}; ${expires}; path=/`; 
}

// SET COOKIE ROL
function setRolCookie(rol, minutes) {
  const d = new Date();
  d.setTime(d.getTime() + (minutes * 60 * 1000));
  const expires = "expires=" + d.toUTCString();
  
  document.cookie = `Rol=${rol}; ${expires}; path=/`;
}

function getCookie(nombre) {
  const valor = `; ${document.cookie}`;
  const partes = valor.split(`; ${nombre}=`);
  if (partes.length === 2) return partes.pop().split(';').shift();
}

const dniGuardado = getCookie("DNI_empleado");
const rolGuardado = getCookie("Rol");
console.log(dniGuardado);
console.log(rolGuardado);

</script>

<style scoped>
.login-container {
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.input-group {
  margin-bottom: 15px;
  text-align: left;
}

label {
  display: block;
  margin: 5px;
  color: white;
}


.recuadro.verde {
  width: 500px;
  height: 300px;
  padding: 35px;
  padding-left: 80px;
  padding-right: 80px;
}

/*PANTALLA MEDIANA*/
@media (max-width: 1024px) {
}

/*PANTALLA PEQUEÑA*/
@media (max-width: 768px) {
  .recuadro.verde {
    width: 70%;
    padding: 15px;
    padding-left: 40px;
    padding-right: 40px;
  }
}
</style>
