<template>
  <div class="modal">
    <div class="modal-contenido">
      <span class="cerrar" @click="closeModal">&times;</span>

      <h1>Añadir {{ tableName }}</h1> <br>

      <div class="form-container">
        <form @submit.prevent="submitForm">
          <!-- num aleatorio cuenta/tarjeta -->
          <div v-if="tableName == 'tarjetas' || tableName == 'cuentas'">
            <label style="font-weight: 500;">Número: {{ numAleatorio }}</label>
            <input type="hidden" id="ID" name="ID" v-model="formData.ID">
          </div>

          <!-- campos -->
          <div v-for="(campo, index) in addFields" :key="campo.Field">
            <label :for="campo.field">{{ campo.header }}</label>
            
            <!-- selects -->
            <div v-if="enumValues[campo.field]">
              <select v-model="formData[campo.field]" :id="campo.field" :name="campo.field" required>
                <option value="" disabled selected>Selecciona una opción</option>
                <option v-for="(valor, idx) in enumValues[campo.field]" :key="idx" :value="valor">
                  {{ valor }}
                </option>
              </select>
            </div>
            <!-- resto -->
            <div v-else>
              <input :type="getInputType(campo.field)" :id="campo.field" :name="campo.field" v-model="formData[campo.field]" required/>
            </div>
          </div>

          <button type="submit" class="btn-orange">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineProps, defineEmits, onMounted  } from "vue";

const props = defineProps({
  tableName: { type: String, required: true,},
});

const emit = defineEmits(["close"]);

const tableName = props.tableName;
const campos = ref([]);
const enumValues = ref({});
const formData = ref({});
const numAleatorio = ref('');

// CAMPOS DEL FORMULARIO
const addFieldsTablas = {
  tarjetas: [
    { field: "Titular", header: "Titular" },
    { field: "ID_cuenta", header: "Número de cuenta" },
    { field: "Tipo_tarjeta", header: "Tipo" },
    { field: "Limite_operativo", header: "Límite operativo" }
  ],

  cuentas: [
    { field: "ID_cliente", header: "Titular" },
    { field: "Tipo_cuenta", header: "Tipo" },
    { field: "Saldo", header: "Saldo" },
    { field: "Estado_cuenta", header: "Estado" }
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

  ]
};

// GET CAMPOS FORMULARIO
const addFields = computed(() => addFieldsTablas[tableName] || []);
const getCampos = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${tableName}?campos=1`);
    const camposAPI = await response.json();
    campos.value = camposAPI;

    camposAPI.forEach(campo => {
      if (campo.EnumValues) {
        enumValues.value[campo.Field] = campo.EnumValues;
      }
      formData.value[campo.Field] = ""; // Inicializamos el modelo
    });
  } catch (error) {
    console.error("Error al obtener los campos:", error);
  }
};

// GET CLIENTES
const clientes = ref([]);
const getClientes = async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/clientes?Estado_cliente=Activo`);
    
    if (response.ok) {
      const data = await response.json();
            clientes.value = data; 
    } else {
      console.error("Error al obtener clientes:", response.status);
    }
  } catch (error) {
    console.error("Error al obtener los clientes:", error);
  }
};

//GET CUENTAS DEL CLIENTE
const cuentas = ref([]);
const ID_cliente = ref([]); // faltara ver como cojo el cliente seleccionado del campo del formulario!!!
const getCuentasCliente = async (ID_cliente) => {
      
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/cuentas?ID_cliente=${ID_cliente}`);
    
    if (response.ok) {
      const data = await response.json();
      
      cuentas.value = data;
    } else {
      console.error("Error al obtener las cuentas:", response.status);
    }
  } catch (error) {
    console.error("Error al obtener las cuentas:", error);
  }
}

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
  }

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

onMounted(() => {
  getClientes();
  getCampos();
  if (tableName === 'tarjetas' || tableName === 'cuentas') generarNumero(tableName);
});

// GENERAR TARJETA Y CUENTA ALEATORIOS
const generarNumero = (tipo) => {
  if (tipo === 'tarjetas') {
    let tarjeta = [];
    for (let i = 0; i < 4; i++) tarjeta.push(Math.floor(Math.random() * 10000).toString().padStart(4, '0'));
    numAleatorio.value = tarjeta.join(' ');
  } else if (tipo === 'cuentas') {
    let cuenta = 'ES';
    for (let i = 0; i < 20; i++) cuenta += Math.floor(Math.random() * 10);
    numAleatorio.value = cuenta.replace(/(\d{4})(?=\d)/g, '$1 ');
  } else {
    numAleatorio.value = 'Tipo no válido';
  }
  formData.value.ID = numAleatorio.value;
};


// CERRAR MODAL
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
