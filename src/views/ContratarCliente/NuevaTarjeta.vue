<template>
    <HeaderCliente />
  
    <div class="main">
      <h1>Contrata una nueva tarjeta</h1>
        <div class="recuadro verde">
                <CardTarjeta
                v-for="tarjeta in tarjetas"
                :key="tarjeta.id"
                :tarjeta="tarjeta"
                :isSelected="tipoTarjeta === tarjeta.id"
                @select="tipoTarjeta = tarjeta.id"
                />
            
        </div>
      
    </div>
  
    <FooterInicio />
  </template>
  
  <script setup>
  import HeaderCliente from "../../components/HeaderCliente.vue";
  import FooterInicio from "../../components/FooterInicio.vue";
  import CardTarjeta from "../../components/CardTarjeta.vue";
  import tarjetaCredito from "../../assets/tarjetaCredito.jpg";
  import tarjetaDebito from "../../assets/tarjetaDebito.jpg";

  // Reactive variables
  const tipoTarjeta = ref("");
  const nombre = ref("");
  const fechaNacimiento = ref("");

  const tarjetas = [
    {
      id: "debito",
      nombre: "Tarjeta de Débito SkyBank",
      descripcion: "Accede a tu dinero de forma segura y rápida, sin costos de mantenimiento.",
      imagen: tarjetaCredito,
    },
    {
      id: "credito",
      nombre: "Tarjeta de Crédito",
      descripcion: "Obtén financiamiento y beneficios exclusivos con nuestra tarjeta de crédito.",
      imagen: tarjetaDebito,
    },
  ];

  // Method to handle form submission
  const submitForm = () => {
    alert(`Solicitud enviada para la tarjeta: ${tipoTarjeta.value}`);
  };
</script>

  
  <style>
  .container {
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }
  </style>
  
  <style scoped>
  .main {
    text-align: center;
    padding: 20px;
  }
  .card-form {
    max-width: 400px;
    margin: auto;
    display: flex;
    flex-direction: column;
  }
  label {
    margin-top: 10px;
  }
  input, select, button {
    margin-top: 5px;
    padding: 10px;
    font-size: 16px;
  }
 
  img {
    height: 200px;
    width: 200px;
  }
  .recuadro.verde{
    display: flex;
    gap: 10px;
  }
  </style>