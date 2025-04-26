<template>
  <HeaderEmpleado />
  <div class="main">
    <div class="perfil-header">
      <h1>Perfil del Empleado</h1>
      <button class="edit-btn" @click="handleProfileClick">
        <img src="../../assets/icons/edit.svg" alt="edit" width="24" height="24" />
      </button>
    </div>

    <div class="perfil-container">
      <div class="perfil-flex">
        <!-- Imagen de perfil a la izquierda -->
        <div class="imagen-perfil">
          <img :src="`/src/assets/imagenes_perfil/${empleado.Foto_empleado}`" alt="Imagen de Perfil" class="perfil-img" />
        </div>

        <!-- Datos del empleado a la derecha -->
        <div class="info-card">
          <h2>{{ empleado.Nombre }} {{ empleado.Apellidos }}</h2> <br>
          <div class="info-grid">
            <div>
              <label class="label-perfil" style="display: inline;">ID: </label> 
              <span>{{ empleado.ID_empleado }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Correo: </label> 
              <span>{{ empleado.Email }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Teléfono: </label> 
              <span>{{ empleado.Telefono }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Fecha de Ingreso: </label> 
              <span>{{ empleado.Fecha_contratacion }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Dirección: </label> 
              <span>{{ empleado.Direccion }}</span>
            </div>
            <div>
              <label class="label-perfil" style="display: inline;">Número de la Seguridad Social: </label> 
              <span>{{ empleado.Num_SS }}</span>
            </div>
          </div>
          <br>
          <div>
            <a @click="handleClickPassword">Cambiar contraseña</a>
          </div>
        </div>
      </div>

      <!-- Secciones en dos columnas -->
      <div class="secciones-grid">
        <div class="seccion">
          <h3>Documentos Importantes</h3> <br>
          <table class="styled-table">
            <thead>
              <tr>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(doc, index) in empleado.documentos" :key="index">
                <td><a class="a-docs" :href="doc.url" target="_blank">{{ doc.nombre }}</a></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="seccion">
          <h3>Registro de Actividad</h3> <br>
          <table class="styled-table">
            <thead>
              <tr>
                <th>Actividad</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(registro, index) in empleado.registroActividad" :key="index">
                <td>{{ registro.actividad }}</td>
                <td>{{ registro.fecha }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <EditForm v-if="editVisible" :tableName="tableEdit" :id="empleado.ID_empleado"  @close="editVisible = false"/>
  <FooterEmpleado />
</template>

<script setup>
import { onMounted, ref } from "vue";
import FooterEmpleado from "../../components/Empleado/FooterEmpleado.vue";
import HeaderEmpleado from "../../components/Empleado/HeaderEmpleado.vue";
import EditForm from "./EditForm.vue";


// ACCEDER A LA COOKIE
const Num_ident = getCookie("DNI_empleado");

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

// GET DATOS SEGUN NUM INDENT
const empleado = ref([]);

onMounted(async () => {
  const url = `http://localhost/SkyBank/backend/public/api.php/empleados?Num_ident=${Num_ident}`;

  fetch (url, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
    },
  })
  .then (response => response.json())
  .then(data => {
      if (data.error) {
        console.error("Error:", data.error);
      } else {
        empleado.value = data;
      }
    })
    .catch(error => {
      console.error("Error al obtener el empleado:", error);
    });
});


//MODAL
const editVisible = ref(false);
const openEditModal = () => {
  (editVisible.value = true), (editId = id);
  console.log("Modal abierto");
};

const tableEdit = ref('');

const handleProfileClick = () => {
  tableEdit.value = 'perfil';  
  editVisible.value = true;  
};

const handleClickPassword = () => {
  tableEdit.value = 'contraseña_verif';  
  editVisible.value = true;     
};


</script>

<style scoped>

/* HEADER */
.perfil-container {
  max-width: 900px;
  background: #eee9e0;
  padding: 25px;
  border-radius: 10px;
  margin: 20px auto;
  border: 2px solid #780000;
}

.perfil-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 900px;
  margin: 0 auto 20px;
}

.edit-btn {
  background-color: transparent;
  border: none;
  cursor: pointer;
  padding: 5px;
  transition: transform 0.2s ease-in-out;
}

a {
  color: #e88924;
  text-decoration: none;
  font-size: 18px;
  cursor: pointer;
}

a:hover {
  color: #e88924;
}

/* FOTO PERFIL */

.perfil-flex {
  display: flex;
  align-items: center;
  gap: 30px;
  margin-bottom: 20px;
}

.imagen-perfil {
  flex-shrink: 0;
  text-align: center;
}

.perfil-img {
  width: 140px;
  height: 140px;
  object-fit: cover;
}

/* DATOS DE PERFIL */

.info-card {
  flex-grow: 1;
  padding: 20px;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 22px;
}

h2, h3 {
  color: #780000;
  text-transform: uppercase;
}

.label-perfil {
  font-weight: 600;
}

.secciones-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-top: 20px;
}

/* TABLA */

.styled-table {
  width: 100%;
  border-collapse: collapse;
}

.styled-table th, .styled-table td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ccc;
}

.styled-table thead {
  background-color: #9dac7b;
  color: white;
}

.styled-table tbody tr:nth-child(odd) {
  background-color: #eee9e0;
}

.styled-table tbody tr:nth-child(even) {
  background-color: #e4ded5;
}

.a-docs {
  color: black;
  text-decoration: none;
  font-size: 16px;

}

.a-docs:hover {
  color: #e88924;
  text-decoration: underline;
}
</style>