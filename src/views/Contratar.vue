<template>
    <HeaderCliente />
  
    <div class="main">
      <h1>Contratar un nuevo servicio</h1> <br><br>
  
      <!-- Sección de Cuentas -->
      <p class="slogan">Elige la cuenta que mejor se adapte a tus necesidades y empieza a disfrutar de la banca digital con SkyBank.</p>
      
      <div class="tipo-servicio">
        <div class="opciones">
          <div class="opcion">
            <h4>Cuenta Online SkyBank</h4>
            <p>Accede a tu dinero en cualquier momento sin comisiones ocultas.</p>
            
          </div>
          <div class="opcion">
            <h4>Cuenta de Ahorro</h4>
            <p>Haz crecer tu dinero con tasas de interés competitivas.</p>
            
          </div>
          
        </div> <br>
        <button class="btn-orange">Contratar</button>
      </div>
  
      <br><br>
      <!-- Sección de Tarjetas -->
      <p class="slogan">Escoge la tarjeta que más te convenga y aprovecha sus beneficios exclusivos.</p>
      <div class="tipo-servicio">
        <div class="opciones">
          <div class="opcion">
            <h4>Tarjeta de Débito SkyBank</h4>
            <p>Compra de forma segura y accede a tu dinero en cualquier parte del mundo.</p>
            
          </div>
          <div class="opcion">
            <h4>Tarjeta de Crédito SkyBank</h4>
            <p>Disfruta de financiamiento flexible y recompensas en cada compra.</p>
            
          </div>
          
        </div> <br>
        <button class="btn-orange">Solicitar</button>
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

  .opciones {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
  }
  
  .opcion {
    background-color: #eee9e0;
    padding: 15px;
    border-radius: 8px;
    width: 300px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
  }
  
  .opcion h4 {
    color: #780000;
  }

  .slogan {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  </style>
  