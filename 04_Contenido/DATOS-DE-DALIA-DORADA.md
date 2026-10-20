# Datos tomados del tour propio de Dalia Dorada / Finca Atardeceres

> **Por qué existe:** Julio (20 oct 2026) indicó que la información del tour de Dalia Dorada sirve
> para completar preguntas del tour conjunto. **Ojo:** es el tour **propio** de Atardeceres (un solo
> anfitrión, grupos de 10), no este tour de 4 casas. Cada dato dice si se usó en la landing.
>
> **Fuentes:**
> - **[web]** Página del tour en daliadorada.com (`/producto/tour-finca-dalia-dorada/`), consultada el 2026-10-20.
> - **[ctx]** `SynologyDrive/…/[Proyecto Atardeceres]/Dalia Dorada/CONTEXTO_PROYECTO.md`.
> - **[ago]** `…/vídeos/Tour Agosto 2026/DaliaDorada_TourAgosto2026_Transcripciones_v1.docx` (recorrido con Jhon).
> - **[código]** `Template-Dalia-Dorada-Coffee/functions.php`.

## Usados en la landing (2026-10-20)

| Dato | Valor | Fuente | Dónde quedó |
|---|---|---|---|
| Recogida | Medellín o San Antonio de Prado; tú solo llegas al punto de encuentro | [web] | Datos clave, «Cómo llegar», FAQ, producto (`_tour_punto`) |
| Ubicación exacta | En Dalia «se envía al confirmar la reserva». Aquí: «te la enviamos antes del tour» (el correo automático aún no la trae; alguien debe enviarla) | [web] | «Cómo llegar», gracias, correo |
| Hora de recogida | Entre 7:00 y 8:00 a. m. | [web] | Datos clave, producto (`_tour_hora`) |
| Regreso | Salida de la finca 7:00 p. m., llegada a Medellín ~8:30 p. m. | [web] | «Cómo llegar», FAQ |
| Duración | Todo el día (7:00 a. m. a 8:30 p. m.) | [web] | Datos clave, producto (`_tour_duracion`) |
| Distancia | Unos 90 minutos de Medellín. Sin peajes; lo largo son las curvas | [web], [ctx], [ago] | «Cómo llegar» (reemplaza «unas dos horas» del prototipo) |
| Transporte | Ida y vuelta incluido | [web] | Incluye |
| Hospedaje | No incluido: tour de un día. Se recomiendan hoteles de la zona | [web], [ago] | No incluye, FAQ |

## NO usados todavía (confirmar con Jhon antes de publicar)

| Dato | Valor en Dalia | Fuente | Por qué no se usó |
|---|---|---|---|
| Lluvia | «Ante lluvia fuerte se reprograma sin costo; nublado, se hace igual» | [web] | Es una política del negocio y aquí hay 4 casas (pendiente #28). Además, en el código de Dalia esa garantía está **oculta** «pendiente de aprobación del dueño» (`dd_tour_mostrar_reprogramacion`), aunque la web la dice |
| Seguro | Incluido | [web] | No sabemos si este tour lo tiene |
| Niños | «Apto para familias», sin edad mínima | [web] | Este tour arranca con licor de bienvenida (pendiente #32) |
| Grupo | Máximo 10 (la FAQ dice «dos grupos de diez») | [web] | Este tour es de 15 (reunión 16 oct) |
| No incluye | Consumos a la carta y compras en la tienda | [web] | Probable, pero lo define cada casa |
| Transportador | Cotrasana (Medellín → Armenia), «negociación pendiente» | [ctx] | Interno; no se publica |
| Atardecer | «A las cinco hacemos el rito del atardecer» | [ago] | Coincide con el cierre ~5 p. m. de la transcripción del 16 oct; sirve para el pendiente #23 |
| Contacto | Formulario web e Instagram @dalia.dorada; la web no publica WhatsApp | [web] | Sigue pendiente el WhatsApp del tour (#35) |
