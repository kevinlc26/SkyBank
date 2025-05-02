<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>Perfil</h1>
      <br />

      <div class="contenedorT">
        <menuPerfil />
        <div class="recuadro-central gris">
          <h3>Datos personales</h3>

          <label for="Nombre">Nombre y apellidos</label>
          <input type="text" :value="cliente.Nombre + ' ' + cliente.Apellidos" readonly>

          <label for="Telefono">Teléfono</label>
          <input type="tel" :value="cliente.Telefono" readonly>

          <label for="mail">Mail</label>
          <input type="email" :value="cliente.Email" readonly>

          <label for="Direccioin">Dirección postal</label>
          <input type="text" name="direccion" :value="cliente.Direccion" readonly>

          <label for="documentacion">Documentación</label>
          <input type="text" :value="cliente.Num_ident" readonly>

          <!-- Botones -->
          <div class="botones">
            <button class="btn-orange" @click="abrirModal">Ver más y editar</button>
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
import { ref, onMounted } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import menuPerfil from "../../components/Cliente/MenuPerfil.vue";
import ModalEditarCliente from "../../components/Cliente/ModalEditarCliente.vue";
import { getCookie } from "../../utils/cookies";

const ID_cliente = getCookie("ID_cliente");
const cliente = ref({});
const showModal = ref(false);

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

onMounted(() => {
  obtenerDatosCliente();
});
</script>

