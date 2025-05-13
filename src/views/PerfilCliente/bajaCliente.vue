<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloPerfil }}</h1>
      <br />

      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloCancelarCuenta }}</h3>
          <br />
          <p>{{ textos.mensajeConfirmacion1 }}</p>
          <p>{{ textos.mensajeConfirmacion2 }}</p>
          <p>{{ textos.mensajeConfirmacion3 }}</p>
          <p>{{ textos.mensajeConfirmacion4 }}</p>
          <p>{{ textos.mensajeConfirmacion5 }}</p>
          <br />

          <div class="reason">
            <label for="reason">{{ textos.labelMotivo }}</label><br />
            <select id="reason" v-model="cancellationReason">
              <option value="">{{ textos.opcionSeleccionarMotivo }}</option>
              <option value="unsatisfied">{{ textos.opcionInsatisfecho }}</option>
              <option value="better_bank">{{ textos.opcionMejorBanco }}</option>
              <option value="no_need">{{ textos.opcionNoNecesario }}</option>
              <option value="other">{{ textos.opcionOtros }}</option>
            </select><br /><br />
            <textarea
              v-if="cancellationReason === 'other'"
              v-model="otherReason"
              :placeholder="textos.placeholderComentarios"
            ></textarea>
          </div>

          <div class="actions">
            <button class="btn-orange" @click="showModal = true">{{ textos.btnCancelarCuenta }}</button>
          </div>
          <br />

          <div v-if="showConfirmation">
            <p>{{ textos.mensajeCancelacionConfirmada1 }}</p>
            <p>{{ textos.mensajeCancelacionConfirmada2 }}</p>
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
import { ref, onMounted, watch, inject  } from "vue";
import { useRouter } from "vue-router";
import { getCookie } from "../../utils/cookies";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import menuPerfil from "../../components/Cliente/MenuPerfil.vue";
import ConfirmDelete from "../../components/Empleado/ConfirmDelete.vue";
import { gestionarTextos } from "../../utils/traductor.js";

const selectedLang = inject("selectedLang");

const router = useRouter();

const cancellationReason = ref("");
const otherReason = ref("");
const showConfirmation = ref(false);
const showModal = ref(false);

const idCliente = getCookie("ID_cliente");

const handleConfirmedDelete = () => {
  showConfirmation.value = true;

  setTimeout(() => {
    router.push("/");
  }, 5000);
};

const textos = ref({
  tituloPerfil: "Perfil",
  tituloCancelarCuenta: "Cancelar Cuenta",
  mensajeConfirmacion1: "¿Estás seguro de que deseas cancelar tu cuenta?",
  mensajeConfirmacion2: "Al cancelar tu cuenta, perderás acceso a todos tus datos y transacciones.",
  mensajeConfirmacion3: "Los pagos y transferencias programados serán cancelados.",
  mensajeConfirmacion4: "Si tienes productos contratados, estos serán cancelados o se te informará de cómo proceder.",
  mensajeConfirmacion5: "Si tienes saldo pendiente, deberás retirarlo antes de cancelar la cuenta.",
  labelMotivo: "Motivo de la cancelación (opcional):",
  opcionSeleccionarMotivo: "Selecciona un motivo",
  opcionInsatisfecho: "No estoy satisfecho con el servicio.",
  opcionMejorBanco: "He encontrado un mejor banco.",
  opcionNoNecesario: "Ya no necesito la cuenta.",
  opcionOtros: "Otros.",
  placeholderComentarios: "Comentarios adicionales",
  btnCancelarCuenta: "Cancelar Cuenta",
  mensajeCancelacionConfirmada1: "Tu solicitud de cancelación ha sido enviada correctamente. En breves, un gestor confirmará la baja.",
  mensajeCancelacionConfirmada2: "Gracias por haber sido nuestro cliente."
});

// Traducir textos dinámicamente
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

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
