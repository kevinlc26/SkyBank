<template>
  <div class="modal">
    <div class="modal-contenido">
      <span class="cerrar" @click="closeModal">&times;</span>

      <h1>Añadir {{ tableName }}</h1> <br>

      <div class="form-container">
        <form v-if="fields.length">
          <div v-for="(field, i) in fields" :key="field.COLUMN_NAME">
            <label :for="field.COLUMN_NAME">{{ titulos[i] }}</label>
            <input
              :type="getInputType(field.DATA_TYPE)"
              :id="field.COLUMN_NAME"
              :name="field.COLUMN_NAME"
              v-model="formData[field.COLUMN_NAME]"
            />
          </div>
          <button type="submit" class="btn-orange">Guardar</button>
        </form>
        <p v-else>No hay campos para esta tabla.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits } from "vue";

// Definir la propiedad tableName para que esté disponible en este componente
const props = defineProps({
  tableName: {
    type: String,
    required: true,
  },
});

// Usar tableName desde props
const tableName = props.tableName;

// Datos de las tablas
const table = {
  cuentas: [
    { COLUMN_NAME: "Titulares", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Tipo_cuenta", DATA_TYPE: "enum" },
    { COLUMN_NAME: "Estado", DATA_TYPE: "enum" },
    { COLUMN_NAME: "Saldo", DATA_TYPE: "int" },
    { COLUMN_NAME: "Fecha_creacion", DATA_TYPE: "date" },
  ],
  tarjetas: [
    { COLUMN_NAME: "ID_cuenta", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Titular", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Tipo_tarjeta", DATA_TYPE: "enum" },
    { COLUMN_NAME: "Estado_tarjeta", DATA_TYPE: "enum" },
    { COLUMN_NAME: "Fecha_caducidad", DATA_TYPE: "date" },
    { COLUMN_NAME: "Límite operativo", DATA_TYPE: "int" },
  ],
  movimientos: [
    { COLUMN_NAME: "ID_cuenta_emisor", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "ID_cuenta_beneficiario", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Tipo_movimiento", DATA_TYPE: "enum" },
    { COLUMN_NAME: "Importe", DATA_TYPE: "int" },
    { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date" },
    { COLUMN_NAME: "Concepto", DATA_TYPE: "varchar" },
  ],
};

// CABECERAS DE LAS TABLAS
const cabeceras = {
  cuentas: [
    "Titulares",
    "Tipo",
    "Estado",
    "Saldo",
    "Fecha de apertura",
  ],
  tarjetas: [
    "Número de cuenta",
    "Titular",
    "Tipo",
    "Estado",
    "Fecha de caducidad",
    "Límite operativo",
  ],
  movimientos: [
    "Número emisor",
    "Número beneficiario",
    "Número Tarjeta",
    "Tipo",
    "Importe",
    "Fecha",
    "Concepto",
  ],
};

// Obtener los campos para la tabla seleccionada
const fields = computed(() => table[tableName] || []);
const titulos = computed(() => cabeceras[tableName] || []);
const formData = ref({});

// Función para determinar el tipo de entrada según el tipo de datos
const getInputType = (dataType) => {
  if (["int", "decimal", "float"].includes(dataType)) return "number";
  if (dataType === "date") return "date";
  if (dataType === "email") return "email";
  if (dataType === "tel") return "tel";
  return "text";
};

const emit = defineEmits(["close"]);

const closeModal = () => {
  emit("close");
};
</script>

<style scoped>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-contenido {
  background-color: #efe7da;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
  width: 90%;
  max-width: 500px;
  max-height: 80vh;
  overflow-y: auto;
  position: relative;
  border: 1px solid #780000;
}

.cerrar {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 24px;
  cursor: pointer;
  color: #333;
}

.cerrar:hover {
  color: #e88924;
}

.form-container {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

form {
  width: 100%;
  display: flex;
  flex-direction: column;
}

form div {
  margin-bottom: 15px;
}

label {
  display: block;
  font-weight: normal;
  margin-bottom: 5px;
}

input {
  background-color: #e4ded5;
  width: 95%;
  padding: 8px;
  border: 1px solid black;
  border-radius: 5px;
}

input:focus {
  border-color: #780000;
  outline: none;
}
</style>
