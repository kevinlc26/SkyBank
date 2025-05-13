<template>
  <div class="recuadro-lateral">
    <ul>
      <li><router-link to="/transferencias-cliente">{{ textos.menuRealizarTransferencia }}</router-link></li>
      <li><router-link to="/consulta-Transferencias">{{ textos.menuConsultaTransferencias }}</router-link></li>
      <li><router-link to="/traspaso">{{ textos.menuTraspasoCuentas }}</router-link></li>
    </ul>
  </div>
</template>


<script setup>
import { ref, onMounted, watch, inject } from "vue";
import { gestionarTextos } from "../../utils/traductor.js";

// TRADUCCIÓN
const selectedLang = inject("selectedLang");

onMounted(() => {
  gestionarTextos(textos, selectedLang.value);
});

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

const textos = ref({
  menuRealizarTransferencia: "Realizar Transferencia",
  menuConsultaTransferencias: "Consulta Transferencias",
  menuTraspasoCuentas: "Traspaso entre cuentas"
});

</script>

<style>
.recuadro-lateral {
    width: 20%;
    padding: 20px;
    border-radius: 8px;
  }
  .recuadro-lateral li:hover{
    background: #8d9c65;
    padding-left: 30px;
  }
  
  ul, li, a {
    color: #FFFFFF;
    list-style: none;
    font-size: 18px;
    text-decoration: none;
  }
  .recuadro-thin.verde {
    height: 59px;
    width: 80%;
    margin-left: 8%;
    margin-top: 1%;
  }
  .contenedorT {
    display: flex;
    justify-content: center; /* Centra el contenido */
    align-items: flex-start;
    gap: 40px;
    margin-top: 20px;
    margin-left: 0%;
  }
  
  /* Recuadro central */
  .recuadro-central.gris {
    width: 50%;
    padding: 20px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center; /* Alinea los elementos al centro */
    justify-content: center; /* Alinea los elementos verticalmente en el centro */
  }

  .botones {
    display: flex;
    gap: 20px; /* Espacio entre los botones */
    margin-top: 20px;
    justify-content: center;
  }
  .contenedorGrande{
    width: 100%;
  }
  @media (max-width: 768px) {
  .contenedorT{
    flex-direction: column;
  }
  .recuadro-central.gris {
    width: 90%;
  }
  .recuadro-lateral{
    width: 90%;
    margin-left: 0%;
  }
  .recuadro-thin.verde {
  width: 96%;
  margin-left: 0%;
  background-color: #efe7da;
  }
  }
</style>