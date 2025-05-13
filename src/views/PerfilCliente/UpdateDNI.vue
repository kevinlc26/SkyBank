<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloPerfil }}</h1>
      <br />

      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloActualizarDNI }}</h3><br>
          <div>
            <input type="file" @change="onFileChange" accept="image/*" />
            <img v-if="previewUrl" :src="previewUrl" :alt="textos.altVistaPrevia" class="preview" />
            <button class="btn-orange" @click="siguientePaso" v-if="selectedFile">{{ textos.btnConfirmar }}</button>
          </div>
        </div>
      </div>
    </div>
  </div><br><br><br><br>

  <FooterInicio />
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import menuPerfil from "../../components/Cliente/MenuPerfil.vue";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const ID_cliente = getCookie("ID_cliente");
const selectedFile = ref(null);
const previewUrl = ref(null);

const textos = ref({
  tituloPerfil: "Perfil",
  tituloActualizarDNI: "Actualizar DNI",
  altVistaPrevia: "Vista previa de la imagen",
  btnConfirmar: "Confirmar",
  alertaSeleccionArchivo: "Debe seleccionar un archivo y tener sesión iniciada.",
  alertaDNIActualizado: "DNI actualizado correctamente.",
  alertaErrorDNI: "Error al actualizar el DNI:",
  alertaErrorConexion: "Error al conectar con el servidor."
});

// File change handler
const onFileChange = (event) => {
  const file = event.target.files[0];
  selectedFile.value = file;

  // Set preview URL if file exists
  if (file) {
    previewUrl.value = URL.createObjectURL(file);
  } else {
    previewUrl.value = null;
  }
};

// Next step handler
const siguientePaso = async () => {
  if (!selectedFile.value || !ID_cliente) {
    alert(textos.value.alertaSeleccionArchivo);
    return;
  }

  const formData = new FormData();
  formData.append("imagen", selectedFile.value);
  formData.append("ID_cliente", ID_cliente);

  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/clientes", {
      method: "POST",
      body: formData
    });

    const result = await response.json();

    if (response.ok) {
      alert(textos.value.alertaDNIActualizado);
      selectedFile.value = null;
      previewUrl.value = null;
    } else {
      alert(`${textos.value.alertaErrorDNI} ${result.error || "Error desconocido"}`);
    }
  } catch (error) {
    console.error("Error de red:", error);
    alert(textos.value.alertaErrorConexion);
  }
};

// Traducir textos dinámicamente
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});
</script>

<style>
  .preview {
  max-width: 200px;
  max-height: 200px;
  }
</style>