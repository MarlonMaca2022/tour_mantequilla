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
- Pago de prueba: «Cheque» renombrado como «Pago de prueba (solo local)». En producción va Wompi.

## Producción en Hostinger: tourmantequilla.com

Desde el 2026-10-29 (feria) el sitio está en producción en `https://tourmantequilla.com`, en la misma cuenta de
Hostinger. La carpeta del staging (`palevioletred-sardine-209107.hostingersite.com`) ya no existe.

- **Subir el tema:** `bash tema/deploy-hostinger.sh` muestra qué cambiaría sin subir nada. `--subir` lo sube.
  Antes compara cada archivo del servidor con el último commit. Si alguien editó algo allá, se detiene hasta que ese
  cambio esté en el repo (`--forzar` lo pisa, y queda en el respaldo). Revisa la sintaxis PHP, respalda el tema en
  `~/tmp-tourmantequilla/respaldos/` y, si la portada no responde 200, vuelve solo al tema anterior.
  Llave SSH `~/.ssh/tourmantequilla_deploy`. Nunca sube la BD.
- Primero hay que hacer **commit**: el script acepta las versiones que estén en algún commit; un archivo subido sin commit sale como
  «editado en el servidor».
- La configuración inicial del staging (idioma, WooCommerce, páginas y producto) ya **no está** en el script (ver el
  commit `2d55c3a`). En producción ocultaba el sitio, lo marcaba noindex y activaba el pago de prueba.
- Pago: **Wompi** (plugin `wompi-portal-de-pagos`). Wompi devuelve a la página de gracias con el pedido todavía
  «pendiente» y lo cancela si el banco lo rechaza, así que la página de gracias habla según el estado del pedido.
- LiteSpeed Cache está **activo** en producción. Si un cambio de la landing no se ve: WordPress → LiteSpeed Cache →
  Purgar todo (es solo de este sitio).
- ⚠️ **La cuenta SSH es compartida con sitios de clientes en producción (daliadorada.com entre ellos).** Todo comando lleva
  la ruta del tour. Nunca `wp cache flush` ni vaciar el memcached, porque puede ser compartido.
- WP-CLI con `/opt/alt/php83/usr/bin/php` (el de la web). El PHP 8.1 por defecto no ve el memcached, y la web seguía
  leyendo opciones viejas.

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

- Correos (2026-10-29): `inc/correos.php` les pone los colores de la marca, el encabezado (`tema/gen_correo_encabezado.py`) y los textos de «reserva». Asuntos, títulos y cierres van en código, así que lo que se cambie en WooCommerce → Ajustes → Correos no se aplica a esos campos.
- ⛔ ¿Jhon necesita cédula o NIT para facturar? Si sí, se agrega el campo.
- ⛔ Sitio y hora de recogida (#27) en la página de gracias y en el correo.
- ⛔ El tour no tiene número de WhatsApp cargado (pestaña «Tour»): sin él no sale el botón en los correos ni en la página de gracias.

## Cordillera de la escena del atardecer

La genera `tema/gen_cordillera.py` (semilla fija, siempre sale igual) y la escribe en `prototipo.html`:
```
python -I tema/gen_cordillera.py .
python -I tema/build_landing.py .
bash tema/sync-local.sh
```
Para cambiar las montañas se ajustan las capas (`cresta(base, amplitud, ondulación, picos)`), los colores o la cantidad de árboles y luces en el script. No se edita el SVG a mano.

## Recortes de fotos reales (borrador 8, propuesta)

`tema/gen_recortes.py` saca de `03_Fotos/` la cordillera, el pueblo, la luna, los granos de café y las nubes, con transparencia, y los deja en `05_Landing/assets/recortes/`. Siempre da la misma salida y no usa IA. Los usa `05_Landing/diseno/prototipo-realismo.html`, que **todavía no pasa a la plantilla** (pendiente #36). Necesita Pillow y numpy en el Python base, porque `-I` ignora los paquetes instalados con `--user`:
```
python -m pip install --no-user pillow numpy
python -I tema/gen_recortes.py .
```
**Copia para Julio en Hostinger (2026-10-21):** `https://palevioletred-sardine-209107.hostingersite.com/propuesta-realismo-bcae4bb9/`.
Es una carpeta estática en `public_html/propuesta-realismo-bcae4bb9/` (`diseno/index.html` + solo los archivos de `assets/` que usa). No toca WordPress, el tema ni la BD, y no pasa por el «Próximamente»: cualquiera con el enlace la ve. Lleva `noindex` y no lista carpetas. Para actualizarla, se vuelve a subir la carpeta y se le da permisos `644`/`755`, porque el unzip deja los archivos privados y da 403. El HTML se cachea 7 días, así que hay que avisar que recarguen con `?v=2`. Para borrarla: `rm -rf` de esa carpeta, solo esa.

Si Julio lo aprueba, hay que copiar `assets/recortes/` a `tourmantequilla-child/assets/img/recortes/` (igual que `fotos-web`) antes de `build_landing.py`.
