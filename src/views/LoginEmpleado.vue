<template>
    <HeaderEmpleado /> 
    
    <div class="login-container">
      <br><br><br><br><br>
      <h1>Acceso al portal del empleado</h1>
      <br><br>
        <div class="recuadro verde">
          <br><br> 

          <form @submit.prevent="login">

            <div class="input-group">
              <label for="user">Usuario</label>
              <input id="user" type="text" v-model="user" placeholder="Introduce tu usuario" />
            </div>
            <div class="input-group">
              <label for="password">Password</label>
              <input id="password" type="password"  v-model="password" placeholder="Introduce tu password" />
            </div>
              <button type="submit" class="btn-orange">Iniciar sesión</button>
          </form>

        </div>
    </div>

    <FooterEmpleado /> 
</template>

<script>
import FooterEmpleado from '../components/FooterEmpleado.vue'
import HeaderEmpleado from '../components/HeaderEmpleado.vue'

export default {
  components: {
    HeaderEmpleado,
    FooterEmpleado,
  },
  data() {
    return {
        user: "",
        password: "",
    };
  },
  methods: {
      login() {
        if (this.user && this.password) {
          this.$router.push('/inicio-empleado');
        } else {
          alert ('Usuario o password no válidos');
        }
      },
    },
}

</script>

<style scoped>

.login-container {
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.input-group {
    margin-bottom: 15px;
    text-align: left;
}

  label {
    display: block;
    margin: 5px;
    color: white;
  }
  
  input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ffffff;
    border-radius: 5px;
    padding-right: 0;
  }

  .recuadro.verde{
    width: 500px;
    height: 300px;
    padding: 35px;
    padding-left: 80px;
    padding-right: 80px;
  }

/*PANTALLA MEDIANA*/
@media (max-width: 1024px) {
  
}

/*PANTALLA PEQUEÑA*/
@media (max-width: 768px) {
  .recuadro.verde {
    width: 70%;
    padding: 15px;
    padding-left: 40px;
    padding-right: 40px;
  }
}
</style>