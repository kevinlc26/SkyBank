<template>
    <HeaderCliente />
  
    <div class="main">
      <div class="contenedorGrande">
          <h1>perfil</h1>
        <br />
        <div class="contenedorT">
          <menuPerfil />
          <div class="recuadro-central gris">
            <h3>Cancelar Cuenta</h3> <br>
            <p>¿Estás seguro de que deseas cancelar tu cuenta?</p>
            <p>Al cancelar tu cuenta, perderás acceso a todos tus datos y transacciones.</p>
            <p>Los pagos y transferencias programados serán cancelados.</p>
            <p>Si tienes productos contratados, estos serán cancelados o se te informara de como proceder.</p>
            <p>Si tienes saldo pendiente, deberás retirarlo antes de cancelar la cuenta.</p>
            <br>
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
              <button class="btn-orange" @click="cancelAccount">Cancelar Cuenta</button>
            </div>
            <br>
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
  
  <script setup>
  import { ref } from "vue";
  import { useRouter } from "vue-router";
  import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
  import FooterInicio from "../../components/Cliente/FooterInicio.vue";
  import menuPerfil from "../../components/Cliente/MenuPerfil.vue";

  const router = useRouter();

  // Reactive state
  const cancellationReason = ref("");
  const otherReason = ref("");
  const showConfirmation = ref(false);

  // Cancel account function
  const cancelAccount = () => {
    showConfirmation.value = true;

    // Redirect to home page after 5 seconds
    setTimeout(() => {
      router.push("/");
    }, 5000);
  };

  // Go back function
  const goBack = () => {
    router.go(-1);
  };
</script>

  
  <style scoped>
  

  </style>