<template>
  <div v-if="showModal" class="modal-overlay">
    <div class="modal-content">
      <p>¿Estás seguro de que quieres dar de baja este registro?</p>
      <div class="modal-buttons">
        <button class="btn-orange" @click="confirmDelete">Confirmar</button>
        <button class="btn-blanco" @click="cancelDelete">Cancelar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  showModal: {
    type: Boolean,
    required: true,
  },
  idToDelete: {
    type: Number,
    required: true,
  },
});

const emit = defineEmits(["confirm", "cancel"]);

const confirmDelete = () => {
  emit("confirm", props.idToDelete);
};

const cancelDelete = () => {
  emit("cancel");
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #efe7da;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 600px;
  height: 120px;
  text-align: center;
  display: grid;
  align-items: center;
}

.modal-buttons {
  display: flex;
  justify-content: space-around;
  margin-top: 20px;
}
</style>
