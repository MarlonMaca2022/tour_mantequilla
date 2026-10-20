"""Convierte 05_Landing/diseno/prototipo.html en la plantilla del child theme.
Uso: python -I build_landing.py <repo_root>
Genera: templates/landing-tour.php, assets/css/landing.css, assets/js/landing.js
"""
import re, sys, pathlib

root = pathlib.Path(sys.argv[1])
proto = (root / '05_Landing/diseno/prototipo.html').read_text(encoding='utf-8')
theme = root / 'tema/tourmantequilla-child'

css = re.search(r'<style>\n(.*?)</style>', proto, re.S).group(1)
js = re.search(r'<script>\n(\(function \(\) \{.*?\}\)\(\);)\n</script>', proto, re.S).group(1)
body = re.search(r'<body>\n(.*?)\n<script src="https://cdnjs', proto, re.S).group(1)

# ---------- plantilla ----------
body = body.replace('../assets/', '<?php echo $a; ?>/')
body = body.replace('$440.000', '<?php echo esc_html($precio); ?>')

# Fechas: las arma el motor de reservas
body, n = re.subn(r'      <ul class="fechas">.*?      </ul>\n',
                  '      <div class="avisos"><?php if (function_exists(\'wc_print_notices\')) { wc_print_notices(); } ?></div>\n'
                  '      <?php tm_render_fechas(); ?>\n', body, flags=re.S)
assert n == 1, 'bloque de fechas'
body, n = re.subn(r'      <p style="margin-top:16px"><span class="falta">Fechas de ejemplo.*?</p>\n', '', body, flags=re.S)
assert n == 1, 'nota de fechas de ejemplo'

# Barra flotante: fecha y cupos reales del motor
body, n = re.subn(r'<div class="barra" .*?\n</div>\n', '<?php tm_render_barra(); ?>\n', body, flags=re.S)
assert n == 1, 'barra'

# Botón de pendientes: solo para el equipo
body, n = re.subn(r'(      <button class="toggle-falta".*?</button>\n)',
                  r"      <?php if (tm_ver_pendientes()) : ?>\n\1      <?php endif; ?>\n", body, flags=re.S)
assert n == 1, 'toggle'

msgs = {
    'whatsapp_recibir_info': 'Hola, quiero recibir la información del Tour Mantequilla.',
    'whatsapp_grupo': 'Hola, somos un grupo de 6 o más personas y queremos reservar el Tour Mantequilla.',
    'whatsapp_dudas': 'Hola, tengo una duda sobre el Tour Mantequilla.',
    'whatsapp_cierre': 'Hola, tengo una pregunta sobre el Tour Mantequilla.',
}
def wa(m):
    cls, ev = m.group(1) or '', m.group(2)
    return "<a <?php echo tm_whatsapp_attrs('%s', '%s', '%s'); ?>>" % (msgs[ev].replace("'", "\\'"), ev, cls)
body, n = re.subn(r'<a (?:class="([^"]*)" )?href="#" data-evento="(whatsapp_\w+)">', wa, body)
assert n == len(msgs), n

header = '''<?php
/**
 * Template Name: Landing del tour
 * Generada desde 05_Landing/diseno/prototipo.html (scripts: ver tema/README.md).
 * Las fechas y el botón de reserva salen del motor (inc/reservas.php + tm_render_fechas()).
 */
if (!defined('ABSPATH')) {
    exit;
}
$a = esc_url(get_stylesheet_directory_uri() . '/assets/img');
$precio = tm_precio();
?><!doctype html>
<html <?php language_attributes(); ?><?php echo tm_ver_pendientes() ? '' : ' class="limpio"'; ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="description" content="Café, queso y atardecer en Armenia Mantequilla: un día con cuatro casas del pueblo, con recogida en Medellín.">
<script>document.documentElement.classList.add('js')</script>
<?php wp_head(); ?>
</head>
<body <?php body_class('tm-landing'); ?>>
<?php wp_body_open(); ?>
'''
footer = '''
<?php wp_footer(); ?>
</body>
</html>
'''
(theme / 'templates').mkdir(parents=True, exist_ok=True)
(theme / 'templates/landing-tour.php').write_text(header + body + footer, encoding='utf-8', newline='\n')

# ---------- CSS ----------
extra = '''
/* ---------- sitio WordPress ---------- */
.admin-bar .cab{top:32px}
@media (max-width:782px){.admin-bar .cab{top:46px}}
/* reserva dentro de cada fecha */
.reserva{display:grid;gap:12px}
.personas{display:flex;align-items:center;justify-content:space-between;gap:12px}
.personas label{font-weight:700}
.stepper{display:inline-flex;align-items:center;border:1.5px solid var(--linea);border-radius:999px;background:#fff}
.stepper .paso{width:44px;height:44px;border:0;background:transparent;font:700 1.375rem/1 var(--sans);color:var(--montana);cursor:pointer;border-radius:50%}
.stepper .paso:disabled{color:var(--linea);cursor:default}
.stepper input{width:3ch;border:0;text-align:center;font:700 var(--t-s) var(--sans);color:var(--montana);background:transparent;-moz-appearance:textfield}
.stepper input::-webkit-outer-spin-button,.stepper input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
.reserva .total{display:flex;justify-content:space-between;align-items:baseline;font-size:var(--t-xs);color:var(--bruma)}
.reserva .total strong{font-family:var(--serif);font-size:var(--t-m);color:var(--montana)}
/* avisos de WooCommerce (p. ej. "Solo quedan 3 cupos") */
.avisos{margin-top:20px}
.avisos:empty{display:none}
.avisos ul,.avisos .woocommerce-error,.avisos .woocommerce-message,.avisos .woocommerce-info{list-style:none;margin:0 0 8px;padding:12px 16px;border-radius:8px;background:#fff;border-left:4px solid var(--corredor);font-weight:700}
'''
(theme / 'assets/css').mkdir(parents=True, exist_ok=True)
(theme / 'assets/css/landing.css').write_text(css + extra, encoding='utf-8', newline='\n')

# ---------- JS ----------
js = js.replace(
    "  var toggle = document.querySelector('.toggle-falta');\n  toggle.addEventListener('click', function () {",
    "  var toggle = document.querySelector('.toggle-falta');\n  if (toggle) toggle.addEventListener('click', function () {")
assert 'if (toggle)' in js
stepper = '''
  // reserva: personas −/+ y total en vivo (el servidor valida el cupo igual)
  var precio = (window.tmTour && +window.tmTour.precio) || 0;
  function cop(n) { return '$' + Math.round(n).toLocaleString('es-CO'); }
  document.querySelectorAll('form.reserva').forEach(function (f) {
    var q = f.querySelector('input[name="quantity"]'), out = f.querySelector('[data-total]');
    var menos = f.querySelector('[data-paso="-1"]'), mas = f.querySelector('[data-paso="1"]');
    var max = +q.max || 1;
    function pintar() {
      var v = Math.min(max, Math.max(1, parseInt(q.value, 10) || 1));
      q.value = v; menos.disabled = v <= 1; mas.disabled = v >= max;
      if (precio && out) out.textContent = cop(precio * v);
    }
    f.querySelectorAll('.paso').forEach(function (b) {
      b.addEventListener('click', function () { q.value = (parseInt(q.value, 10) || 1) + (+b.dataset.paso); pintar(); });
    });
    q.addEventListener('change', pintar);
    f.addEventListener('submit', function () { f.querySelector('button[type="submit"]').disabled = true; });
    pintar();
  });
'''
anchor = "  // medición: eventos clave"
assert anchor in js
js = js.replace(anchor, stepper.lstrip('\n') + '\n' + anchor, 1)
(theme / 'assets/js').mkdir(parents=True, exist_ok=True)
(theme / 'assets/js/landing.js').write_text(js + '\n', encoding='utf-8', newline='\n')
print('ok')
