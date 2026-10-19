# Brief para diseño y desarrollo — Landing del Tour Mantequilla

> **Léelo completo antes de abrir Figma o el editor.** Lo marcado ⛔ **FALTA** no se inventa: se pide a Julio o a Jhon (ver `../PENDIENTES.md`).
> Estado: borrador 2 · 2026-10-19 · armado a partir de la **transcripción** de la reunión «Informe FyGP» (16 oct, `../00_Reuniones/2026-10-16_informe-fygp_TRANSCRIPCION.md`) y los WhatsApp de Jhon (17 oct). Lo que viene de la transcripción puede traer errores de voz: se marca «confirmar».

## 1. Qué se construye
Una **landing independiente** (dominio propio, no es una página de daliadorada.com) que vende un **tour conjunto de 3 fechas (grupos de 15 personas, desde Medellín)** por Armenia Mantequilla, Antioquia, con **pasarela de pagos**. Es el resultado de una participación conjunta en una feria turística entre empresarios locales.

| | |
|---|---|
| Cliente / canal | M&T Consulting (Jhon Velásquez, CEO) |
| Origen | Alcaldía con estand en la feria de turismo de Plaza Mayor, **29, 30 y 31 oct**. Cuatro empresarios «muy unidos» arman el tour |
| Quién construye | Equipo Softux (dirige Julio Vanegas) |
| Precio fijado | **$440.000 COP** («en eso acordamos»). ⛔ **Confirmar**: ¿por persona? ¿qué incluye? ¿impuestos? |
| Fechas | 3 fechas en **diciembre 2026**. Jhon dijo «el 7, el 21 y el 12» ⛔ **confirmar con el audio** (¿7, 14 y 21?) |
| Cupo | **15 personas por fecha** (Jhon: «grupos inicialmente de 15») |
| Nombre oficial del tour | ⛔ **FALTA** (los textos dicen «Tour Mantequilla») |
| Dominio | Por comprar (Julio). ⛔ **FALTA** nombre |
| Pasarela de pagos | **OnePay** (la transcripción dice «OneP»; ⛔ confirmar el nombre). **Jhon factura todas las inscripciones.** |

## 2. A quién le hablamos y para qué
- Turista o grupo (viajeros desde Medellín, a 2 h) que busca una experiencia auténtica de pueblo, café, quesos y paisaje.
- Acción única de la página: **reservar y pagar un cupo en una de las 3 fechas.** Todo lo demás apoya esa acción.
- ⛔ **FALTA** confirmar público (¿familias, grupos, extranjeros?) y política de cancelación. Cupo: 15 por fecha.

## 3. Quiénes participan (4 empresas, confirmado en la reunión)
| Empresa | Qué aporta al tour | Ficha | Logo |
|---|---|---|---|
| **Café Cabras** | Aperitivo de licor de café, almuerzo de fiambres, taller de métodos de preparación | `../02_Empresas/cafe-cabras/FICHA.md` | 4 versiones |
| **Finca Mirador La Florida** | Mirador, cocina de horno de barro y leña, atardecer | `../02_Empresas/finca-mirador-la-florida/FICHA.md` | ⛔ **FALTA** |
| **Bonne Nature** | Quesería artesanal colombo-francesa | `../02_Empresas/bonne-nature/FICHA.md` | 1 versión |
| **Atardeceres SAS** (finca; marca **Dalia Dorada**) | **Parada 1:** recorrido por los cultivos de café y taller de turismo. Finca en Armenia Mantequilla, 1.750 m.s.n.m. Fundador: Jhon Velásquez | `../02_Empresas/atardeceres-dalia-dorada/FICHA.md` | Atardeceres (letrero, finca, lema, 1.750) y Dalia Dorada |

**Atardeceres / Dalia Dorada es un participante más, con el mismo peso que los demás.** Jhon quiere dominio propio justamente «para que ninguna empresa se sienta relegada»: la landing no debe parecer una página de Dalia Dorada. Ya opera un tour propio de un día: ⛔ **definir** cómo se relaciona con este. Ojo con lo heredado de su historia: RNT sin verificar, tarifa diferencial y cupos falsos; aquí no se repite nada de eso.

**Itinerario (Jhon, reunión del 16 oct; confirmar con las empresas):**
1. Recogida en Medellín.
2. Llegada al **mirador La Florida**.
3. **Atardeceres:** recorrido por cultivos de café y taller de turismo.
4. **Café Cabras:** almuerzo, café y digestivo.
5. **Quesería** (⛔ nombre: en el audio «quesería de Mintí»; ¿es Bonne Nature?): taller de quesos.
6. ~**5 p. m., La Florida:** pizza y atardecer.
⛔ Hora de salida y de regreso, punto de encuentro y si el almuerzo de fiambres de Cabras (su documento) es el de este itinerario.

Los logos de las **4 empresas** van en la landing (pedido de la reunión). Jhon dijo que seis o siete empresarios irán al estand, pero solo estas cuatro arman el tour.

## 4. Estructura de la página (propuesta, validar con Julio)
1. **Hero:** nombre del tour (provisional «Tour Mantequilla» / «el tour del paisaje, el café y el queso»), una frase, rango de fechas, precio, botón «Reservar».
2. **Qué vas a vivir:** línea de tiempo o 3 a 4 paradas, con foto de cada una.
3. **Las empresas:** una tarjeta por participante (logo, 2 a 3 líneas, foto).
4. **Fechas y cupos:** las 3 fechas con su estado (disponible/agotado) y botón de pago.
5. **Qué incluye / qué no incluye:** ⛔ **FALTA** el contenido.
6. **Cómo llegar y qué llevar:** ⛔ **FALTA**.
7. **Preguntas frecuentes:** cancelación, edad, accesibilidad, lluvia.
8. **Pie:** logos de las 4 empresas, contacto, WhatsApp, términos.

**QR:** Jhon pidió un código QR que lleve a la información del tour y a las fechas disponibles (para el estand de la feria, 29 a 31 oct). Hay que generarlo apuntando a la URL final y que funcione ya en esa fecha.

## 5. Reglas de contenido (no negociables)
- **Fotos auténticas, sin IA.** Lo pidió Jhon en la reunión. Las fotos oficiales de la finca Atardeceres están en un álbum de iCloud: enlace en `../03_Fotos/atardeceres-dalia-dorada/FOTOS-OFICIALES.md`. Cada foto debe rastrearse a quién la tomó (`../03_Fotos/INVENTARIO.md`).
- **Una afirmación, una fuente.** Si un dato no tiene fuente en esta carpeta, no se publica. Esto aplica sobre todo a: «leche cruda», «sin conservantes, colorantes o espesantes», licor de café, «trazabilidad real», «más de 80 años». Ver las secciones «Por verificar» de cada ficha.
- **Corregir antes de publicar:** «hace 10 años, en 2014» (Bonne Nature) → «desde 2014»; «San nicolás / citará / cerro bravo» → San Nicolás, Citará, Cerro Bravo; unificar «Armenia» y «Armenia Mantequilla».
- **Nada de cupos o prueba social inventados** (contadores falsos, «+347 tazas», etc.): esa lección viene de Dalia Dorada.
- Español colombiano, tono cálido y de pueblo; sin lenguaje publicitario inflado.

## 6. Diseño
**Ya existe:**
- Logo Café Cabras: negro, amarillo, café y blanco (fondo transparente), 3.000 px aprox.
- Logo Bonne Nature: colorido, 1080×1080.
- Logos Atardeceres / Dalia Dorada: letrero de madera con colibríes, «Finca Atardeceres», lema «Primera fila al atardecer», «1.750 m.s.n.m.» y logo Dalia Dorada (SVG, PNG, PDF). Paleta de la marca: naranja y verde azulado. En `../02_Empresas/atardeceres-dalia-dorada/logo/`.
- Paleta visible en las fotos de Cabras: café, amarillo mostaza, azul de la bolsa.

**Falta definir (decisión de diseño):**
- ¿La landing tiene marca propia del tour o usa una paleta neutra que deje brillar a los tres aliados? *Recomendación: paleta neutra y cálida, y que cada tarjeta use el color de su marca.*
- Logo o nombre del tour.
- Tipografías.

**Restricciones de diseño:**
- Mobile first (la mayoría llegará desde el celular por WhatsApp o redes).
- Los logos de los aliados con el mismo peso visual, ninguno destacado.
- Contraste AA mínimo; botones grandes.

## 7. Desarrollo
- **Base técnica (decidido 2026-10-19):** WordPress + Elementor + WooCommerce, partiendo de una **copia limpia, sin historial, del child theme de Dalia Dorada**. Repo propio y privado: `softux-co/tourmantequilla` (`main` = producción, `staging` = integración). Proyecto separado: no comparte sitio, hosting, medición ni deploy con Dalia Dorada.
  - Se hereda: shortcodes de tour (`tour_dates`, `tour_precio`, `tour_reservar`, etc.), checkout y gracias de WooCommerce, correos, medición, hooks y `validate.sh`.
  - No se hereda: marca, textos, páginas de Elementor, IDs de GA4/GTM/Pixel, scripts de deploy ni datos.
  - Primera tarea de desarrollo: leer `docs/BASE-HEREDADA.md`, depurar el código del café y comprobar que los shortcodes de tour soportan 3 fechas con tres aliados.
  - El diseño de Elementor vive en la base de datos, no en git: se construye en el sitio nuevo.
- **Hosting y dominio:** ⛔ **FALTA** decidir (¿misma cuenta de Hostinger?). Dominio por comprar.
- **Pagos:** **OnePay** (⛔ confirmar nombre y que sirve para WooCommerce). Requisitos: cobrar COP, confirmación por correo, comprobante. Jhon factura todo: ⛔ ¿datos de facturación por inscripción?
- **Reservas por fecha:** 3 productos o variaciones (una por fecha) con control de cupo: **15 por fecha**.
- **Rendimiento:** comprimir imágenes (hay una de 25 MB), WebP, lazy load. Meta: carga rápida en 4G.
- **Medición:** GA4 y Meta Pixel ⛔ **FALTA** decidir cuentas. Evento clave: clic en «Reservar» y compra completada.
- **Legal:** términos y condiciones, política de datos y de cancelación. ⛔ **FALTA** redactar.
- **Credenciales técnicas:** es una clave de WordPress que hay que cambiar para replicar la configuración del sitio de Atardeceres en el nuevo (Julio, 16 oct). Nunca va al Drive en texto plano.
- **Ambientes:** staging primero, producción solo con visto bueno de Julio.

## 8. Dónde está cada cosa
| Necesito… | Está en |
|---|---|
| Textos originales de Jhon | `../01_WhatsApp/` |
| Datos y pendientes de cada empresa | `../02_Empresas/<empresa>/FICHA.md` |
| Fotos y de dónde salió cada una | `../03_Fotos/` + `INVENTARIO.md` |
| Lo que se acordó | `../00_Reuniones/2026-10-16_informe-fygp.md` |
| Qué falta y quién lo debe | `../PENDIENTES.md` |

## 9. Orden de trabajo sugerido
1. Julio confirma con Jhon los ⛔ de la sección 1 (fechas exactas, si el precio es por persona, nombre del tour, nombre de la quesería, dominio). Julio prometió la landing «de aquí a la otra semana».
2. Diseño: moodboard y wireframe móvil con las secciones del punto 4.
3. Contenido: texto final por sección, aprobado por Julio.
4. Desarrollo en staging: página, reservas, pagos de prueba.
5. Revisión de Julio, luego producción.
