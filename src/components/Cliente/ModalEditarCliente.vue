<template>
    <div class="modal">
      <div class="modal-content">
        <h3>Editar datos del cliente</h3>
  
        <label name="Nombre">Nombre:</label>
        <input v-model="form.Nombre" type="text" readonly />
  
        <label name="Apellidos">Apellidos:</label>
        <input v-model="form.Apellidos" type="text" readonly />
  
        <label>Nacionalidad:</label>
        <input v-model="form.Nacionalidad" type="text" readonly />
  
        <label>Fecha de nacimiento:</label>
        <input v-model="form.Fecha_nacimiento" type="date" readonly />
  
        <label>Teléfono:</label>
        <input v-model="form.Telefono" type="tel" />
  
        <label>Email:</label>
        <input v-model="form.Email" type="email" />
  
        <label>Dirección:</label>
        <input v-model="form.Direccion" type="text" />
  
        <label>PIN: Opcional (4 digitos numéricos)</label>
        <input 
          type="password" 
          @input="validarPin" 
          placeholder="●●●●" 
        />
  
        <div class="botones">
          <button class="btn-orange" @click="guardarCambios">Guardar</button>
          <button @click="$emit('cerrar')" class="btn-blanco">Cancelar</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { reactive, onMounted } from "vue";
  import { getCookie } from "../../utils/cookies";
  
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
  
  const validarPin = (event) => {
  const input = event.target;
  const value = input.value;

  const numericValue = value.replace(/[^0-9]/g, '');
  if (numericValue.length > 4) {
    input.value = numericValue.slice(0, 4);
    form.PIN = numericValue.slice(0, 4); 
  } else {
    form.PIN = numericValue;
  }
};
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
    alert("El PIN debe tener exactamente 4 dígitos numéricos.");
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
      alert("Datos actualizados correctamente.");
      emit("cerrar");
    } else {
      alert("Error al actualizar: " + (result.error || "Error desconocido"));
    }
  } catch (error) {
    console.error("Error:", error);
    alert("Error de conexión.");
  }
  };
  
  onMounted(() => {
    cargarDatosCliente();
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
  }
  .modal-content {
    background: #efe7da;
    padding: 2rem;
    border-radius: 10px;
    max-width: 500px;
    width: 100%;
  }
  .botones {
    margin-top: 1rem;
    display: flex;
    justify-content: space-between;
  }
  </style>  