<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>Tarjeta {{ ID_tarjeta }}</h1>
      <br />

      <div class="contenedorT">
        <MenuTarjeta />
        <div class="recuadro-central gris">
          
          <template v-if="estadoTarjeta === 'Inactiva'">
            <h3>Activar tarjeta</h3>
            <br />
            <p>La tarjeta {{ ID_tarjeta }} se encuentra Inactiva. No se pueden realizar operaciones con ella.</p>
            <br />
            <p>¿Quieres activar la tarjeta?</p><br>
            <button class="btn-orange" @click="cambiarEstadoTarjeta('Activa')">Activar</button>
          </template>

          
          <template v-else>
            <h3>BLOQUEAR TARJETA</h3>
            <br />
            <div class="opciones">
              <label>
                <input type="checkbox" v-model="motivos" value="robo" />
                Robo
              </label>
              <label>
                <input type="checkbox" v-model="motivos" value="perdida" />
                Pérdida
              </label>
              <label>
                <input type="checkbox" v-model="motivos" value="deterioro" />
                Deterioro
              </label>
            </div>

            <!-- Botones -->
            <div class="botones">
              <button @click.prevent="openConfirmModal" class="btn-orange">Bloquear</button>
            </div>
            <br><br><hr>
            <h3>Desactivar Tarjeta</h3>
            <br>
            <p>Si desactivas tu tarjeta, no podrás utilizarla para ninguna operación hasta que la actives nuevamente.</p>
            <br>
            <button class="btn-orange" @click="cambiarEstadoTarjeta('Inactiva')">Desactivar</button>
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
import { ref, onMounted } from "vue";
import { getCookie } from "../../utils/cookies";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import MenuTarjeta from "../../components/Cliente/menuTarjeta.vue";
import ConfirmDelete from "../../components/Empleado/ConfirmDelete.vue";


const motivos = ref([]);
const showModal = ref(false);
const ID_tarjeta = getCookie("ID_tarjeta");
const estadoTarjeta = ref("");


const openConfirmModal = () => {
  showModal.value = true;
};


const confirmDelete = (id) => {
  console.log(`Tarjeta con ID ${id} bloqueada por motivo(s):`, motivos.value);
  showModal.value = false;
};


const cancelDelete = () => {
  console.log("Operación de bloqueo cancelada.");
  showModal.value = false;
};

const obtenerDetalles = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/tarjetas?ID_tarjeta=${ID_tarjeta}`);
    const data = await response.json();
    
    if (response.ok) {
      estadoTarjeta.value = data.Estado_tarjeta;
    } else {
      console.error("Error al obtener los detalles de la tarjeta", data.error || "Desconocido");
    }
  } catch (error) {
    console.error("Error de conexión:", error);
  }
};
const cambiarEstadoTarjeta = async (nuevoEstado) => {
  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/tarjetas", {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ID_tarjeta: ID_tarjeta,
        Estado_tarjeta: nuevoEstado,
      }),
    });

    const data = await response.json();

    if (response.ok) {
      alert(`Tarjeta ${nuevoEstado === 'Activa' ? 'activada' : 'desactivada'} correctamente.`);
      estadoTarjeta.value = nuevoEstado;
    } else {
      alert("Error al cambiar el estado de la tarjeta: " + (data.error || "Desconocido"));
    }
  } catch (error) {
    console.error("Error de red:", error);
    alert("No se pudo conectar con el servidor.");
  }
};

onMounted(() => {
  obtenerDetalles();
});
</script>

<style scoped>
/* Estilo del recuadro superior */
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

/* Estilo del contenedor de botones (espacio entre ellos) */
.botones {
  display: flex;
  gap: 20px; /* Espacio entre los botones */
  margin-top: 20px;
  justify-content: center;
}
/* Estilo para alinear los checkboxes a la izquierda */
label {
display: flex;
align-items: center;
gap: 10px; /* Espacio entre el checkbox y el texto */
font-size: 16px;
cursor: pointer;
}

/* Ajusta el tamaño del checkbox si es necesario */
input[type="checkbox"] {
width: 18px;
height: 18px;
}

/* Responsividad para pantallas pequeñas */
@media (max-width: 768px) {
  .contenedorT {
    flex-direction: column; /* En móviles, los recuadros se apilan */
    align-items: center;
  }

  .recuadro-lateral,
  .recuadro-central {
    width: 90%;
  }
}
</style>
  