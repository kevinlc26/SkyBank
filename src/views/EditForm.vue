<template>
  <div class="modal">
    <div class="modal-contenido">
      <span class="cerrar" @click="cerrarFormulario">&times;</span>

      <div class="main">
        <h1>Editar {{ tableName }}</h1>
        <div class="form-container">
          <form v-if="fields.length">
          <div v-for="field in fields" :key="field.COLUMN_NAME">
            <label :for="field.COLUMN_NAME">{{ field.COLUMN_NAME }}</label>
            <input :type="getInputType(field.DATA_TYPE)" 
                    :id="field.COLUMN_NAME" 
                    :name="field.COLUMN_NAME" 
                    v-model="formData[field.COLUMN_NAME]"  />
          </div>
          <button type="submit" class="btn-orange">Guardar</button>
        </form> 
        <p v-else>No hay campos para esta tabla.</p>
        </div>
      </div>
    </div>
  </div>
</template>
  
  <script setup>
  import { ref, computed, watchEffect } from "vue";
  import { useRoute } from "vue-router";
  
  // Simulación de estructura de la BD
  const table = {
    clientes: [
      { COLUMN_NAME: "ID_cliente", DATA_TYPE: "int" },  
      { COLUMN_NAME: "Num_ident", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "Nombre", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "Apellido", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "Nacionalidad", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "Fecha_nacimiento", DATA_TYPE: "date" },  
      { COLUMN_NAME: "Telefono", DATA_TYPE: "tel" },  
      { COLUMN_NAME: "Email", DATA_TYPE: "email" },  
      { COLUMN_NAME: "Direccion", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "PIN", DATA_TYPE: "int" },  
    ],
    cuentas: [
        { COLUMN_NAME: "ID_cuenta", DATA_TYPE: "varchar" },  
        { COLUMN_NAME: "Fecha_creacion", DATA_TYPE: "date" },  
        { COLUMN_NAME: "Tipo_cuenta", DATA_TYPE: "enum" },  
        { COLUMN_NAME: "Saldo", DATA_TYPE: "int" },  
        { COLUMN_NAME: "Estado", DATA_TYPE: "enum" } 
    ],
    tarjetas: [
        { COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar" },  
        { COLUMN_NAME: "ID_cuenta", DATA_TYPE: "varchar" },  
        { COLUMN_NAME: "Tipo_tarjeta", DATA_TYPE: "enum" },  
        { COLUMN_NAME: "Estado_tarjeta", DATA_TYPE: "enum" },  
        { COLUMN_NAME: "Fecha_caducidad", DATA_TYPE: "date" },  
        { COLUMN_NAME: "Límite operativo", DATA_TYPE: "int" },   
    ],
    movimientos: [
        { COLUMN_NAME: "ID_movimiento", DATA_TYPE: "int" },  
        { COLUMN_NAME: "ID_cuenta_emisor", DATA_TYPE: "varchar" },  
        { COLUMN_NAME: "ID_cuenta_beneficiario", DATA_TYPE: "varchar" },  
        { COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar" },  
        { COLUMN_NAME: "Tipo_movimiento", DATA_TYPE: "enum" },  
        { COLUMN_NAME: "Importe", DATA_TYPE: "int" },  
        { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date" },   
        { COLUMN_NAME: "Concepto", DATA_TYPE: "varchar" },   
    ],
  };

  const cabeceras = {
    clientes_cabecera : ["ID", "DNI/NIE", "Nombre", "Apellido", "Nacionalidad", "Fecha de naciomiento", "Teléfono", "Email", "Dirección"],
    cuentas_cabecera : ["Número de cuenta", "Titulares", "Tipo", "Estado", "Saldo", "Fecha de apertura"],
    tarjetas_cabecera : ["Número de tarjeta", "Número de cuenta", "Titular", "Tipo", "Estado", "Fecha de caducidad", "Límete operativo"],
    movimientos_cabecera : ["ID", "Número emisor", "Número beneficiario", "Número Tarjeta", "Tipo", "Importe", "Fecha", "Concepto"],
  };

  //PARAMETROS DE LA RUTA
  const route = useRoute();
  const tableName = ref("");
  const id = ref("");

  // Datos de formulario
  const formData = ref({});

  // Obtener los parámetros de la ruta
  watchEffect(() => {
    tableName.value = route.params.table;
    id.value = route.params.id;

    // Cuando cambiamos el `tableName`, necesitamos cargar los campos correspondientes
    formData.value = {}; // Resetear formData al cambiar de tabla
  });

  // Obtener los campos de la tabla
  const fields = computed(() => table[tableName.value] || []);

  // Función para definir el tipo de input según el tipo de dato
  const getInputType = (dataType) => {
    if (["int", "decimal", "float"].includes(dataType)) return "number";
    if (dataType === "date") return "date";
    if (dataType === "email") return "email";
    if (dataType === "tel") return "tel";
    return "text"; // Default
  };

  const cerrarFormulario = () => {
    router.go(-1); // Cierra el modal y vuelve a la pantalla anterior
  };

  </script>
    
    
    <!-- CODIGO POSTERIOR PARA PEDIR DATOS A LA BASE DE DATOS
    const fields = ref([]);

    // Función para obtener los campos de la tabla desde PHP
    const getFields = async () => {
    try {
        const response = await fetch(`http://localhost/getFields.php?table=${tableName.value}`);
        const data = await response.json();
        fields.value = data;
    } catch (error) {
        console.error("Error al obtener los campos:", error);
    }
    };

    // Mapeo de tipos de datos MySQL a inputs HTML
    const getInputType = (dataType) => {
    if (dataType.includes("int") || dataType.includes("decimal") || dataType.includes("float")) return "number";
    if (dataType.includes("date")) return "date";
    if (dataType.includes("boolean")) return "checkbox";
    return "text"; // Default para VARCHAR, TEXT, etc.
    };

    onMounted(getFields); -->
<style>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.3);
  display: flex;
  justify-content: center;
  align-items: center;
}

/* Contenedor del formulario */
.modal-contenido {
  background-color: #EFE7DA;
  border: 1px solid #780000;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
  width: 50%;
  height: 80vh;
  position: relative;
}

.form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 75vh;
  width: 100%;
  position: fixed;
  left: 0;
}

form {
  padding: 20px;
  border-radius: 8px;
  width: 50%;
  margin-top: 20px;
  display: flex;
  flex-direction: column;

}

form div {
  margin-bottom: 15px;
}

label {
  display: block;
  font-weight: normal;
  color: black;
  margin-bottom: 5px;
}

input {
  width: 95%;
  padding: 8px;
  border: 0.5px solid black;
  border-radius: 5px;
  background-color: #e4ded5;
}

input:focus {
  border-color: #780000;
  outline: none;
  background-color: #e4ded5;
}


</style>