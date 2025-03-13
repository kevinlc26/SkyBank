<template>
  <HeaderInicio />
  <div class="main">
    <h1 class="titulo">ALTA CLIENTE CUENTA ONLINE</h1>
    <div class="login-container">
      <div class="recuadro verde" v-if="pasoActual === 1">
        <h3>DOCUMENTO DE IDENTIDAD</h3> <br>
        <form @submit.prevent="siguientePaso">
          <label for="dni">DNI / NIE</label>
          <input type="text" id="dni" v-model="dni" required /> <br>
            <p>Para abrir tu Cuenta Online es necesario que tengas un documento físico original en vigor y en buen estado.</p> <br>
          <button class="btn-orange" type="submit">Confirmar</button>
        </form>
      </div>

      <div class="recuadro verde" v-if="pasoActual === 2">
        <h3>Datos personales</h3> <br>
              <label for="Nombre">Nombre</label>
              <input type="text" name="nombre" id="">
              <label for="Nombre">Apellidos</label>
              <input type="text" name="nombre" id="">
              <label for="email">Correo electrónico</label>
              <input type="email" name="email" id="">
              <label for="Nombre">Número de teléfono</label>
              <input type="number" name="numero" id="">
              <label for="Nombre">Calle</label>
              <input type="text" name="nombre" id="">
              <label for="numero">Número</label>
              <input type="text" name="numero" id="">
              <label for="CP">Código Postal</label>
              <input type="text" name="CP" id="">
              <label for="Nombre">Localidad</label>
              <input type="text" name="nombre" id=""> <br> <br>
        <button class="btn-orange" @click="siguientePaso">Continuar</button>
      </div>
      
      <div class="recuadro verde" v-if="pasoActual === 3">
        <h3>Subir fotografía DNI</h3>
        <input type="file" @change="onFileChange" accept="image/*" />
        <img v-if="previewUrl" :src="previewUrl" alt="Vista previa" class="preview" />
        <button class="btn-orange" @click="siguientePaso" v-if="selectedFile">Confirmar</button>
      </div>
      
      <div class="recuadro verde" v-if="pasoActual === 4">
        <h3>Tratamiento de datos y documentación</h3><br>
        <p>Al enviar esta solicitud, usted autoriza a Skybank, [datos de la entidad], a tratar sus datos personales con la finalidad de 
          gestionar su solicitud, formalizar la relación contractual, prestarle los servicios solicitados, así como para cumplir con las 
          obligaciones legales aplicables.
          Sus datos serán tratados de conformidad con lo dispuesto en el Reglamento General de Protección de Datos (RGPD) y demás normativa 
          aplicable. Puede consultar nuestra política de privacidad completa en politica de privacidad.
        </p>
        <button class="btn-orange" @click="siguientePaso">Finalizar</button>
      </div>
      <div class="rectangulo2">
              <h3>Pasos para completar el alta</h3> <br>
              <div class="pasos">
                  <div class="paso-container">
                      <img src="../../assets/icons/paso1.svg" alt="Icono 1" class="paso">
                      <p id="p1">Tu documento de identidad</p>
                  </div>
                  <div class="paso-container">
                      <img src="../../assets/icons/paso2.svg" alt="Icono 2" class="paso">
                      <p id="p1">Datos personales</p>
                  </div>
                  <div class="paso-container">
                      <img src="../../assets/icons/paso3.svg" alt="Icono 3" class="paso">
                      <p id="p1">Subir fotografía DNI</p>
                  </div>
                  <div class="paso-container">
                      <img src="../../assets/icons/paso4.svg" alt="Icono 4" class="paso">
                      <p id="p1">Aceptar el tratamiento de datos y docs.</p>
                  </div>
              </div>
          </div><br>
    </div>
  </div>
  <FooterInicio/>
</template>

<script setup>
import { ref } from "vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import HeaderInicio from "../../components/Cliente/HeaderInicio.vue";

const pasoActual = ref(1);
const dni = ref("");
const selectedFile = ref(null);
const previewUrl = ref(null);

const siguientePaso = () => {
  if (pasoActual.value < 4) {
    pasoActual.value++;
  }
};

const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};
</script>


<style scoped>
.main {
  margin-bottom: 200px;
  margin-top: 200px;
}
.login-container {
    background-color: #efe7da;
    min-height: 10vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.login-box {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 350px;
}
.input-group {
    margin-bottom: 15px;
    text-align: left;
}
    
label {
 color: white;
}

.recuadro.verde p{
  color: white;
}
.rectangulo2{
    padding: 30px 10px;
    background: #eee9e0;
    border: 2px solid #780000;
    border-radius: 15px;
    display: flexbox;
    justify-content: center;
    align-items: center;
    text-align: center;
    margin-left: 5%;
    padding-left: 5%;
    padding-right: 5%;
    width: 370px;
    height: 160px;
    border-radius: 15px;
    padding-bottom: 100px;
}
h3 {
  color: #780000;
  text-transform: uppercase;
  font-family: Raleway, sans-serif;
}
#p1{
    text-align: center;
    color: #000000;
}
.recuadro.verde{
    width: 35%;
    margin: 5%;
    padding: 2%;
}

h2{
    text-align: center;
    margin-bottom: 3%;
      
}
.pasos {
    display: flex;
    flex-direction: column;
    text-align: left;
    gap: 15px;
}

.paso-container {
    display: flex;
    align-items: center;
    gap: 10px;
}

.paso {
    height: 25px;
    width: 25px;
}

#p1 {
    margin: 0;
    font-size: 16px;
    color: #000000;
}
.upload-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}
.preview {
  max-width: 200px;
  max-height: 200px;
  margin-top: 10px;
  border: 1px solid #ccc;
  border-radius: 10px;
}
</style>
