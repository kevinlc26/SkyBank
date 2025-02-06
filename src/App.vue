<template>
  <router-view /> 
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap');
  * {
    margin: 0;
    font-family: 'Raleway', sans-serif;
  }
  body {
    background-color: #EFE7DA;
  }
  h1{
    font-size: 25px;
    color: #780000;
    text-transform: uppercase;
  }

  .recuadro {
    width: 80%;
    border-radius: 15px;
  }
  .recuadro.verde {
    background-color: #9DAC7B;
  }

  .recuadro.gris {
    background-color: #D9D9D9;
  }

  .recuadro-thin {
    width: 80%;
    border-radius: 5px;
  }

  .recuadro-thin.verde {
    background-color: #9DAC7B;
  }

  .recuadro-thin.gris {
    background-color: #D9D9D9;
  }

  .recuadro-lateral {
    background-color: #9DAC7B;
    border-radius: 15px;
    width: 285px;
    height: fit-content;
  }

  .recuadro-central.gris {
    background-color: #D9D9D9;
    border-radius: 15px;
    width: 746px;
  }

  .btn-orange {
    width: 189px;
    height: 40px;
    background-color: #E88924;
    color: white;
    font-size: 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  .btn-orange:hover {
    background-color: #d87c1a;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);

  }

  .btn-blanco {
    width: 189px;
    height: 40px;
    background-color: white;
    color: #E88924;
    font-size: 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .btn-blanco:hover {
    background-color: #E88924;
    color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }
</style>

