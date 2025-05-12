<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>perfil</h1>
      <br />

      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>Actualizar DNI</h3><br>
          <div>
            <input type="file" @change="onFileChange" accept="image/*" />
            <img v-if="previewUrl" :src="previewUrl" alt="Vista previa" class="preview" />
            <button class="btn-orange" @click="siguientePaso" v-if="selectedFile">Confirmar</button>
          </div>
        </div>
      </div>
    </div>
  </div><br><br><br><br>
  <FooterInicio />
</template>

<script setup>
  import { ref } from "vue";
  import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
  import FooterInicio from "../../components/Cliente/FooterInicio.vue";
  import menuPerfil from "../../components/Cliente/MenuPerfil.vue";
  import { getCookie } from "../../utils/cookies";

  const ID_cliente =getCookie("ID_cliente");
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
  const siguientePaso = async () => {
  if (!selectedFile.value || !ID_cliente) {
    alert("Debe seleccionar un archivo y tener sesión iniciada.");
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
      alert("✅ DNI actualizado correctamente.");
      selectedFile.value = null;
      previewUrl.value = null;
    } else {
      alert("❌ Error al actualizar el DNI: " + (result.error || "Error desconocido"));
    }
  } catch (error) {
    console.error("Error de red:", error);
    alert("❌ Error al conectar con el servidor.");
  }
};
</script>

  
<style>
  .preview {
  max-width: 200px;
  max-height: 200px;
  }
</style>