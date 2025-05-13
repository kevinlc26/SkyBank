<template>
    <HeaderInicio />
    <br><br><br><br><br><br><br>
    <div class="container">
    
      <section class="carousel ">
        <h1 class="carousel-title">{{ textos.bienvenido }}</h1>
        <p>{{ textos.descripcion }}</p>
        <div class="carousel-container">
          <img :src="images[currentImage]" class="carousel-image" alt="SkyBank Image" />
        </div>
      </section>

      <section class="benefits">
        <h2>{{ textos.beneficios }}</h2><br />
        <div class="benefits-container">
          <div class="benefit">
            <img src="../assets/icons/icon-bloq.svg" alt="Seguridad" />
            <p class="nom-benefit">{{ textos.benefit1 }}</p>
          </div>
          <div class="benefit">
            <img src="../assets/icons/icon-24h.svg" style="height: 50px; width: 50px;" alt="Soporte 24/7" />
            <p class="nom-benefit">{{ textos.benefit2 }}</p>
          </div>
          <div class="benefit">
            <img src="../assets/icons/icon-phone.svg" style="height: 50px; width: 50px;" alt="Acceso móvil" />
            <p class="nom-benefit">{{ textos.benefit3 }}</p>
          </div>
          <div class="benefit">
            <img src="../assets/icons/icon-money.svg" style="height: 50px; width: 50px;" alt="Comisiones bajas" />
            <p class="nom-benefit">{{ textos.benefit4 }}</p>
          </div>
        </div>
      </section>

      <div class="cards">
        <div class="card" v-for="(card, index) in textos.cards" :key="index">
          <h2>{{ card.title }}</h2><br />
          <p>{{ card.description }}</p>
        </div>
      </div>

      <div class="cuentas">
        <h1>{{ textos.tituloCuentas }}</h1>
        <div class="cuenta" v-for="(cuenta, index) in textos.cuentas" :key="index">
          <h2 class="nomCuenta">{{ cuenta.title }}</h2>
          <p>{{ cuenta.description }}</p>
        </div>
      </div>

      <div class="tarjetas">
        <h1>{{ textos.tituloTarjetas }}</h1>
        <div class="tarjeta" v-for="(tarjeta, index) in textos.tarjetas" :key="index">
          <h2 class="nomTarjeta">{{ tarjeta.title }}</h2>
          <p>{{ tarjeta.description }}</p>
        </div>
      </div>
      <FooterInicio />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, inject } from "vue";
import HeaderInicio from "../components/Cliente/HeaderInicio.vue";
import FooterInicio from "../components/Cliente/FooterInicio.vue";
import { traducirTexto } from "../utils/traductor.js";


const selectedLang = inject("selectedLang");

onMounted(traducirContenido);
watch(selectedLang, traducirContenido);


const textos = ref({
  bienvenido: "Bienvenido a SkyBank",
  descripcion: "Tu banco en línea seguro, rápido y accesible desde cualquier lugar",
  beneficios: "¿POR QUÉ ELEGIR SKYBANK?",
  benefit1: "Seguridad garantizada en todas tus transacciones.",
  benefit2: "Atención al cliente disponible las 24 horas.",
  benefit3: "Accede a tu cuenta desde cualquier dispositivo.",
  benefit4: "Bajas comisiones en todas tus transacciones.",
  tituloCuentas: "TIPOS DE CUENTAS",
  tituloTarjetas: "TIPOS DE TARJETAS",
  cards: [
    { title: "TARJETAS", description: "Descubre nuestras tarjetas con beneficios exclusivos y seguridad garantizada." },
    { title: "CUENTAS", description: "Ahorra y administra tu dinero con nuestras cuentas flexibles y sin comisiones ocultas." },
    { title: "AHORRO", description: "Planifica tu futuro con nuestras cuentas de ahorro de alto rendimiento." }
  ],
  cuentas: [
    { title: "Cuenta Online Skybank", description: "Accede a tu cuenta desde cualquier lugar y realiza operaciones sin complicaciones." },
    { title: "Cuenta Ahorro", description: "Empieza a ahorrar con intereses competitivos y sin costos de mantenimiento." }
  ],
  tarjetas: [
    { title: "TARJETA SKYDEBIT", description: "Pagos rápidos y seguros en todo el mundo con nuestra tarjeta de débito." },
    { title: "TARJETA SKYCREDIT", description: "Crédito a tu medida con tasas preferenciales y beneficios exclusivos." }
  ]
});


const traducirContenido = async () => {
  const idioma = selectedLang.value;
  if (idioma === "es") return;

  const claves = ["bienvenido", /* más claves */];

  for (const key of claves) {
    textos.value[key] = await traducirTexto(textos.value[key], idioma);
  }

  textos.value.cards = await Promise.all(
    textos.value.cards.map(async (card) => ({
      title: await traducirTexto(card.title, idioma),
      description: await traducirTexto(card.description, idioma)
    }))
  );

  // Repite lo mismo con cuentas y tarjetas si corresponde
};



const images = ref([
  "/src/assets/carrusel/foto2.jpg",
  "/src/assets/carrusel/foto3.jpg",
  "/src/assets/carrusel/foto4.jpg",
  "/src/assets/carrusel/foto5.jpg",
]);

const currentImage = ref(0);
let interval = null;

const nextImage = () => {
  currentImage.value = (currentImage.value + 1) % images.value.length;
};

onMounted(async () => {
  interval = setInterval(nextImage, 8000)
})

onUnmounted(() => {
  clearInterval(interval);
});

</script>


<style scoped>
* {
  box-sizing: border-box;
}

.container {
  background-color: #EFE7DA;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.benefits h2 {
  color: #780000;
  align-items: center;
  justify-content: center;
}


/* CARROUSSEL */
.carousel {
  width: 100%;
  position: relative;
  overflow: hidden;
}

.carousel-container {
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.carousel-image {
  width: 100%;
  height: 400px;
  object-fit: cover;
  transition: opacity 2s ease-in-out;
  opacity: 40%;
}

.carousel-title{
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: #780000;
  font-size: 36px;
  z-index: 10;
}

.carousel p {
  font-family: Raleway;
  font-weight: 500;
  color: black;
  position: absolute;
  top: 65%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 10;
  font-size: 22px;
  margin-top: 50px;
}

/* FIN DEL CARROUSSEL*/


.benefits h2 {
  font-size: 22px;
}
.cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
  margin-top: 20px;
}

.card {
  background-color: #e88924;
  color: white;
  padding: 20px;
  border-radius: 10px;
  width: 280px;
  text-align: center;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.cuentas, .tarjetas, .benefits {
  background-color: #9DAC7B;
  width: 90%;
  max-width: 1168px;
  padding: 30px;
  border-radius: 15px;
  margin: 40px 0;
}

.benefits-container {
  display: grid;
  grid-template-columns: 1fr 1fr; 
  gap: 20px
}

.tarjetas {
  background-color: #e88924;
}

.cuenta, .tarjeta, .benefit {
  background-color: #eee9e0;
  padding: 20px;
  margin: 15px;
  border-radius: 10px;
}

.nomCuenta, .nomTarjeta {
  color: #780000;
  font-weight: bold;
  margin-bottom: 10px;
}

.nom-benefit {
  margin-top: 20px;
}
.footer {
  position: relative;
}
</style>
