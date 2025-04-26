<template>
  <div class="modal">
    <div v-if="isModalOpen" class="modal-contenido">
      <span class="cerrar" @click="closeModal">&times;</span>

      <h1>Editar {{ tableName }} {{ id }}</h1>
      <br/>
      <form v-if="campos.length && formData">
      <div v-for="(campo, index) in editFields" :key="index">
        <label :for="campo.field">{{ campo.header }}</label>

        <div v-if="enumValues[campo.field]">
          <select v-model="formData[campo.field]" :id="campo.field" :name="campo.field">
            <option v-for="(valor, idx) in enumValues[campo.field]" :key="idx" :value="valor">
              {{ valor }}
            </option>
          </select>
        </div>
        <div v-else>
          <input :type="getInputType(campo.field)" :id="campo.field" :name="campo.field" v-model="formData[campo.field]"/>
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

//VARIABLES
const isModalOpen = ref(true);
const tableName = computed(() => props.tableName);
const id = computed(() => props.id);
const formData = ref({});
const enumValues = ref({});
const campos = ref([]);


// Cargar los datos cuando el componente se monta
onMounted(() => {
  if (tableName.value !== 'contraseña' && tableName.value !== 'contraseña_verif') {
    getDatos(id);
    
  }
  getCampos(tableName.value);
});

// GET ID KEY
function getID(tableName) {
  switch (tableName.value) {
    case 'clientes':
      return 'ID_cliente';
    case 'empleados':
      return 'ID_empleado';
    case 'cuentas':
      return 'ID_cuenta';
    case 'tarjetas':
      return 'ID_tarjeta';
    case 'transferencias':
      return 'ID_movimiento';
    case 'movimientos':
      return 'ID_movimiento';
    case 'perfil':
      return 'ID_empleado';
    default:
      return 'ID no encontrado';
  }
}

// CARGAR DATOS
const getDatos = async (id) => {
  try {
    let keyID = getID(tableName);

    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tableName.value}?${keyID}=${id.value}`);
    const data = await response.json();
    
    console.log(data);
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

  if (tableName === 'contraseña') {
    const camposAPI = [
      {
        Field: 'PIN_empleado_nuevo',
        Type: 'password',
        Name: 'PIN_empleado1',
        Id: 'PIN_empleado1'
      },
      {
        Field: 'Confirm_PIN_empleado',
        Type: 'password',
        Name: 'Confirm_PIN_empleado',
        Id: 'Confirm_PIN_empleado'
      }
    ];

    campos.value = camposAPI;

  }  else if (tableName === 'contraseña_verif') {
    const camposAPI = [
      {
        Field: 'PIN_empleado_old',
        Type: 'password',
        Name: 'PIN_empleado1',
        Id: 'PIN_empleado1'
      }
    ];

    campos.value = camposAPI;

  } else  {
    try {
      const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tableName}?campos=1}`);
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
  
}

// MANDAR DATOS PARA EDITAR
const mandarEdit = async (event) => {
  event.preventDefault(); 

  if (tableName.value === 'contraseña_verif') {
    const tabla = 'empleados';
    const oldPassword = md5(formData.value['PIN_empleado_old']);
    const body = {"PIN_empleado_old": oldPassword};
    try {
      const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tabla.value}?verif_password=1&Num_Ident=${id}`, {
        method: 'GET',  
        headers: {
          'Content-Type': 'application/json',
        },
        body: body,
      });
      console.log("body", body.values);
      if (response.ok) {
        //set tableName = contraseña
        alert("Contraseña correcta");
      } else {
        alert("Contraseña incorrecta");
      }
    } catch (error) {
      console.error("Error al enviar los datos:", error);
    }
  } else {

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
  
}

// 

// CABECERAS DE LAS TABLAS
const cabeceras = {
  clientes: ["ID","DNI/NIE/Pasaporte","Nombre","Apellido/s","Nacionalidad","Fecha de nacimiento","Teléfono","Email","Dirección",],
  cuentas: ["Número de cuenta","Titulares","Tipo","Estado","Saldo","Fecha de apertura",],
  tarjetas: ["Número de tarjeta","Número de cuenta","Titular","Tipo","Estado","Fecha de caducidad","Límite operativo",],
  movimientos: ["ID","Número emisor","Número beneficiario","Número Tarjeta","Tipo","Importe","Fecha","Concepto",],
  transferencias: ["ID","Número emisor","Número beneficiario","Tipo","Importe", "Fecha","Concepto",],
  perfil: ["Número de empleado","Nombre","Apellidos","Teléfono","Email","Fecha de contratación","Superior","Documentos","Imagen de perfil",],
  contraseña_verif: ["Introduce la contraseña actual"],
  contraseña: ["Nueva contraseña", "Repetir contraseña"],
  detalleCliente: ["ID", "Número de indentidad", "Nacionalidad", "Nombre", "Apellido", "Fecha de nacimiento", "Teléfono", "Email", "Dirección", "Tarjetas", "Cuentas"],
  datelleCuenta: ["Número de cuenta", "Titulares", "ID cliente", "Tipo", "Saldo", "Estado", "Fecha de apertura", "Tarjeta asociada"],
  detalleTarjeta: ["Número de tarjeta", "Titulares", "ID cliente", "Tipo", "Límite operativo", "Estado", "Fecha de caducidad", "Cuenta asociada"],
};

// CAMPOS DEL FORMULARIO
const editFields = computed(() => editFieldsTablas[props.tableName] || []);
const editFieldsTablas = {
  tarjetas: [
    { field: "Estado_tarjeta", header: "Estado" },
    { field: "Fecha_caducidad", header: "Fecha de caducidad" },
    { field: "Limite_operativo", header: "Límite operativo" }
  ],

  cuentas: [
    { field: "ID_cliente", header: "Titular" },
    { field: "Estado_cuenta", header: "Estado" }
  ],

  clientes: [
    { field: "Nombre", header: "Nombre" },
    { field: "Apellido", header: "Apellido" },
    { field: "Email", header: "Email" },
    { field: "Num_indent", header: "Número de indentificación" },
    { field: "Estado_cliente", header: "Estado" }
  ],

  empleados: [
    { field: "Num_ident", header: "Número de indentificación" },
    { field: "Nombre", header: "Nombre" },
    { field: "Apellidos", header: "Apellidos" },
    { field: "Nacionalidad", header: "Nacionalidad" },
    { field: "Fecha_nacimiento", header: "Fecha de nacimiento" },
    { field: "Telefono", header: "Teléfono" },
    { field: "Email", header: "Email" },
    { field: "Direccion", header: "Dirección" },
    { field: "Rol", header: "Rol" },
    { field: "Num_SS", header: "Número de la Seguridad Social" },
    { field: "Fecha_contratacion", header: "Fecha de contratación" },

  ],

  contraseña: [
    {field: "PIN_empleado_nuevo", header: "Nueva Contraseña"},
    {field: "Confirm_PIN_empleado", header: "Repetir Contraseña"},
  ],

  contraseña_verif: [
    {field: "PIN_empleado_old", header: "Introduce la contraseña actual"}
  ]
};

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
const getInputType = (fieldName) => {
  const campo = campos.value.find(c => c.Field === fieldName);
  if (!campo) return "text";

  const type = campo.Type.toLowerCase();

  if (type.includes("int") || type.includes("decimal") || type.includes("float") || type.includes("double")) {
    return "number";
  } else if (type.includes("date")) {
    return "date";
  } else if (type.includes("email")) {
    return "email";
  } else if (type.includes("password")) {
    return "password";
  }
  return "text";
};


</script>

<style setup>
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
