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
          <template v-for="(label, key) in cabeceraMap" :key="key">
            <div v-if="datosCliente && datosCliente[key] !== undefined" class="detalle-item">
              <label>{{ label }}:</label>

              <span v-if="Array.isArray(datosCliente[key])">
                <ul>
                  <li v-for="(item, index) in datosCliente[key]" :key="index">
                    <router-link
                      :to="{
                        name: 'detalle-empleado',
                        query: {
                          identificador: item.ID_tarjeta || item.ID_cuenta || item.Num_ident || item,
                          tableName: tableName || undefined,
                          
                        }
                      }"
                    >
                      {{ item.Titular || item.Nombre || item }}
                    </router-link>
                  </li>
                </ul>
              </span>

              <span v-else>{{ datosCliente[key] }}</span>
            </div>
          </template>
        </div>

        <br>

        <h3>últimos movimientos</h3>

        <FiltroEmpleado :filtro="filtro" @filtrarDatos="aplicarFiltro"/>

        <TablaEmpleado :headers="cabeceraUltimos" :rows="filteredRows" :tableName="'movimientos'"/>
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
import { ref, computed, onMounted, watchEffect, watch } from "vue";
import { useRoute, useRouter } from "vue-router";


const props = defineProps({
  identificador: String,
  tableName: String
});

const route = useRoute();
const tablaOriginal = props.tableName;
const identificador = computed(() => route.query.identificador || '');
const datosCliente = ref(null);
const datosTabla = ref([]);
const cabeceraMap = ref({}); 

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
watchEffect(() => {
  if (datosCliente.value) {
    const { value } = identificador;

    if (/^ES\d[\d ]*$/.test(value)) { // CUENTA
      cabeceraMap.value = {
        Fecha_creacion: "Fecha de creación",
        Tipo_cuenta: "Tipo",
        Saldo: "Saldo(€)",
        Estado_cuenta: "Estado de la cuenta",
        Titulares: "Titulares",
        Tarjetas: "Tarejtas asociadas"
      };
    } else if (/^[\d ]{19}$/.test(value)) { // TARJETA
      cabeceraMap.value = {
        ID_cuenta: "ID cuenta",
        Tipo_tarjeta: "Tipo",
        Estado_tarjeta: "Estado",
        Fecha_caducidad: "Fecha de caducidad",
        Limite_operativo: "Límite operativo(€)",
        cuentas_asociadas: "Cuenta asociada",
        titulares: "Titulares"
      };
    } else {                          // CLIENTES
      cabeceraMap.value = {
        ID_cliente: "ID cliente",
        Num_ident: "Número de identidad",
        Titular: "Titular",
        Nacionalidad: "Nacionalidad",
        Fecha_nacimiento: "Fecha de nacimiento",
        Telefono: "Teléfono",
        Email: "Email",
        Direccion: "Dirección",
        Estado_cliente: "Estado del cliente",
        tarjetas_asociadas: "Tarjetas asociadas",
        cuentas_asociadas: "Cuentas asociadas"
      };
    }
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
    params = { ID_tarjeta_datos: identificador.value };
  } else {
    endpoint = "clientes";
    params = { Num_Ident_empleado: identificador.value };
  }

  if (endpoint) {
    url = `http://localhost/SkyBank/backend/public/api.php/${endpoint}?${new URLSearchParams(params).toString()}`;
    
    try {
      const response = await fetch(url, {
        headers: {
          'Content-Type': 'application/json',
        },
      });

      const data = await response.json();

      if (!data || data.error) {
        alert('El registro no existe en la base de datos. Serás redirigido a la página anterior.');
        window.history.go(-1); 
        return;
      }

      datosCliente.value = data;
      console.log(datosCliente.value);

      if (Object.keys(data).length === 0 || Object.values(data).every(val => val === null || val === undefined)) {
        alert('No se encontraron datos. Recargando...');
        recargarRuta();
        return;
      }
      
    } catch (error) {
      console.error("Error de red:", error);
      alert('Ha ocurrido un error al recuperar los datos. Serás redirigido a la página anterior.');
      window.history.go(-1); 
      window.location.reload();
    }
  }
};



// DATOS PARA ULTIMOS MOVIMIENTOS
const fetchUltimosMovimientos = async () => {
  const urlBase = "http://localhost/SkyBank/backend/public/api.php/movimientos";
  let url = "";
  let params = {};

  if (/^ES\d[\d ]*$/.test(identificador.value)) { // CUENTA
    url = urlBase;
    params = { ID_cuenta_empleado: identificador.value };
  } else if (/^[\d ]{19}$/.test(identificador.value)) { // TARJETA
    url = urlBase;
    params = { ID_tarjeta: identificador.value };
  } else { // CLIENTE
    url = urlBase;
    params = { Num_Ident: identificador.value };
  }

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

      if (data.error) {
        console.error("Error al obtener los movimientos:", data.error);
        datosTabla.value = []; 
      } else {
        datosTabla.value = data;
        console.log("Movimientos obtenidos:", datosTabla.value);
      }
    } catch (error) {
      console.error("Error al obtener los movimientos:", error);
      datosTabla.value = []; 
    }
  }
};

// CABECERA ULTIMOS MOVIMIENTOS
const cabeceraUltimos = ["ID", "Cuenta Emisor", "Titular Emisor", "Cuenta Beneficiario", "Titular Beneficiario", "Número de Tarjeta", "Tipo", "Importe(€)","Fecha de Realización", "Concepto", "Estado"];

const filtro = [
  { KEY: "ID_movimiento", COLUMN_NAME: "ID_movimiento", DATA_TYPE: "int", TITULO: "ID: " },
  { KEY: "ID_cuenta_emisor", COLUMN_NAME: "ID_cuenta_emisor", DATA_TYPE: "varchar", TITULO: "Cuenta Emisor: " },
  { KEY: "Titular_Emisor", COLUMN_NAME: "Titular_Emisor", DATA_TYPE: "varchar", TITULO: "Titular Emisor: " },
  { KEY: "ID_cuenta_beneficiario", COLUMN_NAME: "ID_cuenta_beneficiario", DATA_TYPE: "varchar", TITULO: "Cuenta Beneficiario: " },
  { KEY: "Titular_Beneficiario", COLUMN_NAME: "Titular_Beneficiario", DATA_TYPE: "varchar", TITULO: "Titular Beneficiario: " },
  { KEY: "ID_tarjeta", COLUMN_NAME: "ID_tarjeta", DATA_TYPE: "varchar", TITULO: "Núm Tarjeta: " },
  { KEY: "Tipo_movimiento", COLUMN_NAME: "Tipo_movimiento", DATA_TYPE: "enum", TITULO: "Tipo: ", OPTIONS: ["Cobro", "Comisión", "Recibo", "Traspaso", "Pago", "Ingreso"] },
  { KEY: "Estado", COLUMN_NAME: "Estado", DATA_TYPE: "enum", TITULO: "Estado: ", OPTIONS: ["Activo", "Bloqueado"] },
  { KEY: "ImporteDesde", COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe desde: " },
  { KEY: "ImporteHasta", COLUMN_NAME: "Importe", DATA_TYPE: "int", TITULO: "Importe hasta: " },
  { KEY: "FechaDesde", COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha desde: " },
  { KEY: "FechaHasta", COLUMN_NAME: "Fecha_movimiento", DATA_TYPE: "date", TITULO: "Fecha hasta: " },
];


//FUNCIONAMIENTO DEL FILTRO
const filtroActivo = ref({});

// Aplicar filtro sobre los datos
const filteredRows = computed(() => {
  return datosTabla.value.filter((row) => {
    return Object.entries(filtroActivo.value).every(([key, filtroValor]) => {
      if (!filtroValor) return true;

      // Rango de fechas
      if (key === "FechaDesde") {
        return new Date(row.Fecha_movimiento) >= new Date(filtroValor);
      }
      if (key === "FechaHasta") {
        return new Date(row.Fecha_movimiento) <= new Date(filtroValor);
      }

      // Rango de importe
      if (key === "ImporteDesde") {
        return Number(row.Importe) >= Number(filtroValor);
      }
      if (key === "ImporteHasta") {
        return Number(row.Importe) <= Number(filtroValor);
      }

      // Resto de filtros
      const columna = filtro.find(f => f.KEY === key)?.COLUMN_NAME || key;
      const rowValor = row[columna];

      if (rowValor === undefined || rowValor === null) return false;

      const rowStr = rowValor.toString().toLowerCase();
      const filtroStr = filtroValor.toString().trim().toLowerCase();

      return rowStr.includes(filtroStr);
    });
  });
});



// Recibir datos del filtro y actualizar `filtroActivo`
const aplicarFiltro = (filtros) => {
  filtroActivo.value = filtros;
};

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
a:hover {
  color: #e88924;
}

.detalle-header {
    display: flex;
    flex-direction: row;
}


.edit-btn {
    all: unset;
}

</style>