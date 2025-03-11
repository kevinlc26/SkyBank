<template>
  <footer>
    <span class="fecha">{{ currentDateTime }}</span>
    <span class="nombre">SkyBank</span>
  </footer>
</template>

<script setup>
import { ref, onMounted } from "vue";

const currentDateTime = ref("");

// Función para actualizar la fecha y hora
const updateDateTime = () => {
  const now = new Date();
  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  };
  currentDateTime.value = now.toLocaleDateString("es-ES", options);
};

// Actualiza la fecha y hora al montar el componente
onMounted(() => {
  updateDateTime();
  setInterval(updateDateTime, 1000); // Actualiza la hora cada segundo
});
</script>

<style scoped>
footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  padding: 10px 20px;
  background: #263e33;
  color: white;
  font-size: 14px;
  position: fixed;
  bottom: 0;
  width: 100%;
}
.nombre {
  margin-right: 40px;
}

@media (max-width: 768px) {
  footer {
    position: relative;
    text-align: center;
    flex-direction: column;
    gap: 5px;
    margin-bottom: 0;
  }
}
</style>
