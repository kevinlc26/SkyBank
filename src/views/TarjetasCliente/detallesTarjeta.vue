  <template>
      <HeaderCliente />
      <div class="main">

        <h1>MI TARJETA {{ ID_tarjeta }}</h1>

        <br/>
        
        <div class="contenedorT">
          <MenuTarjeta/>
          <div class="recuadro-central gris">
            <h3>Detalles tarjeta</h3><br>
            <div class="detalles">
              <label for="text">Tipo de tarjeta:</label>
              <span>{{ detalle.Tipo_tarjeta }}</span> <br> <br>
              <label for="num">Numero de tarjeta:</label>
              <span>{{ detalle.ID_tarjeta }}</span> <br> <br>
              <label for="text">Estado de tarjeta:</label>
              <span>{{ detalle.Estado_tarjeta }}</span> <br> <br>
              <label for="caducidad">Fecha de caducidad:</label>
              <span>{{ detalle.Fecha_caducidad}}</span> <br> <br>
              <label for="cuenta">Cuenta vinculada:</label>
              <span>{{ detalle.ID_cuenta }}</span>

            </div><br>
          </div>
        </div>
      </div>
      <br /><<br><br><br><br><br><br><br><br>
      <FooterInicio />
  </template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { getCookie } from "../../utils/cookies";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTarjeta from "../../components/Cliente/menuTarjeta.vue";

const detalles = ref(null);
const detalle = computed(() => detalles.value ?? {});
const ID_tarjeta = ref(null);

onMounted(() => {
  ID_tarjeta.value = getCookie('ID_tarjeta');

  if (ID_tarjeta.value) {
    obtenerDetalles(ID_tarjeta.value);
  } else {
    console.error("No se encontró ID_tarjeta en las cookies.");
  }
});

const obtenerDetalles = async (id) => {
  try {
    const response = await fetch(
      `http://localhost/SkyBank/backend/public/api.php/tarjetas?ID_tarjeta=${id}`
    );
    const data = await response.json();

    if (response.ok) {
      detalles.value = data;
    } else {
      console.error("Error en respuesta del servidor:", data.error || data);
    }
  } catch (error) {
    console.error("Error en la petición fetch:", error);
  }
};
</script>


<style>
  .detalle{
      text-align: center;
  }
  h1{
      text-align: center;
      padding-left: 0%    ;
  }
</style>