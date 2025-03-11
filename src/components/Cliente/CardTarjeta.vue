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
    />
    <div class="text-center p-2">
      <h2 class="text-lg font-semibold text-gray-800">{{ tarjeta.nombre }}</h2>
      <p class="text-sm text-gray-600">{{ tarjeta.descripcion }}</p>
    </div>
  </div>
</template>

<script>
export default {
props: {
  tarjeta: {
    type: Object,
    required: true,
  },
  isSelected: {
    type: Boolean,
    default: false,
  },
},
methods: {
  selectCard() {
    this.$emit("select");
  },
},
};
</script>

<style scoped>
.card-tarjeta {
width: 100%;
max-width: 300px;
background-color: #ffffff;
border-radius: 12px;
box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
transition: transform 0.2s ease-in-out;
}

.card-tarjeta:hover {
transform: translateY(-4px);
}

.imagen-tarjeta {
border-radius: 12px 12px 0 0;
width: 100%;
height: 160px;
object-fit: cover;
}
</style>
