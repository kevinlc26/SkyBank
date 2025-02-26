<template>
  <HeaderEmpleado />
    <div class="main">
      <h1>Editar {{ tableName }}</h1>
      <form v-if="fields.length">
        <div v-for="field in fields" :key="field.COLUMN_NAME">
          <label :for="field.COLUMN_NAME">{{ field.COLUMN_NAME }}</label>
          <input :type="getInputType(field.DATA_TYPE)" 
                  :id="field.COLUMN_NAME" 
                  :name="field.COLUMN_NAME" 
                  v-model="formData[field.COLUMN_NAME]"  />
        </div>
        <button type="submit">Guardar</button>
      </form> 
      <p v-else>No hay campos para esta tabla.</p>
    </div>
  <FooterEmpleado />
</template>
  
  <script setup>
  import { ref, computed, watchEffect } from "vue";
  import { useRoute } from "vue-router";
  import HeaderEmpleado from '../components/HeaderEmpleado.vue';
  import FooterEmpleado from '../components/FooterEmpleado.vue';
  
  // Simulación de estructura de la BD
  const table = {
    clientes: [
      { COLUMN_NAME: "id", DATA_TYPE: "int" },  
      { COLUMN_NAME: "dni_nie", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "nombre", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "apellido", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "nacionalidad", DATA_TYPE: "varchar" },  
      { COLUMN_NAME: "fecha_nacimiento", DATA_TYPE: "date" },  
      { COLUMN_NAME: "telefono", DATA_TYPE: "tel" },  
      { COLUMN_NAME: "email", DATA_TYPE: "email" },  
      { COLUMN_NAME: "direccion", DATA_TYPE: "varchar" },  
    ],
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
