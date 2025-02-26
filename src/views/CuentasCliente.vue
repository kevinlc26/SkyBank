<template>
     <HeaderCliente />
    <div class="main">
        <h1>Mis Cuentas</h1>
        <div class="recuadro verde">
            <p class="subtitulo">Mis cuentas</p>
            <div class="recuadro-thin gris">
                <p class="nombreCuenta">Cuenta Online Skybank</p>
            </div><br>
            <div class="recuadro-thin gris">
                <p class="nombreCuenta">Cuenta Ahorro Skybank</p>
            </div>
            <br><hr><br>
            <button class="btn-orange">Añadir nueva cuenta</button>
        </div>
    </div>
    <FooterInicio/>
</template>
<style>
h1{
    padding-left: 22%;
    padding-top: 5%;
}
.recuadro-thin.gris{
    width: 90%;
    margin-top: 10px;
    padding-top: 10px;
}
.subtitulo{
    color: #EFE7DA;
}
.nombreCuenta{
    color: #780000;
}
.recuadro.verde{
    margin:20px;
    align-items: center;
}
.btn-orange{
    height: 42px;
    width: 446px;
    display: flex;
    justify-content: center;
    align-content: center;
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