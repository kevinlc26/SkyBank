<template>
    <HeaderCliente />
    
    <div class="main">
      <h1>Bienvenido a SkyBank</h1>
  
      <!-- Información de la cuenta -->
      <div class="movimientos">
        <h2>Tus cuentas</h2> <br>
        <div class="cuenta-inicio">
          <p>Cuenta número: <strong>**** **** **** 1234</strong></p>
          <p>Saldo disponible: <strong>$5,000.00</strong></p>
          <button class="btn-orange"><router-link to="/verCuenta">Ver movimientos</router-link></button>
        </div>
      </div>
      
      <br>
      
      <!-- Accesos rápidos -->
      <div class="seccion">
        <h2>Accesos rápidos</h2>
        <div class="opciones">
          <button class="btn-orange"><router-link to="/transferencias-cliente">Transferencias</router-link></button>
          <button class="btn-orange"><router-link to="/ahorroClientes">Ahorro</router-link></button>
          <button class="btn-orange"><router-link to="/altaTarjeta">Solicitar tarjeta</router-link></button>
          <button class="btn-orange"><router-link to="/nuevaCuenta">Nueva cuenta</router-link></button>
        </div>
      </div>
  
      <br>
      <!-- Últimos movimientos -->
      <div class="seccion">
        <h2>Últimos movimientos</h2> <br>
        <table class="styled-table">
          <thead>
            <tr>
              <th>Actividad</th>
              <th>Monto</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Pago en supermercado</td>
              <td>$120.50</td>
            </tr>
            <tr>
              <td>Depósito recibido</td>
              <td>$1,500.00</td>
            </tr>
            <tr>
              <td>Pago de tarjeta</td>
              <td>$300.00</td>
            </tr>
          </tbody>
        </table>
        <button class="btn-orange">Ver más</button>
      </div>
    </div>
    <br><br><br><br><br><br><br>
  
    <FooterInicio />
  </template>
  
  <style scoped>
  
  .cuenta-inicio {
    background-color: #eee9e0;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  
  .accesos-rapidos, .beneficios, .movimientos {
    margin-top: 30px;
    padding: 20px;
  }

  .beneficios, .movimientos {
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
    background-color: #eee9e0;
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
  