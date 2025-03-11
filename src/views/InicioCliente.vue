<template>
    <HeaderCliente />
    
    <div class="main">
      <h1>Bienvenido a SkyBank</h1>
  
      <!-- Información de la cuenta -->
      <div class="movimientos">
        <div class="cuenta-inicio">
          <p>Cuenta número: <strong>**** **** **** 1234</strong></p>
          <p>Saldo disponible: <strong>$5,000.00</strong></p>
          <button class="btn-operacion"><router-link to="/verCuenta">Ver movimientos</router-link></button>
        </div>
      </div>
  
      <!-- Accesos rápidos -->
      <div class="accesos-rapidos">
        <h2>Accesos rápidos</h2>
        <div class="opciones">
          <button class="btn-accion"><router-link to="/transferencias-cliente">Transferencias</router-link></button>
          <button class="btn-accion"><router-link to="/ahorroClientes">Ahorro</router-link></button>
          <button class="btn-accion"><router-link to="/altaTarjeta">Solicitar tarjeta</router-link></button>
          <button class="btn-accion"><router-link to="/nuevaCuenta">Abrir una nueva cuenta</router-link></button>
        </div>
      </div>
  
      <!-- Últimos movimientos -->
      <div class="movimientos">
        <h2>Últimos movimientos</h2>
        <ul>
          <li>Pago en supermercado - $120.50</li>
          <li>Depósito recibido - $1,500.00</li>
          <li>Pago de tarjeta - $300.00</li>
        </ul>
        <button class="btn-operacion">Ver más</button>
      </div>
    </div><br><br><br><br>
  
    <FooterInicio />
  </template>
  
  <style scoped>
  
  .cuenta-inicio {
    background-color: #D9D9D9;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .btn-operacion {
    background-color: #FF7F00;
    color: white;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
  }
  
  .btn-operacion:hover {
    background-color: #e66f00;
  }
  
  .accesos-rapidos, .beneficios, .movimientos {
    margin-top: 30px;
    padding: 20px;
    background-color:#9DAC7B;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .opciones {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-top: 10px;
  }
  
  .btn-accion {
    background-color: #FF7F00;
    color: white;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
  }
  
  .btn-accion:hover {
    background-color: #e66f00;
  }
  
  .benefit-items {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
  }
  
  .benefit {
    text-align: center;
    width: 200px;
  }
  
  .benefit img {
    width: 80px;
    height: 80px;
    margin-bottom: 10px;
  }
  
  .movimientos ul {
    list-style: none;
    padding: 0;
  }
  
  .movimientos li {
    background-color: #F5F5F5;
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
    color: black;
  }
  </style>
  
  <script>
  import HeaderCliente from "../components/HeaderCliente.vue";
  import FooterInicio from "../components/FooterInicio.vue";
  
  export default {
    components: {
      HeaderCliente,
      FooterInicio
    }
  };
  </script>
  