# Tema: tourmantequilla-child

Child theme de Hello Elementor con la landing del tour y el motor de reservas.
Vive aquí mientras se define el repo de código definitivo (ver `CLAUDE.md`).

| Archivo | Qué hace |
|---|---|
| `inc/reservas.php` | Motor de reservas multi-fecha, **copia del de Dalia Dorada** (mismos nombres `dd_tour_*`, metas `_tour_*` y shortcodes `[tour_*]`). Sin tarifa doble ni «Momentos» |
| `inc/landing.php` | Helpers de la landing y la sección «Elige tu fecha» (`tm_render_fechas()`) |
| `templates/landing-tour.php` | Plantilla «Landing del tour». **Se genera** desde `05_Landing/diseno/prototipo.html` |
| `assets/css/landing.css`, `assets/js/landing.js` | También se generan desde el prototipo (más el CSS y JS de la reserva) |
| `assets/img/` | Copia de `05_Landing/assets/` (fotos web y logos) |

## Cómo reserva

1. La landing lista las fechas futuras del producto con slug `tour-mantequilla`.
2. Cada tarjeta tiene un formulario con la fecha fija y personas −/+. Envía `add-to-cart` + `dd_tour_date` + `quantity`.
3. El servidor valida que la fecha exista, sea futura y tenga cupo. Si falla, vuelve a `#fechas` con el aviso.
4. Si pasa, va al checkout. Al quedar el pedido en `on-hold`/`processing`/`completed`, sube `booked`. Si se cancela, reembolsa o falla, baja.

Las fechas y los cupos se cargan en **Productos → Tour Mantequilla → pestaña «Tour»** (`AAAA-MM-DD | 15`).

## Sitio local

- Local: `tourmantequilla.local` (PHP 8.2, WooCommerce 11.2, Hello Elementor 3.5).
- Copiar el tema después de cada cambio: `bash tema/sync-local.sh`.
- Pago de prueba: «Cheque» renombrado como «Pago de prueba (solo local)». En producción va OnePay.

## Sitio de validación en Hostinger (para Julio)

- URL: `https://palevioletred-sardine-209107.hostingersite.com/`. Está oculto con el «Próximamente» de WooCommerce
  y se ve con el enlace privado `?woo-share=<llave>` (la llave: `wp option get woocommerce_share_key`).
- Primera vez (tema + configuración + producto): `bash tema/deploy-hostinger.sh`. Solo el tema: `bash tema/deploy-hostinger.sh --tema`.
  Llave SSH `~/.ssh/tourmantequilla_deploy`. No sube la BD local; si se corre otra vez, no pisa los cupos reservados.
- ⚠️ **La cuenta SSH es compartida con sitios de clientes en producción (daliadorada.com entre ellos).** Todo comando lleva
  `--path` del tour. Nunca `wp cache flush` ni vaciar el memcached, porque puede ser compartido.
- WP-CLI con `/opt/alt/php83/usr/bin/php` (el de la web). El PHP 8.1 por defecto no ve el memcached, y la web seguía
  leyendo opciones viejas.
- LiteSpeed Cache está **desactivado** a propósito: el CDN guardaba la respuesta del enlace privado sin la cookie
  `woo-share`, y al reservar se caía en «coming soon».
- Pago: «Pago de prueba (validación)», sin cobro. Los correos salen de verdad: probar con correos propios.

## Verificado el 2026-10-20 en local

- 16 personas en una fecha de 15 → «Solo quedan 15 cupos para esa fecha».
- Fecha que no está cargada → «Esa fecha ya no está disponible».
- Fecha agotada → la tarjeta sale «Agotado» sin botón, y si se fuerza el envío → «Esa fecha está agotada».
- Pedido de 2 personas → `on-hold`, `booked` 0→2. Pasar a `processing` no cuenta doble. Cancelar → 2→0.
- Navegador: personas −/+ con total en vivo ($880.000) → checkout con «Fecha del tour: Sábado 5 de diciembre».

## Regenerar la plantilla desde el prototipo

```
python -I tema/build_landing.py .
bash tema/sync-local.sh
```
(desde la raíz del repo). Sobrescribe `templates/landing-tour.php`, `assets/css/landing.css` y `assets/js/landing.js`.

## Checkout (clásico, igual que Dalia)

`inc/checkout.php`: campos mínimos (nombre y apellido, celular, correo, país), sin cupones, botón
«Confirmar reserva · $total», confianza bajo el botón, «← Cambiar fecha o personas», carrito que
salta directo al checkout y página de gracias con fecha y personas. Estilo en `assets/css/checkout.css`.

## Configuración que vive en la BD (no está en git; repetir en cada sitio nuevo)

| Qué | Cómo |
|---|---|
| Idioma español (Colombia) para WP, WooCommerce y Hello | `wp language core install es_CO --activate` + `wp language plugin install woocommerce es_CO` + `wp language theme install hello-elementor es_CO` |
| Checkout y carrito clásicos | Página Checkout = `[woocommerce_checkout]`, Carrito = `[woocommerce_cart]` (no bloques) |
| Moneda | COP, 0 decimales, miles con «.», símbolo a la izquierda sin espacio (`woocommerce_currency_pos = left`) |
| Formato de fecha | `j \d\e F \d\e Y` («20 de octubre de 2026») |
| Zona horaria | `America/Bogota` |
| Portada | Página «Inicio» con la plantilla «Landing del tour» |
| Producto | Simple, virtual, slug `tour-mantequilla`, $440.000, fechas en la pestaña «Tour» |
| Pago de prueba | «Cheque» habilitado como «Pago de prueba (solo local)». **Apagarlo en producción** |

## Pendiente del checkout

- Algunos textos del correo de confirmación salen en inglés (falta en la traducción es_CO de WooCommerce 11). Se pueden reemplazar con el mismo mapa de `gettext` o personalizar en WooCommerce → Ajustes → Correos.
- ⛔ ¿Jhon necesita cédula o NIT para facturar? Si sí, se agrega el campo.
- ⛔ Sitio y hora de recogida (#27) en la página de gracias y en el correo.
- OnePay: medios de pago reales bajo el botón cuando esté conectado.

## Cordillera de la escena del atardecer

La genera `tema/gen_cordillera.py` (semilla fija, siempre sale igual) y la escribe en `prototipo.html`:
```
python -I tema/gen_cordillera.py .
python -I tema/build_landing.py .
bash tema/sync-local.sh
```
Para cambiar las montañas se ajustan las capas (`cresta(base, amplitud, ondulación, picos)`), los colores o la cantidad de árboles y luces en el script. No se edita el SVG a mano.
