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
          <h1>Actualizar DNI</h1>
          <div class="recuadro verde">
            <input type="file" @change="onFileChange" accept="image/*" />
            <img v-if="previewUrl" :src="previewUrl" alt="Vista previa" class="preview" />
            <button @click="siguientePaso" v-if="selectedFile">Confirmar</button>
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
    components: {
      HeaderCliente,
      FooterInicio,
      menuPerfil,
    },
    setup() {
      const selectedFile = ref(null);
      const previewUrl = ref(null);
  
      const onFileChange = (event) => {
        const file = event.target.files[0];
        selectedFile.value = file;
  
        if (file) {
          previewUrl.value = URL.createObjectURL(file);
        } else {
          previewUrl.value = null;
        }
      };
  
      const siguientePaso = () => {
        
        console.log("Archivo seleccionado:", selectedFile.value);
        // ...
      };
  
      return {
        selectedFile,
        previewUrl,
        onFileChange,
        siguientePaso,
      };
    },
  };
  </script>
  
  <style scoped>
  .preview {
    max-width: 200px;
    max-height: 200px;
  }
  </style>