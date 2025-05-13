// TRADUCCION CON API
export const traducirTexto = async (texto, idiomaDestino, idiomaOrigen = "es") => {
  if (!texto || idiomaDestino === idiomaOrigen || /(^\d|:)/.test(texto)) return texto;

  try {
    const response = await fetch("http://localhost/SkyBank/backend/public/api.php/traduccion", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        texto,
        idioma_origen: idiomaOrigen,
        idioma_destino: idiomaDestino
      })
    });

    const data = await response.json();
    return data.status === "success" ? data.traduccion : texto;
  } catch (err) {
    console.error("Error en traducción:", err);
    return texto;
  }
};

// GESTIONAR QUE TEXTO TRADUCIR
export const gestionarTextos = async (textos, idioma, arraysATraducir = []) => {
  if (idioma === "es") return;

  // Traducir textos simples
  for (const key in textos.value) {
    const valor = textos.value[key];
    if (typeof valor === "string") {
      textos.value[key] = await traducirTexto(valor, idioma);
    }
  }

  // Traducir arrays de objetos si existen
  for (const arrayKey of arraysATraducir) {
    const items = textos.value[arrayKey];
    if (Array.isArray(items)) {
      textos.value[arrayKey] = await Promise.all(
        items.map(async (item) => {
          const nuevoItem = {};
          for (const campo in item) {
            if (typeof item[campo] === "string") {
              nuevoItem[campo] = await traducirTexto(item[campo], idioma);
            } else {
              nuevoItem[campo] = item[campo];
            }
          }
          return nuevoItem;
        })
      );
    }
  }
};
