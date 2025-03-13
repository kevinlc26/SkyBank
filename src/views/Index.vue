<template>
  <HeaderInicio />
  <br><br><br><br><br>
  <div class="container">
  
    <section class="bienvenida">
      <h1>Bienvenido a SkyBank</h1>
      <p>Tu banco en línea seguro, rápido y accesible desde cualquier lugar. Gestiona tus finanzas con facilidad y tranquilidad.</p>
    </section>

    <!-- Sección de Beneficios -->
    <section class="benefits">
      <h2>¿Por qué elegir SkyBank?</h2><br>
      <div class="benefit-items">
        <div class="benefit">
          <img src="../assets/icons/icon-bloq.svg" alt="Seguridad" />
          <p>Seguridad garantizada en todas tus transacciones.</p>
        </div>
        <div class="benefit">
          <img src="../assets/icons/icon-24h.svg" style="height: 50px; width: 50px;" alt="Soporte 24/7" />
          <p>Atención al cliente disponible las 24 horas.</p>
        </div>
        <div class="benefit">
          <img src="../assets/icons/icon-phone.svg" style="height: 50px; width: 50px;" alt="Acceso móvil" />
          <p>Accede a tu cuenta desde cualquier dispositivo.</p>
        </div>
      </div>
    </section>

    <!-- Sección de Tarjetas -->
    <div class="cards">
      <div class="card" v-for="(card, index) in cards" :key="index">
        <h2>{{ card.title }}</h2>
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
import { ref } from "vue";
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
</script>


<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.container {
  background-color: #EFE7DA;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  padding: 20px;
}

.banner {
  width: 100%;
  height: 250px;
  object-fit: cover;
  border-radius: 10px;
  margin-top: 25px;
}

.bienvenida {
  display: flex;
  flex-direction: column;
  background-image: url(../assets/FotoIndex.jpg);
  width: 100%;
  height: 250px;
  object-fit: cover;
  border-radius: 10px;
  color: white;
  align-items: center;
  justify-content: center;
}

.bienvenida h1 {
  color: #780000;
  align-items: center;
  justify-content: center;
}

.benefits {
  background-color: #9DAC7B;
  padding: 20px;
  border-radius: 10px;
  margin: 40px 0;
  color: white;
}

.benefits h2 {
  color: #780000;
}

.benefit-items {
  display: flex;
  justify-content: center;
  gap: 20px;
  flex-wrap: wrap;
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

.cuentas, .tarjetas {
  background-color: #9DAC7B;
  width: 90%;
  max-width: 1168px;
  padding: 30px;
  border-radius: 15px;
  margin: 40px 0;
}

.tarjetas {
  background-color: #e88924;
}

.cuenta, .tarjeta {
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
</style>
