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
      /> <hr><br>
      <div class="text-center">
        <h2 class="text-lg font-semibold">{{ tarjeta.nombre }}</h2>
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
.card-tarjeta {
  width: 100%;
  max-width: 280px;
  background-color: #f8fbff;
  transition: transform 0.2s ease-in-out;
  border-radius: 5px;
}

.card-tarjeta:hover {
  transform: translateY(-4px);
}

.imagen-tarjeta {
border-radius: 5px, 5px, 0px, 0px;
  width: 100%;
  max-width: 280px;
  height: 120px;
  object-fit: cover;
}
</style>
