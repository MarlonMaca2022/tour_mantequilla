# Dirección de diseño — Landing Tour Mantequilla

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
