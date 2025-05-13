<template>
  <HeaderInicio />
  <div class="main">
    <h1 class="titulo">{{ textos.tituloAlta }}</h1>
    <div class="login-container">
      <div class="recuadro verde" v-if="pasoActual === 1">
        <h3>{{ textos.documentoIdentidad }}</h3> <br>
        <form @submit.prevent="siguientePaso">
          <label for="dni">{{ textos.labelDni }}</label>
          <input type="text" id="dni" v-model="dni" required /> <br>
          <p>{{ textos.avisoDni }}</p> <br>
          <button class="btn-orange" type="submit">{{ textos.btnConfirmar }}</button>
        </form>
      </div>

      <div class="recuadro verde" v-if="pasoActual === 2">
        <h3>{{ textos.tituloDatosPersonales }}</h3> <br>

        <label for="nombre">{{ textos.labelNombre }}</label>
        <input type="text" id="nombre" v-model="formData.Nombre" required  />

        <label for="apellidos">{{ textos.labelApellidos }}</label>
        <input type="text" id="apellidos" v-model="formData.Apellidos" required  />

        <label for="fecha_nacimiento">{{ textos.labelFechaNacimiento }}</label>
        <input type="date" id="fecha_nacimiento" v-model="formData.Fecha_nacimiento" required  />
        
        <label for="Nacionalidad">{{ textos.labelNacionalidad }}</label>
        <input type="text" id="Nacionalidad" v-model="formData.Nacionalidad" required />

        <label for="email">{{ textos.labelEmail }}</label>
        <input type="email" id="email" v-model="formData.Email" required  />

        <label for="telefono">{{ textos.labelTelefono }}</label>
        <input type="number" id="telefono" v-model="formData.Telefono" required  />

        <label for="calle">{{ textos.labelCalle }}</label>
        <input type="text" id="calle" v-model="formData.Calle" required  />

        <label for="numero">{{ textos.labelNumero }}</label>
        <input type="text" id="numero" v-model="formData.Numero" required  />

        <label for="cp">{{ textos.labelCp }}</label>
        <input type="text" id="cp" v-model="formData.CP" required  />

        <label for="localidad">{{ textos.labelLocalidad }}</label>
        <input type="text" id="localidad" v-model="formData.Localidad" required  />

        <br> <br>
        <button class="btn-orange" @click="siguientePaso">{{ textos.btnContinuar }}</button>
      </div>

      <div class="recuadro verde" v-if="pasoActual === 3">
        <h3>{{ textos.tituloFotoDni }}</h3>
        <input type="file" @change="onFileChange" accept="image/*" />
        <img v-if="previewUrl" :src="previewUrl" alt="Vista previa" class="preview" />
        <button class="btn-orange" @click="siguientePaso" v-if="selectedFile">{{ textos.btnConfirmarFoto }}</button>
      </div>
      
      <div class="recuadro verde" v-if="pasoActual === 4">
        <h3>{{ textos.tituloConfigurarContrasena }}</h3><br>
        <div class="parrafo">
          <p>{{ textos.instruccionesContrasena }}</p><br>
          <label for="password">{{ textos.labelPassword }}</label>
          <input type="password" id="password" v-model="formData.PIN" required />
        </div>
        <button class="btn-orange" @click="siguientePaso">{{ textos.btnFinalizar }}</button>
      </div>

      <div class="recuadro verde" v-if="pasoActual === 5">
        <h3>{{ textos.tituloTratamientoDatos }}</h3><br>
        <div class="parrafo">
          <p>{{ textos.textoTratamientoDatos }}</p>
          <br>
          <p>{{ textos.textoRgpd }}</p>
        </div>
        <button class="btn-orange" @click="registrarCliente">{{ textos.btnFinalizar }}</button>
      </div>

      <div class="rectangulo2">
        <h3>{{ textos.tituloPasosAlta }}</h3> <br>
        <div class="pasos">
          <div class="paso-container">
            <img src="../../assets/icons/paso1.svg" alt="Icono 1" class="paso">
            <p id="p1">{{ textos.paso1 }}</p>
          </div>
          <div class="paso-container">
            <img src="../../assets/icons/paso2.svg" alt="Icono 2" class="paso">
            <p id="p1">{{ textos.paso2 }}</p>
          </div>
          <div class="paso-container">
            <img src="../../assets/icons/paso3.svg" alt="Icono 3" class="paso">
            <p id="p1">{{ textos.paso3 }}</p>
          </div>
          <div class="paso-container">
            <img src="../../assets/icons/paso4.svg" alt="Icono 4" class="paso">
            <p id="p1">{{ textos.paso4 }}</p>
          </div>
          <div class="paso-container">
            <img src="../../assets/icons/paso5.svg" alt="Icono 5" class="paso">
            <p id="p1">{{ textos.paso5 }}</p>
          </div><br>
        </div>
      </div><br>
    </div>
  </div>
  <FooterInicio/>
</template>


<script setup>
import { ref, onMounted, watch, inject } from "vue";
import FooterInicio from "../../components/Cliente/FooterInicio.vue";
import HeaderInicio from "../../components/Cliente/HeaderInicio.vue";
import { gestionarTextos } from "../../utils/traductor.js";

const dni = ref("");
const pasoActual = ref(1);
const selectedFile = ref(null);
const previewUrl = ref(null);

// Datos del formulario
const formData = ref({
  Num_ident: "",
  Nombre: "",
  Apellidos: "",
  Email: "",
  Telefono: "",
  Calle: "",
  Numero: "",
  CP: "",
  Localidad: "",
  Direccion: "",
  Nacionalidad: "",
  Fecha_nacimiento: "",
  PIN: ""
});


// Avanzar paso
const siguientePaso = () => {
  if (pasoActual.value === 1) {
    formData.value.Num_ident = dni.value;
    if (!dni.value) {
      alert("Ingrese un DNI válido.");
      return;
    }
  }
  if (pasoActual.value === 2) {
    if (!formData.value.Nombre || !formData.value.Apellidos || !formData.value.Email) {
      alert("Por favor, complete todos los campos obligatorios.");
      return;
    }
    formData.value.Direccion = `${formData.value.Calle}, ${formData.value.Numero}, ${formData.value.CP}, ${formData.value.Localidad}`;
  }

  if (pasoActual.value < 5) {
    pasoActual.value++;
  }
};

// Manejo de la imagen del DNI
const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file;
    previewUrl.value = URL.createObjectURL(file);
  }
};

// Registrar cliente en la API
const registrarCliente = async () => {
  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/clientes", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(formData.value),
    });

    const data = await response.json();
    
    if (data.ID_cliente) {
      

      if (selectedFile.value) {
        await subirImagenDNI(data.ID_cliente);
      }

      // Reiniciar formulario
      formData.value = {
        Num_ident: "",
        Nombre: "",
        Apellidos: "",
        Email: "",
        Telefono: "",
        Calle: "",
        Numero: "",
        CP: "",
        Localidad: "",
        Direccion: "",
        Nacionalidad: "",
        Fecha_nacimiento: "",
        PIN: ""
      };
      pasoActual.value = 1;
    } else {
      alert(data.error || "Error al registrar el cliente.");
    }
  } catch (error) {
    console.error("Error al registrar cliente:", error);
    alert("Hubo un error en el registro");
  }
};

// Subir imagen del DNI
const subirImagenDNI = async (ID_cliente) => {
  const formData = new FormData();
  formData.append("imagen", selectedFile.value);
  formData.append("ID_cliente", ID_cliente);

  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/clientes", {
      method: "POST",
      body: formData,
    });

    const data = await response.json();
    window.location.href = '/login-cliente';
  } catch (error) {
    console.error("Error al subir la imagen:", error);
    alert("Hubo un error al subir la imagen");
  }
};

// TRADUCCION
const selectedLang = inject("selectedLang");

watch(selectedLang, async () => {
  await gestionarTextos(textos, selectedLang.value);
});

onMounted(async () => {
  await gestionarTextos(textos, selectedLang.value);
});

const textos = ref({
  tituloAlta: "ALTA CLIENTE CUENTA ONLINE",
  documentoIdentidad: "DOCUMENTO DE IDENTIDAD",
  labelDni: "DNI / NIE",
  avisoDni: "Para abrir tu Cuenta Online es necesario que tengas un documento físico original en vigor y en buen estado.",
  btnConfirmar: "Confirmar",
  
  tituloDatosPersonales: "Datos personales",
  labelNombre: "Nombre",
  labelApellidos: "Apellidos",
  labelFechaNacimiento: "Fecha de nacimiento",
  labelNacionalidad: "Nacionalidad",
  labelEmail: "Correo electrónico",
  labelTelefono: "Número de teléfono",
  labelCalle: "Calle",
  labelNumero: "Número",
  labelCp: "Código Postal",
  labelLocalidad: "Localidad",
  btnContinuar: "Continuar",

  tituloFotoDni: "Subir fotografía DNI",
  btnConfirmarFoto: "Confirmar",

  tituloConfigurarContrasena: "Configurar Contraseña",
  instruccionesContrasena: "Crea una contraseña segura para proteger tu cuenta. Deberá tener al menos 8 caracteres e incluir letras mayúsculas, minúsculas y números.",
  labelPassword: "Contraseña",
  btnFinalizar: "Finalizar",

  tituloTratamientoDatos: "Tratamiento de datos y documentación",
  textoTratamientoDatos: "Al enviar esta solicitud, usted autoriza a Skybank, a tratar sus datos personales con la finalidad de gestionar su solicitud, formalizar la relación contractual, prestarle los servicios solicitados, así como para cumplir con las obligaciones legales aplicables.",
  textoRgpd: "Sus datos serán tratados de conformidad con lo dispuesto en el Reglamento General de Protección de Datos (RGPD) y demás normativa aplicable. Puede consultar nuestra política de privacidad completa en politica de privacidad.",

  tituloPasosAlta: "Pasos para completar el alta",
  paso1: "Tu documento de identidad",
  paso2: "Datos personales",
  paso3: "Subir fotografía DNI",
  paso4: "Configurar contraseña de acceso",
  paso5: "Aceptar el tratamiento de datos y documentación"
});


</script>


<style scoped>
.main {
  margin-bottom: 200px;
  margin-top: 190px;
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
.parrafo{
  background-color: #efe7da;
  padding: 15px 10px;
  border-radius: 10px;
  margin-bottom: 2%;
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
    padding-bottom: 120px;
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
    padding-bottom: 10px;
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
@media (max-width: 768px) {
  .login-container{
    flex-direction: column;
  }
  .login-container {
    width: 100%;
  }
  .recuadro.verde{
    width: 96%;
    margin-left: 0%;
    margin-right: 0%;
  }
  .rectangulo2{
    width: 90%;
    margin-left: 0%;
  }
  }
</style>