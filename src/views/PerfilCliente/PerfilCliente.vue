<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloPerfil }}</h1>
      <br />

      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloDatosPersonales }}</h3>

          <label for="Nombre">{{ textos.labelNombre }}</label>
          <input type="text" :value="cliente.Nombre + ' ' + cliente.Apellidos" readonly>

          <label for="Telefono">{{ textos.labelTelefono }}</label>
          <input type="tel" :value="cliente.Telefono" readonly>

          <label for="mail">{{ textos.labelEmail }}</label>
          <input type="email" :value="cliente.Email" readonly>

          <label for="Direccioin">{{ textos.labelDireccion }}</label>
          <input type="text" name="direccion" :value="cliente.Direccion" readonly>

          <label for="documentacion">{{ textos.labelDocumentacion }}</label>
          <input type="text" :value="cliente.Num_ident" readonly>

          <!-- Botones -->
          <div class="botones">
            <button class="btn-orange" @click="abrirModal">{{ textos.btnEditar }}</button>
          </div>
        </div>
      </div>
    </div>
  </div><br><br><br><br><br><br>

  <FooterInicio />

  <ModalEditarCliente 
    v-if="showModal" 
    :cliente="cliente" 
    @cerrar="cerrarModal" 
  />
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import menuPerfil from "../../components/Cliente/MenuPerfil.vue";
import ModalEditarCliente from "../../components/Cliente/ModalEditarCliente.vue";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js";

const selectedLang = inject("selectedLang");

const ID_cliente = getCookie("ID_cliente");
const cliente = ref({});
const showModal = ref(false);

const textos = ref({
  tituloPerfil: "Perfil",
  tituloDatosPersonales: "Datos personales",
  labelNombre: "Nombre y apellidos",
  labelTelefono: "Teléfono",
  labelEmail: "Mail",
  labelDireccion: "Dirección postal",
  labelDocumentacion: "Documentación",
  btnEditar: "Ver más y editar"
});

const obtenerDatosCliente = async () => {
  if (!ID_cliente) {
    console.error("No se encontró ID_cliente en las cookies.");
    return;
  }

  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/clientes?ID_cliente=${ID_cliente}`);
    const data = await response.json();
    
    if (response.ok) {
      cliente.value = data;
    } else {
      console.error("Error desde API:", data.error || data);
    }
  } catch (error) {
    console.error("Error de red:", error);
  }
};

const abrirModal = () => {
  showModal.value = true;
};

const cerrarModal = () => {
  showModal.value = false;
  obtenerDatosCliente();
};

// Traducir textos dinámicamente
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
  obtenerDatosCliente();
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});
</script>
