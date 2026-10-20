# CLAUDE.md — Tour Armenia Mantequilla

> **Qué es:** landing independiente (dominio propio) que vende un tour de un día desde Medellín
> con 4 empresas locales: Café Cabras, Finca Mirador La Florida, Bonne Nature y Atardeceres / Dalia Dorada.
> **$440.000 por persona**, 3 fechas en diciembre 2026, **15 cupos por fecha**. Feria: **29 a 31 oct 2026**.
>
> Este archivo redirige a la fuente de cada cosa. Límite: 10.000 caracteres. Lo que crece va en los docs.

---

## Dos lugares, no confundirlos

| Qué | Dónde | Git |
|---|---|---|
| **Contenido y decisiones** (esta carpeta): reuniones, fichas, fotos, brief, diseño | `tour_mantequilla/` | `MarlonMaca2022/tour_mantequilla` |
| **Código** (child theme WordPress) | `tema/tourmantequilla-child/` en este repo, **provisional** hasta que Julio defina el repo de código. El `softux-co/tourmantequilla` que cita el README **no existe**. Detalle: `tema/README.md` | este repo |
| **Sitio local de pruebas** | Local (Windows): `tourmantequilla.local` → `C:\Users\Marlon Hoyos\Local Sites\tourmantequilla`. Copiar el tema: `bash tema/sync-local.sh` | — |
| **Referencia del motor de reservas** (solo lectura) | `C:\Users\Marlon Hoyos\Documents\DaliaDorada\Template-Dalia-Dorada-Coffee` | `softux-co/Template-Dalia-Dorada-Coffee` |

**Nunca** se edita ni se pushea al repo de Dalia Dorada desde este proyecto: sus scripts de deploy apuntan a su producción. Se copia lo necesario, no se trabaja encima.

## Orden de lectura

1. `README.md`: mapa de carpetas, empresas y reglas de contenido.
2. `05_Landing/BRIEF-DISENO-Y-DESARROLLO.md`: qué se construye.
3. `05_Landing/diseno/DIRECCION.md` + `prototipo.html`: dirección visual y copy.
4. `PENDIENTES.md`: lo abierto, con responsable y fuente.
5. ⛔ `docs/BASE-HEREDADA.md`, que según el README estaba en el repo de código, **no existe todavía**. Lo que se hereda de Dalia está en la sección siguiente.

---

## Prioridad actual (2026-10-20): que la plantilla reserve

**Decisión de Julio:** el sistema de reservas funciona **igual al de Dalia Dorada**, que es el módulo
custom multi-fecha (ADR-004 de Dalia, que reemplaza a ADR-002). Esto **reemplaza** la línea del brief
«3 productos o variaciones (una por fecha)»: es **un solo producto con 3 fechas**.

### Cómo funciona el motor (fuente: `functions.php` de Dalia, líneas ~389–1745)

- **Modelo.** Un tour = **un producto WooCommerce simple**. Post meta `_tour_dates` = array de
  `{date:'Y-m-d', cupo:int, booked:int}`. `restante = cupo − booked`. «Es tour» = `_tour_dates` no vacío
  (`dd_tour_is_tour`). Escalar `_tour_next_date` para ordenar listados.
- **Cupo por fecha, NO stock nativo.** Al guardar un tour se fuerza `_manage_stock = no`.
- **Admin, pestaña «Tour»** del producto:
  - `_tour_dates_text`: textarea `AAAA-MM-DD | cupo`, una por línea. Se valida y se deduplica, y conserva el `booked`.
  - Tabla de solo lectura con cupo, reservado, restante y estado.
  - Campos: `_tour_hora`, `_tour_punto`, `_tour_duracion`, `_tour_itinerario` (`HH:MM | actividad`),
    `_tour_incluye` (uno por línea), `_tour_momentos` (opcional) y `_tour_whatsapp`.
- **Front (hook `woocommerce_before_add_to_cart_button`).**
  - Paso 1, fecha: tarjetas-radio `dd_tour_date`, solo futuras. Las agotadas se ven tachadas.
  - Paso 2, momento: opcional, solo si `_tour_momentos` tiene opciones.
  - Paso 3, personas: es la cantidad de Woo, con tope en el mayor cupo restante.
  - Subtotal en vivo con `assets/js/dd-tour.js`. El formulario funciona sin JS.
- **Validación del servidor** (`woocommerce_add_to_cart_validation`): la fecha debe existir, ser futura y
  cumplir `en carrito + cantidad ≤ restante`. Los datos se guardan en el ítem del carrito y en la línea del
  pedido (`_dd_tour_date` oculto + «Fecha del tour» visible en el admin y los correos).
- **Ciclo de cupo.** `processing/completed/on-hold` suben `booked`. `cancelled/refunded/failed` lo bajan.
  La escritura va bajo `GET_LOCK` de MySQL (`dd_tour_with_lock`), es idempotente con `_dd_tour_counted`
  y, si hay sobrecupo, deja una nota en el pedido en vez de rechazar el pago.
- **Sin fechas con cupo, no se puede comprar** (`woocommerce_is_purchasable`). Botón «Reservar».
  Al agregar al carrito redirige al checkout. Si el carrito solo tiene tours, el botón dice «Confirmar reserva».
- **Shortcodes:** `[tour_reservar]` (formulario completo para páginas Canvas o plantilla a código),
  `[tour_dates]`, `[tour_dates_count]`, `[tour_fecha]`, `[tour_precio]`, `[tour_hora]`,
  `[tour_punto]`, `[tour_duracion]`, `[tour_itinerario]`, `[tour_incluye]`, `[tour_whatsapp]`. Atributo `id`.

### Qué cambia respecto a Dalia Dorada (no heredar a ciegas)

| De Dalia | En este tour |
|---|---|
| Tarifa doble (Colombia / visitante, precio regular vs. oferta) | **Fuera.** Precio único $440.000. Sin precio de oferta, el código no la activa. Quitar el copy |
| Copy fijo del widget (`$tf`: «El mismo sol…», lista de incluye, «grupos de máximo 10») | **Reescribir** con el copy de `DIRECCION.md` y lo que incluye confirmado (pendiente 18) |
| `dd_tour_id()` por slug `tour-finca-dalia-dorada`, logo por línea, identidad teal | Cambiar al slug del tour nuevo o eliminar |
| Momentos (elegir una experiencia) | No aplica salvo que Jhon lo pida → dejar `_tour_momentos` vacío |
| Escasez «¡Solo quedan N!» | Se permite **solo** si sale del `booked` real. Nada de contadores inventados |
| Strings EN con TranslatePress | Solo español por ahora (⛔ pendiente 35: ¿se atiende en inglés?) |
| Pasarela Wompi | **OnePay** ⛔ confirmar que tenga plugin de WooCommerce (pendiente 10) |

### Criterios de aceptación de hoy

1. En el admin, un producto con 3 fechas `| 15` muestra la tabla de cupos.
2. La landing muestra las 3 fechas con su estado real y un botón por fecha (ver `DIRECCION.md` §5).
3. Elegir fecha + personas → checkout → pago de prueba → el pedido trae «Fecha del tour» y `booked` sube.
4. Si se cancela el pedido, el cupo vuelve. Con 0 restantes, la fecha sale agotada y no se puede reservar.
5. No se puede pedir más personas que el cupo restante (lo valida el servidor, no solo el JS).

**Estado (2026-10-20):** los 5 criterios están verificados en `tourmantequilla.local` (ver `tema/README.md`).
Las personas se eligen con −/+ **en cada tarjeta de fecha**, que va directo al checkout (pendiente #24, falta que Julio lo valide).
La página del producto también reserva (selector de fechas + `?fecha=AAAA-MM-DD`).
**Ojo:** `templates/landing-tour.php`, `landing.css` y `landing.js` se generan desde `prototipo.html`. Si se toca el
prototipo, hay que regenerarlos. Si se toca la plantilla a mano, el prototipo deja de ser la fuente.

---

## Reglas que no se rompen

1. **Cada dato lleva fuente. Sin fuente no se publica.** Lo dudoso va con ⛔ (README §Reglas).
2. **Lo original no se edita** (`00_Reuniones`, `01_WhatsApp`): el trabajo va en archivos aparte.
3. **Nada inventado:** ni cupos, ni reseñas, ni prueba social. Fotos auténticas, sin IA, con permiso
   (pendientes 19, 20 y 25). Las afirmaciones de salud o regulatorias se verifican antes de publicarse.
4. **Las 4 empresas tienen el mismo peso.** La landing no parece de Dalia Dorada.
5. **Editar solo el child theme.** Nunca el tema padre ni el core de WP. El diseño de Elementor vive en la BD.
6. **El contenido baja, el código sube.** Nunca subir la BD local a producción.
7. **Verificación** = `php -l functions.php` + smoke en el sitio local con pedido de prueba. Push a `main` = producción y lo decide Julio.
8. **Versión de assets con `filemtime()`**, nunca fija a mano (en Dalia una `$v` sin subir dejó 7 días de caché vieja).
9. **No usar `backdrop-filter`/`transform`/`filter` en el header** porque rompe el side-cart (lección de Dalia).
10. Al cerrar algo: actualizar `PENDIENTES.md` (con fecha y fuente) y, si cambió el alcance, el brief.

## Convenciones

- Español en «tú», sin voseo. Commits `feat:` / `fix:` / `docs:` / `style:` / `refactor:`.
- Fechas absolutas (`2026-10-20`), nunca «ayer» ni «la otra semana».
- Personas del proyecto: **Julio** (lead, Softux), **Jhon Velásquez** (Atardeceres, factura), y los anfitriones de cada empresa (ver `02_Empresas/*/FICHA.md`).
