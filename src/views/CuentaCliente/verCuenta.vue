<template>
  <HeaderCliente/>
  <div class="main">
    <div class="contenedorGrande">
      <div class="recuadro-thin verde">
        <b><p>{{ cuentas[0] }}</p></b>
      </div>
      <br />
      <div class="contenedorT">
        <menuCuenta/>
        <div class="recuadro-central gris">
          <h1>Movimientos de la cuenta</h1><br>
          
          <!-- Filtros -->
          <div class="filtros">
            <input type="text" v-model="filtroConcepto" placeholder="Filtrar por concepto" />
            <input type="date" v-model="filtroFecha" placeholder="Filtrar por fecha" />
            <select v-model="filtroTipo">
              <option value="">Todos</option>
              <option value="ingreso">Ingresos</option>
              <option value="gasto">Gastos</option>
            </select>
            <input type="number" v-model="filtroImporte" placeholder="Filtrar por importe" />
          </div>
          
          <table class="tabla">
            <thead>
              <tr>
                <th>Fecha y hora</th>
                <th>Concepto</th>
                <th>Importe</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(movimiento, index) in movimientosFiltrados" :key="index">
                <td>{{ movimiento.fecha }}</td>
                <td>{{ movimiento.concepto }}</td>
                <td>{{ movimiento.importe }}€</td>
                <td>{{ movimiento.saldo }}€</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <FooterInicio/>
</template>

<script>
import HeaderCliente from '../../components/HeaderCliente.vue';
import FooterInicio from '../../components/FooterInicio.vue';
import menuCuenta from '../../components/menuCuenta.vue';

export default {
components: {
  HeaderCliente,
  FooterInicio,
  menuCuenta
},
data() {
  return {
    cuentas: [
      "Cuenta Online Skybank",
      "Cuenta Ahorro Skybank"
    ],
    movimientos: [
      { fecha: "05/03/25 10:00", concepto: "Compra Tarj. Supermercado", importe: -20, saldo: 5000.00 },
      { fecha: "04/03/25 15:30", concepto: "Ingreso nómina", importe: 1500, saldo: 5020.00 },
      { fecha: "03/03/25 18:45", concepto: "Pago recibo luz", importe: -60, saldo: 3520.00 },
      { fecha: "02/03/25 09:15", concepto: "Retiro cajero", importe: -100, saldo: 3580.00 },
      { fecha: "01/03/25 20:00", concepto: "Transferencia recibida", importe: 200, saldo: 3680.00 }
    ],
    filtroConcepto: "",
    filtroFecha: "",
    filtroTipo: "",
    filtroImporte: ""
  };
},
computed: {
  movimientosFiltrados() {
    return this.movimientos.filter(movimiento => {
      return (
        (!this.filtroConcepto || movimiento.concepto.toLowerCase().includes(this.filtroConcepto.toLowerCase())) &&
        (!this.filtroFecha || movimiento.fecha.startsWith(this.filtroFecha)) &&
        (!this.filtroImporte || movimiento.importe == this.filtroImporte) &&
        (!this.filtroTipo || (this.filtroTipo === 'ingreso' && movimiento.importe > 0) || (this.filtroTipo === 'gasto' && movimiento.importe < 0))
      );
    });
  }
}
};
</script>

<style scoped>
.tabla {
width: 100%;
border-collapse: collapse;
margin-top: 20px;
}

.tabla th, .tabla td {
border: 1px solid #ddd;
padding: 8px;
text-align: left;
}

.tabla th {
background-color: #9DAC7B;
color: white;
}

.tabla tbody tr:nth-child(even) {
background-color: #e4ded5;
}

.tabla tbody tr:nth-child(odd) {
background-color: #eee9e0;
}

.tabla tbody tr:hover {
background-color: #f1f1f1;
}

.filtros {
  display: flex;
  gap: 10px;
  margin-bottom: 15px;
}

.filtros input, .filtros select {
  padding: 5px;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style>
