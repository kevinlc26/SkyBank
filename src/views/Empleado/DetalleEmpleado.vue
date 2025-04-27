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

    <EditForm v-if="editVisible && tableName" :tableName="tableName" :id="identificador" :datos="datosClienteArray" @close="editVisible = false"/>
    <FooterEmpleado />
</template>

<script setup>
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import TablaEmpleado from "../../components/Empleado/TablaEmpleado.vue";
import EditForm from "./EditForm.vue";
import FiltroEmpleado from "../../components/Empleado/FiltroEmpleado.vue";
import { ref, computed, watch } from "vue";
import { useRoute } from "vue-router";

defineProps({
  identificador: String,
  tableName: String
});

const route = useRoute();
const identificador = computed(() => route.query.identificador || '');
const tablaOrigen = computed(() => route.query.tableName || '');

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

console.log("tabla: ", tableName.value);

// TITULO
const tituloPag = computed(() => {
  const { value } = identificador;
  
  if (/^ES\d[\d ]*$/.test(value)) { // CUENTA
    return `Cuenta: ${value}`;
  } else if (/^[\d ]{19}$/.test(value)) { // TARJETA
    return `Tarjeta: ${value}`;
  } else { // CLIENTE
    return `${datosCliente.value.ID_cliente} - ${datosCliente.value.Nombre} ${datosCliente.value.Apellido}`;
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

//MODAL
const editVisible = ref(false);
const openEditModal = () => {
  (editVisible.value = true), (editId = identificador.value);
  console.log("Modal abierto");
};


// GET DATOS
const datosCliente = computed(() => {
  let url = "";
  let endpoint = "";
  let params = {};
  const tarjeta = ref(null);

  if (tablaOrigen.value === "clientes" || (tablaOrigen.value === "cuentas" && !/\d/.test(identificador.value)) || 
        (tablaOrigen.value === "tarjetas" && /^[A-Za-z].*[A-Za-z]$/.test(identificador.value)) || /^\d{3}$/.test(identificador.value)) { // CLIENTE
    endpoint = "clientes";
    params = { Num_Ident: identificador.value };
  } else if (/^ES\d[\d ]*$/.test(identificador.value)) { // CUENTA
    endpoint = "cuentas";
    params = { ID_cuenta: identificador.value };
  } else if (/^[\d ]{19}$/.test(identificador.value)) { // TARJETA
    endpoint = "tarjetas";
    params = { ID_tarjeta: identificador.value };
  }

  if (endpoint) {
    url = `http://localhost/SkyBank/backend/public/api.php/${endpoint}?${new URLSearchParams(params).toString()}`;
    console.log(url);

    return fetch(url, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        console.error("Error:", data.error);
        return null; 
      } else {
        tarjeta.value = data;
        console.log(tarjeta.value);
        return data; 
      }
    })
    .catch(error => {
      console.error("Error al obtener el dato:", error);
      return null; 
    });
  }

  return null; 
});


const datosClienteArray = computed(() => {
    return Object.entries(datosCliente.value || {}).map(([key, value]) => ({ key, value }));

});


// DATOS PARA ULTIMOS MOVIMIENTOS
const datosTabla = computed(() => {
    let datos = [];

    const urlBase = "http://localhost/SkyBank/backend/public/api.php/movimientos";

    let url = "";
    let params = {};

    if (/^ES\d[\d ]*$/.test(identificador.value)) { //CUENTA
        url = urlBase;
        params = { ID_cuenta: identificador.value };
    } else if (/^[\d ]{19}$/.test(identificador.value)) { // TARJETA
        url = urlBase;
        params = { ID_tarjeta: identificador.value };
    } else {                                            // CLIENTE
        url = urlBase;
        params = { Num_Ident: identificador.value };
    }

    if (url && Object.keys(params).length > 0) {
        const urlWithParams = `${url}?${new URLSearchParams(params).toString()}`;
        console.log(urlWithParams);

        fetch(urlWithParams, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error al obtener los movimientos:", data.error);
                return []; 
            } else {
                datos = data; 
                console.log(datos);
            }
        })
        .catch(error => {
            console.error("Error al obtener los movimientos:", error);
            return []; 
        });
    }

    return datos; 
});

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