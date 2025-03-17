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
            <input :type="getInputType(field.DATA_TYPE)" :id="field.COLUMN_NAME" :name="field.COLUMN_NAME" v-model="formData[field.COLUMN_NAME]"/>
          </div>

          <!-- fotos perfil -->
          <div v-if="tableName === 'perfil'">
            <p>Selecciona una foto de perfil:</p>
            
            <div class="fotos-wrapper">
              <div v-for="(img, index) in imageOptions" :key="index" :id="'foto-container' + (index + 1)" class="foto-container">
              <input type="radio" :id="'foto_' + (index + 1)" :name="'foto_empleado'" v-model="formData.foto_empleado" :value="img.ruta" style="display: none;"/>
              
              <label :for="'foto_' + (index + 1)" :id="'foto_' + (index + 1)">
                <img :src="img.ruta" :alt="'foto' + (index + 1)" style="width: 150px; cursor: pointer;" :class="{'seleccionado': formData.foto_empleado === img.ruta}" />
              </label>
              </div>
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

// RECOGER DATOS ORIGEN
const props = defineProps({
  tableName: String,
  id: String,
  datos: Array,
});

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
    { COLUMN_NAME: "Fecha_contratacion", DATA_TYPE: "date",},
    { COLUMN_NAME: "Superior", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Documentos", DATA_TYPE: "file"},
    { COLUMN_NAME: "foto_empleado", DATA_TYPE: "varchar"},
  ],
  detalleCliente: [
    { COLUMN_NAME: "Número de indentidad", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Nacionalidad", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Nombre", DATA_TYPE: "telf" },
    { COLUMN_NAME: "Apellido", DATA_TYPE: "email" },
    { COLUMN_NAME: "Fecha de nacimiento", DATA_TYPE: "date",},
    { COLUMN_NAME: "Teléfono", DATA_TYPE: "tef" },
    { COLUMN_NAME: "Email", DATA_TYPE: "email"},
    { COLUMN_NAME: "Dirección", DATA_TYPE: "varchar"},
    { COLUMN_NAME: "Tarjetas", DATA_TYPE: "varchar"},
    { COLUMN_NAME: "Cuentas", DATA_TYPE: "varchar"},
]

 
};

// CABECERAS DE LAS TABLAS
const cabeceras = {
  clientes: ["ID","DNI/NIE/Pasaporte","Nombre","Apellido/s","Nacionalidad","Fecha de nacimiento","Teléfono","Email","Dirección",],
  cuentas: ["Número de cuenta","Titulares","Tipo","Estado","Saldo","Fecha de apertura",],
  tarjetas: ["Número de tarjeta","Número de cuenta","Titular","Tipo","Estado","Fecha de caducidad","Límite operativo",],
  movimientos: ["ID","Número emisor","Número beneficiario","Número Tarjeta","Tipo","Importe","Fecha","Concepto",],
  transferencias: ["ID","Número emisor","Número beneficiario","Tipo","Importe", "Fecha","Concepto",],
  perfil: ["Número de empleado","Nombre","Apellidos","Teléfono","Email","Fecha de contratación","Superior","Documentos","Imagen de perfil",],
  detalleCliente: ["ID", "Número de indentidad", "Nacionalidad", "Nombre", "Apellido", "Fecha de nacimiento", "Teléfono", "Email", "Dirección", "Tarjetas", "Cuentas"],
  datelleCuenta: ["Número de cuenta", "Titulares", "ID cliente", "Tipo", "Saldo", "Estado", "Fecha de apertura", "Tarjeta asociada"],
  detalleTarjeta: ["Número de tarjeta", "Titulares", "ID cliente", "Tipo", "Límite operativo", "Estado", "Fecha de caducidad", "Cuenta asociada"],
};

// TRATAR CAMPOS DEL EDIT
const fields = computed(() => table[tableName.value] || []);
const titulos = computed(() => cabeceras[tableName.value] || []);
const formData = ref({
  foto_empleado: "",
});
const getInputType = (dataType) => {
        const numberTypes = ["int", "integer", "decimal","float", "double", "bit"];
        const dateTypes = ["date", "datetime", "timestamp", "datetime-local"];
        const timeTypes = ["time"];
        const booleanTypes = ["boolean", "bool"];
        const passwordTypes = ["password"];
        const emailTypes = ["email"];
        const phoneTypes = ["phone", "tel"];
        const urlTypes = ["url"];
        const fileTypes = ["file"];
        const rangeTypes = ["range"];
        const checkboxTypes = ["checkbox"];
        const radioTypes = ["radio"];
        const hiddenTypes = ["hidden"];
        const searchTypes = ["search"];
        const monthTypes = ["month"];
        const weekTypes = ["week"];

        if (numberTypes.includes(dataType)) return "number";
        if (dateTypes.includes(dataType)) return "date";
        if (timeTypes.includes(dataType)) return "time";
        if (emailTypes.includes(dataType)) return "email";
        if (phoneTypes.includes(dataType)) return "tel";
        if (urlTypes.includes(dataType)) return "url";
        if (passwordTypes.includes(dataType)) return "password";
        if (rangeTypes.includes(dataType)) return "range";
        if (checkboxTypes.includes(dataType)) return "checkbox";
        if (radioTypes.includes(dataType)) return "radio";
        if (fileTypes.includes(dataType)) return "file";
        if (hiddenTypes.includes(dataType)) return "hidden";
        if (searchTypes.includes(dataType)) return "search";
        if (monthTypes.includes(dataType)) return "month";
        if (weekTypes.includes(dataType)) return "week";
        if (booleanTypes.includes(dataType)) return "checkbox"; 
        if (dataType === "enum") return "select";

        return "text";
    };


//VARIABLES
const tableName = computed(() => props.tableName);
const id = computed(() => props.id);



// Inicializar formData con los valores de datos
props.datos.forEach(dato => {
  formData.value[dato.COLUMN_NAME] = dato.VALUE;
});


// MODAL
const emit = defineEmits(["close"]);
const closeModal = () => {
  emit("close");
};

//IMAGENES DE PERFIL
const imageOptions = [
  { ruta: "/src/assets/imagenes_perfil/1.png" },
  { ruta: "/src/assets/imagenes_perfil/2.png" },
  { ruta: "/src/assets/imagenes_perfil/3.png" },
  { ruta: "/src/assets/imagenes_perfil/4.png" },
  { ruta: "/src/assets/imagenes_perfil/5.png" },
  { ruta: "/src/assets/imagenes_perfil/6.png" },
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

.label-form {
  display: block;
  font-weight: normal !important;
  margin-bottom: 5px;
}

#campo-id {
  color: #780000 !important;
  font-weight: bold !important;
}


/* FOTOS */

.fotos-wrapper {
  display: grid;                        
  grid-template-columns: repeat(3, 1fr); 
  grid-template-rows: repeat(2, 1fr); 
  margin-top: 20px;
  gap: 10px;                             
  justify-items: center;                
  align-items: center;                 
  grid-template-areas: 
        "col1 col2 col3"
        "col4 col5 col6";
}

.foto-container1 { grid-area: col1; }
.foto-container2 { grid-area: col2; }
.foto-container3 { grid-area: col3; }
.foto-container4 { grid-area: col4; }
.foto-container5 { grid-area: col5; }
.foto-container6 { grid-area: col6; }

.foto-container img.seleccionado {
  transform: scale(1.10);
}

.foto-container img:hover {
  transform: scale(1.10);                 
}

</style>
