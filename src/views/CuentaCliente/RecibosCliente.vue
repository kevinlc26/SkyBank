<template>
  <HeaderCliente />

  <div class="main">
    <div class="contenedorGrande">
      <h1>{{ textos.tituloCuenta }}</h1>
      <br />

      <div class="contenedorT">
        <menuCuenta />
        <div class="recuadro-central gris">
          <h3>{{ textos.tituloRecibos }}</h3>
          <table class="tabla">
            <thead>
              <tr>
                <th>{{ textos.columnaFecha }}</th>
                <th>{{ textos.columnaConcepto }}</th>
                <th>{{ textos.columnaImporte }}</th>
                <th>{{ textos.columnaEstado }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(recibo, index) in recibos" :key="index">
                <td>{{ recibo.Fecha_movimiento }}</td>
                <td>{{ recibo.Concepto }}</td>
                <td>{{ recibo.Importe }}</td>
                <td @click="mostrarPopup(recibo)">
                  <img v-if="recibo.Estado === textos.estadoBloqueado" src="../../assets/icons/bloqueado.svg" :alt="textos.altBloqueado">
                  <img v-else-if="recibo.Estado === textos.estadoActivo" src="../../assets/icons/icon-desbloq.svg" :alt="textos.altActivo" style="height: 24px; width: 24px;">
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <FooterInicio />

  <!-- Popup -->
  <div v-if="popupVisible" class="popup-overlay">
    <div class="popup">
      <p>{{ textos.popupPregunta }} {{ reciboSeleccionado.Estado === textos.estadoBloqueado ? textos.popupDesbloquear : textos.popupBloquear }}?</p>
      <button @click="cambiarEstado" class="btn-orange">{{ textos.btnConfirmar }}</button>
      <button @click="popupVisible = false" class="btn-blanco">{{ textos.btnCancelar }}</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import menuCuenta from "../../components/Cliente/menuCuenta.vue";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const idCuenta = ref(null);
const recibos = ref([]);
const popupVisible = ref(false);
const reciboSeleccionado = ref(null);

const textos = ref({
  tituloCuenta: "Cuenta Online Skybank",
  tituloRecibos: "Recibos domiciliados en la cuenta",
  columnaFecha: "Fecha",
  columnaConcepto: "Concepto",
  columnaImporte: "Importe",
  columnaEstado: "Estado",
  estadoBloqueado: "Bloqueado",
  estadoActivo: "Activo",
  altBloqueado: "Bloqueado",
  altActivo: "Desbloquear",
  popupPregunta: "¿Desea",
  popupBloquear: "bloquear",
  popupDesbloquear: "desbloquear",
  btnConfirmar: "Confirmar",
  btnCancelar: "Cancelar"
});

// Función para obtener la cookie
const obtenerCookie = (nombre) => {
  const name = `${nombre}=`;
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i].trim();
    if (c.indexOf(name) === 0) {
      return c.substring(name.length, c.length);
    }
  }
  return null;
};

// Obtener recibos desde la API
const obtenerRecibos = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/movimientos?ID_cuentaRecibos=${idCuenta.value}`);
    const data = await response.json();
    if (response.ok) {
      recibos.value = data;
    } else {
      console.error("Error en API:", data.error);
    }
  } catch (error) {
    console.error("Error al obtener los recibos:", error);
  }
};

// Mostrar popup de confirmación
const mostrarPopup = (recibo) => {
  reciboSeleccionado.value = recibo;
  popupVisible.value = true;
};

// Cambiar estado del recibo
const cambiarEstado = async () => {
  if (!reciboSeleccionado.value || !idCuenta.value) return;

  const nuevoEstado = reciboSeleccionado.value.Estado === textos.value.estadoBloqueado ? textos.value.estadoActivo : textos.value.estadoBloqueado;

  try {
    const response = await fetch('http://localhost/SkyBank/backend/public/api.php/movimientos', {
      method: 'PATCH',
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        ID_cuenta: idCuenta.value,
        ID_movimiento: reciboSeleccionado.value.ID_movimiento,
        Estado: reciboSeleccionado.value.Estado
      })
    });

    const result = await response.json();

    if (response.ok && result.success) {
      reciboSeleccionado.value.Estado = nuevoEstado;
      popupVisible.value = false;
    } else {
      console.error("No se pudo actualizar el estado:", result.error || result.warning);
      alert("No se pudo actualizar el estado del recibo.");
    }
  } catch (error) {
    console.error("Error en la solicitud:", error);
    alert("Error de conexión con el servidor.");
  }
};

// Traducir textos dinámicamente
onMounted(async () => {
  idCuenta.value = obtenerCookie("ID_cuenta");
  if (idCuenta.value) {
    obtenerRecibos();
  } else {
    console.error("No se encontró ID_cuenta ni en la URL ni en las cookies.");
  }
  
  await gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});
</script>



<style scoped>
.tabla {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.tabla th, .tabla td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.tabla th {
  background-color: #9DAC7B;
  color: white;
}

.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup {
  background: #efe7da;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
}
</style>
