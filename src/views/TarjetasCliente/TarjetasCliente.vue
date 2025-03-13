<template>
    <HeaderCliente/>
    <div class="main">
        <h1 class="titulo">Mis Tarjetas</h1>
        <div class="contenedor-cuentas">
            <div class="tarjeta-cuenta">
                <router-link to="/miTarjeta">
                    <p class="nombre-cuenta">Tarjeta crédito Skybank</p>
                </router-link>
            </div>
            <div class="tarjeta-cuenta">
                <router-link to="/miTarjeta">
                    <p class="nombre-cuenta">Tarjeta crédito Skybank</p>
                </router-link>
            </div><br>
            <hr><br>
            <router-link to="/nuevaTarjeta">
                <button class="btn-orange">
                    Contratar nueva
                </button>
            </router-link>
        </div>
    </div>
    
    <FooterInicio/>
</template>

<script>
import HeaderCliente from '../../components/Cliente/HeaderCliente.vue';
import FooterInicio from '../../components/Cliente/FooterInicio.vue';
export default{
    components: {
        HeaderCliente,
        FooterInicio
    },
}
</script>
<style scoped>
.titulo {
color: #780000;
text-align: center;
padding-top: 5%;
}

.contenedor-cuentas {
background-color: #9dac7b;
margin: 20px;
padding: 20px;
border-radius: 10px;
text-align: center;
}

.tarjeta-cuenta {
background-color: #eee9e0;
width: 90%;
margin: 10px auto;
padding: 10px;
text-align: center;
display: block;
border-radius: 5px;
transition: background-color 0.2s ease-in-out;
cursor: pointer;
}

.tarjeta-cuenta:hover {
background-color: #e2ddd3;
}

.nombre-cuenta {
color: #780000;
font-weight: bold;
}

.boton-agregar {
background-color: #e88924;
color: white;
height: 42px;
width: 446px;
display: flex;
justify-content: center;
align-items: center;
border: none;
border-radius: 5px;
cursor: pointer;
font-size: 16px;
font-weight: bold;
}

.boton-agregar:hover {
background-color: #cc751f;
}
</style>