<template>
  <HeaderCliente />

  <div class="tarjeta-container">
    <h1 class="tarjeta-titulo">Contrata una nueva Cuenta</h1>
    <div class="tarjeta-lista">
      <div v-for="cuenta in cuentas" :key="cuenta.id" class="tarjeta-item">
        <CardTarjeta
          :tarjeta="cuenta"
          :isSelected="tipoCuenta === cuenta.id"
          @select="tipoCuenta = cuenta.id"
        />
        <button class="btn-orange" @click="confirmarGestion(cuenta.id)">
          Solicitar Cuenta
        </button>
      </div>
    </div>

    <div v-if="mostrarPopup" class="popup">
      <div class="popup-contenido">
        <p>¿Confirmas la solicitud de la cuenta {{ tipoCuenta }}?</p>
        <button @click="procesarSolicitud" class="btn-orange">Confirmar</button>
        <button @click="cerrarPopup" class="btn-blanco">Cancelar</button>
      </div>
    </div>

    <div v-if="mensaje" class="mensaje-container">
      <p class="mensaje">{{ mensaje }}</p>
    </div>
  </div>

  <FooterInicio />
</template>

<script>
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import CardTarjeta from "../../components/Cliente/CardTarjeta.vue";
import ImgCuenta from "../../assets/img-cuenta.jpg";
import ImgAhorro from "../../assets/img-ahorro.jpg";
import { getCookie } from "../../utils/cookies";
import { generarNumero, formData, numAleatorio } from "../../utils/formUtils";

const ID_cliente = getCookie("ID_cliente");

export default {
  components: {
    HeaderCliente,
    FooterInicio,
    CardTarjeta
  },
  data() {
    return {
      tipoCuenta: "",
      cuentas: [
        {
          id: "online",
          nombre: "Cuenta Online",
          descripcion:
            "Accede a tu dinero de forma segura y rápida, sin costos de mantenimiento.",
          imagen: ImgCuenta
        },
        {
          id: "ahorro",
          nombre: "Cuenta Ahorro",
          descripcion:
            "Empieza a ahorrar con intereses competitivos y sin costos de mantenimiento.",
          imagen: ImgAhorro
        }
      ],
      mostrarPopup: false,
      mensaje: ""
    };
  },
  methods: {
    confirmarGestion(id) {
      this.tipoCuenta = id;
      this.mostrarPopup = true;
    },
    async procesarSolicitud() {
      generarNumero("cuentas");

      formData.value = {
        ID: numAleatorio.value,
        Tipo_cuenta: this.tipoCuenta,
        ID_cliente: ID_cliente
      };

      try {
        const response = await fetch("http://localhost/SkyBank/backend/public/api.php/cuentas", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(formData.value)
        });

        const result = await response.json();
        this.mensaje = result.mensaje || "Cuenta creada con éxito.";
      } catch (error) {
        console.error("Error al crear la cuenta:", error);
        this.mensaje = "Error al crear la cuenta.";
      }

      this.mostrarPopup = false;
    },
    cerrarPopup() {
      this.mostrarPopup = false;
    }
  }
};
</script>
  
  <style scoped>
  .tarjeta-container {
    text-align: center;
    padding: 20px;
    background-color: #efe7da;
  }
  
  .tarjeta-titulo {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #780000;
  }
  
  .tarjeta-lista {
    display: flex;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
    background-color: #9dac7b;
    padding: 20px;
    border-radius: 10px;
  }
  
  .tarjeta-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
  }
  
  .popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .popup-contenido {
    background: #efe7da;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    border: 1px solid #780000;
  }
  
  .popup-contenido button {
    margin: 10px;
    padding: 10px;
    border: none;
    cursor: pointer;
  }
  
  .mensaje-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }
  
  .mensaje {
    font-size: 1.2rem;
    color: #780000;
    background-color: #9dac7b;
    padding: 15px;
    border-radius: 8px;
    width: 50%;
    text-align: center;
    font-weight: bold;
  }
  </style>
  