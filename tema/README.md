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
