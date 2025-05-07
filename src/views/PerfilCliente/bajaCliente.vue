<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>Perfil</h1>
      <br />
      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>Cancelar Cuenta</h3>
          <br />
          <p>¿Estás seguro de que deseas cancelar tu cuenta?</p>
          <p>Al cancelar tu cuenta, perderás acceso a todos tus datos y transacciones.</p>
          <p>Los pagos y transferencias programados serán cancelados.</p>
          <p>Si tienes productos contratados, estos serán cancelados o se te informará de cómo proceder.</p>
          <p>Si tienes saldo pendiente, deberás retirarlo antes de cancelar la cuenta.</p>
          <br />
          <div class="reason">
            <label for="reason">Motivo de la cancelación (opcional):</label><br />
            <select id="reason" v-model="cancellationReason">
              <option value="">Selecciona un motivo</option>
              <option value="unsatisfied">No estoy satisfecho con el servicio.</option>
              <option value="better_bank">He encontrado un mejor banco.</option>
              <option value="no_need">Ya no necesito la cuenta.</option>
              <option value="other">Otros.</option>
            </select><br /><br />
            <textarea
              v-if="cancellationReason === 'other'"
              v-model="otherReason"
              placeholder="Comentarios adicionales"
            ></textarea>
          </div>

          <div class="actions">
            <button class="btn-orange" @click="showModal = true">Cancelar Cuenta</button>
          </div>
          <br />

          <div v-if="showConfirmation">
            <p>Tu solicitud de cancelación ha sido enviada correctamente. En breves, un gestor confirmará la baja.</p>
            <p>Gracias por haber sido nuestro cliente.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <ConfirmDelete
    :showModal="showModal"
    tableName="clientes"
    :idToDelete="idCliente"
    accion="delete"
    @confirm="handleConfirmedDelete"
    @cancel="showModal = false"
  />

  <FooterInicio />
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { getCookie } from "../../utils/cookies";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import menuPerfil from "../../components/Cliente/MenuPerfil.vue";
import ConfirmDelete from "../../components/Empleado/ConfirmDelete.vue";

const router = useRouter();

// Reactive state
const cancellationReason = ref("");
const otherReason = ref("");
const showConfirmation = ref(false);
const showModal = ref(false);

const idCliente = getCookie("ID_cliente");

// When deletion is confirmed
const handleConfirmedDelete = () => {
  showConfirmation.value = true;

  // Optional: you could also log/send the reason to backend here if needed

  setTimeout(() => {
    router.push("/");
  }, 5000);
};
</script>

<style scoped>
.reason select,
.reason textarea {
  width: 100%;
  max-width: 500px;
  margin-top: 5px;
}

.actions {
  margin-top: 20px;
}
</style>
