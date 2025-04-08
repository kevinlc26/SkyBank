<template>
  <div v-if="showModal" class="modal-overlay">
    <div class="modal-content">
      <p>{{ mensaje }}</p>
      <p>{{ tableName }}</p>
      <p>{{ idToDelete }}</p>
      <p>{{ accion }}</p>
      <div class="modal-buttons">
        <button class="btn-orange" @click="confirmDelete">Confirmar</button>
        <button class="btn-blanco" @click="cancelDelete">Cancelar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, onMounted, ref, computed } from "vue";

const props = defineProps({
  showModal: { type: Boolean, required: true},
  tableName: String,
  idToDelete: [String, Number],
  accion: String,
});

const formData = ref({});
const emit = defineEmits(["confirm", "cancel"]);

// DETERMINAR MENSAJE
const mensaje = computed(() => {
  switch (props.accion) {
    case 'bloquear':
      return "¿Estás seguro de que quieres bloquear este registro?";
    case 'activar':
      return "¿Estás seguro de que quieres activar este registro?";
    case 'desbloquear':
      return "¿Estás seguro de que quieres desbloquear este registro?";
    case 'delete':
      return "¿Estás seguro de que quieres desactivar este registro?";
    default:
      return "No se ha podido encontrar la acción";
  }
});

const getJsonEdit = (tableName, accion, idToDelete) => {
  switch (tableName) {
    case "clientes":
      switch (accion) {
        case "activar":
          return {
            Estado_cliente: "Activo",
            ID_cliente: idToDelete,
          };
        case "delete":
          return {
            Estado_cliente: "Inactivo",
            ID_cliente: idToDelete,
          };
      }
      break;

    case "empleados":
    switch (accion) {
      case "activar":
        return {
          Estado_empleado: "Activo",
          ID_empleado: idToDelete,
        };
      case "delete":
        return {
          Estado_empleado: "Inactivo",
          ID_empleado: idToDelete,
        };
    }
    break;

    case "cuentas":
      switch (accion) {
        case "activar":
          return {
            Estado_cuenta: "Activa",
            ID_cuenta: idToDelete,
          };
        case "delete":
          return {
            Estado_cuenta: "Inactiva",
            ID_cuenta: idToDelete,
          };
        case "bloquear":
          return {
            Estado_cuenta: "Bloqueada",
            ID_cuenta: idToDelete,
          };
        case "desbloquear":
          return {
            Estado_cuenta: "Activa",
            ID_cuenta: idToDelete,
          };
      }
      break;

    case "tarjetas":
      switch (accion) {
        case "activar":
          return {
            Estado_tarjeta: "Activa",
            ID_tarjeta: idToDelete,
          };
        case "delete":
          return {
            Estado_tarjeta: "Inactiva",
            ID_tarjeta: idToDelete,
          };
        case "bloquear":
          return {
            Estado_tarjeta: "Bloqueada",
            ID_tarjeta: idToDelete,
          };
        case "desbloquear":
          return {
            Estado_tarjeta: "Activa",
            ID_tarjeta: idToDelete,
          };
      }
      break;

    default:
      return {};
  }
};

// GET DATOS 
onMounted(async () => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/${props.tableName}?ID_tarjeta=${props.idToDelete}`);
    const data = await response.json();
    
    if (response.ok) {
      formData.value = data;  
    } else {
      console.error("Error al obtener los datos de la tabla");
    }
  } catch (error) {
    console.error("Error al realizar el fetch:", error);
  }
});



// ACTIVAR/DELETE
const confirmDelete = async () => {

  try {

    const url = `http://localhost/SkyBank/backend/public/api.php/${props.tableName}`;
    const body = getJsonEdit(props.tableName, props.accion, props.idToDelete);
    console.log("datos" + body.ID_tarjeta);
    const response = await fetch(url, {
      method: "PATCH",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(body)
    });

    if (!response.ok) {
      throw new Error(`Error en la petición: ${response.status}`);
    }

    console.log(`Registro con ID ${props.idToDelete} actualizado con éxito.`);
    
    emit("confirm", props.idToDelete);
    emit("cancel");

  }  catch (error) {
    console.error("Error al actualizar:", error);
  }
};


const cancelDelete = () => {
  emit("cancel");
};


</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #efe7da;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 600px;
  height: 120px;
  text-align: center;
  display: grid;
  align-items: center;
}

.modal-buttons {
  display: flex;
  justify-content: space-around;
  margin-top: 20px;
}
</style>
