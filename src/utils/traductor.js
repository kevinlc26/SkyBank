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
