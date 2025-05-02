import { ref } from "vue";

export const numAleatorio = ref("");
export const formData = ref({});

export const generarNumero = (tipo) => {
  if (tipo === "tarjetas") {
    let tarjeta = [];
    for (let i = 0; i < 4; i++) {
      tarjeta.push(Math.floor(Math.random() * 10000).toString().padStart(4, "0"));
    }
    numAleatorio.value = tarjeta.join(" ");
  } else if (tipo === "cuentas") {
    let cuenta = "ES";
    for (let i = 0; i < 20; i++) {
      cuenta += Math.floor(Math.random() * 10);
    }
    numAleatorio.value = cuenta.replace(/(\d{4})(?=\d)/g, "$1 ");
  } else {
    numAleatorio.value = "Tipo no válido";
  }
  formData.value.ID = numAleatorio.value;
};
