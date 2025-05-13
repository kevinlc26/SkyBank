<template>
  <div class="modal">
    <div class="modal-content">
      <h3>{{ textos.tituloEditarDatos }}</h3>

      <label name="Nombre">{{ textos.labelNombre }}</label>
      <input v-model="form.Nombre" type="text" readonly />

      <label name="Apellidos">{{ textos.labelApellidos }}</label>
      <input v-model="form.Apellidos" type="text" readonly />

      <label>{{ textos.labelNacionalidad }}</label>
      <input v-model="form.Nacionalidad" type="text" readonly />

      <label>{{ textos.labelFechaNacimiento }}</label>
      <input v-model="form.Fecha_nacimiento" type="date" readonly />

      <label>{{ textos.labelTelefono }}</label>
      <input v-model="form.Telefono" type="tel" />

      <label>{{ textos.labelEmail }}</label>
      <input v-model="form.Email" type="email" />

      <label>{{ textos.labelDireccion }}</label>
      <input v-model="form.Direccion" type="text" />

      <label>{{ textos.labelPIN }}</label>
      <input 
        type="password" 
        @input="validarPin" 
        :placeholder="textos.placeholderPIN" 
      />

      <div class="botones">
        <button class="btn-orange" @click="guardarCambios">{{ textos.btnGuardar }}</button>
        <button @click="$emit('cerrar')" class="btn-blanco">{{ textos.btnCancelar }}</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, watch, inject } from "vue";
import { getCookie } from "../../utils/cookies";
import { gestionarTextos } from "../../utils/traductor.js"; // Ruta corregida

const selectedLang = inject("selectedLang");

const ID_cliente = getCookie("ID_cliente");
const props = defineProps(["cliente"]);
const emit = defineEmits(["cerrar"]);

const form = reactive({
  Num_ident: "",
  Nombre: "",
  Apellidos: "",
  Nacionalidad: "",
  Fecha_nacimiento: "",
  Telefono: "",
  Email: "",
  Direccion: "",
  PIN: ""
});

const textos = ref({
  tituloEditarDatos: "Editar datos del cliente",
  labelNombre: "Nombre:",
  labelApellidos: "Apellidos:",
  labelNacionalidad: "Nacionalidad:",
  labelFechaNacimiento: "Fecha de nacimiento:",
  labelTelefono: "Teléfono:",
  labelEmail: "Email:",
  labelDireccion: "Dirección:",
  labelPIN: "PIN: Opcional (4 dígitos numéricos)",
  placeholderPIN: "●●●●",
  btnGuardar: "Guardar",
  btnCancelar: "Cancelar",
  mensajeErrorPIN: "El PIN debe tener exactamente 4 dígitos numéricos.",
  mensajeExito: "Datos actualizados correctamente.",
  mensajeErrorActualizar: "Error al actualizar:",
  mensajeErrorConexion: "Error de conexión."
});

// Validar entrada de PIN
const validarPin = (event) => {
  const input = event.target;
  const value = input.value.replace(/[^0-9]/g, "");

  if (value.length > 4) {
    input.value = value.slice(0, 4);
    form.PIN = value.slice(0, 4);
  } else {
    form.PIN = value;
  }
};

// Cargar datos del cliente
const cargarDatosCliente = async () => {
  try {
    const res = await fetch(`http://localhost/SkyBank/backend/public/api.php/clientes?InfoCliente=${ID_cliente}`);
    const data = await res.json();

    if (res.ok && data) {
      Object.assign(form, data);
      console.log(data);
    } else {
      console.error("Error al cargar datos:", data.error || data.mensaje);
    }
  } catch (err) {
    console.error("Error de red:", err);
  }
};

// Guardar cambios en los datos del cliente
const guardarCambios = async () => {
  const datosActualizados = {
    ID_cliente: ID_cliente,
    Telefono: form.Telefono,
    Email: form.Email,
    Direccion: form.Direccion,
  };

  if (form.PIN.length === 4) {
    datosActualizados.PIN = form.PIN;
  } else if (form.PIN.length > 0 && form.PIN.length !== 4) {
    alert(textos.value.mensajeErrorPIN);
    return;
  }

  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/clientes`, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(datosActualizados)
    });

    const result = await response.json();

    if (response.ok) {
      alert(textos.value.mensajeExito);
      emit("cerrar");
    } else {
      alert(`${textos.value.mensajeErrorActualizar} ${result.error || "Error desconocido"}`);
    }
  } catch (error) {
    console.error("Error:", error);
    alert(textos.value.mensajeErrorConexion);
  }
};

// Traducir textos dinámicamente
onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
  cargarDatosCliente();
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});
</script>

  
  <style scoped>
  .modal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.4);
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: rgba(0, 0, 0, 0.4);

  }
  .modal-content {
    background: #efe7da;
    padding: 2rem;
    border-radius: 10px;
    max-width: 500px;
    width: 100%;
    border: 1px solid #780000;

  }
  .botones {
    margin-top: 1rem;
    display: flex;
    justify-content: space-between;
  }
  </style>  