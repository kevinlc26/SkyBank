<template>
    <HeaderEmpleado />
      <div class="main">
        <h1>Añadir {{ tableName }}</h1>
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
      cuentas: [
        { COLUMN_NAME: "ID", DATA_TYPE: "int" },  
        { COLUMN_NAME: "Titulares", DATA_TYPE: "varchar" },  
        { COLUMN_NAME: "Tipo", DATA_TYPE: "enum" },  
        { COLUMN_NAME: "Saldo", DATA_TYPE: "int" },  
        { COLUMN_NAME: "Estado", DATA_TYPE: "varchar" },  
        { COLUMN_NAME: "Fecha", DATA_TYPE: "date" }
      ],
    };
  
    //PARAMETROS DE LA RUTA
    const route = useRoute();
    let tableName = route.params.table;
  
    // Datos de formulario
    const formData = ref({});
  
    // Obtener los parámetros de la ruta
    watchEffect(() => {
      tableName = route.params.table;
  
      // Cuando cambiamos el `tableName`, necesitamos cargar los campos correspondientes
      formData.value = {}; // Resetear formData al cambiar de tabla
    });
  
    // Obtener los campos de la tabla
    const fields = computed(() => table[tableName] || []);
  
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
  