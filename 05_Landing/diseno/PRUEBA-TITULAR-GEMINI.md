# Prueba: titular nuevo con ayuda de Gemini (21 oct 2026)

> **Por qué:** Julio, en el audio 1 del 21 oct, dijo que el titular actual «está chévere si yo supiera qué es Armenia Mantequilla» (`01_WhatsApp/2026-10-21_julio-recomendaciones.md`, pendientes #44 y #42).
> Gemini propone, pero el equipo decide. Antes de usar cualquier dato que traiga Gemini hay que verificarlo contra la fuente: si no está en el mensaje de abajo, no se usa.

## Mensaje para pegar en Gemini

```
Actúa como una mesa de 6 expertos en copy y conversión que revisa el titular (H1) y el subtítulo de la
portada de una landing que vende un tour. Cada experto opina desde su lente:

1. Alex Hormozi: la oferta y el valor. Que el resultado soñado sea claro y que se sienta fácil y seguro.
2. Donald Miller (StoryBrand): el cliente es el héroe y nosotros los guías. Mensaje claro antes que ingenioso.
3. Un especialista en CRO (optimización de conversión): claridad en 5 segundos. ¿Se entiende qué es, dónde
   es y para quién, y lleva a hacer clic en «Reservar mi fecha»?
4. Seth Godin: la historia que se cuenta el cliente («la gente como yo hace cosas así») y lo memorable.
5. Robert Cialdini + Nir Eyal: persuasión ética (autoridad real, pertenencia) y el disparador que mueve a actuar.
6. Monge Malo: copy en español sin humo, concreto, que suene a persona y no a folleto de turismo.

EL PRODUCTO (son datos verificados; no agregues otros):
- Tour de un día desde Medellín (te recogemos en Medellín) a Armenia Mantequilla, un pueblo de montaña del
  suroeste de Antioquia, Colombia, a unas dos horas de Medellín. Le dicen «el Bello Balcón de Antioquia».
- Lo arman 4 negocios del pueblo, todos con el mismo peso:
  · Café Cabras: café de origen que nació en un kiosco de la plaza. Catas y taller de café con Caterine y Fernando.
  · Bonne Nature: quesería colombo-francesa. Luz Marina aprendió el oficio en Francia y hace quesos de
    receta francesa con ají, achiote y hierbas colombianas. Taller de quesos.
  · Finca Mirador La Florida: casa antioqueña de más de 80 años con vista a las montañas.
  · Finca Atardeceres: finca cafetera donde el día cierra viendo caer el sol.
- El día va de la primera taza de café de la mañana hasta el atardecer en la montaña.
- $440.000 por persona. Grupos de 15. Salidas en fines de semana de diciembre de 2026.
- Se vende en la página web y en un estand en una feria de turismo en Medellín (29 al 31 de octubre).
  El titular también va impreso en el estand y junto al código QR.

EL PROBLEMA DEL TITULAR ACTUAL:
H1 actual: «Tu día en Armenia Mantequilla, de la primera taza al último sol»
Subtítulo actual: «Te recogemos en Medellín para un día de café de origen, quesos artesanales y atardecer en
la montaña. Grupos de 15.»
El líder del proyecto dijo: «Está chévere si yo supiera qué es Armenia Mantequilla». La mayoría de la gente de
Medellín no conoce el pueblo; el nombre suena raro y no dice que es un pueblo, ni que queda cerca.

EL CONCEPTO DE COMUNICACIÓN (del líder del proyecto): «Lo que quiere cualquier ser humano es prosperar y ser
feliz». La foto de portada muestra a un grupo de amigos riendo con café y quesos en un mirador de montaña al
atardecer.

REGLAS:
- H1 de máximo 10 palabras, que se entienda en 2 segundos al pasar frente al estand.
- Responde a «¿qué tienes para mí?» y deja claro que es un pueblo / un plan de un día cerca de Medellín.
- En español de Colombia, tuteando, sin voseo.
- Ningún negocio puede pesar más que los otros. No uses «Primera fila al atardecer» (es el lema de uno).
- Palabras prohibidas: único, inolvidable, exclusivo, mágico, el mejor, paraíso, experiencia única,
  «últimos cupos».
- Nada inventado: ni cifras, ni premios, ni reseñas, ni datos que no estén arriba.
- Ya se descartaron: «El día termina cuando se va el sol» (no dice qué ni dónde) y «Armenia Mantequilla,
  un pueblo que se prueba» («se prueba» no se entiende).

LO QUE NECESITO:
1. Cada experto propone 2 titulares (12 en total), en una tabla con: titular · número de palabras ·
   experto · por qué funciona · riesgo.
2. Luego la mesa vota y elige 3 finalistas. Para cada una, escribe un subtítulo de máximo 25 palabras que
   complete lo que el H1 no dice (recogida en Medellín, café, quesos, atardecer, grupos de 15).
3. Para cada finalista, la «prueba del desconocido»: ¿qué se imagina alguien de Medellín que nunca ha oído
   hablar de Armenia Mantequilla al leerlo en 2 segundos?
4. Tu recomendación final, en una línea.
```

## Lo que se hace con la respuesta

1. Se pega la respuesta de Gemini debajo de este archivo, en una sección «Respuesta de Gemini (fecha)».
2. Se prueban las 3 finalistas con 2 o 3 personas que no conozcan el pueblo, como pidió Julio (#44).
3. Claude pone la ganadora en `prototipo-realismo.html` y la sube a la página de Julio. Si Julio la aprueba, pasa a `DIRECCION.md` (nuevo borrador) y también al estand y al QR (#31).

## Elegida por Marlon de la respuesta de Gemini (21 oct)

| Lugar | Texto |
|---|---|
| H1 (9 palabras) | Pausa tu semana en el balcón de la montaña antioqueña |
| Subtítulo | Te recogemos en Medellín. Un día en Armenia Mantequilla probando café, haciendo quesos y viendo el atardecer. Grupos de 15 personas. |

- Gemini proponía «…viendo el atardecer en grupos de 15 personas». Se cortó en «atardecer.» porque, sin el punto, se lee como si el atardecer fuera lo que se ve en grupos.
- «Balcón» sale de una fuente real: Armenia Mantequilla es «el Bello Balcón de Antioquia» (texto de Jhon, 17 oct).
- ⛔ «Haciendo quesos» le promete al cliente que va a hacer queso con sus manos. Hay que confirmar que el taller de Bonne Nature es práctico y no solo una degustación o una demostración (#18 y la línea de valor del hero).
- Está aplicado solo en `prototipo-realismo.html` y en la página de Julio. Falta la prueba con 2 o 3 personas que no conozcan el pueblo (#44). Si se aprueba, va a `DIRECCION.md`, al estand y al QR (#31).

## Cambio aparte: «La quesera que aprendió el oficio cerca de Grenoble»

Muy poca gente sabe dónde queda Grenoble, y el título de una parada debe entenderse sin tener que buscar nada.
- **Título nuevo:** «La quesera que aprendió el oficio en Francia». Tiene 8 palabras y es preciso: la fuente dice que aprendió «en queserías artesanales en el campo cerca de la ciudad de Grenoble» (Jhon, 17 oct) y que la familia vivió 30 años en Francia.
- **Descartadas:**
  - «Grenoble, Francia»: es más larga y suena a ficha técnica.
  - «Los Alpes franceses»: es más evocadora, pero la fuente no dice «Alpes» y no se puede asegurar.
- **El detalle no se pierde:** pasa a la tarjeta de «Quiénes te reciben», con «aprendió el oficio en queserías del campo francés, cerca de Grenoble», donde hay espacio para darle contexto.
- **Dónde está aplicado:** solo en `prototipo-realismo.html`. Si se aprueba, se cambia también en `prototipo.html` y en `DIRECCION.md`, que nombra este título en el borrador 4.
