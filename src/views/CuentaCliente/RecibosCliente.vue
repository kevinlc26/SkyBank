<template>
  <HeaderCliente />
  <div class="main">
    <div class="contenedorGrande">
      <h1>Cuenta Online Skybank</h1>
      <br />
      
      <div class="contenedorT">
        <menuCuenta />
        <div class="recuadro-central gris">
          <h3>Recibos domiciliados en la cuenta</h3>
          <table class="tabla">
            <thead>
              <tr>
                <th>Fecha Inicio</th>
                <th>Concepto</th>
                <th>Importe</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(recibo, index) in recibos" :key="index">
                <td>{{ recibo.fecha }}</td>
                <td>{{ recibo.Concepto }}</td>
                <td>{{ recibo.importe }}</td>
                <td @click="mostrarPopup(recibo)">
                  <img v-if="recibo.estado === 'bloqueado'" src="../../assets/icons/bloqueado.svg" alt="Bloq">
                  <img v-else src="../../assets/icons/icon-desbloq.svg" alt="Desbloquear" style="height: 24px; width: 24px;">
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
      <p>¿Desea {{ reciboSeleccionado.estado === 'bloqueado' ? 'desbloquear' : 'bloquear' }} este recibo?</p>
      <button @click="cambiarEstado" >Confirmar</button>
      <button @click="popupVisible = false" style="background-color: white; color: black;">Cancelar</button>
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';
  import HeaderCliente from '../../components/Cliente/HeaderCliente.vue';
  import FooterInicio from '../../components/Cliente/FooterInicio.vue';
  import menuCuenta from '../../components/Cliente/menuCuenta.vue';

  // Reactive data
  const recibos = ref([
    { fecha: "01/24", Concepto: "Recibo Luz", importe: "80,00€", estado: "bloqueado" },
    { fecha: "02/25", Concepto: "Recibo Gas", importe: "50,00€", estado: "desbloqueado" },
    { fecha: "12/24", Concepto: "Recibo Coche", importe: "80,00€", estado: "bloqueado" },
    { fecha: "03/25", Concepto: "Recibo Seguro Vida", importe: "50,00€", estado: "desbloqueado" }
  ]);

  const popupVisible = ref(false);
  const reciboSeleccionado = ref(null);

  // Methods
  const mostrarPopup = (recibo) => {
    reciboSeleccionado.value = recibo;
    popupVisible.value = true;
  };

  const cambiarEstado = () => {
    if (reciboSeleccionado.value) {
      reciboSeleccionado.value.estado = reciboSeleccionado.value.estado === 'bloqueado' ? 'desbloqueado' : 'bloqueado';
      popupVisible.value = false;
    }
  };
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
  background: white;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
}
</style>
