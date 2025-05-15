<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloTarjeta }} {{ ID_tarjeta }}</h1>
      <br />

      <div class="contenedorT">
        <MenuTarjeta />
        <div class="recuadro-central gris">
          
          <template v-if="estadoTarjeta === textos.estadoInactiva">
            <h3>{{ textos.tituloActivarTarjeta }}</h3>
            <br />
            <p>{{ textos.mensajeTarjetaInactiva }} {{ ID_tarjeta }} {{ textos.mensajeNoOperaciones }}</p>
            <br />
            <p>{{ textos.mensajeActivar }}</p><br>
            <button class="btn-orange" @click="cambiarEstadoTarjeta(textos.estadoActiva)">{{ textos.btnActivar }}</button>
          </template>

          <template v-else>
            <h3>{{ textos.tituloBloquearTarjeta }}</h3>
            <br />
            <div class="opciones">
              <label>
                <input type="checkbox" v-model="motivos" :value="textos.opcionRobo" />
                {{ textos.opcionRobo }}
              </label>
              <label>
                <input type="checkbox" v-model="motivos" :value="textos.opcionPerdida" />
                {{ textos.opcionPerdida }}
              </label>
              <label>
                <input type="checkbox" v-model="motivos" :value="textos.opcionDeterioro" />
                {{ textos.opcionDeterioro }}
              </label>
            </div>

            <!-- Botones -->
            <div class="botones">
              <button @click.prevent="openConfirmModal" class="btn-orange">{{ textos.btnBloquear }}</button>
            </div>
            <br><br><hr>
            <h3>{{ textos.tituloDesactivarTarjeta }}</h3>
            <br>
            <p>{{ textos.mensajeDesactivar }}</p>
            <br>
            <button class="btn-orange" @click="cambiarEstadoTarjeta(textos.estadoInactiva)">{{ textos.btnDesactivar }}</button>
          </template>
        </div>
      </div>
    </div>
  </div>
  <br /><br><br><br><br><br>

  <ConfirmDelete 
    :showModal="showModal" 
    :tableName="'tarjetas'" 
    :accion="'bloquear'" 
    :idToDelete="ID_tarjeta"
    @confirm="confirmDelete" 
    @cancel="cancelDelete"
  />

  <FooterInicio />
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTarjeta from "../../components/Cliente/menuTarjeta.vue";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const ID_tarjeta = getCookie("ID_tarjeta");
const estadoTarjeta = ref("");
const motivos = ref([]);
const showModal = ref(false);

const textos = ref({
  tituloTarjeta: "Tarjeta",
  estadoActiva: "Activa",
  estadoInactiva: "Inactiva",
  tituloActivarTarjeta: "Activar tarjeta",
  mensajeTarjetaInactiva: "La tarjeta",
  mensajeNoOperaciones: "se encuentra Inactiva. No se pueden realizar operaciones con ella.",
  mensajeActivar: "¿Quieres activar la tarjeta?",
  btnActivar: "Activar",
  tituloBloquearTarjeta: "BLOQUEAR TARJETA",
  opcionRobo: "Robo",
  opcionPerdida: "Pérdida",
  opcionDeterioro: "Deterioro",
  btnBloquear: "Bloquear",
  tituloDesactivarTarjeta: "Desactivar Tarjeta",
  mensajeDesactivar: "Si desactivas tu tarjeta, no podrás utilizarla para ninguna operación hasta que la actives nuevamente.",
  btnDesactivar: "Desactivar"
});

// Obtener estado de la tarjeta
onMounted(async () => {
  try {
    const url = `http://localhost/SkyBank/backend/public/api.php/tarjetas?ID_tarjeta=${ID_tarjeta}`;
    const response = await fetch(url);
    const data = await response.json();

    if (response.ok && data.Estado_tarjeta) {
      estadoTarjeta.value = data.Estado_tarjeta;
    } else {
      console.error("No se encontraron datos para esta tarjeta.");
    }

    await gestionarTextos(textos, selectedLang.value);
  } catch (error) {
    console.error("Error al obtener datos de la tarjeta:", error);
  }
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

// Cambiar estado de la tarjeta
const cambiarEstadoTarjeta = async (nuevoEstado) => {
  try {
    const url = `http://localhost/SkyBank/backend/public/api.php/tarjetas`;
    const data = { ID_tarjeta: ID_tarjeta, Estado_tarjeta: nuevoEstado };

    const response = await fetch(url, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
    });

    const result = await response.json();

    if (response.ok) {
      estadoTarjeta.value = nuevoEstado;
    } else {
      console.error("Error al actualizar el estado:", result.error || result);
    }
  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
};

// Confirmar bloqueo de tarjeta
const confirmDelete = async () => {
  await cambiarEstadoTarjeta(textos.value.estadoInactiva);
  showModal.value = false;
};

const cancelDelete = () => {
  showModal.value = false;
};
</script>


<style scoped>
.recuadro-thin.verde {
  height: 59px;
  width: 80%;
  margin-left: 8%;
  margin-top: 1%;
}
h1{
    text-align: center;
    padding-left: 0%    ;
}

.botones {
  display: flex;
  gap: 20px; 
  margin-top: 20px;
  justify-content: center;
}
label {
display: flex;
align-items: center;
gap: 10px; 
font-size: 16px;
cursor: pointer;
}

input[type="checkbox"] {
width: 18px;
height: 18px;
}

@media (max-width: 768px) {
  .contenedorT {
    flex-direction: column; 
    align-items: center;
  }

  .recuadro-lateral,
  .recuadro-central {
    width: 90%;
  }
}
</style>
  