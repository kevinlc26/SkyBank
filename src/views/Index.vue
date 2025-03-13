<template>
  <HeaderInicio />
  <br><br><br><br><br><br><br>
  <div class="container">
  
    <section class="carousel ">
      <h1 class="carousel-title">Bienvenido a SkyBank</h1>
      <p>Tu banco en línea seguro, rápido y accesible desde cualquier lugar</p>
      <div class="carousel-container">
        <img :src="images[currentImage]" class="carousel-image" alt="SkyBank Image">
      </div>
    </section>

    <!-- Sección de Beneficios -->
    <section class="benefits">
      <h2>¿POR QUÉ ELEGIR SKYBANK?</h2><br>
      <div class="benefits-container">
        <div class="benefit">
          <img src="../assets/icons/icon-bloq.svg" alt="Seguridad" />
          <p class="nom-benefit">Seguridad garantizada en todas tus transacciones.</p>
        </div>
        <div class="benefit">
          <img src="../assets/icons/icon-24h.svg" style="height: 50px; width: 50px;" alt="Soporte 24/7" />
          <p class="nom-benefit">Atención al cliente disponible las 24 horas.</p>
        </div>
        <div class="benefit">
          <img src="../assets/icons/icon-phone.svg" style="height: 50px; width: 50px;" alt="Acceso móvil" />
          <p class="nom-benefit">Accede a tu cuenta desde cualquier dispositivo.</p>
        </div>
        <div class="benefit">
          <img src="../assets/icons/icon-money.svg" style="height: 50px; width: 50px;" alt="Acceso móvil" />
          <p class="nom-benefit">Bajas comisiones en todas tus transacciones.</p>
        </div>
      </div>
    </section>

    <!-- Sección de Tarjetas -->
    <div class="cards">
      <div class="card" v-for="(card, index) in cards" :key="index">
        <h2>{{ card.title }}</h2> <br>
        <p>{{ card.description }}</p>
      </div>
    </div>

    <!-- Sección de Cuentas -->
    <div class="cuentas">
      <h1>TIPOS DE CUENTAS</h1>
      <div class="cuenta" v-for="(cuenta, index) in cuentas" :key="index">
        <h2 class="nomCuenta">{{ cuenta.title }}</h2> 
        <p>{{ cuenta.description }}</p>
      </div>
    </div>

    <div class="tarjetas">
      <h1>TIPOS DE TARJETAS</h1>
      <div class="tarjeta" v-for="(tarjeta, index) in tarjetas" :key="index">
        <h2 class="nomTarjeta">{{ tarjeta.title }}</h2>
        <p>{{ tarjeta.description }}</p>
      </div>
    </div>
    <FooterInicio />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import HeaderInicio from "../components/Cliente/HeaderInicio.vue";
import FooterInicio from "../components/Cliente/FooterInicio.vue";

const cards = ref([
  { title: "TARJETAS", description: "Descubre nuestras tarjetas con beneficios exclusivos y seguridad garantizada." },
  { title: "CUENTAS", description: "Ahorra y administra tu dinero con nuestras cuentas flexibles y sin comisiones ocultas." },
  { title: "AHORRO", description: "Planifica tu futuro con nuestras cuentas de ahorro de alto rendimiento." }
]);

const cuentas = ref([
  { title: "Cuenta Online Skybank", description: "Accede a tu cuenta desde cualquier lugar y realiza operaciones sin complicaciones." },
  { title: "Cuenta Ahorro", description: "Empieza a ahorrar con intereses competitivos y sin costos de mantenimiento." }
]);

const tarjetas = ref([
  { title: "TARJETA SKYDEBIT", description: "Pagos rápidos y seguros en todo el mundo con nuestra tarjeta de débito." },
  { title: "TARJETA SKYCREDIT", description: "Crédito a tu medida con tasas preferenciales y beneficios exclusivos." }
]);

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

onMounted(() => {
  interval = setInterval(nextImage, 8000);
});

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
