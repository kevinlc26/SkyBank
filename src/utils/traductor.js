export const traducirPagina = async (lang) => {
  const elementosTexto = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, button, span, label, div')

  for (let elemento of elementosTexto) {
    const originalText = elemento.innerText.trim()
    if (originalText === '') continue

    const formData = new FormData()
    formData.append('text', originalText)
    formData.append('lang', lang)

    try {
      const res = await fetch('http://localhost/SkyBank/backend/public/api.php/traduccion', {
        method: 'POST',
        body: formData
      })

      const data = await res.json()
      if (data.translatedText) {
        elemento.innerText = data.translatedText
      }
    } catch (error) {
      console.error('Error en traducción:', error)
    }
  }
}
