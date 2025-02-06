<template>
    <footer>
      <div>{{ currentDateTime }}</div>
      <div class="right">SkyBank</div>
    </footer>
  </template>
  
  <script>
  import { ref, onMounted } from "vue";
  
  export default {
    setup() {
      const currentDateTime = ref("");
  
      const updateDateTime = () => {
        const now = new Date();
        const options = { weekday: "long", year: "numeric", month: "long", day: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit" };
        currentDateTime.value = now.toLocaleDateString("es-ES", options);
      };
  
      onMounted(() => {
        updateDateTime();
        setInterval(updateDateTime, 1000); // Actualiza la hora cada segundo
      });
  
      return { currentDateTime };
    },
  };
  </script>
  
  <style scoped>
  footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    background: #263E33;
    color: white;
    font-size: 14px;
    position: fixed;
    bottom: 0;
    width: 100%;
  }
  .right {
    margin-left: auto;
  }
  </style>
  