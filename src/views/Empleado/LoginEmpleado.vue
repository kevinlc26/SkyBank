<template>
  <HeaderEmpleado />

  <div class="login-container">
    <br /><br /><br /><br /><br />
    <h1>Acceso al portal del empleado</h1>
    <br /><br />
    <div class="recuadro verde">
      <br /><br />

      <form @submit.prevent="login">
        <div class="input-group">
          <label class="label-form" for="user">Usuario</label>
          <input class="input-form"
            id="user"
            type="text"
            v-model="user"
            placeholder="Introduce tu usuario"
            required
          />
        </div>
        <div class="input-group">
          <label class="label-form" for="password">Password</label>
          <input class="input-form"
            id="password"
            type="password"
            v-model="password"
            placeholder="Introduce tu password"
            required
          />
        </div>
        <button type="submit" class="btn-orange">Iniciar sesión</button>
      </form>
    </div>
  </div>

  <FooterEmpleado />
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";

const user = ref("");
const password = ref("");

const router = useRouter();

const login = () => {
  if (user.value && password.value) {
    router.push("/inicio-empleado");
  } else {
    alert("Usuario o contraseña no válidos");
  }
};
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


.recuadro.verde {
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
