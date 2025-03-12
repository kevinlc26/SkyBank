<template>
    <HeaderCliente />
    <div class="main">
      <div class="contenedorGrande">
          <h1>TRANSFERENCIAS</h1>
        <br />
        <div class="contenedorT">
          <MenuTransferencias />
          <div class="recuadro-central gris">
            <h3>Consultar Transferencias</h3>
            
            <div class="filtros">
              <input type="text" v-model="busqueda" placeholder="Buscar por concepto..." />
              <input type="number" v-model="cantidad" placeholder="Cantidad mínima" />
              <input type="date" v-model="fecha" placeholder="Fecha" />
              <select v-model="tipo">
                <option value="">Todos</option>
                <option value="recibido">Recibidos</option>
                <option value="enviado">Enviados</option>
              </select>
              <button class="btn-orange" @click="filtrarTransferencias">Buscar</button>
            </div>
            
            <div class="lista-transferencias">
              <div v-for="transferencia in transferenciasFiltradas" :key="transferencia.id" class="transferencia">
                <p><b>Concepto:</b> {{ transferencia.concepto }}</p>
                <p><b>Cantidad:</b> €{{ transferencia.cantidad }}</p>
                <p><b>Fecha:</b> {{ transferencia.fecha }}</p>
                <p><b>Tipo:</b> {{ transferencia.tipo }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <FooterInicio />
  </template>
  

  
  <script setup>
  import { ref, computed } from "vue";
  import HeaderCliente from "../../components/HeaderCliente.vue";
  import FooterInicio from "../../components/FooterInicio.vue";
  import MenuTransferencias from "../../components/MenuTransferencia.vue";

  // Reactive state
  const busqueda = ref("");
  const cantidad = ref(null);
  const fecha = ref("");
  const tipo = ref("");
  const transferencias = ref([
    { id: 1, concepto: "Pago de servicio", cantidad: 500, fecha: "2024-03-01", tipo: "enviado" },
    { id: 2, concepto: "Salario", cantidad: 1500, fecha: "2024-02-25", tipo: "recibido" },
    { id: 3, concepto: "Renta", cantidad: 1000, fecha: "2024-02-20", tipo: "enviado" },
  ]);

  // Computed property for filtered transfers
  const transferenciasFiltradas = computed(() => {
    return transferencias.value.filter(t => {
      return (
        (!busqueda.value || t.concepto.toLowerCase().includes(busqueda.value.toLowerCase())) &&
        (!cantidad.value || t.cantidad >= cantidad.value) &&
        (!fecha.value || t.fecha === fecha.value) &&
        (!tipo.value || t.tipo === tipo.value)
      );
    });
  });
</script>


<style>
.filtros {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}
.transferencia {
  border: 1px solid #ccc;
  padding: 10px;
  margin-bottom: 10px;
  background-color: white;
  width: 80%;
}
.lista-transferencias{
  width: 90%;
  align-items: center;
}
</style>