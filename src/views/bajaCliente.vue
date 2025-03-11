<template>
    <HeaderCliente />
  
    <div class="main">
      <div class="contenedorGrande">
        <div class="recuadro-thin verde">
          <b><p>Lorem Ipsum</p></b>
        </div>
        <br />
        <div class="contenedorT">
          <menuPerfil />
          <div class="recuadro-central gris">
            <h1>Cancelar Cuenta</h1>
            <p>¿Estás seguro de que deseas cancelar tu cuenta?</p>
            <p>Al cancelar tu cuenta, perderás acceso a todos tus datos y transacciones.</p>
            <p>Los pagos y transferencias programados serán cancelados.</p>
            <p>Si tienes productos contratados, estos serán cancelados o se te informara de como proceder.</p>
            <p>Si tienes saldo pendiente, deberás retirarlo antes de cancelar la cuenta.</p>
  
            <div class="reason">
              <label for="reason">Motivo de la cancelación (opcional):</label><br>
              <select id="reason" v-model="cancellationReason">
                <option value="">Selecciona un motivo</option>
                <option value="unsatisfied">No estoy satisfecho con el servicio.</option>
                <option value="better_bank">He encontrado un mejor banco.</option>
                <option value="no_need">Ya no necesito la cuenta.</option>
                <option value="other">Otros.</option>
              </select><br><br>
              <textarea v-if="cancellationReason === 'other'" v-model="otherReason" placeholder="Comentarios adicionales"></textarea>
            </div>
  
            <div class="actions">
              <button class="cancel-button" @click="cancelAccount">Cancelar Cuenta</button>
            </div>
            <div v-if="showConfirmation">
              <p>Tu solicitud de cancelación ha sido enviada correctamente, en breves un gestor confirmará la baja</p>
              <p>Gracias por haber sido nuestro cliente.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <FooterInicio />
  </template>
  
  <script>
  import { ref } from "vue";
  import HeaderCliente from "../components/HeaderCliente.vue";
  import FooterInicio from "../components/FooterInicio.vue";
  import menuPerfil from "../components/MenuPerfil.vue";
  export default {
    data() {
      return {
        cancellationReason: '',
        otherReason: '',
        showConfirmation: false,
      };
    },
    methods: {
      cancelAccount() {
        // Aquí iría la lógica para cancelar la cuenta (llamada a la API, etc.)
        // Por ejemplo:
        // this.api.cancelAccount().then(() => {
        //   this.showConfirmation = true;
        // });
        this.showConfirmation = true;
  
        //Redirigir a la pagina principal luego de 5 segundos.
        setTimeout(() => {
          this.$router.push('/');
        }, 5000);
      },
      goBack() {
        // Redirigir a la página anterior
        this.$router.go(-1);
      },
    },
    components: {
      HeaderCliente,
      FooterInicio,
      menuPerfil
    },
  };
  </script>
  
  <style scoped>
  
  .cancel-button {
    background-color: #FF7F00;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
  }
  
  .back-button {
    background-color: #ccc;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
  }
  

  </style>