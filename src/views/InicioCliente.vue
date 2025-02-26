<template>
    <HeaderCliente />
    <div class="main">
        <h1>Bienvenido a Skybank</h1>
        <div class="recuadro verde">
            <div class="recuadro-thin gris">
                <p>Cuenta número </p>
            </div>
        </div>
    </div>
    <FooterInicio/>
</template>
<style>
.recuadro.verde{
    margin: 100px;
    padding: 70px;
}
.recuadro-thin.gris{
    padding: 10px;
    
}
</style>
<script>
import HeaderCliente from '../components/HeaderCliente.vue';
import FooterInicio from '../components/FooterInicio.vue';
export default{
    components: {
        HeaderCliente,
        FooterInicio
    },
}
</script>