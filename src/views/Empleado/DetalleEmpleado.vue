<template>
    <HeaderEmpleado />
    <div class="main">
        <!-- TITULO-->
        <div class="detalle-header">
            <h1> {{ tituloPag }} </h1> 

            <button class="edit-btn" @click="openEditModal">
                <img src="../../assets/icons/edit.svg" alt="edit" width="24" height="24" />
            </button>
        </div>

        <!-- DATOS -->
        <div class="detalle-container">
            
            <div v-for="(value, key) in datosCliente" :key="key" class="detalle-item">
                <label> {{ key }}: </label>
                
            
                <span v-if="Array.isArray(value) || key === 'ID cliente'">
                    <!-- num cuenta o tarjeta -->
                    <ul v-if="Array.isArray(value)">                        
                        <li v-for="(item, index) in value" :key="index"> 
                            <router-link
                                :to="{
                                    name: 'detalle-empleado',
                                    query: {
                                        identificador: item,
                                        tableName: tableName || undefined,
                                    }
                                }"
                            >
                                {{ item }}
                            </router-link>
                        </li>
                    </ul>  
                    <!-- ID cliente -->
                    <router-link        
                        v-else 
                        :to="{
                            name: 'detalle-empleado',
                            query: {
                                identificador: value,
                                tableName: tableName || undefined,
                            }
                        }"
                    >
                        {{ value }}
                    </router-link>
                </span>

                <span v-else>{{ value }}</span>
            </div>
            
        </div>

        <h3>últimos movimientos</h3>
        <FiltroEmpleado :filtro="filtro"/>
        <!-- TABLA MOVIMIENTOS -->
        <TablaEmpleado :headers="cabeceras" :rows="datosTabla" :tableName="'detalleCliente'"/>
        <br>
    </div>

    <EditForm v-if="editVisible && tableName" :tableName="tableName" :id="editId" @close="editVisible = false"/>
    <FooterEmpleado />
</template>

<script setup>
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";
import EditForm from "./EditForm.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import { ref, computed, onMounted, watchEffect } from "vue";
import { useRoute } from "vue-router";

const props = defineProps({
  identificador: String,
  tableName: String
});

const route = useRoute();
const tablaOriginal = props.tableName;
const identificador = computed(() => route.query.identificador || '');
const datosCliente = ref(null);
const datosTabla = ref([]);

onMounted(() => {
    fetchDatosCliente();
    fetchUltimosMovimientos();
});

//TABLE NAME
const tableName = computed(() => {
  const { value } = identificador;

  if (/^ES\d[\d ]*$/.test(value)) { // CUENTA
    return "detalleCuenta";
  } else if (/^[\d ]{19}$/.test(value)) { // TARJETA
    return "detalleTarjeta";
  } else { // CLIENTE
    return "detalleCliente";
  } 
});

// TITULO
const tituloPag = computed(() => {

    if (!datosCliente.value) {
    return "Cargando...";  
  }

  const { value } = identificador;
  
  if (/^ES\d[\d ]*$/.test(value)) { // CUENTA
    return `Cuenta: ${value}`;
  } else if (/^[\d ]{19}$/.test(value)) { // TARJETA
    return `Tarjeta: ${value}`;
  } else { // CLIENTE
    return `${datosCliente.value.ID_cliente} - ${datosCliente.value.Nombre} ${datosCliente.value.Apellidos}`;
  }
});                 

// CABECERA 
const cabeceras = computed(() => {
  const { value } = identificador;

  if (/^ES\d[\d ]*$/.test(value)) { // CUENTA
    return ["ID", "Cuenta beneficiario", "Tarjeta asociada", "Tipo", "Importe", "Fecha de realización", "Concepto"];
  } else if (/^[\d ]{19}$/.test(value)) { // TARJETA
    return ["ID", "Beneficiario", "Tipo", "Importe", "Fecha de realización", "Concepto"];
  } else { // CLIENTE
    return ["ID", "Número de cuenta", "Número Tarjeta", "Tipo", "Importe", "Fecha", "Concepto"];
  }
});


// GET DATOS
const fetchDatosCliente = async () => {
  let url = "";
  let endpoint = "";
  let params = {};

  if (/^ES\d[\d ]*$/.test(identificador.value)) {
    endpoint = "cuentas";
    params = { ID_cuenta_datos: identificador.value };
  } else if (/^[\d ]{19}$/.test(identificador.value)) {
    endpoint = "tarjetas";
    params = { ID_tarjeta: identificador.value };
  } else {
    endpoint = "clientes";
    params = { Num_Ident: identificador.value };
  }

  if (endpoint) {
    url = `http://localhost/SkyBank/backend/public/api.php/${endpoint}?${new URLSearchParams(params).toString()}`;
    
    try {
      const response = await fetch(url, { headers: { 'Content-Type': 'application/json' } });
      const data = await response.json();
      if (!data.error) {
        datosCliente.value = data;

      } else {
        console.error("Error:", data.error);
      }
    } catch (error) {
      console.error("Error de red:", error);
    }
  }
};


const datosClienteArray = computed(() => {
  return Object.entries(datosCliente.value || {}).map(([key, value]) => ({ key, value }));
});


// DATOS PARA ULTIMOS MOVIMIENTOS
const fetchUltimosMovimientos = async () => {
  const urlBase = "http://localhost/SkyBank/backend/public/api.php/movimientos";
  let url = "";
  let params = {};

  // Validar el identificador para saber qué parámetros enviar
  if (/^ES\d[\d ]*$/.test(identificador.value)) { // CUENTA
    url = urlBase;
    params = { ID_cuenta: identificador.value };
  } else if (/^[\d ]{19}$/.test(identificador.value)) { // TARJETA
    url = urlBase;
    params = { ID_tarjeta: identificador.value };
  } else { // CLIENTE
    url = urlBase;
    params = { Num_Ident: identificador.value };
  }

  // Realizar la solicitud si la URL y los parámetros son válidos
  if (url && Object.keys(params).length > 0) {
    const urlWithParams = `${url}?${new URLSearchParams(params).toString()}`;

    try {
      const response = await fetch(urlWithParams, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const data = await response.json();

      // Si no hay error, actualizamos los datos
      if (data.error) {
        console.error("Error al obtener los movimientos:", data.error);
        datosTabla.value = []; // Si hay error, dejamos los datos vacíos
      } else {
        datosTabla.value = data; // Asignamos los datos obtenidos
        console.log("Movimientos obtenidos:", datosTabla.value);
      }
    } catch (error) {
      console.error("Error al obtener los movimientos:", error);
      datosTabla.value = []; // En caso de error, dejamos los datos vacíos
    }
  }
};

//FILTRO
const filtro = computed(() => { // FALTA HARDCODEAR PARA CUENTA/TARJETA/CLIENTE

    let datosFiltro = "";

    datosFiltro = [

        { COLUMN_NAME: "ID_movimiento", DATA_TYPE: "int", TITULO: "ID: " },
        { COLUMN_NAME: "ID_cuenta_beneficiario", DATA_TYPE: "varchar", TITULO: "Beneficiario: " },
        { COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar", TITULO: "Núm Tarjeta: " },
        { COLUMN_NAME: "Tipo_movimiento", DATA_TYPE: "enum", TITULO: "Tipo: " , OPTIONS: ["transferencia", "cobro", "pago", "ingreso"]},
        { COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe desde: " },
        { COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe hasta: " },
        { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha desde: " },
        { COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha hasta: " },
    ];

    return datosFiltro;
});

//MODAL EDIT
const editVisible = ref(false);
const editId = ref(null);
let id = identificador.value;
watchEffect(() => {
  if (tablaOriginal === 'clientes' && datosCliente.value) {
    id = datosCliente.value.ID_cliente; 
  }
});
const openEditModal = () => {
  (editVisible.value = true), 
  (editId.value = id);
  console.log("Modal abierto");
};


</script>

<style scoped>
.detalle-container {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr; 
  gap: 10px; 
  margin-top: 10px;
}

.detalle-item {
  display: flex;
  flex-direction: row;
}


label {
  font-weight: 600;
  gap: 8px;
}

span {
  margin-bottom: 10px;
  margin-left: 8px;
}

ul {
  padding-left: 20px;
  list-style-type: none;
}

ul li {
  margin-bottom: 5px;
  
}

a {
    color: black;
    text-decoration: none;
    font-size: 16px;
    font-weight: normal
}

.detalle-header {
    display: flex;
    flex-direction: row;
}


.edit-btn {
    all: unset;
}

</style>