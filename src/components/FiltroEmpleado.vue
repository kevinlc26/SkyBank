<template>
    <br /><br />
    <div class="cabecera-filtro">
        <div class="filtro small">
        <p>Filtrar</p>
        <button style="all: unset; cursor: pointer;" @click="openFiltro">
            <img
            style="width: 12px; transition: transform 0.3s ease;"
            :style="{ transform: filtroVisible ? 'rotate(180deg)' : 'rotate(0deg)' }"
            src="../assets/icons/flecha.svg"
            alt="flecha"
            class="flecha"
            />
        </button>
        </div>
        <button v-if="filtroVisible" @click="enviarFormulario" class="btn-orange">Buscar</button>  
    </div>
    
  
    <!-- FILTRO POPUP -->
    <div v-if="filtroVisible" class="filtro contenedor">
      <form id="formFiltro" v-if="datosFiltro.length">
        <div class="formulario">
            <div v-for="(dato, i) in datosFiltro" :key="dato.COLUMN_NAME">
                <label :for="dato.COLUMN_NAME">{{ dato.TITULO }}</label>
                <input 
                    :type="getInputType(dato.DATA_TYPE)"
                    :id="dato.COLUMN_NAME"
                    :name="dato.COLUMN_NAME"
                    v-model="formData[dato.COLUMN_NAME]"
                />
            </div>
        </div>   
      </form>
      <p v-else>No hay valores para filtrar</p>
    </div>
  </template>
  
<script setup>
    import { ref, defineProps } from "vue";

    const props = defineProps({
    tableName: {
        type: String,
        required: true,
    },
    filtro: {
        type: Array,
        required: true,
    }
    });

    const tableName = props.tableName;
    const datosFiltro = props.filtro;

    //FILTRO
    const filtroVisible = ref(false);
    const openFiltro = () => {
        filtroVisible.value = !filtroVisible.value; // ALTERNA TRUE/FALSE
    };

    //DATOS FILTRO
    const getInputType = (dataType) => {
        if (["int", "decimal", "float"].includes(dataType)) return "number";
        if (dataType === "date") return "date";
        if (dataType === "email") return "email";
        if (dataType === "tel") return "tel";
        return "text";
    };
    const formData = ref({});

    const enviarFormulario = () => {
        const formulario = document.getElementById('formFiltro');

        if (formulario) {
            //logica de mandar formulario de momento cerramos el filtro
            filtroVisible.value = false;
        } else {
            alert("No se ha mandado el formulario del filtro");
        }
    }
</script>

<style>

.flecha {
    filter: invert(47%) sepia(100%) saturate(800%) hue-rotate(10deg);
}

.btn-orange {
    right: 0;
}

/* ESTILOS FILTRO */
.filtro {
    border: 2px solid #e88924;
    border-radius: 7px;
    width: 300px;
    padding: 3px;
    padding-right: 5px;
}

.small {
    color: #e88924;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-weight: bold;
}

.contenedor {
    position: relative; 
    background: #efe7da;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    padding: 15px;
    border-radius: 8px;
    z-index: 1000;
    width: 97.8%;
}

.formulario {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    grid-template-rows: repeat(2, auto);
    grid-gap: 20px;
    grid-auto-flow: row;
}


.cabecera-filtro  {
    display: flex;
    flex-direction: row;
    gap: 20px;
}
</style>
  