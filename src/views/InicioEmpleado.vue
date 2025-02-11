<template>
     <HeaderEmpleado /> 

      <div class="menu-container">
        <router-link to="/cuentas-empleado" class="menu-card">
          <span id="item1">Cuentas</span>
        </router-link>
        <router-link to="/clientes-empleado" class="menu-card">
          <span id="item2">Clientes</span>
        </router-link>
        <router-link to="/tarjetas-empleado" class="menu-card">
          <span id="item3">Tarjetas</span>
        </router-link>
        <router-link to="/transferencias-empleado" class="menu-card">
          <span id="item4">Transferencias</span>
        </router-link>
        <router-link to="/movimientos-empleado" class="menu-card">
          <span id="item5">Movimientos</span>
        </router-link>
      </div>
        

     <FooterEmpleado /> 
</template>

<script>
import FooterEmpleado from '../components/FooterEmpleado.vue'
import HeaderEmpleado from '../components/HeaderEmpleado.vue'
import { library } from "@fortawesome/fontawesome-svg-core";
import { faWallet, faUsers, faCreditCard, faExchangeAlt, faFileInvoiceDollar } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faWallet, faUsers, faCreditCard, faExchangeAlt, faFileInvoiceDollar);

export default {
  components: {
    HeaderEmpleado,
    FooterEmpleado,
    FontAwesomeIcon
  }
}
</script>

<style>

.menu-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-template-rows: auto auto; 
  gap: 20px;
  padding: 40px;
}

.menu-container > :nth-child(4),
.menu-container > :nth-child(5) {
  grid-column: span 1; 
  justify-self: center; 
}

.menu-container > :nth-child(4) {
  grid-column-start: 2; 
}

.menu-card {
  background: #9DAC7B;
  padding: 20px;
  text-align: center;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  text-decoration: none;
  color: white;
  font-size: 18px;
  width: 150px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.menu-card i {
  font-size: 30px;
  margin-bottom: 10px;
  color: #007bff;
}

.menu-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}
</style>