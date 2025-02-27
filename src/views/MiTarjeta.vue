<template>
    <HeaderCliente />
    <div class="main">
      <div class="contenedorGrande">
        <div class="recuadro-thin verde">
        <b><p>MI TARJETA 234******55</p></b>
      </div>
      <br />
      
      <div class="contenedorT">
        <MenuTarjeta/>
        <div class="recuadro-central gris">
          <h1>Tarjeta 234******55</h1>
          <h3>Indica el motivo de bloqueo</h3>
          
          <div class="opciones">
            <label>
            <input type="checkbox" v-model="motivos" value="robo" />
            Robo
          </label>
          <label>
            <input type="checkbox" v-model="motivos" value="perdida" />
            Pérdida
          </label>
          <label>
            <input type="checkbox" v-model="motivos" value="deterioro" />
            Deterioro
          </label>
          </div>

          
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
  /* Estilo para alinear los checkboxes a la izquierda */
label {
  display: flex;
  align-items: center;
  gap: 10px; /* Espacio entre el checkbox y el texto */
  font-size: 16px;
  cursor: pointer;
}

/* Ajusta el tamaño del checkbox si es necesario */
input[type="checkbox"] {
  width: 18px;
  height: 18px;
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
  import MenuTarjeta from "../components/menuTarjeta.vue";
  
  export default {
    components: {
      HeaderCliente,
      FooterInicio,
      MenuTarjeta
    },
    setup() {
      const motivos = ref([]);
      return { motivos };
    },
  };
  </script>
  