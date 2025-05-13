<template>
    <div 
        class="card-tarjeta border p-4 rounded-2xl flex flex-col items-center gap-4 cursor-pointer hover:shadow-xl transition-shadow duration-300" 
        :class="{ 'border-blue-500 shadow-md': isSelected }" 
        @click="selectCard"
    >
      <img 
        :src="tarjeta.imagen" 
        :alt="tarjeta.nombre" 
        class="imagen-tarjeta object-cover rounded-lg"
      /> <br><br>
      <div class="text-center">
        <h2 class="text-lg font-semibold">{{ tarjeta.nombre }}</h2> <br>
        <p class="text-sm text-gray-600">{{ tarjeta.descripcion }}</p>
      </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  tarjeta: {
    type: Object,
    required: true,
  },
  isSelected: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["select"]);

const selectCard = () => {
  emit("select");
};
</script>




<style scoped>
.tarjeta-container {
  display: flex;
  justify-content: center;
  gap: 20px;
}

.card-tarjeta {
  width: 280px;
  background-color: #eee9e0;
  transition: transform 0.2s ease-in-out;
  border-radius: 12px;
  padding: 16px;
  text-align: center;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.card-tarjeta:hover {
  transform: translateY(-4px);
}

h2 {
  color: #780000;
}

.imagen-tarjeta {
  width: 100%;
  height: 150px;
  object-fit: cover;
}
</style>
