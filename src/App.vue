<template>
  <router-view />
</template>

<style>

@import url("https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap");
@import url("https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap");
* {
  margin: 0;
  font-family: Montserrat, sans-serif;
}
body {
  background-color: #efe7da;
}
h1 {
  font-size: 25px;
  color: #780000;
  text-transform: uppercase;
  font-family: Raleway, sans-serif;
}

.recuadro {
  width: 80%;
  border-radius: 15px;
}
.recuadro.verde {
  background-color: #9dac7b;
}

.recuadro.gris {
  border: 1px solid #780000;
}

.recuadro-thin {
  width: 80%;
  border-radius: 5px;
}

.recuadro-thin.verde {
  background-color: #9dac7b;
}

.recuadro-thin.gris {
  border: 1px solid #780000;
}

.recuadro-lateral {
  background-color: #9dac7b;
  border-radius: 15px;
  width: 285px;
  height: fit-content;
}

.recuadro-central.gris {
  background: #eee9e0;
  border: 2px solid #780000;
  border-radius: 15px;
  width: 746px;
}

/* FORMULARIOS */

label {
  font-weight: normal;
}

.input, select {
  background-color: #e4ded5; 
  width: 95%;
  padding: 3px;
  border: 1px solid black;
  border-radius: 5px;
}

.input:focus, select:focus {
  border-color: #780000;
  outline: none;
}

/* BOTONES */

.btn-orange {
  width: 189px;
  height: 40px;
  background-color: #e88924;
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
  color: #e88924;
  font-size: 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-blanco:hover {
  background-color: #e88924;
  color: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* margenes de toda la pagina sin contar header/footer */
.main {
  margin: 20px 10px 50px 20px;

}

/*PANTALLA MEDIANA*/
@media (max-width: 1024px) {
}

/*PANTALLA PEQUEÑA*/
@media (max-width: 768px) {
  h1 {
    margin-left: 20px;
  }
}

</style>
