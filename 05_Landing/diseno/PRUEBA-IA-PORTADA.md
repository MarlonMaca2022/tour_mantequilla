# Prueba: portada con una imagen generada por IA (21 oct 2026)

> **Es un experimento, no está aprobado.** La regla 3 de `CLAUDE.md` permite usar la IA solo para mejorar o reescalar fotos reales. Julio abrió la puerta en su audio 3 («si no, la creamos, pero que no parezca inteligencia artificial»), pero eso sigue sin confirmar (pendiente #43). Mientras tanto, la imagen solo va en `prototipo-realismo.html`, con una etiqueta amarilla que diga que es IA. Nunca va a la landing real sin el sí de Julio.
> Concepto (Julio, 21 oct): «personas felices y que quieren prosperar». Ver `01_WhatsApp/2026-10-21_julio-recomendaciones.md`.

## Resultado (21 oct)

Marlon la generó con Gemini: `03_Fotos/_prueba-ia/gemini-2026-10-21.jpg`. Pasó la revisión de cerca: las manos y las tazas están bien, las caras son distintas entre sí y no trae marca de agua visible. El único detalle es un logo falso en una chaqueta.

No se le corrigió el color: ya casa con el dorado de las demás fotos. Tampoco se agrandó: con 1696 px alcanza. La sirven `assets/fotos-web/prueba-ia/portada-ia{,-w960,-w480}.webp`. Se ve en `prototipo-realismo.html?portada=ia` con la etiqueta «Imagen generada con IA · prueba», que no se esconde ni en el modo limpio.

## Dónde se genera

Se genera fuera del proyecto, con una herramienta de imagen como ChatGPT, Gemini, Midjourney o Adobe Firefly. Firefly es la que da licencia comercial más clara. La imagen se guarda en `03_Fotos/_prueba-ia/` con el nombre de la herramienta y el prompt usado.

## Para Gemini: mensaje completo para pegar

Adjuntar 3 fotos reales para que copie el lugar y no lo invente:
1. `03_Fotos/finca-mirador-la-florida/la-florida-02-10-43-19.jpg` (el grupo en el prado, al atardecer)
2. `03_Fotos/finca-mirador-la-florida/la-florida-01-10-43-18.jpg` (la cordillera en bruma)
3. `03_Fotos/finca-mirador-la-florida/la-florida-04-10-45-52.jpg` (el pueblo con la iglesia)

```
Necesito una fotografía hiperrealista para la portada de una página web que vende un tour.

EL TOUR: un día completo saliendo de Medellín hacia Armenia Mantequilla, un pueblo de montaña en Antioquia
(Colombia). Grupos pequeños de 15 personas. En el día se prueba café de origen en una cafetería del pueblo,
se hace un taller de quesos artesanales colombo-franceses y se cierra viendo el atardecer desde un mirador en
una finca antioqueña tradicional, con la cordillera en capas de bruma y el pueblo con su iglesia en la loma.

EL ESPÍRITU: lo que busca cualquier persona es prosperar y ser feliz. La foto tiene que transmitir eso: gente
real, feliz, disfrutando un día distinto en la montaña, con calma y con ganas de vivir mejor. Cálida, cercana,
nada de lujo exagerado ni pose de anuncio.

LA ESCENA: 5 o 6 amigos colombianos de entre 30 y 50 años, de distintos tipos y edades, en el prado del
mirador al atardecer, alrededor de una mesa de madera rústica con tazas de café y una tabla de quesos. Se ríen
entre ellos y conversan, sin mirar a la cámara. Ropa casual de montaña (chaquetas livianas, algún sombrero).
Sol bajo y dorado detrás de ellos que les ilumina el borde del pelo. Al fondo, la cordillera en capas azules de
bruma, como en la foto 2, y en la loma un pueblo pequeño con la torre blanca de la iglesia, como en la foto 3.
Usa la foto 1 como referencia del lugar, la luz y el ambiente.

ESTILO: fotografía documental, espontánea, tomada con cámara full frame y lente de 35 mm a f/2. Grano de
película suave, colores naturales sin saturar, piel con textura real y manos bien hechas. Que NO parezca
inteligencia artificial: nada de piel de plástico, sonrisas idénticas, simetrías perfectas ni brillo de
render.

FORMATO: vertical 2:3. Las personas en la franja media e inferior del cuadro y el cielo arriba, porque en
celular la foto se recorta a una franja horizontal del centro y en computador se recortan un poco los lados.
Sin texto, sin logos y sin marcas de agua.
```

Si sale «demasiado perfecta», responderle a Gemini: «más espontánea, como foto de celular de un amigo; menos
perfecta, con alguien a medio reír y alguien de perfil». Pedir 4 variantes y escoger con la lista de abajo.

La que se escoja se guarda en `03_Fotos/_prueba-ia/` con el nombre `gemini-<fecha>.png`. Claude la pasa a
`assets/fotos-web/prueba-ia/portada-ia*.webp`, y así aparece en la propuesta con `?portada=ia`.

## Prompt A: el atardecer en el mirador (otras herramientas, en inglés)

Formato vertical 2:3, como la portada actual.

```
Candid documentary photograph, vertical 2:3. A small group of five Colombian friends in their 30s and 40s
laughing together on the grassy terrace of a traditional Antioquian farmhouse (white walls, red clay roof
tiles, wooden balcony) at golden hour. Behind them, layers of Andean mountains fade into blue mist, and on a
distant ridge sits a small hillside town with a white church tower. On a rustic wooden table: cups of black
coffee and a board of artisan cheeses. Warm low sunlight from behind rims their hair; a few faces turned
toward each other, not toward the camera. Genuine, unposed expressions, natural skin texture, casual
everyday clothes. Shot on a full-frame camera, 35mm lens at f/2, soft natural film grain, true-to-life colors.
No text, no logos, no watermark.
```

## Prompt B: el café de la mañana (alternativa)

```
Candid documentary photograph, vertical 2:3. Morning in a small coffee shop in a rural Colombian mountain
town: a smiling woman in her 40s shares a freshly brewed Chemex pour-over with two friends at a wooden table,
steam rising from the cups, colorful hand-painted coffee artwork on the warm yellow wall, soft window light,
green mountains visible through an open door. Genuine laughter, natural skin texture, unposed. 35mm lens,
f/2, light film grain, true-to-life colors. No text, no logos, no watermark.
```

## Para que no parezca IA

- Pedir 4 variantes o más y escoger la que tenga **las manos bien o escondidas**, caras distintas entre sí, piel con textura y luz que venga de un solo lado.
- Descartar las que tengan a todos mirando a la cámara, sonrisas idénticas, piel de plástico, texto deforme o un fondo demasiado perfecto.
- Si la herramienta acepta foto de referencia, subir `03_Fotos/finca-mirador-la-florida/la-florida-02-10-43-19.jpg` (el grupo real frente al atardecer) para que copie el lugar y la luz de verdad.
- Después, Claude ajusta el color para que case con las demás fotos y, si hace falta, la agranda con Upscayl.
