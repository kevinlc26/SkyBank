<template>
    <HeaderCliente />
    <div class="main">
      <div class="recuadro-thin verde">
        <p>MI TARJETA 6545646546546</p>
      </div>
      <br />
      
      <div class="contenedorT">
        <div class="recuadro-lateral">
          <ul>
            <u><li>Bloquear Tarjeta</li></u>
            <u><li>Modificar límites</li></u>
            <u><li>Consultar PIN</li></u>
            <u><li>Ver detalles</li></u>
          </ul>
        </div>
        <div class="recuadro-central gris">
          <h1>Tarjeta 6545646546546</h1>
          <h3>Indica el motivo de bloqueo</h3>
          
          <label>
            <input type="checkbox" v-model="motivos" value="robo" />
            Robo
          </label>
          <br />
  
          <label>
            <input type="checkbox" v-model="motivos" value="perdida" />
            Pérdida
          </label>
          <br />
  
          <label>
            <input type="checkbox" v-model="motivos" value="deterioro" />
            Deterioro
          </label>
          <br />
          
          <br />
          <p>¿Estás seguro de bloquear la tarjeta?</p><br>
          <p>El bloqueo será definitivo</p>
          
          <!-- Botones -->
          <div class="botones">
            <button class="btn-orange">Bloquear</button>
            <button class="btn-blanco">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <br />
    <FooterInicio />
  </template>
  
  <style scoped>
  /* Estilo del recuadro superior */
  .recuadro-thin.verde {
    height: 59px;
    width: 80%;
    margin-left: 8%;
    margin-top: 1%;
  }
  
  /* Contenedor que alinea los recuadros en fila */
  .contenedorT {
    display: flex;
    justify-content: center; /* Centra el contenido */
    align-items: flex-start;
    gap: 40px;
    margin-top: 20px;
  }
  
  /* Recuadro lateral */
  .recuadro-lateral {
    width: 20%;
    padding: 20px;
    border-radius: 8px;
  }
  
  ul, li {
    color: #FFFFFF;
    list-style: none;
    font-size: 18px;
  }
  
  /* Recuadro central */
  .recuadro-central.gris {
    width: 50%;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center; /* Alinea los elementos al centro */
    justify-content: center; /* Alinea los elementos verticalmente en el centro */
  }
  
  /* Botón Bloquear */
  .btn-orange {
    width: 189px;
    background-color: #FF7F00;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  /* Botón Cancelar */
  .btn-blanco {
    width: 189px;
    background-color: white;
    color: #263E33;
    padding: 10px;
    border: 1px solid #263E33;
    border-radius: 5px;
    cursor: pointer;
  }
  
  /* Estilo del contenedor de botones (espacio entre ellos) */
  .botones {
    display: flex;
    gap: 20px; /* Espacio entre los botones */
    margin-top: 20px;
    justify-content: center;
  }
  
  /* Responsividad para pantallas pequeñas */
  @media (max-width: 768px) {
    .contenedorT {
      flex-direction: column; /* En móviles, los recuadros se apilan */
      align-items: center;
    }
  
    .recuadro-lateral,
    .recuadro-central {
      width: 90%;
    }
  }
  </style>
  
  <script>
  import { ref } from "vue";
  import HeaderCliente from "../components/HeaderCliente.vue";
  import FooterInicio from "../components/FooterInicio.vue";
  
  export default {
    components: {
      HeaderCliente,
      FooterInicio,
    },
    setup() {
      const motivos = ref([]);
      return { motivos };
    },
  };
  </script>
  