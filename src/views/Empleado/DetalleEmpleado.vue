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
                                        datos: datos ? JSON.stringify(datos) : undefined
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
                                datos: datos ? JSON.stringify(datos) : undefined
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
  tableName: String,
  datos: Object
});

const route = useRoute();
const identificador = computed(() => route.query.identificador || '');
const tablaOrigen = computed(() => route.query.tableName || '');
const datos = computed(() => route.query.datos ? JSON.parse(route.query.datos) : {});

//TABLE NAME
const tableName = computed(() => {

if (tablaOrigen.value === "clientes" || (tablaOrigen.value === "cuentas" && !/\d/.test(identificador.value)) || (tablaOrigen.value === "tarjetas" && /^[A-Za-z].*[A-Za-z]$/.test(identificador.value)) || /^\d{3}$/.test(identificador.value)) {

  return `detalleCliente`;

} else  if (/^ES\d[\d ]*$/.test(identificador.value)) { // cuenta

  return `detalleCuenta`;

} else if (/^[\d ]{19}$/.test(identificador.value)) { // tarjetas
  
  return `detalleTarjeta`;
} else {
    return "unknown";
}
}); 

console.log("tabla: ", tableName.value);

// TITULO
const tituloPag = computed(() => {

  if (tablaOrigen.value === "clientes" || (tablaOrigen.value === "cuentas" && !/\d/.test(identificador.value)) || (tablaOrigen.value === "tarjetas" && /^[A-Za-z].*[A-Za-z]$/.test(identificador.value)) || /^\d{3}$/.test(identificador.value)) {

    return `${datosCliente.value.ID} - ${datosCliente.value.Nombre} ${datosCliente.value.Apellido}`;

  } else  if (/^ES\d[\d ]*$/.test(identificador.value)) { // cuenta

    return "Cuenta: " + identificador.value;

  } else if (/^[\d ]{19}$/.test(identificador.value)) { // tarjetas
    
    return "Tarjeta: " + identificador.value;
  }
});                   


//MODAL
const editVisible = ref(false);
const openEditModal = () => {
  (editVisible.value = true), (editId = identificador.value);
  console.log("Modal abierto");
};


// DATOS

const datosCliente = computed(() => {
  if (tablaOrigen.value === "clientes" || (tablaOrigen.value === "cuentas" && !/\d/.test(identificador.value)) || (tablaOrigen.value === "tarjetas" && /^[A-Za-z].*[A-Za-z]$/.test(identificador.value)) || /^\d{3}$/.test(identificador.value)) {
        return {
        ID: "035",
        "Número de indentidad": 'X2345678B',
        Nacionalidad: "Británica",
        Nombre: "Sara",
        Apellido: "Smith",
        "Fecha de nacimiento": "25-11-1997",
        Teléfono: "648978561",
        Email: "sara.smith@example.com",
        Dirección: "calle Ejemplo 1",
        Tarjetas: ['1234 5678 9012 3460'],
        Cuentas: ['ES91 2100 0418 4502 0005 1332', 'ES91 2100 0418 4502 0005 1336']};

  } else  if (/^ES\d[\d ]*$/.test(identificador.value)) { // cuenta

        return {
            "Número de cuenta": identificador.value,
            Titulares: "Sara Smith",
            "ID cliente": "035",
            Tipo: "online",
            Saldo: "5000",
            Estado: "Activo",
            "Fecha de apertura": "18-02-2022",
            "Tarjeta asociada": ['1234 5678 9012 3460'],
        };

    } else if (/^[\d ]{19}$/.test(identificador.value)) { // tarjetas

        const url = `http://localhost/SkyBank/backend/public/api.php/tarjetas?ID_tarjeta=${identificador.value}`;

        fetch (url, {
            method: 'GET',
            headers: {
            'Content-Type': 'application/json',
            },
        })
        .then (response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                tarjeta.value = data;
                console.log(tarjeta.value);
            }
            })
            .catch(error => {
            console.error("Error al obtener el empleado:", error);
            });

        return data;
    }
});  

const datosClienteArray = computed(() => {
    return Object.entries(datosCliente.value || {}).map(([key, value]) => ({ key, value }));

});


const cabeceras = computed(() => {
    if (tablaOrigen.value === "clientes" || (tablaOrigen.value === "cuentas" && !/\d/.test(identificador.value)) || (tablaOrigen.value === "tarjetas" && /^[A-Za-z].*[A-Za-z]$/.test(identificador.value)) || (tablaOrigen.value === "detalleCliente" && /^ES\d[\d ]*$/.test(identificador.value)) || /^\d{3}$/.test(identificador.value)) {
        return  ["ID", "Número de cuenta", "Número Tarjeta", "Tipo", "Importe", "Fecha", "Concepto"];
    } else  if (/^ES\d[\d ]*$/.test(identificador.value)) { // cuenta
        return ["ID", "Cuenta beneficiario", "Tarjeta asociada", "Tipo", "Importe", "Fecha de realización", "Concepto"];
    } else if (/^[\d ]{19}$/.test(identificador.value)) { // tarjetas
        return ["ID", "Beneficiario", "Tipo", "Importe", "Fecha de realización", "Concepto"];
    }
});

const datosTabla = computed(() => {

    let datos = "";

    if (/^ES\d[\d ]*$/.test(identificador.value)) { // cuenta 
        datos = [
            {  
                ID: 6,
                "Cuenta beneficiario": "ES91 2100 0418 4502 0005 1337",
                Tipo: "Transferencia",
                Importe: "-150",
                "Fecha de realización": "2025-02-18",
                "Concepto": "Devolución de compras"
            },
            {
                ID: 7,
                Tipo: "Pago",
                Importe: "-500",
                "Fecha de realización": "2025-02-17",
                "Tarjeta asociada": "1234 5678 9012 3460",
                "Concepto": "Vuelos Grecia"
            },
            {
                ID: 8,
                Tipo: "Pago",
                Importe: "-200",
                "Fecha de realización": "2025-02-16",
                "Tarjeta asociada": "1234 5678 9012 3460",
                "Concepto": "Hoteles verano"
            },
            {
                ID: 9,
                Tipo: "Cobro",
                Importe: "-199",
                "Fecha de realización": "2025-02-16",
                "Concepto": "Factura de la luz 2º trimestre"
            },
            {
                ID: 10,
                Tipo: "Ingreso",
                Importe: "+2000",
                "Fecha de realización": "2025-02-14",
                "Concepto": "Nómina febrero"
            },
        ];
    } else if (/^[\d ]{19}$/.test(identificador.value)) { // tarjeta
        datos = [
            {  
                ID: 11,
                "Beneficiario": "ES91 2100 0418 4502 0005 1337",
                Tipo: "Pago",
                Importe: "-150",
                "Fecha de realización": "2025-02-18",
                "Concepto": "Compras online"
            },
            {
                ID: 12,
                "Beneficiario": "Tienda",
                Tipo: "Pago",
                Importe: "-500",
                "Fecha de realización": "2025-02-17",
                "Concepto": ""
            },
            {
                ID: 13,
                "Beneficiario": "Supermercado",
                Tipo: "Pago",
                Importe: "-200",
                "Fecha de realización": "2025-02-16",
                "Concepto": "Compra del mes"
            },
            {
                ID: 14,
                "Beneficiario": "ES91 2100 0418 4502 0005 1338",
                Tipo: "Pago",
                Importe: "-199",
                "Fecha de realización": "2025-02-16",
                "Concepto": "Regalo"
            },
            {
                ID: 15,
                "Beneficiario": "Parking",
                Tipo: "Pago",
                Importe: "-7,5",
                "Fecha de realización": "2025-02-14",
                "Concepto": ""
            },
        ];
    } else {
        datos = [
            {  
                ID: 1,
                "Número de cuenta": "ES91 2100 0418 4502 0005 1332",
                "Número Tarjeta": "1234 5678 9012 3460",
                Tipo: "Pago",
                Importe: 150,
                Fecha: "2025-02-18",
                Concepto: "Compra en supermercado",
            },
            {
                ID: 2,
                "Número de cuenta": "ES91 2100 0418 4502 0005 1332",
                "Número Tarjeta": "1234 5678 9012 3460",
                Tipo: "Ingreso",
                Importe: 500,
                Fecha: "2025-02-17",
                Concepto: "Depósito en efectivo",
            },
            {
                ID: 3,
                "Número de cuenta": "ES91 2100 0418 4502 0005 1336",
                "Número Tarjeta": null,
                Tipo: "Pago",
                Importe: 200,
                Fecha: "2025-02-16",
                Concepto: "Transferencia a cuenta corriente",
            },
            {
                ID: 4,
                "Número de cuenta": "ES91 2100 0418 4502 0005 1332",
                "Número Tarjeta": "1234 5678 9012 3460",
                Tipo: "Pago",
                Importe: 75,
                Fecha: "2025-02-15",
                Concepto: "Compra en tienda online",
            },
            {
                ID: 5,
                "Número de cuenta": "ES91 2100 0418 4502 0005 1336",
                "Número Tarjeta": null,
                Tipo: "Ingreso",
                Importe: 300,
                Fecha: "2025-02-14",
                Concepto: "Ingreso por transferencia",
            },
        ];
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