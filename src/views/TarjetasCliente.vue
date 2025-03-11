<template>
    <HeaderCliente/>
    <div class="main">
        <h1>Mis Tarjetas</h1>
        <div class="recuadro verde">
            <p class="subtitulo">Mis tarjetas</p>
            <div class="recuadro-thin gris">
                <router-link to="/miTarjeta">
                    <p class="nombreCuenta">Tarjeta crédito Skybank</p>
                </router-link>
                
            </div><br>
            <div class="recuadro-thin gris">
                <router-link to="/miTarjeta">
                    <p class="nombreCuenta">Tarjeta crédito Skybank</p>
                </router-link>
            </div><br>
            <hr><br>
            <button class="btn-orange">
                <router-link to="/altaTarjeta">
                    Contratar nueva
                </router-link>
            </button>
            
        </div>
    </div>
    
    <FooterInicio/>
</template>


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