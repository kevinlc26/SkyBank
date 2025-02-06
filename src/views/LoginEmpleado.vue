<template>
    <HeaderEmpleado /> 
    <div class="login-container">
        <div class="login-box">
        <h2>Iniciar Sesión</h2>

        <form>
            <div class="input-group">
            <label for="email">Correo electrónico</label>
            <input
                id="email"
                type="email"
                placeholder="ejemplo@correo.com"
            />
            </div>

            <div class="input-group">
            <label for="password">Contraseña</label>
            <input
                id="password"
                type="password"
                placeholder="******"
            />
            </div>

            <button type="submit" class="login-btn">Iniciar sesión</button>
        </form>

        <p class="register-link">
            ¿No tienes cuenta? <a href="#">Regístrate</a>
        </p>
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
  }
}
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f8fff5;
}

.login-box {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  text-align: center;
}

.login-box h2 {
  color: #388e3c; 
  font-size: 24px;
  margin-bottom: 1.5rem;
}

.input-group {
  margin-bottom: 1rem;
  text-align: left;
}

.input-group label {
  display: block;
  color: #388e3c;
  font-size: 14px;
  margin-bottom: 0.5rem;
}

.input-group input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #388e3c;
  border-radius: 5px;
  font-size: 14px;
  color: #388e3c;
}

.input-group input:focus {
  outline: none;
  border-color: #2e7d32; 
  box-shadow: 0 0 5px rgba(46, 125, 50, 0.5);
}

.login-btn {
  width: 100%;
  padding: 1rem;
  background-color: #388e3c; 
  color: white;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.login-btn:hover {
  background-color: #2e7d32; 
}

.register-link {
  margin-top: 1rem;
  font-size: 14px;
}

.register-link a {
  color: #388e3c;
  text-decoration: none;
}

.register-link a:hover {
  text-decoration: underline;
}
</style>