<template>
    <HeaderCliente />
    
    <div class="main">
      <h1>Bienvenido a SkyBank</h1>
  
      <!-- Información de la cuenta -->
      <div class="movimientos">
        <h3>Tus cuentas</h3> <br>
        <div class="cuenta-inicio">
          <p>Cuenta número: <strong>**** **** **** 1234</strong></p>
          <p>Saldo disponible: <strong>$5,000.00</strong></p> <br>
          <button class="btn-orange"><router-link to="/verCuenta">Ver movimientos</router-link></button>
        </div>
      </div>
      
      <br>
      
      <!-- Accesos rápidos -->
      <div class="seccion">
        <h3>Accesos rápidos</h3>
        <div class="opciones">
          <button class="btn-orange"><router-link to="/transferencias-cliente">Transferencias</router-link></button>
          <button class="btn-orange"><router-link to="/ahorroClientes">Ahorro</router-link></button>
          <button class="btn-orange"><router-link to="/altaTarjeta">Solicitar tarjeta</router-link></button>
          <button class="btn-orange"><router-link to="/nuevaCuenta">Nueva cuenta</router-link></button>
        </div>
      </div>
  
      <br><br>
      <!-- Últimos movimientos -->
      <div class="seccion">
        <h3>Últimos movimientos</h3> 
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
        </table><br>
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

  a {
    text-decoration: none;
  }
  /* TABLA */
  .styled-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.styled-table th,
.styled-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.styled-table th {
  background-color: #9dac7b;
  color: white;
}

.styled-table tbody tr:nth-child(even) {
  background-color: #e4ded5;
}

.styled-table tbody tr:nth-child(odd) {
  background-color: #eee9e0;
}

.styled-table tbody tr:hover {
  background-color: #f1f1f1;
}
  
  </style>
  
  <script>
  import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
  import FooterInicio from "../../components/Cliente/FooterInicio.vue";
  
  export default {
    components: {
      HeaderCliente,
      FooterInicio
    }
  };
  </script>
  