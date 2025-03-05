<template>
  <div class="modal">
    <div class="modal-contenido">
      <span class="cerrar" @click="closeModal">&times;</span>

      <h1>Editar {{ tableName }}</h1>
      <br />
      <div class="form-container">
        <form v-if="fields.length">
          <label id="campo-id" for="ID">{{ titulos[0] }}: {{ id }}</label>
          <br />
          <div v-for="(field, i) in fields" :key="field.COLUMN_NAME">
            <label :for="field.COLUMN_NAME">{{ titulos[i + 1] }}</label>
            <input
              :type="getInputType(field.DATA_TYPE)"
              :id="field.COLUMN_NAME"
              :name="field.COLUMN_NAME"
              v-model="formData[field.COLUMN_NAME]"
            />
          </div>
          <div v-if="tableName === 'perfil'">
            <p>Selecciona una foto de perfil:</p>
            <div
              v-for="(img, index) in imageOptions"
              :key="index"
              class="foto-container"
            >
              <input
                type="radio"
                :id="'foto_' + index + 1"
                :name="'foto_empleado'"
                v-model="formData.foto_empleado"
                :value="img.ruta"
              />
              <img :src="img.ruta" :alt="'foto' + (index + 1)" width="150px" />
            </div>
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

// DATOS DE LAS TABLAS
const table = {
  clientes: [
    { COLUMN_NAME: "Num_ident", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Nombre", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Apellido", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Nacionalidad", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Fecha_nacimiento", DATA_TYPE: "date" },
    { COLUMN_NAME: "Telefono", DATA_TYPE: "tel" },
    { COLUMN_NAME: "Email", DATA_TYPE: "email" },
    { COLUMN_NAME: "Direccion", DATA_TYPE: "varchar" },
  ],
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
  transferencias: [
    { COLUMN_NAME: "ID_cuenta_emisor", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "ID_cuenta_beneficiario", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Tipo_movimiento", DATA_TYPE: "enum" },
    { COLUMN_NAME: "Importe", DATA_TYPE: "int" },
    { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date" },
    { COLUMN_NAME: "Concepto", DATA_TYPE: "varchar" },
  ],
  perfil: [
    { COLUMN_NAME: "Nombre", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Apellidos", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Telefono", DATA_TYPE: "telf" },
    { COLUMN_NAME: "Email", DATA_TYPE: "email" },
    {
      COLUMN_NAME: "Fecha_contratacion",
      DATA_TYPE: "date",
    },
    { COLUMN_NAME: "Superior", DATA_TYPE: "varchar" },
    {
      COLUMN_NAME: "Documentos",
      DATA_TYPE: "file",
    },
    {
      COLUMN_NAME: "foto_empleado",
      DATA_TYPE: "radiobutton",
    },
  ],
};

// CABECERAS DE LAS TABLAS
const cabeceras = {
  clientes: [
    "ID",
    "DNI/NIE/Pasaporte",
    "Nombre",
    "Apellido/s",
    "Nacionalidad",
    "Fecha de nacimiento",
    "Teléfono",
    "Email",
    "Dirección",
  ],
  cuentas: [
    "Número de cuenta",
    "Titulares",
    "Tipo",
    "Estado",
    "Saldo",
    "Fecha de apertura",
  ],
  tarjetas: [
    "Número de tarjeta",
    "Número de cuenta",
    "Titular",
    "Tipo",
    "Estado",
    "Fecha de caducidad",
    "Límite operativo",
  ],
  movimientos: [
    "ID",
    "Número emisor",
    "Número beneficiario",
    "Número Tarjeta",
    "Tipo",
    "Importe",
    "Fecha",
    "Concepto",
  ],
  transferencias: [
    "ID",
    "Número emisor",
    "Número beneficiario",
    "Tipo",
    "Importe",
    "Fecha",
    "Concepto",
  ],
  perfil: [
    "Número de empleado",
    "Nombre",
    "Apellidos",
    "Teléfono",
    "Email",
    "Fecha de contratación",
    "Superior",
    "Documentos",
    "Imagen de perfil",
  ],
};

// TRATAR DATOS DEL EDIT
const fields = computed(() => table[tableName.value] || []);
const titulos = computed(() => cabeceras[tableName.value] || []);
const formData = ref({});
const getInputType = (dataType) => {
  if (["int", "decimal", "float"].includes(dataType)) return "number";
  if (dataType === "date") return "date";
  if (dataType === "email") return "email";
  if (dataType === "tel") return "tel";
  if (dataType === "file") return "file";
  if (dataType === "radiobutton") return "radiobutton";
  return "text";
};

// RECOGER DATOS ORIGEN
const props = defineProps({
  tableName: String,
  id: String,
});

//VARIABLES
const tableName = computed(() => props.tableName);
const id = computed(() => props.id);

// MODAL
const emit = defineEmits(["close"]);
const closeModal = () => {
  emit("close");
};

//IMAGENES DE PERFIL
const imageOptions = [
  { ruta: "../assets/imagenes_perfil/1.png" },
  { ruta: "../assets/imagenes_perfil/2.png" },
  { ruta: "../assets/imagenes_perfil/3.png" },
  { ruta: "../assets/imagenes_perfil/4.png" },
  { ruta: "../assets/imagenes_perfil/5.png" },
  { ruta: "../assets/imagenes_perfil/6.png" },
];
</script>

<style>
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

#campo-id {
  color: #780000;
  font-weight: bold;
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
