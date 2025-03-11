<template>
    <HeaderCliente />
  
    <div class="main">
      <h1>Contratar un nuevo servicio</h1>
  
      <!-- Sección de Cuentas -->
      <div class="tipo-servicio">
        <div class="verd">
          <h3>Cuentas</h3>
        </div>
        <p>Elige la cuenta que mejor se adapte a tus necesidades y empieza a disfrutar de la banca digital con SkyBank.</p>
        <div class="opciones">
          <div class="opcion">
            <h4>Cuenta Online SkyBank</h4>
            <p>Accede a tu dinero en cualquier momento sin comisiones ocultas.</p>
            
          </div>
          <div class="opcion">
            <h4>Cuenta de Ahorro</h4>
            <p>Haz crecer tu dinero con tasas de interés competitivas.</p>
            
          </div>
          
        </div>
        
        <button class="btn-contratar"><router-link to="/nuevaCuenta">Contratar</router-link></button>
      </div>
  
      <!-- Sección de Tarjetas -->
      <div class="tipo-servicio">
        <div class="verd">
          <h3>Tarjetas</h3>
        </div>
        <p>Escoge la tarjeta que más te convenga y aprovecha sus beneficios exclusivos.</p>
        <div class="opciones">
          <div class="opcion">
            <h4>Tarjeta de Débito SkyBank</h4>
            <p>Compra de forma segura y accede a tu dinero en cualquier parte del mundo.</p>
            
          </div>
          <div class="opcion">
            <h4>Tarjeta de Crédito SkyBank</h4>
            <p>Disfruta de financiamiento flexible y recompensas en cada compra.</p>
            
          </div>
          
        </div>
        <button class="btn-contratar"><router-link to="/altaTarjeta">Solicitar</router-link></button>
      </div>
    </div>
  
    <!-- <FooterInicio /> -->
  </template>
  
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
  
  <style scoped>
  
  .tipo-servicio {
    background-color: #9DAC7B;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    text-align: center;
  }
  
  .verd {
    background-color: #9DAC7B;
    display: inline-block;
    padding: 10px 20px;
    border-radius: 5px;
    color: white;
    font-size: 18px;
    font-weight: bold;
  }
  
  .opciones {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
  }
  
  .opcion {
    background-color: white;
    padding: 15px;
    border-radius: 8px;
    width: 300px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  
  .opcion h4 {
    color: #780000;
  }
  
  .btn-contratar {
    background-color: #FF7F00;
    color: white;
    border: none;
    padding: 10px 15px;
    margin-top: 10px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
  }
  
  .btn-contratar:hover {
    background-color: #e66f00;
  }
  </style>
  