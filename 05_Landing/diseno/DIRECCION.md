# Dirección de diseño — Landing Tour Mantequilla

## Borrador 8 · 2026-10-21 · propuesta · realismo con recortes de fotos reales
Lo pidió Julio: llevar la página al siguiente nivel cambiando las animaciones vectoriales de los pasos del tour por elementos reales sacados de nuestras fotos. **Es una propuesta y no está aprobada.** Vive en `prototipo-realismo.html`. `prototipo.html`, la plantilla y el tema no cambian hasta que Julio la apruebe (pendiente #36).

**Decisiones (equipo, 21 oct):**
- **Primera vuelta:** el análisis más una prueba con 4 recortes.
- **Recorte por luz y color con un script reproducible** (`tema/gen_recortes.py`), sin IA (regla de Jhon, 16 oct). Ningún píxel se inventa ni se pinta. Lo complejo va a un diseñador (#38).
- **Alcance: el cielo más objetos reales por parada.** Esto revierte la regla del borrador 6 («solo cielo, sin ilustraciones por parada, nunca fotos retocadas»).

### Qué se puede sacar de cada foto (análisis)

| Hoy (vector) | Real | Foto | Cómo | Estado |
|---|---|---|---|---|
| Cordillera SVG (5 capas) | 3 capas: la cresta con el pico, las lomas sobre el mar de nubes y la ladera cercana | La Florida 01 | Contorno por columna: la bruma ya separa las capas por tono | **En la prueba**. ⛔ Es la vista desde La Florida y la escena es de Atardeceres (#37) |
| Loma con árboles y cafetal | El pueblo con la torre de la iglesia | La Florida 08, sin cielo y agrandada por Marlon (vuelta 3) | Quitarle el halo azul del cielo; tono de tarde y de noche con filtros | **En la prueba**. ⛔ Pasó por IA (#40). Primero se probó la silueta de la 04, que se veía pixelada |
| Luna (degradado CSS) | La luna real | La Florida 08 | Canal rojo contra el azul del día, más niveles para pasarla a la noche | **En la prueba**. Foto de Alex Betancure (#19) |
| Nubes SVG | Nubes blancas, doradas y violetas | La Florida 02 y 04 | Máscara suave por el color del cielo (azul en la 02, naranja en la 04), quitando el cielo mezclado en el borde | **En la prueba** (vuelta 2). El cúmulo de La Florida 01 no se separa por color: diseñador (#38) |
| Sol (CSS) | Se queda | — | Ninguna foto tiene un disco de sol usable (en la 07 está detrás de la torre, en la 02 y la 04 quemado) | — |
| Bandada | Se queda | — | No hay fotos de aves volando; a ese tamaño la silueta ya se ve real | — |
| Parada 01 | Pareja de toritos posados | La Florida 03 | Diseñador: verde sobre bosque verde | #38 |
| Parada 01 | Guayaba partida | La Florida 03 | Máscara por color | Fase 2 |
| Parada 01 | Horno de barro | La Florida 05 | Descartado: es una captura de video | — |
| Parada 02 | **Granos de café sueltos** | Cabras 05 y 06 | Elipse medida a mano | **En la prueba** |
| Parada 02 | Bolsa de café azul | Cabras 09 | Máscara por color (celeste sobre rojo) | Fase 2 |
| Parada 02 | Botella de licor | Cabras 05 | Diseñador. ⛔ No destacarla hasta resolver el #7 | #38 |
| Parada 02 | Chemex | Cabras 03 | Diseñador: vidrio transparente | #38 |
| Parada 03 | Queso con cuadrícula, queso naranja, romero | Bonne Nature 02 y 03 | Diseñador. Son las mejores fotos (cámara, en alta) | #38 |
| Parada 03 | Cuña cenital, granos, manzanilla | Bonne Nature 01 | Diseñador: el mantel de cuadros impide la máscara | #38 |
| 04 Atardeceres | La escena (cordillera, pueblo, luna) | — | ⛔ Las fotos de la finca siguen en el álbum de iCloud (#15) | — |

### Cómo quedó la prueba (`prototipo-realismo.html`)
- **Escena del atardecer:** la cordillera SVG se reemplaza por 4 `<img>`: lejos, medio, cerca y el pueblo encima.
  - Cada capa tiene un ancho mínimo de 760 px y un punto focal (`--f`/`--x`). En móvil recorta los lados sin perder el pico ni la torre, y no abre scroll horizontal.
  - Parallax 6/4/2 % como antes. Al caer la noche, cada capa baja su brillo (`filter: brightness` atado al scroll) hasta quedar en silueta.
  - 14 luces titilan sobre las casas reales y el reloj de la torre se enciende.
  - La bruma SVG y la capa `noche-lejos` desaparecen: la bruma ya está en la foto.
- **Luna:** la escena y el cierre usan la foto. Su borde inferior se pierde en el azul de la foto: no se completa el disco (sería inventar); se desvanece en 28 px, como si la tapara la bruma.
- **Parada 02:** 6 granos flotan sobre la foto como primer plano. Vienen desenfocados por la foto, y eso los hace leer como cercanos a la cámara. Cada uno lleva su propia velocidad (`data-prof`) y un giro leve. En móvil, los de arriba no se suben al texto.
- **Peso:** los 16 recortes suman 172 KB. Las capas grandes no llevan `-w480`/`-w960`: en móvil se ven a 760 px CSS, así que la versión chica se vería blanda.

**Vuelta 2 (21 oct): nitidez y nubes reales.** El equipo vio los recortes pixelados y pidió cambiar también las nubes vectoriales por nubes de las fotos.
- **Por qué se pixelaban:** las fotos de La Florida llegaron por WhatsApp (912–1000 px, muy comprimidas). En escritorio se estiran 1,6 veces y en una pantalla retina unas 3; el borde duro de 1 px se volvía escalera.
- **Arreglo sin IA:** las siluetas salen al doble (1824–2000 px) y el borde se calcula sobre el contorno interpolado (en curva) en vez del píxel. Además se mete 0,6 px hacia adentro para no arrastrar el hilo claro del cielo. El interior sigue blando: eso solo lo arreglan los originales en alta (#19) o agrandar con IA (#40).
- **Nubes reales:**
  - **Recorrido:** las 11 nubes SVG del cielo pasan a ser 2 nubes reales de la foto 02, repetidas con tamaños distintos y en espejo. De mañana y al mediodía van blancas: la misma nube con su relieve de luz, pasada a blanco con sombra fría, como la luna. En la tarde se funden con su versión dorada original (`--tarde`, atado al mismo scroll que el color del sol).
  - **Escena:** las 3 nubes pasan de doradas (02) a violetas (04) al caer la noche (`--noche-e`). De noche se apagan con el brillo, porque su borde dorado es luz de atardecer.
  - **Cómo se recortan:** máscara por el color del cielo detrás de la nube, sin la mezcla de cielo en el borde (matting estándar). Solo los lados donde la nube seguía fuera del encuadre se desvanecen.
- **Lo que se nota:**
  - de mañana, sobre el fondo crema, las nubes blancas apenas se ven, igual que las vectoriales (van al 50–75 % de opacidad);
  - solo hay 2 nubes de día y 1 de noche, así que se repiten: hacen falta más fotos de cielo, y el álbum de Atardeceres (#15) puede traerlas;
  - quedan unos puntos claros en valles muy angostos de la cresta.

**Vuelta 3 (21 oct): el pueblo nítido al final.** Marlon montó una versión de La Florida 08 (la torre roja con el pueblo) sin cielo y agrandada 4× para cerrar la escena.
- **Una sola imagen del pueblo** (`pueblo-iglesia.webp`, foto 08 nítida). Reemplaza del todo la silueta de la foto 04, que se veía pixelada: Marlon pidió no tener dos. De tarde va con tono cálido (sepia y brillo .82); al caer la noche se oscurece (brillo .45) y prende las luces en sus ventanas reales. La luna sale detrás de la aguja y termina justo sobre la cruz, como en la foto original de Alex.
- **Tamaño:** `--wp` limita el ancho a 150 svh para que en pantallas anchas y bajas (1440×760) la torre no quede debajo de la cabecera; los lados se disuelven en las montañas. Las medidas van en `cqw` (la escena es contenedor) porque se usan en `left` y en `top`.
- **Medido** (Chrome, 1440×900, 1440×760 y 375×667): la luna queda centrada sobre la cruz, 15 px arriba, y sin scroll horizontal. En móvil la torre queda casi toda detrás del velo del texto.
- **Esta imagen pasó por IA** (remove.bg preview + Upscayl 4×). Está permitido: Julio aclaró el 21 oct que la IA sirve para mejorar y reescalar fotos reales, nunca para generar (#40).
- **Firma y crédito:** la firma de Alex Betancure queda en la imagen (no se recorta) y la escena lleva un crédito «Foto del pueblo y de la luna: Alex Betancure» (#19).

**Vuelta 4 (21 oct): nubes más limpias.** Julio aclaró (vía Marlon) que la IA sí se puede usar para mejorar y reescalar fotos reales, nunca para generar.
- **Fotos de las nubes agrandadas:** La Florida 02 y 04 pasan por Upscayl 4× (`upscayl-standard-4x`, en `03_Fotos/finca-mirador-la-florida/mejoradas/`) y las nubes se recortan sobre esa versión. Se probaron tres modelos: `high-fidelity-4x` inventa grano y manchas (descartado) y `remacri-4x` casi no mejora.
- **Resultado:** desaparecen los bloques de compresión de WhatsApp y el borde queda más fino. Salen al doble del tamaño original (2×). La mejora es moderada, porque las nubes ya venían desenfocadas en la foto.
- **Hilo celeste:** en el borde semitransparente de las nubes de la 02 el azul se limita al mayor entre rojo y verde.
- **Cielo de la mañana:** el fondo crema del recorrido no deja leer una nube blanca. Detrás de las nubes va un celeste suave (`.cielo-dia::before`) que se apaga hacia el mediodía (`--azul`, atado al mismo scroll que el color del sol). El texto azul oscuro sobre ese celeste supera de sobra 4,5:1.
- **Foto de banco** (`dramatic-sunset-clouds…jpg`): no se usa hasta saber el sitio, la licencia y si es generada con IA (#41).
- **Sin JS o con movimiento reducido:** la escena queda de noche, con las capas en silueta, las luces y la luna. Los granos quedan quietos.
- **Verificado 21 oct** (Chrome con iframes de 1440×900 y 375×667):
  - el recorrido completo de la escena (tarde → noche);
  - sin scroll horizontal (`scrollWidth` 367 < 375);
  - sin errores en consola;
  - con movimiento reducido;
  - el script corrido dos veces da los mismos hashes.
  - El texto sigue sobre `.velo-texto` sin cambios y se lee bien en las capturas. **El contraste no se midió píxel a píxel**: queda pendiente repetir la medición del borrador 6 antes de aprobar.
- **Lo que se nota:**
  - las nubes vectoriales ahora desentonan al lado de lo real (fase 2);
  - el pueblo se escala ~1,44× en escritorio y se ve algo blando (pedir originales, #19);
  - la foto 01 ya sale en la franja de las cuatro casas, así que la cordillera se repite.

## Borrador 7 · 2026-10-20 · motion de nivel Awwwards
Lo pidió Julio después de ver el sitio en Hostinger: inspirarse en Awwwards y sumar animaciones más profesionales.

**Qué se revisó:**
- los listados de [Travel](https://www.awwwards.com/websites/travel/), [Food & Drink](https://www.awwwards.com/websites/food-drink/), [GSAP](https://www.awwwards.com/websites/gsap/) y [Scrolling](https://www.awwwards.com/websites/scrolling/);
- en vivo, [MontRural](https://mont-rural.emblematica.agency) (Honorable Mention, 21 sep 2026), una masía rural con titular editorial, texto que entra por líneas, una hiedra que crece y un «Book now» flotante;
- Santioni Spirits (Site of the Day, 5 oct 2026), que no se pudo abrir desde el navegador de pruebas.

El equipo eligió tres de las cuatro propuestas. Quedó fuera la «ruta que se dibuja».

- **Scroll con inercia (Lenis 1.1.20):** solo con mouse. En pantallas táctiles queda el scroll nativo.
  - Las anclas se deslizan y respetan los 72 px de la cabecera.
  - El salto con teclado al botón de la escena sigue funcionando.
  - El relevo del sol sigue en 1 px.
- **Fotos con cortina:** cada foto de las paradas se destapa de abajo hacia arriba, atada al scroll, mientras su zoom baja de 1.25 a 1.
  - La foto pequeña entra desde la derecha.
  - El recorte compensa la escala, así que el marco nunca crece.
  - `main{overflow-x:clip}` evita que el zoom abra scroll horizontal en móvil.
- **Párrafos línea por línea (SplitText de GSAP 3.13):** el subtítulo del hero, los `.lead` y los textos de cada parada.
  - Se vuelven a partir solos si cambia el ancho o cargan las fuentes.
  - El lector de pantalla lee el párrafo entero.
- **Barra de reserva con datos reales:** en escritorio es una píldora flotante abajo a la derecha; en móvil va de borde a borde, en versión corta.
  - Dice «próxima salida 5 de diciembre · Quedan 15 cupos». Sale del motor: la primera fecha con cupo y lo que queda (`tm_render_barra()`).
  - Si la fecha se agota, pasa a la siguiente. Si no hay ninguna con cupo, no aparece.
  - No aparece sobre el hero, en Fechas ni en la escena.
- **Datos clave (Duración, Salida, Fechas, Grupo):** se arman con el scroll, una tarjeta tras otra y en este orden: el ícono se dibuja, la tarjeta sube, aparecen la etiqueta y el valor y se pinta el divisor.
  - En escritorio van de izquierda a derecha; en móvil, fila por fila.
  - Se deshacen al subir.
  - Si se entra a mitad de página (por ejemplo, en `/#fechas`), ya están completos.
- **Sin JS o con movimiento reducido:** nada de esto corre. Las fotos se ven completas y el texto sin partir.
- **Medido:** el botón del hero termina en 540 px en 375×667 y 360×667, sin desborde horizontal. Los fps reales quedan pendientes en un equipo de verdad, porque el Chrome automatizado limita el rAF a 1 fps.

## Borrador 8.1 · 2026-10-21 · vuelta de mobile (iPhone), solo en `prototipo-realismo.html`
Julio (audio 2 y mensaje del 21 oct): en desktop está «una nota», pero en su iPhone las transiciones se sienten bruscas. Causas y cambios:
- **Escena del atardecer:** en móvil ya no usa el pin de ScrollTrigger, que en Safari de iPhone tiembla y salta cuando la barra aparece o se esconde.
  - La sección mide `max(100svh, 560px) + 140svh` y la escena queda fija con `position: sticky` (clase `.pegajosa`). El recorrido es el mismo y el escritorio sigue con pin.
- **La noche, sin filtros animados:** cada capa de montaña y el pueblo tienen una copia oscura encima (`.nc`, con el filtro fijo) que se funde con opacidad.
  - Animar `filter` repintaba fotos de 1824 a 2000 px en cada cuadro; la opacidad la mueve la GPU. Se ve igual en las dos versiones.
- **Inercia en táctil:** las 7 animaciones con `scrub: true` pasan a `scrub: 0.5` cuando no hay Lenis, porque 1 a 1 cada tirón del dedo se veía como un salto. Con mouse no cambia nada.
- **Barra de Safari:** `ScrollTrigger.config({ ignoreMobileResize: true })` evita que cuando la barra aparece o se esconde se recalcule todo.
- **Nubes** en capa propia de GPU (`will-change: transform`) y las fotos de la escena con `decoding="async"`.
- **Hero:** la foto pasa de una franja de `22svh` a `30svh` (199 px en un iPhone 14), para que se vea la gente.
- **Medido en Chrome a 390×664 (iPhone 14 con barras):**
  - La escena queda en `top: 0` al 0, 25, 50, 75 y 100 % del recorrido.
  - La noche y la luna llegan al final y no hay pin-spacer.
  - Sin errores ni desborde. El botón del hero termina en 647 px.
- **Medido en 1440×800:** sigue con pin y con el mismo fundido.
- ⛔ **Falta:**
  - **Probarlo en el iPhone de Julio.** Desde aquí no se pueden medir los fps reales.
  - **En un iPhone SE con las barras de Safari (375×548), el botón del hero queda en 609 px, fuera del primer pantallazo.** Antes también quedaba fuera. En la vista limpia, sin las notas amarillas, sube.
  - **El cambio de color del fondo del recorrido repinta una sección grande.** Si en el iPhone se siente pesado, es lo siguiente a revisar.

## Borrador 6 · 2026-10-21 · el día en el cielo (motion)
Lo pidió el equipo: transiciones más sorprendentes «como Dalia», con un sol que acompaña todo el recorrido, nubes, pájaros y que termine en luna. Decisiones: el hero no se fija, y solo hay cielo, sin ilustraciones por parada. Todo es ilustración SVG con GSAP: no hay video ni fotos retocadas, y el sol nunca va encima de una foto.
- **Recorrido:** una capa de cielo fija (`.cielo-pista` > `.cielo-dia`, sticky) va detrás de las paradas.
  - El sol sale del horizonte a la izquierda en la mañana, llega a lo alto al mediodía y baja al centro en la tarde. Cambia de color con la hora.
  - Hay nubes en 3 profundidades, cada una a su velocidad; las de adelante van apareciendo.
  - Cruza una bandada en cada cambio de parada.
- **Relevo:** el sol del recorrido termina con su centro en el borde inferior de la sección y el de la escena empieza con su centro en el borde superior. Es el mismo punto y el mismo tamaño (medido: 0 px de diferencia), así que se lee como un solo sol.
- **Escena del atardecer** (pin de 2 pantallas en escritorio y 1,4 en móvil): el sol se esconde del todo tras la cordillera, las nubes pasan de durazno a violeta, una bandada vuelve a la montaña y la luna sale tras la cordillera con las estrellas.
- **Cierre:** luz de luna, una luna pequeña y estrellas.
- **Fechas, aliados, incluye y FAQ:** sin cielo, para no distraer de la reserva.
- **Sin JS o con movimiento reducido:** sol al mediodía, nubes quietas, sin pájaros, y la escena final con la luna.
- **Móvil:** sin la capa de nubes lejanas. El botón del hero sigue en el primer pantallazo (termina en 548 px en 375×667).
- **Contraste:** el texto que pasa sobre el sol o las nubes no baja de 4,85:1. Se midió cruzando la posición del texto con la del sol y las nubes cada 60 px de scroll; no es un muestreo de píxeles.
- **Pendiente:** medir los fps en un celular real. En la pestaña de prueba el navegador frena las animaciones y la medida no sirvió.
- **Cordillera elaborada (21 oct):** reemplaza las 4 capas de picos rectos. La genera `tema/gen_cordillera.py` con semilla fija, y siempre sale igual.
  - 5 capas con crestas suaves y degradado, más claras a lo lejos por la bruma.
  - Bandas de bruma entre capas, que se apagan al caer la noche.
  - Árboles en silueta en las dos lomas cercanas.
  - Hileras de cafetal en puntos en la loma de adelante, siguiendo su forma.
  - De noche las capas lejanas se oscurecen y prenden las luces de un pueblo lejano y de algunas fincas, que titilan.
  - Se ajusta recortando y no estirando (`xMidYMax slice`): en móvil recorta los lados y los árboles no se deforman.
  - Es ilustración genérica: no pretende ser un lugar exacto.

## Borrador 5 · 2026-10-21 · titular nuevo
El H1 del borrador 4 era largo, sonaba a lista y no emocionaba. El subtítulo de los «cuatro vecinos te abren su casa» sonaba mal (equipo, 21 oct). Se rehízo la mesa de los seis lentes con estas reglas:
- Máximo 10 palabras, que se lea en 2 s en el estand.
- Criterio de Julio: «qué tienes para mí».
- Ninguna empresa con más peso: no se usa «Primera fila al atardecer», que es el lema de Atardeceres.
- Solo verdades con fuente.

| Lugar | Texto |
|---|---|
| H1 (elegido por el equipo, finalista 1) | Tu día en Armenia Mantequilla, de la primera taza al último sol |
| Subtítulo | Te recogemos en Medellín para un día de café de origen, quesos artesanales y atardecer en la montaña. Grupos de 15. |

Finalistas descartadas: «El día termina cuando se va el sol» (la más poética, pero no dice qué ni dónde) y «Armenia Mantequilla, un pueblo que se prueba» (llama la atención en el estand, pero «se prueba» puede no entenderse). Este H1 es también el titular del estand y del QR (pendiente #31).

## Borrador 4 · 2026-10-19 · mensaje y microcopy
Sale de una mesa de seis lentes de expertos (Hormozi, Donald Miller, CRO, Seth Godin, Cialdini + Eyal y Monge Malo). El acta completa, en tablas, está en el plan `~/.claude/plans/al-final-se-debe-immutable-corbato.md`.

**Consenso:** el protagonista es el visitante de Medellín y las cuatro casas son sus guías, con nombre y cara. La promesa es un día resuelto con recogida en Medellín. Se muestra todo lo que incluye, se deja un plan de tres pasos y solo se usan verdades comprobables. La página pide una sola cosa: elegir fecha, con la confianza al lado del botón.

| Lugar | Texto |
|---|---|
| H1 (criterio de Julio: qué tienes para mí) | Reserva tu día de café, queso y atardecer en Armenia Mantequilla |
| Subtítulo (por qué a nosotros) | Cuatro vecinos del pueblo te abren su casa y te recogemos en Medellín. Grupos de 15 personas. |
| Línea de valor | Incluye almuerzo, taller de café y taller de quesos ⛔ |
| Precio | $440.000 por persona (confirmado por el equipo el 19 oct) ⛔ impuestos |
| Botón (qué hago ahora; mismo verbo que el H1) | Reservar mi fecha, y en cada fecha «Reservar el sábado 5 de diciembre» |
| Bajo el botón | Pago en línea · confirmación al correo («seguro» queda pendiente hasta confirmar la pasarela) |
| Recorrido | «Llegar a un pueblo no es lo mismo que conocerlo. Este día lo arman quienes viven ahí» |
| Paradas | Títulos humanos («El café que nació en un kiosco de la plaza», «La quesera que aprendió el oficio cerca de Grenoble»), con retrato ⛔, nombre y frase |
| Fechas | Plan 1-2-3, «Grupo de 15 personas» (capacidad, no cupos restantes) y estado en vivo desde WooCommerce |
| Cierre | Vuelves a Medellín habiendo comido, aprendido y visto caer la tarde con gente de aquí |

**Palabras de poder:** tú, elegir, día, vecinos, casa, de aquí, incluye, recogida, cupos, reservar.
**Prohibidas:** único, inolvidable, exclusivo, mágico, el mejor, «últimos cupos» que no salgan del stock.
**Medición:** cada clic en un botón envía `cta_click` con su `origen` (`utm_source`, la feria) a `dataLayer`.

**Verificado (Playwright, 19 oct):**
- Botón del hero dentro del primer pantallazo: termina en 573 px en 360×640, en 579 en 375×667 y en 621 en 390×844.
- Contraste del recorrido: mínimo 4,99:1, medido cada 8 px.
- Escena del atardecer: 0 píxeles de letra bajo AA, con mínimo 9,38:1, en 6 anchos, animada y con movimiento reducido.
- Sin errores de consola y sin desborde.
- La página no menciona «Dalia Dorada» ni palabras sin fuente («seguro», «orgánica», «cordillera»).

**Borrador 4.1 (19 oct, mismo día):** sale del panel de personas comunes y del árbitro 3.
- **Fechas:** salen del motor de reservas heredado y se proponen fines de semana (Julio). En la página van como ejemplo: sábados 5, 12 y 19 de diciembre ⛔.
- **Vista limpia:** las preguntas sin respuesta, «No incluye», los retratos, los enlaces legales y los créditos sin autor no se publican mientras estén vacíos.
- **WhatsApp:** «Recibe la información por WhatsApp» bajo el botón del hero, y la línea de grupos (condicionada a que Jhon la confirme).
- **Escena del atardecer:**
  - El velo va a todo el ancho y aparece con el texto.
  - El sol queda sobre el velo de noche y se ve cálido hasta que lo tapa la cordillera.
  - La parada de Atardeceres tiene espacio para su anfitrión.
- **Franja de las cuatro casas:** con movimiento reducido o sin GSAP es estática y muestra las 4.
- **Textos:**
  - Sin «la mejor» y sin dar el regreso por hecho.
  - Todos los controles miden 44 px o más.

**Bloqueantes para la feria:** pendientes #25 a #35 (retratos y frases, fechas que caen lunes, transporte, cambio de fecha, preguntas reales, reseñas y titular igual en el estand).

---

## Borrador 3 · 2026-10-19 · prototipo navegable (`prototipo.html`)
Reemplaza al wireframe como pieza de revisión (`direccion.html` queda como moodboard y registro del borrador 2).

**Decisiones de este borrador (tomadas con el equipo el 19 oct):**
- **Cuatro empresas iguales**, Finca Atardeceres primera en la lista de aliados y en el pie.
- **El día cierra al atardecer en Finca Atardeceres** (lo dijo Julio). La transcripción del 16 oct dice otra cosa (atardecer con pizza en La Florida y Atardeceres como parada 1): queda como pendiente #23 para cerrar con Jhon. Brief, README y CONTENIDO-BASE del Drive siguen la transcripción.
- **Stack:** la landing es una **plantilla a código en el child theme (PHP + GSAP)**; WooCommerce sigue para reservas, checkout y correos. Cambia lo «decidido» en el brief (Elementor): Julio debe confirmarlo y registrarlo.
- **Por fases:** fase 1 para la feria (29–31 oct, el QR lleva aquí) = página completa que vende + reloj de sol + escena del atardecer + reservas y OnePay. Fase 2 = pulido Awwwards con fotos de la finca (álbum de iCloud), originales en alta y microinteracciones.

**Cómo cuenta la historia el scroll:** toda la página es un día. En el recorrido, un reloj de sol fijo mueve el sol por su arco y dice la hora (mañana, mediodía, tarde) mientras el fondo pasa del lavanda de la mañana al dorado de la tarde. Al llegar a Atardeceres, la escena se fija (pin) y con el scroll el sol se esconde detrás de cuatro capas de cordillera, el cielo pasa del dorado a la noche, salen estrellas y aparece «Primera fila al atardecer» con el botón de reserva. Es ilustración (SVG + degradados), no foto: no choca con la regla «fotos reales, sin IA».

**Motion:** un solo momento cinematográfico (la escena del atardecer). Reloj de sol y revelado de fotos son sutiles. Sin JS o con movimiento reducido, la escena queda en su estado final y el resto se ve completo.

**CRO:** promesa + precio + botón en el primer pantallazo (botón a 621 px en 375×667) · datos clave (un día, salida desde Medellín, 3 fechas, 15 personas) · barra fija que se oculta en Fechas · botón por fecha con la fecha en el texto · señales de confianza junto a las fechas (pago, correo, cancelación, WhatsApp) · un CTA al final del atardecer y otro de cierre · eventos `cta_click` en `dataLayer` para GA4/Pixel · cero contadores o prueba social inventada.

**Verificado (Playwright, 19 oct):** 375×667, 1440×900 y movimiento reducido; sin errores de consola; sin desborde horizontal; la escena llega a noche y texto visibles al final del pin.

**Botón «Ocultar pendientes»** (arriba a la izquierda en móvil, abajo a la derecha en escritorio): esconde los recuadros amarillos para ver el diseño limpio.

---

> Borrador 2 · 2026-10-19 · para validar con Julio. Vista visual: `direccion.html` (abrir en el navegador).
> Fuentes: `../BRIEF-DISENO-Y-DESARROLLO.md`, `../../04_Contenido/CONTENIDO-BASE.md`, fichas de `02_Empresas/`, `../../03_Fotos/INVENTARIO.md`.
> El borrador 2 incorpora la auditoría de heurísticas (33 Softux) y la revisión de diseño UI/UX del 19 oct. Resumen de cambios al final.

## Idea
**Un día en el pueblo, de la bienvenida al atardecer.** El tour termina en el mirador de La Florida viendo caer la tarde sobre Armenia Mantequilla; la página cuenta el recorrido en ese mismo orden y el fondo de «Lo que vas a vivir» pasa de la luz del mediodía al azul de la noche (degradado **estático**, sin efecto de scroll). Ese es el único gesto fuerte de la página; todo lo demás es sobrio para que manden las fotos y las tres marcas.

Sigue la recomendación del brief: **paleta propia neutra** (cal, montaña) y cada aliado aporta su color en su fila.

## Paleta (tokens)
| Token | Hex | De dónde sale | Uso |
|---|---|---|---|
| `cal` | `#FBFCFA` | Paredes encaladas de las casas del pueblo | Fondo |
| `montana` | `#1E2B45` | Las capas de cordillera al anochecer (foto La Florida 01) | Texto, anillo de foco, fondos oscuros |
| `bruma` | `#5E6F8C` | La niebla entre montañas | Texto secundario, líneas |
| `corredor` | `#A3231B` | El rojo de los corredores y la torre (fotos 08 y 10) | **Solo** botones de reserva |
| `atardecer` | `#E9A23B` | Fotos 02 y 04 | Degradado del día y halo del foco; nunca texto |
| `gris-fondo` | `#F1F3F6` | — | Fondo de secciones alternas |
| `estado-ok` | `#2F7D3A` | — | «Hay cupos», chulos |
| `whatsapp` | `#1E7A45` | — | Solo el botón de WhatsApp |

Colores de aliado (solo como borde superior de 6 px en su fila, como el zócalo pintado de las casas):
| Aliado | Hex | Sale de |
|---|---|---|
| Café Cabras | `#E8B828` | Logo amarillo |
| Bonne Nature | `#2E8C9A` | Teselas del logo |
| La Florida | `#7A4E86` | Cielo violeta del logo (antes rojo: chocaba con «rojo = pagar») |

Contrastes medidos: Montaña/Cal 14:1 · blanco/Corredor 7,5:1 · Bruma/Cal 4,9:1 · Bruma/gris-fondo 4,6:1 · Montaña/dorado 8,5:1 · blanco/WhatsApp 5,4:1.
**Prohibido:** Bruma sobre el dorado del degradado (3,1:1) y cualquier rojo que no sea botón de reserva.
**Foco:** contorno 3 px Montaña + halo Atardecer en fondos claros; contorno Cal en fondos oscuros (hero, noche, pie).

## Tipografía
- **Alegreya** (titulares: 800 solo para H1 y precio, 700 para el resto) y **Alegreya Sans** (texto 400/500/700). Superfamilia de Huerta Tipográfica (Argentina): latinoamericana, cálida, de raíz caligráfica, sin caer en lo folclórico.
- **Escala única:** 15 / 18 / 22 / 28 / 36 / 44 px. Texto a 18 px (Alegreya Sans tiene altura de x baja), interlineado 1,55. Líneas de máximo ~65 caracteres.
- Sin mayúsculas sostenidas para etiquetas, sin «eyebrows» sobre cada título.

## Composición (móvil primero, 390 px)
1. **Cabecera mínima** sobre el hero: nombre del tour y enlace «Fechas».
2. **Hero**: foto 02 de La Florida en 4:3 (máx. 42 % de pantalla), título-promesa «Café, queso y atardecer en Armenia Mantequilla», nombre del tour como subtítulo, línea de fechas, precio y botón «Ver fechas y reservar». Medido a 375×667: el botón termina en 584 px, dentro del primer pantallazo.
3. **Datos clave**: duración, punto de encuentro, fechas, tamaño del grupo.
4. **Lo que vas a vivir**: línea de tiempo de 5 paradas, fotos 3:2; el atardecer cae en un bloque de noche propio.
5. **Elige tu fecha**: una tarjeta por fecha con hora, «$440.000 por persona» y botón que nombra la fecha. Fecha agotada sin forma de botón y con salida a WhatsApp. Debajo: medios de pago, confirmación por correo, cancelación, WhatsApp.
6. **Quiénes te reciben**: tres filas compactas (placa de logo 88 px, nombre, dos líneas).
7. **Qué incluye / qué no**.
8. **Cómo llegar y qué llevar**.
9. **Preguntas frecuentes** (6): pago, cancelación, solo o en grupo, niños, accesibilidad, lluvia.
10. **Pie**: las tres placas de logo, WhatsApp, enlaces legales (44 px de toque).
- **Barra fija** (precio + «Ver fechas»): aparece al salir del hero, se oculta mientras «Elige tu fecha» está a la vista, respeta la zona segura del iPhone. Tres estados según stock: 3 fechas / N fechas con cupo / sin cupos → WhatsApp.

Texto alineado a la izquierda; centrado solo en el pie.
**Escritorio:** hero en dos columnas (foto 5/11 a la izquierda, texto a la derecha) para no estirar la foto de 853 px; fechas en tres columnas; el resto en una columna de 640 px centrada.

## Logos
Placa blanca cuadrada del mismo tamaño para los tres (88 px en filas, 80 px en el pie), logo en `contain`, archivos recortados al borde para que llenen lo mismo (`../assets/logos/`). Una sola versión de Cabras: la café. El de La Florida no es redondo (escudo con cinta) y es muy detallado: pedir una versión simple y PNG transparente.

## Movimiento
Ninguno automático. El acordeón y los botones responden al toque; se respeta `prefers-reduced-motion`.

## Fotos elegidas
| Lugar | Foto | Por qué |
|---|---|---|
| Hero | La Florida 02 | Gente viviendo el tour, no un paisaje vacío |
| Bienvenida | Cabras 05 | Botella y copas: es literalmente el aperitivo |
| Taller | Cabras 02 | Chemex en uso con gente al fondo |
| Almuerzo | ⛔ no hay foto de fiambres | Pedir a Café Cabras |
| Quesería | Bonne Nature 02 | Foto general de quesos, EXIF de cámara |
| Atardecer | La Florida 04 | Atardecer sobre la iglesia |

Descartadas por ahora: Cabras 08 (texto encima), La Florida 09 (rayo), La Florida 10 en la página (firma quemada; queda en el moodboard).

## Construcción en Elementor
- Degradado del día: contenedor con fondo degradado + contenedor aparte para el bloque de noche.
- Números de la línea de tiempo: widget de título dentro de un círculo y línea con borde del contenedor (nada de contadores CSS).
- Fechas: las dibuja el shortcode heredado `tour_dates`; el estilo de la tarjeta va al CSS del child theme y hay que revisarlo contra lo que el shortcode genera.
- Barra fija: «sticky: bottom» de Elementor Pro + unas líneas de JS (IntersectionObserver) para mostrarla tras el hero y ocultarla en Fechas.
- Imágenes con `width`/`height`, `loading="lazy"` salvo el hero (`fetchpriority="high"`).

## Para decidir con Julio
- ¿Dónde se elige cuántas personas? Propuesta: el botón lleva a la página del producto de WooCommerce (ya trae cantidad). Alternativa: selector −/+ en la tarjeta.
- ¿M&T Consulting aparece en el pie?

## Lo que falta para pasar del borrador al diseño final
- Nombre oficial del tour, fechas, hora, duración, punto de encuentro, cupo por fecha, qué incluye el precio, medios de pago.
- Foto del almuerzo de fiambres.
- Original en alta de La Florida 02 (hoy 853 px; bloqueante para el hero).
- Logo de La Florida simplificado y en PNG transparente.

## Cambios del borrador 1 al 2
Fechas antes de aliados y a 3.135 px en vez de 4.599 · franja de datos clave · hero más corto con título-promesa · aliados compactos sin foto · logos en placas iguales y recortados · fechas con precio, hora, salida para agotadas y estados de la barra · La Florida a violeta · foco AA · escala tipográfica única y texto a 18 px · degradado estático · 2 preguntas nuevas · accesibilidad (lista con `role`, decorativos fuera del lector, alt vacío en logos repetidos, toques de 44 px) · boceto de escritorio.
