<template>
  <div class="modal">
    <div v-if="isModalOpen" class="modal-contenido">
      <span class="cerrar" @click="closeModal">&times;</span>

      <h1>Editar {{ tableName }} {{ id }}</h1>
      <br/>
      <form v-if="campos.length && formData">
      <div v-for="(campo, index) in tarjetasFields" :key="index">
        <label :for="campo.field">{{ campo.header }}</label>

        <div v-if="campo.field === 'Tipo_tarjeta' || campo.field === 'Estado_tarjeta'">
          <select v-model="formData[campo.field]" :id="campo.field" :name="campo.field">
            <option v-for="(valor, idx) in enumValues[campo.field]" :key="idx" :value="valor">
              {{ valor }}
            </option>
          </select>
        </div>
        <div v-else>
          <input :type="getInputType(campo.field)" :id="campo.field" :name="campo.field" v-model="formData[campo.field]" :placeholder="campo.header"/>
        </div>
      </div>

      <button type="submit" class="btn-orange" @click="mandarEdit">Guardar</button>
    </form>

    <p v-else>No hay campos para esta tabla o no se han cargado los datos.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits, onMounted } from "vue";

// RECOGER DATOS ORIGEN
const props = defineProps({
  tableName: String,
  id: [String, Number],
});

// Cargar los datos cuando el componente se monta
onMounted(() => {
  getDatos(id);
  getCampos(tableName);
});

//VARIABLES
const isModalOpen = ref(true);
const tableName = computed(() => props.tableName);
const id = computed(() => props.id);
const formData = ref({});
const enumValues = ref({});
const campos = ref([]);

// API

// CARGAR DATOS
const getDatos = async (id) => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tableName.value}?ID_tarjeta=${id.value}`);
    const data = await response.json();
    
    if (response.ok) {
      formData.value = data;  
    } else {
      console.error("Error al obtener los datos de la tabla");
    }
  } catch (error) {
    console.error("Error al realizar el fetch:", error);
  }
};

// CARGAR CAMPOS
const getCampos = async (tableName) => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tableName.value}?campos=1}`);
    const camposAPI = await response.json();
    campos.value = camposAPI;

    camposAPI.forEach(campo => {
      if (campo.EnumValues) {
        enumValues.value[campo.Field] = campo.EnumValues;
      }
    });
  } catch (error) {
    console.error("Error al obtener los campos:", error);
  }
}

// MANDAR DATOS PARA EDITAR

const mandarEdit = async (event) => {
  event.preventDefault(); 

  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tableName.value}`, {
      method: 'PUT',  
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(formData.value),
    });

    if (response.ok) {
      closeModal();
      alert("Datos actualizados correctamente");
    } else {
      alert("Error al actualizar los datos");
    }
  } catch (error) {
    console.error("Error al enviar los datos:", error);
  }
}

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
const tarjetasFields = [
  { field: "ID_tarjeta", header: "Número de tarjeta" },
  { field: "ID_cuenta", header: "Número de cuenta" },
  { field: "Titular", header: "Titular" },
  { field: "Tipo_tarjeta", header: "Tipo" },
  { field: "Estado_tarjeta", header: "Estado" },
  { field: "Fecha_caducidad", header: "Fecha de caducidad" },
  { field: "Limite_operativo", header: "Límite operativo" }
];




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


//DETERMINAR INPUTS
const getInputType = (field) => {
  const numberFields = ["int", "decimal", "float", "double"];
  const dateFields = ["date"];
  const emailFields = ["email"];
  const textFields = ["varchar"];
  
  if (numberFields.includes(field)) {
    return "number";
  } else if (dateFields.includes(field)) {
    return "date";
  } else if (emailFields.includes(field)) {
    return "email";
  }
  return "text";
};
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
