<template>
     <HeaderEmpleado /> 
      <div class="main">
        <h1 style="display: inline">área de empleado</h1>

        <div class="menu-container">
          <router-link to="/cuentas-empleado" class="menu-card">
            <span id="item1"><img src="../../assets/icons/cuentas.svg" class="icon" alt="cuentas" style="width: 43px;"> Cuentas</span>
          </router-link>
          <router-link to="/clientes-empleado" class="menu-card">
            <span id="item2"><img src="../../assets/icons/clientes.svg" class="icon" alt="clientes" style="width: 45px;"> Clientes</span>
          </router-link>
          <router-link to="/tarjetas-empleado" class="menu-card">
            <span id="item3"><img src="../../assets/icons/tarjetas.svg" class="icon" alt="tarjetas" style="width: 40px;"> Tarjetas</span>
          </router-link>
          <router-link to="/transferencias-empleado" class="menu-card">
            <span id="item4"><img src="../../assets/icons/transferencias.svg" class="icon" alt="transferencias" style="width: 40px;"> Transferencias</span>
          </router-link>
          <router-link to="/movimientos-empleado" class="menu-card">
            <span id="item5"><img src="../../assets/icons/movimientos.svg" class="icon" alt="movimientos" style="width: 35px;"> Movimientos</span>
          </router-link>
          <router-link to="/gestion-empleado" class="menu-card">
            <span id="item6"><img src="../../assets/icons/empleados.svg" class="icon" alt="empleados" style="width: 32px;"> Gestión Empleados</span>
          </router-link>
        </div>
      </div>
      
        

     <FooterEmpleado /> 
</template>

<script>
import FooterEmpleado from '../../components/Empleado/FooterEmpleado.vue'
import HeaderEmpleado from '../../components/Empleado/HeaderEmpleado.vue'


export default {
  components: {
    HeaderEmpleado,
    FooterEmpleado
  }
}
</script>

<style>

.menu-container {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-template-rows: auto auto; 
  gap: 20px;
  padding: 40px;
  grid-template-areas: 
        "col1 col2 col3"
        "col4 col5 col6"
        ;
  justify-content: center;
  align-items: center;
}

.item1 { grid-area: col1; }
.item2 { grid-area: col2; }
.item3 { grid-area: col3; }
.item4 { grid-area: col4; }
.item5 { grid-area: col5; }
.item6 { grid-area: col6; }


.menu-card {
  background: #9DAC7B;
  padding: 20px;
  text-align: center !important;
  border-radius: 10px !important;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  text-decoration: none;
  color: white !important;
  font-size: 22px !important;
  width: 440px !important;
  height: 80px !important;
  display: flex !important;
  justify-content: center !important;
  align-items: center !important;
}

.menu-card i {
  font-size: 30px;
  margin-bottom: 10px;
}

.menu-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.icon {
  filter: invert(100%);
}

/*PANTALLA MEDIANA*/
@media (max-width: 1024px) {

  .menu-card {
    background: #9DAC7B;
    padding: 20px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
    color: white;
    font-size: 22px;
    width: 240px !important;
    height: 50px !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
  }
}

/*PANTALLA PEQUEÑA*/
@media (max-width: 768px) {
  
  .menu-container {
    display: grid !important;
    grid-template-columns: 1fr !important;
    grid-template-rows: auto !important; 
    gap: 20px !important;
    padding: 40px !important;
    grid-template-areas: 
          "col1" 
          "col2" 
          "col3"
          "col4" 
          "col5"
          !important;
    justify-content: center !important;
    align-items: center !important;
  }

  .menu-card {
    background: #9DAC7B;
    padding: 20px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    text-decoration: none;
    color: white;
    font-size: 18px !important;
    width: 240px;
    height: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
  }
}

</style>
