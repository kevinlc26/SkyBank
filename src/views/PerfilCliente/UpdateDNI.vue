<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>perfil</h1>
      <br />

      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>Actualizar DNI</h3>
          <div>
            <input type="file" @change="onFileChange" accept="image/*" />
            <img v-if="previewUrl" :src="previewUrl" alt="Vista previa" class="preview" />
            <button class="btn-orange" @click="siguientePaso" v-if="selectedFile">Confirmar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <FooterInicio />
</template>

<script setup>
  import { ref } from "vue";
  import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
  import FooterInicio from "../../components/Cliente/FooterInicio.vue";
  import menuPerfil from "../../components/Cliente/MenuPerfil.vue";

  // Reactive state
  const selectedFile = ref(null);
  const previewUrl = ref(null);

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
  const siguientePaso = () => {
    console.log("Archivo seleccionado:", selectedFile.value);
    // Additional logic can be added here
  };
</script>

  
<style>
  .preview {
  max-width: 200px;
  max-height: 200px;
  }
</style>