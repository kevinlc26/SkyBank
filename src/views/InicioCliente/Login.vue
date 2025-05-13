<template>
  <HeaderInicio />
  <div class="main">
    <h1 class="titulo">{{ textos.titulo }}</h1>
    <div class="login-container">
      <div class="recuadro verde">
        <h3>{{ textos.iniciarSesion }}</h3><br />
        <form @submit.prevent="login">
          <div class="input-group">
            <label for="user">{{ textos.usuario }}</label>
            <input type="text" id="user" v-model="user" required />
          </div>
          <div class="input-group">
            <label for="password">{{ textos.contraseña }}</label>
            <input type="password" id="password" v-model="password" required />
          </div>
          <button class="btn-orange" type="submit" :disabled="loading">
            {{ loading ? textos.cargando : textos.btnEntrar }}
          </button>
        </form>
        <p v-if="errorMessage" class="error-message">{{ textos.mensajeError }}</p>
        <br /><hr /><br />
        <a href="#">{{ textos.olvidasteClave }}</a>
      </div>
      <div class="recuadro3">
        <h3>{{ textos.clienteNuevo[0].title}}</h3> <br>
        <p>{{ textos.clienteNuevo[0].description }}</p>
        <div class="testimonial">
          <p>{{ textos.testimonial[0].text }}</p>
          <p>- {{ textos.testimonial[0].name }}</p>
        </div>
      </div>
    </div>

    <div class="ayuda">
      <h3>{{ textos.ayuda[0].titulo }}</h3>
      <div class="fila-iconos">
        <div class="recurso" v-for="(recurso, index) in textos.recursos" :key="index">
          <img :style="{ height: '100px' }"
              :src="imagenes[index].img"
              :alt="recurso.alt" />
          <p>{{ recurso.name }}</p>
        </div>
      </div>
    </div>
  </div>
  
  <FooterInicio />
</template>


<script setup>
import { useRouter } from "vue-router";
import { ref, onMounted, onUnmounted, watch, inject } from "vue";
import { setCookie } from '../../utils/cookies.js';
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import HeaderInicio from "../../components/Cliente/HeaderInicio.vue";
import { gestionarTextos } from "../../utils/traductor.js";


const user = ref("");
const password = ref("");
const loading =ref(false);
const errorMessage=ref("");
const router= useRouter();


const login = async () => {
  errorMessage.value = "";
  loading.value = true;

  try {
    const queryParams = new URLSearchParams({
      Num_ident: user.value,
      PIN: password.value
    });

    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/clientes?${queryParams.toString()}`, {
      method: "GET",
    });

    let data;
    try {
      data = await response.json();
    } catch (jsonError) {
      throw new Error("La respuesta del servidor no es JSON válido.");
    }

    if (response.ok && data.mensaje === "Login Correcto") {
      setCookie("DNI", data.DNI, 30);
      setCookie("ID_cliente", data["ID cliente"], 30);
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


// Función para guardar el DNI en una cookie con expiración


// TRADUCCION

const selectedLang = inject("selectedLang");

const textos = ref({
  titulo: "ACCESO A LA BANCA ONLINE",
  iniciarSesion: "Iniciar Sesión",
  usuario: "Introduce tu usuario",
  contraseña: "Contraseña",
  btnEntrar: "Entrar",
  cargando: "Cargando...",
  mensajeError: "Error al iniciar sesión. Intenta nuevamente.",
  olvidasteClave: "¿Olvidaste tu clave de acceso?",
  clienteNuevo: [
    {
      title: "¿AÚN NO ERES CLIENTE?",
      description: "Descubre lo que opinan nuestros clientes"
    }
  ],

  // Testimonial, texto simple
  testimonial: [
    {
      text: '"SkyBank me ha ayudado a gestionar mis finanzas de una forma mucho más eficiente. ¡Totalmente recomendable!"',
      name: "- Juan Pérez"
    }
  ],
  // Ayuda, array de objetos
  ayuda: [
    {
      titulo: "¿Tienes alguna duda? Estamos aquí para ayudarte",
    }
  ],
  recursos: [
    { name: "Oficinas por todo el mundo", alt: "oficinas"  },
    { name: "Asistente Virtual", alt: "virtual" },
    { name: "Contáctanos", alt: "call" },
    { name: "Servicio de At. al Cliente", alt: "atencion"  }
  ]
});

const imagenes = ref ([
    { img: "/src/assets/ayuda/location.svg"},
    { img: "/src/assets/ayuda/virtual.svg" },
    { img: "/src/assets/ayuda/call.svg" },
    { img: "/src/assets/ayuda/att.svg"}
]);

watch(selectedLang, async () => {
    await gestionarTextos(textos, selectedLang.value, ["clienteNuevo", "testimonial", "ayuda", "recursos"]);
});

onMounted(async () => {
    await gestionarTextos(textos, selectedLang.value, ["clienteNuevo", "testimonial", "ayuda", "recursos"]);
});

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
