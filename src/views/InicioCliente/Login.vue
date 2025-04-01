<template>
  <HeaderInicio />
  <div class="main">
    <h1 class="titulo">ACCESO A LA BANCA ONLINE</h1>
    <div class="login-container">
      <div class="recuadro verde">
        <h3>Iniciar Sesión</h3><br />
        <form @submit.prevent="login">
          <div class="input-group">
            <label for="text">Introduce tu usuario</label>
            <input type="text" id="user" v-model="user" required />
          </div>
          <div class="input-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" v-model="password" required />
          </div>
          <button class="btn-orange" type="submit" :disabled="loading">
            {{ loading ? "Cargando..." : "Entrar" }}
          </button>
        </form>
        <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        <br /><hr /><br />
        <a href="#">¿Olvidaste tu clave de acceso?</a>
      </div>
      <div class="recuadro3">
        <h3>¿AÚN NO ERES CLIENTE?</h3> <br>
        <p>Descubre lo que opinan nuestros clientes</p>
        <div class="testimonial">
          <p>"{{ currentTestimonial.text }}"</p>
          <p>- {{ currentTestimonial.name }}</p>
        </div>
      </div>
    </div>

    <div class="ayuda">
      <h3>¿Tienes alguna duda? Estamos aquí para ayudarte</h3>
      <div class="fila-iconos">
        <div class="recurso">
          <img style="height: 100px;" src="/src/assets/ayuda/location.svg" alt="oficinas">
          <p>Oficinas por todo el mundo</p>
        </div>
        <div class="recurso">
          <img style="height: 100px;" src="/src/assets/ayuda/virtual.svg" alt="virtual">
          <p>Asistente Virtual</p>
        </div>
        <div class="recurso">
          <img id="telefono" style="height: 100px;" src="/src/assets/ayuda/call.svg" alt="call">
          <p>Contáctanos</p>
        </div>
        <div class="recurso">
          <img style="height: 100px;" src="/src/assets/ayuda/att.svg" alt="atencion">
          <p>Servicio de At. al Cliente</p>
        </div>
      </div>
    </div>
  </div>
  
  <FooterInicio />
</template>

<script setup>
import { useRouter} from "vue-router";
import { ref } from "vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import HeaderInicio from "../../components/Cliente/HeaderInicio.vue";

// Variables reactivas para los inputs
const user = ref("");
const password = ref("");
const loading =ref(false);
const errorMessage=ref("");
const router= useRouter();

// Función para manejar el login
const login = async () => {
  errorMessage.value = "";
  loading.value = true;

  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/loginCliente", {
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
      alert("Login Correcto");
      router.push("/inicio-cliente");
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


// Definimos el testimonio a mostrar
const currentTestimonial = ref({
  text: "SkyBank me ha ayudado a gestionar mis finanzas de una forma mucho más eficiente. ¡Totalmente recomendable!",
  name: "Juan P."
});
// Función para guardar el DNI en una cookie con expiración
function setDniCookie(dni, minutes) {
  const d = new Date();
  d.setTime(d.getTime() + (minutes * 60 * 1000)); // Expiración en minutos
  const expires = "expires=" + d.toUTCString();
  document.cookie = `DNI=${dni}; ${expires}; path=/; Secure; HttpOnly; SameSite=Strict`;
}

</script>

<style scoped>
.main {
  margin-bottom: 200px;
  margin-top: 200px;
}
.error-message {
  color: red;
  font-weight: bold;
}
.login-container {
  background-color: #efe7da;
  min-height: 10vh;
  display: flex;
  justify-content: center;
  align-items: stretch;
  gap: 70px;
  padding: 20px;
  flex-wrap: wrap; /* Permite que los elementos se acomoden en pantallas pequeñas */
}

/* Ajuste de los recuadros para que sean más flexibles */
.recuadro.verde, .recuadro3 {
  width: 40%;
  padding: 30px;
  box-sizing: border-box;
}

/* Estilo de testimonios */
.recuadro3 {
  background-color: #e4ded5;
  border-radius: 15px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 10px;
  border: 2px solid #780000;
}

h2 {
  text-align: center;
  margin-bottom: 3%;
}

label {
  color: white;
  font-weight: normal;
}

.testimonial {
  margin-top: 20px;
  font-size: 1.2rem;
  line-height: 1.6;
}

.testimonial p {
  font-size: 1.2rem;
  margin: 10px 0;
}

/* SECCION AYUDA */
.ayuda {
  text-align: center;
  padding: 20px;
}

.fila-iconos {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 40px;
  flex-wrap: wrap;
  margin-top: 50px;
}

.recurso {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.recurso p {
  margin-top: 20px;
}

#telefono {
  filter: brightness(0.35);
}

/* RESPONSIVE */
@media (max-width: 1024px) {
  .login-container {
    flex-direction: column;
    align-items: center;
    gap: 30px;
  }

  .recuadro.verde, .recuadro3 {
    width: 90%;
  }

  #usuario, #password {
    width: 100%;
  }
}
</style>
