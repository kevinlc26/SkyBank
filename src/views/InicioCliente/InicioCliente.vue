<template>
  <HeaderCliente />
  
  <div class="main">
    <h1>{{ textos.tituloBienvenida }}</h1>

    <!-- Información de la cuenta -->
    <div class="movimientos">
      <h3>{{ textos.tituloCuentas }}</h3> <br>
      <div class="cuenta-inicio" v-for="cuenta in cuentas" :key="cuenta.ID_cuenta">
        <p>{{ textos.cuentaNumero }} <strong>{{ cuenta.ID_cuenta }}</strong></p>
        <p>{{ textos.saldoDisponible }} <strong>{{ cuenta.Saldo }}€</strong></p> <br>
        <button class="btn-orange" @click="guardarIDCuenta(cuenta.ID_cuenta)">{{ textos.btnVerMovimientos }}</button>
      </div>

      <div v-if="cuentas.length === 0">
        <p>{{ textos.sinCuentas }}</p>
      </div>
    </div>
    
    <br>

    <!-- Accesos rápidos -->
    <div class="seccion">
      <h3>{{ textos.tituloAccesosRapidos }}</h3>
      <div class="opciones">
        <button class="btn-orange"><router-link to="/transferencias-cliente">{{ textos.btnTransferencias }}</router-link></button>
        <button class="btn-orange"><router-link to="/traspaso">{{ textos.btnTraspaso }}</router-link></button>
        <button class="btn-orange"><router-link to="/nuevaTarjeta">{{ textos.btnSolicitarTarjeta }}</router-link></button>
        <button class="btn-orange"><router-link to="/nuevaCuenta">{{ textos.btnNuevaCuenta }}</router-link></button>
      </div>
    </div>

    <br><br>
    <!-- Últimos movimientos -->
    <div class="seccion">
      <h3>{{ textos.tituloMovimientos }}</h3> 

      <div v-if="mostrarSinMovimientos">
        <p>No hay movimientos disponibles.</p>
      </div>

      <table v-else class="styled-table">
        <thead>
          <tr>
            <th>{{ textos.columnaActividad }}</th>
            <th>{{ textos.columnaMonto }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(mov, index) in movimientos.slice(0, 3)" :key="index">
            <td>{{ mov.Concepto }}</td>
            <td>{{ mov.Importe }}€</td>
          </tr>

        </tbody>
      </table>
      <br>
    </div>
  </div>
  <br><br><br><br><br><br><br>
  <FooterInicio />
</template>


<script>
import { ref, onMounted, watch, inject } from "vue";
import HeaderCliente from "../../components/Cliente/HeaderCliente.vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import { getCookie, setCookie, deleteCookie } from '../../utils/cookies.js';
import { gestionarTextos } from "../../utils/traductor.js";

export default {
  components: {
    HeaderCliente,
    FooterInicio
  },
  setup() {
    const cuentas = ref([]);
    const errorMessage = ref("");
    const selectedLang = inject("selectedLang");

    const textos = ref({
      tituloBienvenida: "Bienvenido a SkyBank",
      tituloCuentas: "Tus cuentas",
      cuentaNumero: "Cuenta número:",
      saldoDisponible: "Saldo disponible:",
      btnVerMovimientos: "Ver movimientos",
      sinCuentas: "No tienes cuentas disponibles.",

      tituloAccesosRapidos: "Accesos rápidos",
      btnTransferencias: "Transferencias",
      btnTraspaso: "Traspaso",
      btnSolicitarTarjeta: "Solicitar tarjeta",
      btnNuevaCuenta: "Nueva cuenta",

      tituloMovimientos: "Últimos movimientos",
      columnaActividad: "Actividad",
      columnaMonto: "Monto",
      pagoSupermercado: "Pago en supermercado",
      depositoRecibido: "Depósito recibido",
      pagoTarjeta: "Pago de tarjeta"
    });

    watch(selectedLang, async () => {
      await gestionarTextos(textos, selectedLang.value);
    });

    onMounted(async () => {
      await gestionarTextos(textos, selectedLang.value);
      deleteCookie("ID_cuenta");
      obtenerCuentas();
    });

    const movimientos = ref([]);
const mostrarSinMovimientos = ref(false);

const obtenerMovimientos = async (idCuenta) => {
  try {
    const response = await fetch(`http://localhost/SkyBank/backend/public/api.php/movimientos?ID_cuenta=${idCuenta}`);
    const data = await response.json();
    if (response.ok) {
      movimientos.value = data;
      mostrarSinMovimientos.value = data.length === 0;
    } else {
      console.error("Error en API:", data.error);
      mostrarSinMovimientos.value = true;
    }
  } catch (error) {
    console.error("Error al obtener movimientos:", error);
    mostrarSinMovimientos.value = true;
  }
};

const obtenerCuentas = async () => {
  const dni = getCookie("DNI");

  if (!dni) {
    errorMessage.value = "No se ha encontrado el DNI en las cookies.";
    return;
  }

  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/cuentas", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ DNI: dni })
    });

    const data = await response.json();

    if (response.ok) {
      cuentas.value = data;
      if (cuentas.value.length > 0) {
        await obtenerMovimientos(cuentas.value[0].ID_cuenta);
      } else {
        mostrarSinMovimientos.value = true;
      }
    } else {
      errorMessage.value = data.error || "Error al obtener las cuentas.";
    }
  } catch (error) {
    errorMessage.value = "Error al conectar con el servidor.";
  }
};


    const guardarIDCuenta = (idCuenta) => {
      setCookie("ID_cuenta", idCuenta, 1);
      window.location.href = "/verCuenta";
    };

    return {
      cuentas,
      errorMessage,
      textos,
      guardarIDCuenta,
      movimientos,
      mostrarSinMovimientos
    };
  }
};
</script>

  <style scoped>
  
  .cuenta-inicio {
    background-color: #eee9e0;
    padding: 15px;
    margin-bottom: 5px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  
  .accesos-rapidos, .beneficios, .movimientos {
    margin-top: 30px;
    padding: 20px;
  }

  .beneficios, .movimientos {
    background-color:#9DAC7B;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  
  .opciones {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-top: 10px;
  }
  
  .benefit-items {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
  }
  
  .benefit {
    text-align: center;
    width: 200px;
  }
  
  .benefit img {
    width: 80px;
    height: 80px;
    margin-bottom: 10px;
  }
  
  .movimientos ul {
    list-style: none;
    padding: 0;
  }
  
  .movimientos li {
    background-color: #eee9e0;
    padding: 10px;
    margin: 5px 0;
    border-radius: 5px;
    color: black;
  }

  a {
    text-decoration: none;
  }
  /* TABLA */
  .styled-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.styled-table th,
.styled-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.styled-table th {
  background-color: #9dac7b;
  color: white;
}

.styled-table tbody tr:nth-child(even) {
  background-color: #e4ded5;
}

.styled-table tbody tr:nth-child(odd) {
  background-color: #eee9e0;
}

.styled-table tbody tr:hover {
  background-color: #f1f1f1;
}
  
</style>  