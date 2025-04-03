<template>
  <div class="modal">
    <div class="modal-contenido">
      <span class="cerrar" @click="closeModal">&times;</span>

      <h1>Añadir {{ tableName }}</h1> <br>

      <div class="form-container">
        <form @submit.prevent="submitForm">
          <div v-if="tableName == 'tarjetas' || tableName == 'cuentas'">
            <label style="font-weight: 500;">Número: {{ numAleatorio }}</label>
            <!-- Usamos v-model para que el campo oculto también esté vinculado al formData -->
            <input type="hidden" id="ID" name="ID" v-model="formData.ID">
          </div>
          <div v-for="(field, i) in fields" :key="field.COLUMN_NAME">
            <label :for="field.COLUMN_NAME">{{ titulos[i] }}</label>
            <input :type="getInputType(field.DATA_TYPE)" :id="field.COLUMN_NAME" :name="field.COLUMN_NAME" v-model="formData[field.COLUMN_NAME]" />
          </div>
          <button type="submit" class="btn-orange">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits } from "vue";

// Definir la propiedad tableName para que esté disponible en este componente
const props = defineProps({
  tableName: { type: String, required: true,},
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
    { COLUMN_NAME: "Titular", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "ID_cuenta", DATA_TYPE: "varchar" },
    { COLUMN_NAME: "Tipo_tarjeta", DATA_TYPE: "enum" },
    { COLUMN_NAME: "Limite_operativo", DATA_TYPE: "int" },
  ],
  empleados: [   
    { COLUMN_NAME: 'Num_ident', DATA_TYPE: 'varchar' },  
    { COLUMN_NAME: 'Nombre', DATA_TYPE: 'varchar' },  
    { COLUMN_NAME: 'Apellidos', DATA_TYPE: 'varchar' },  
    { COLUMN_NAME: 'Nacionalidad', DATA_TYPE: 'varchar' },  
    { COLUMN_NAME: 'Fecha_nacimiento', DATA_TYPE: 'date' },  
    { COLUMN_NAME: 'Telefono', DATA_TYPE: 'int' },  
    { COLUMN_NAME: 'Email', DATA_TYPE: 'varchar' },  
    { COLUMN_NAME: 'Direccion', DATA_TYPE: 'varchar' },  
    { COLUMN_NAME: 'Rol', DATA_TYPE: 'enum', OPTIONS: ["Administrador", "Gestor"] },  
    { COLUMN_NAME: 'Num_SS', DATA_TYPE: 'varchar' },  
    { COLUMN_NAME: 'Fecha_contratacion', DATA_TYPE: 'date' }   
  ],
};

// CABECERAS DE LAS TABLAS
const cabeceras = {
  cuentas: ["Titulares","Tipo","Estado","Saldo","Fecha de apertura"],
  tarjetas: ["Titular", "Número de cuenta", "Tipo","Límite operativo"],
  empleados: ["Número de indentificación", "Nombre", "Apellidos", "Nacionalidad", "Fecha de Nacimiento", "Teléfono", "Email", "Dirección", "Rol", "Número de Seguridad Social", "Fecha de Contratación"],

};

// Obtener los campos para la tabla seleccionada
const fields = computed(() => table[tableName] || []);
const titulos = computed(() => cabeceras[tableName] || []);
const formData = ref({});

// Función para determinar el tipo de entrada según el tipo de datos
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

// ENVIAR DATOS A LA API
const submitForm = async () => {
  formData.value.ID = numAleatorio;
  const url = `http://localhost/SkyBank/backend/public/api.php/${tableName}`;
  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData.value),
    });

    const result = await response.json();
    alert(result.mensaje || `${tableName} registrado con éxito`);
    emit("close");
  } catch (error) {
    alert(`Error al registrar ${tableName}`);
    console.error(error);
  }
};

const numAleatorio = ref('');

// GENERAR TARJETA Y CUENTA ALEATORIOS
function generarNumero(tipo) {
    if (tipo === 'tarjetas') {
        let tarjeta = [];
        for (let i = 0; i < 4; i++) {
            let bloque = Math.floor(Math.random() * 10000); 
            tarjeta.push(bloque.toString().padStart(4, '0')); 
        }
        numAleatorio.value = tarjeta.join(' '); 
    } 
    else if (tipo === 'cuentas') {
        let cuenta = 'ES';
        for (let i = 0; i < 20; i++) {
            cuenta += Math.floor(Math.random() * 10); 
        }
        cuenta = cuenta.replace(/(\d{4})(?=\d)/g, '$1 ');
        numAleatorio.value = cuenta; 
    } 
    else {
      numAleatorio.value = 'Tipo no válido'; 
    }
}

generarNumero(tableName);


// CERRAR MODAL
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

</style>
