<template>
    <HeaderEmpleado />
    <div class="main">
        <!-- TITULO-->
        <h1 v-if = "(tablaOrigen === 'clientes') ||
                       (tablaOrigen === 'cuentas' &&  !(/\d/.test(identificador))) ||
                       (tablaOrigen === 'tarjetas' && (/^[A-Za-z].*[A-Za-z]$/.test(identificador)))"> 
                       {{ datosCliente.ID }} - {{ datosCliente.Nombre + " "+ datosCliente.Apellido }}
        </h1>
        <h1 v-else> {{ identificador }} </h1> <br>

        <!-- DATOS -->
        <div class="detalle-container" v-if = "(tablaOrigen === 'clientes') ||
                       (tablaOrigen === 'cuentas' &&  !(/\d/.test(identificador))) ||
                       (tablaOrigen === 'tarjetas' && (/^[A-Za-z].*[A-Za-z]$/.test(identificador)))">
            
            <div v-for="(value, key) in datosCliente" :key="key" class="detalle-item">
                <label> {{ key }}: </label>
                
            
                <span v-if="Array.isArray(value)"> 
                <ul>
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
                </span>

                <span v-else>{{ value }}</span>
            </div>
            
        </div>
        <!-- TABLA MOVIMIENTOS -->
        <div v-if = "(tablaOrigen === 'clientes') ||
                       (tablaOrigen === 'cuentas' &&  !(/\d/.test(identificador))) ||
                       (tablaOrigen === 'tarjetas' && (/^[A-Za-z].*[A-Za-z]$/.test(identificador)))">
            <TablaEmpleado :headers="cabeceras" :rows="datosTabla" :tableName="'Detalle'"/>
        </div>
        <div v-else >
            <!-- PENDIENTE EL RESTO <TablaEmpleado :headers="detalle" :rows="detalle" :tableName="'Detalle'"/> -->
        </div>
        
        <br>
    </div>
    <FooterEmpleado />
</template>

<script setup>
import { ref } from "vue";
import FooterEmpleado from "../components/FooterEmpleado.vue";
import HeaderEmpleado from "../components/HeaderEmpleado.vue";
import TablaEmpleado from "../components/TablaEmpleado.vue";
import { computed} from 'vue';
import { useRoute } from 'vue-router';
const route = useRoute();

const identificador = computed(() => route.query.identificador || '');
const tablaOrigen = computed(() => route.query.tableName || '');
const datos = computed(() => route.query.datos ? JSON.parse(route.query.datos) : {});
//const movimientos = computed(() => JSON.parse(route.query.movimientos || '[]'));

const tituloPag = 'Detalle';

const datosCliente = ref({
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
    Cuentas: ['ES91 2100 0418 4502 0005 1332', 'ES91 2100 0418 4502 0005 1336'],
});


const cabeceras = ["ID", "Número de cuenta", "Número Tarjeta", "Tipo", "Importe", "Fecha", "Concepto"];

const datosTabla = [
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



</script>

<style>
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

</style>