<template>
  <div class="modal">
    <div v-if="isModalOpen" class="modal-contenido">
      <span class="cerrar" @click="closeModal">&times;</span>

      <h1>Editar {{ tableName }} {{ id }}</h1>
      <br/>

      <form v-if="campos.length && formData">

        <input type="hidden" :value="id" name="id" id="id">

        <div v-for="(campo, index) in editFields" :key="index">
          <label :for="campo.field">{{ campo.header }}</label>

          <!-- TITULAR-->
          <div v-if="campo.field === 'ID_cliente' || campo.field === 'ID_cliente_2' ">
            <select v-model="formData[campo.field]" :id="campo.field" :name="campo.field">
              <option value="" disabled>Selecciona un cliente</option>
              <option value="deleteTitular"> - Quitar segundo titular -</option>
              <option  v-for="cliente in clientesFormateados" :key="cliente.id" :value="cliente.id">
                {{ cliente.nombreCompleto }}
              </option>
            </select>
          </div>

          <!-- ENUMS -->
          <div v-else-if="enumValues[campo.field]">
            <select v-model="formData[campo.field]" :id="campo.field" :name="campo.field">
              <option v-for="(valor, idx) in enumValues[campo.field]" :key="idx" :value="valor">
                {{ valor }}
              </option>
            </select>
          </div>

          <!-- RESTO -->
          <div v-else>
            <input :type="getInputType(campo.field)" :id="campo.field" :name="campo.field" v-model="formData[campo.field]"/>
          </div>
          
           <!-- IMÁGENES DE PERFIL -->
          <div v-if="tableName === 'perfil' && campo.field === 'Foto_empleado'">
            <label for="Foto_empleado">Imagen de perfil</label>

            <div class="fotos-wrapper">
              <div v-for="(imagen, idx) in imageOptions" :key="idx" :class="['foto-container' + (idx + 1), 'foto-container']"
                @click="formData['Foto_empleado'] = imagen.nombre">
                <img :src="imagen.ruta" :alt="imagen.nombre" :class="{ seleccionado: formData['Foto_empleado'] === imagen.nombre }" style="width: 120px;"/>
              </div>
            </div>
          </div>

        </div>

        <button type="submit" class="btn-orange" @click="mandarEdit">Guardar</button>

      </form>

    <p v-else>No hay campos para esta tabla o no se han cargado los datos.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits, onMounted, nextTick } from "vue";

// RECOGER DATOS ORIGEN
const props = defineProps({
  tableName: String,
  id: [String, Number],
});

//VARIABLES
const isModalOpen = ref(true);
const tableOriginal = computed(() => props.tableName);
let tableName = ref(tableOriginal.value);
const id = computed(() => props.id);
const formData = ref({});
const enumValues = ref({});
const campos = ref([]);

// CARGAR DATOS CUANDO SE MONTA EL COMPONENTE
onMounted(async () => {
  getTableName();
  if (tableName.value !== 'contraseña' && tableName.value !== 'contraseña_verif') {
    await getClientes();
    await nextTick();
    await getDatos(id);
    await nextTick();
  }
  getCampos(tableName.value);
});
console.log("datos: " , formData.value)

// GET TABLE NAME
function getTableName () {
  if (tableOriginal.value === 'detalleCliente') {
    tableName.value = 'clientes';
  } else if (tableOriginal.value === 'detalleCuenta') {
    tableName.value = 'cuentas';
  } else if (tableOriginal.value === 'detalleTarjeta') {
    tableName.value = 'tarjetas';
  } else {
    tableName.value = tableOriginal.value;
  }
}

// GET ID KEY
function getID(tableName) {
  switch (tableName.value) {
    case 'clientes':
      return 'ID_cliente_empleado';
    case 'empleados':
      return 'ID_empleado';
    case 'cuentas':
      return 'ID_cuenta_empleado';
    case 'tarjetas':
      return 'ID_tarjeta';
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

    let tabla = tableName.value;
    if (tableName.value === 'perfil') {
      tabla = 'empleados';
    }
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tabla}?${keyID}=${id.value}`);
    const data = await response.json();
    console.log("data: ", data)
    if (response.ok) {
      formData.value = data; 

      console.log("Datos en formData:", formData.value);
    } else {
      console.error("Error al obtener los datos de la tabla");
    }
  } catch (error) {
    console.error("Error al realizar el fetch:", error);
  }
};


// CARGAR CAMPOS
const getCampos = async (tableName) => {

  // CAMBIAR CONTRASEÑA
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

  // VERIFICAR CONTRASEÑA
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

  // RESTO CAMPOS
  } else  {
    let tabla = tableName;
    if (tableName === 'perfil') {
      tabla = 'empleados';
    }
    try {
      const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tabla}?campos=1}`);
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

// CARGAR TITULARES (CLIENTES)
const clientes = ref([]);
const nombresClientes = ref([]);

const getClientes = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/clientes?Estado_cliente=Activo`);
    
    if (response.ok) {
      const data = await response.json();
      clientes.value = data; 

      nombresClientes.value = clientes.value.map(cliente => cliente.Nombre);

    } else {
      console.error("Error al obtener clientes:", response.status);
    }
  } catch (error) {
    console.error("Error al obtener los clientes:", error);
  }
};

// FORMATEAR NOMBRE CLIENTE
const clientesFormateados = computed(() => {
  console.log("Clientes formateados:", clientes.value);
  return clientes.value.map(cliente => ({
    id: cliente.ID_cliente,
    nombreCompleto: `${cliente.Nombre} ${cliente.Apellidos}`
  }));
});

console.log("ID_cliente en formData:", formData.value.ID_cliente);

// MANDAR PETICION FORMULARIO
const mandarEdit = async (event) => {
  event.preventDefault(); 

  // VERIFICACION DE CONTRASEÑA
  if (tableName.value === 'contraseña_verif') {
    let tabla = 'empleados';
    const oldPassword = formData.value['PIN_empleado_old'];
    const body = JSON.stringify({"PIN_empleado_old": oldPassword});

    try {
      const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tabla}?verif_password=1&ID_empleado=${id.value}`, {
        method: 'POST',  
        headers: {
          'Content-Type': 'application/json',
        },
        body: body,
      });

      if (response.ok) {
        const data = await response.json();

        if (data.success) {
          tableName.value = 'contraseña';
          alert("Contraseña correcta");
          getCampos(tableName.value);

        } else {
          alert("Contraseña errónea");
        }
        
      } else {
        alert("Contraseña incorrecta");
      }
    } catch (error) {
      console.error("Error al enviar los datos:", error);
      alert("Error al enviar");
    }

  // NUEVA CONTRASEÑA
  } else if (tableName.value === 'contraseña') {
    let tabla = 'empleados';
    const password1 = formData.value['PIN_empleado_nuevo'];
    const password2 = formData.value['Confirm_PIN_empleado'];

    if (password1 !== password2) {
      alert("Las contraseñas no coinciden");
      return;
    }

    const body = JSON.stringify({"PIN_empleado": password1});

    try {
      const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tabla}?cambioPassword=1&ID_empleado=${id.value}`, {
        method: 'POST',  
        headers: {
          'Content-Type': 'application/json',
        },
        body: body,
      });

      if (response.ok) {
        alert("La contraseña se ha actualizado correctamente");
        closeModal();
      } else {
        alert("Error al actualizar la contraseña");
      }
    } catch (error) {
      console.error("Error al enviar los datos:", error);
    }


// RESTO DE EDITS
  } else {

    let tabla = tableName.value;
    if (tabla === 'perfil')  {
      tabla = 'empleados';
    }
    try {
      formData.value.id = id.value;
      const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tabla}`, {
        method: 'PUT',  
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData.value),
      });

      console.log(JSON.stringify(formData.value));

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


// CAMPOS DEL FORMULARIO
const editFields = computed(() => editFieldsTablas[tableName.value] || []);
const editFieldsTablas = {
  tarjetas: [
    { field: "Tipo_tarjeta", header: "Tipo de tarjeta" },
    { field: "Fecha_caducidad", header: "Fecha de caducidad" },
    { field: "Limite_operativo", header: "Límite operativo" }
  ],

  cuentas: [
    { field: "ID_cliente", header: "Titular Principal" },
    { field: "ID_cliente_2", header: "Segundo titular" }
  ],

  clientes: [
    { field: "Telefono", header: "Teléfono" },
    { field: "Email", header: "Email" },
    { field: "Direccion", header: "Dirección" }
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
    { field: "Num_SS", header: "Número de la Seguridad Social" }

  ],

  perfil: [
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
    { field: "Foto_empleado", header: "Imagen de perfil" }
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
  { nombre: "1.png", ruta: "/src/assets/imagenes_perfil/1.png" },
  { nombre: "2.png", ruta: "/src/assets/imagenes_perfil/2.png" },
  { nombre: "3.png", ruta: "/src/assets/imagenes_perfil/3.png" },
  { nombre: "4.png", ruta: "/src/assets/imagenes_perfil/4.png" },
  { nombre: "5.png", ruta: "/src/assets/imagenes_perfil/5.png" },
  { nombre: "6.png", ruta: "/src/assets/imagenes_perfil/6.png" },
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
  transform: scale(1.20);
}

.foto-container img:hover {
  transform: scale(1.10);                 
}

</style>
