<template>

  <div class="container">
    <HeaderInicio />
    <img src="../assets/FotoIndex.jpg" alt="Banner" class="banner" />
    
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
      </div>
    </div>
    <div class="tarjetas">
      <h1>TIPOS DE TARJETAS</h1>
      <div class="tarjeta" v-for="(tarjeta, index) in tarjetas" :key="index">
        <h2 class="nomTarjeta"> {{ tarjeta.title }}</h2>
      </div>
    </div>
  </div>
</template>

<script>
import HeaderInicio from "../components/HeaderInicio.vue";


export default {
  components: {
    HeaderInicio,

  },
  data() {
    return {
      cards: [
        { title: "TARJETAS", description: "Descripción de la primera tarjeta." },
        { title: "CUENTAS", description: "Descripción de la segunda tarjeta." },
        { title: "AHORRO", description: "Descripción de la tercera tarjeta." },
      ],
      cuentas: [
        { title: "Cuenta Online Skybank" },
        { title: "Cuenta Ahorro" },
      ],
      tarjetas:[
        {title: "TARJETA SKYDEBIT"},
        {title: "TARJETA SKYCREDIT"},
      ],
    };
  },
};
</script>

<style scoped>
*{
  margin:0;
}
.container {
  margin:0;
  background-color: #efe7da;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.banner {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 10px;
  margin-top: 25px;
}

.cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
  margin-top: 20px;
}

.card {
  background-color: #e09f5a;
  color: white;
  padding: 20px;
  border-radius: 10px;
  width: 250px;
  text-align: center;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
.cuentas{
  background-color: #9DAC7B;
  margin-top: 77px;
  width: 1168px;
  height: 306px;
  display: inline-flex;
  align-items: center;
  text-align: center;
  border-radius: 15px;
  margin: 20px;
  justify-content: center;
}
.cuenta{
  background-color: #D9D9D9;
  height: 120px;
  width: 405px;
  margin: 25px;
  padding: 10px;
  justify-content: center;
  border-radius: 10px;
}
.nomCuenta, .nomTarjeta{
  color: black;
  font-family: Inter;
}
.tarjetas{
  background-color: #E88924;
  width: 1168px;
  height: 306px;
  display: inline-flex;
  align-items: center;
  text-align: center;
  margin-top: 63px;
  border-radius: 15px;
  margin: 20px;
  justify-content: center;
}
.tarjeta{
  background-color: #D9D9D9;
  height: 120px;
  width: 405px;
  margin: 25px;
  padding: 10px;
  justify-content: center;
  border-radius: 10px;
}
</style>
