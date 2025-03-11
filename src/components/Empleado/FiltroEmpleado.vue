<template>
    <br /><br />
    <div class="cabecera-filtro">
        <div class="filtro small">
        <p>Filtrar</p>
        <button style="all: unset; cursor: pointer;" @click="openFiltro">
            <img
            style="width: 12px; transition: transform 0.3s ease;"
            :style="{ transform: filtroVisible ? 'rotate(180deg)' : 'rotate(0deg)' }"
            src="../../assets/icons/flecha.svg"
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
                <label class="label-form" :for="dato.COLUMN_NAME">{{ dato.TITULO }}</label>

                <!-- DATOS INPUT -->
                <select v-if="dato.DATA_TYPE === 'enum'" :id="dato.COLUMN_NAME" :name="dato.COLUMN_NAME" v-model="formData[dato.COLUMN_NAME]">
                <option value=""></option>
                <option v-for="option in dato.OPTIONS" :key="option" :value="option">
                    {{ option }}
                </option>
                </select>

                <!-- OTROS DATOS -->
                <input class="input-form" v-else :type="getInputType(dato.DATA_TYPE)" :id="dato.COLUMN_NAME" :name="dato.COLUMN_NAME" v-model="formData[dato.COLUMN_NAME]"/>
            </div>
        </div>   
      </form>
      <p v-else>No hay valores para filtrar</p>
    </div>
  </template>
  
<script setup>
    import { ref, defineProps } from "vue";

    const props = defineProps({
        filtro: {
            type: Array,
            required: true,
        }
    });

    const datosFiltro = props.filtro;

    //FILTRO
    const filtroVisible = ref(false);
    const openFiltro = () => {
        filtroVisible.value = !filtroVisible.value; // ALTERNA TRUE/FALSE
    };

    //DATOS FILTRO
    const getInputType = (dataType) => {
        const numberTypes = ["int", "integer", "decimal","float", "double", "bit"];
        const dateTypes = ["date", "datetime", "timestamp", "datetime-local"];
        const timeTypes = ["time"];
        const booleanTypes = ["boolean", "bool"];
        const passwordTypes = ["password"];
        const emailTypes = ["email"];
        const phoneTypes = ["phone", "tel"];
        const urlTypes = ["url"];
        const fileTypes = ["file"];
        const rangeTypes = ["range"];
        const checkboxTypes = ["checkbox"];
        const radioTypes = ["radio"];
        const hiddenTypes = ["hidden"];
        const searchTypes = ["search"];
        const monthTypes = ["month"];
        const weekTypes = ["week"];

        if (numberTypes.includes(dataType)) return "number";
        if (dateTypes.includes(dataType)) return "date";
        if (timeTypes.includes(dataType)) return "time";
        if (emailTypes.includes(dataType)) return "email";
        if (phoneTypes.includes(dataType)) return "tel";
        if (urlTypes.includes(dataType)) return "url";
        if (passwordTypes.includes(dataType)) return "password";
        if (rangeTypes.includes(dataType)) return "range";
        if (checkboxTypes.includes(dataType)) return "checkbox";
        if (radioTypes.includes(dataType)) return "radio";
        if (fileTypes.includes(dataType)) return "file";
        if (hiddenTypes.includes(dataType)) return "hidden";
        if (searchTypes.includes(dataType)) return "search";
        if (monthTypes.includes(dataType)) return "month";
        if (weekTypes.includes(dataType)) return "week";
        if (booleanTypes.includes(dataType)) return "checkbox"; 
        if (dataType === "enum") return "select";

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

input, select {
  background-color: #e4ded5 !important;
  width: 95% !important;
  padding: 8px !important;
  border: 1px solid black !important;
  border-radius: 5px !important;
}

input:focus, select:focus {
  border-color: #780000;
  outline: none;
}
</style>
  