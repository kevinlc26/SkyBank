<template>
    <div class="recuadro-lateral">
      <ul>
        <li><router-link to="/transferencias-cliente">Realizar Transferencia</router-link></li>
        <li><router-link to="/consulta-Transferencias">Consulta Transferencias</router-link></li>
        <li><router-link to="/traspaso">Traspaso entre cuentas</router-link></li>
        <!-- <li><router-link to="/detallesTarjeta">Transferencias recibidas</router-link></li> -->
      </ul>
    </div>
  </template>

<style>
.recuadro-lateral {
    width: 20%;
    padding: 20px;
    
    border-radius: 8px;
  }
  
  ul, li, a {
    color: #FFFFFF;
    list-style: none;
    font-size: 18px;
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
</style>