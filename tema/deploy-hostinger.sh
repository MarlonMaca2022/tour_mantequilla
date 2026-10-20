#!/usr/bin/env bash
# Sube el tema y configura el sitio de validación en Hostinger (staging para Julio).
# Uso (desde la raíz del repo):  bash tema/deploy-hostinger.sh          → tema + configuración
#                                 bash tema/deploy-hostinger.sh --tema   → solo actualiza el tema
# Idempotente: correrlo de nuevo no duplica páginas ni el producto, ni pisa los cupos ya reservados.
# OJO: la cuenta tiene sitios de otros clientes (daliadorada.com incluido). Todo va con --path fijo.
set -euo pipefail

HOST=u551250111@217.196.55.28
PORT=65002
KEY="$HOME/.ssh/tourmantequilla_deploy"
SITIO=palevioletred-sardine-209107.hostingersite.com
RUTA=/home/u551250111/domains/$SITIO/public_html
TMP=/home/u551250111/tmp-tourmantequilla

# arrays: el home de Windows tiene espacios («Marlon Hoyos»)
SSH=(ssh -i "$KEY" -o IdentitiesOnly=yes -p "$PORT" "$HOST")
SCP=(scp -i "$KEY" -o IdentitiesOnly=yes -P "$PORT")

DIR=$(cd "$(dirname "$0")" && pwd)
ZIP=$(mktemp -d)/tourmantequilla-child.zip
# zip con «/» (Compress-Archive de PowerShell 5.1 escribe «\» y Linux no lo abre)
python -I -c "import sys,zipfile,pathlib; r=pathlib.Path(sys.argv[1]); z=zipfile.ZipFile(sys.argv[2],'w',zipfile.ZIP_DEFLATED); [z.write(f, f.relative_to(r.parent).as_posix()) for f in sorted(r.rglob('*')) if f.is_file()]; z.close()" "$DIR/tourmantequilla-child" "$ZIP"

"${SSH[@]}" "mkdir -p $TMP"
"${SCP[@]}" "$ZIP" "$HOST:$TMP/tourmantequilla-child.zip"

if [ "${1:-}" = "--tema" ]; then
  "${SSH[@]}" "rm -rf $RUTA/wp-content/themes/tourmantequilla-child && unzip -q $TMP/tourmantequilla-child.zip -d $RUTA/wp-content/themes; rm -rf $TMP"
  echo "Tema actualizado en https://$SITIO/"
  exit 0
fi

"${SSH[@]}" "RUTA=$RUTA TMP=$TMP bash -s" <<'REMOTO'
set -euo pipefail
# PHP 8.3 = el de la web: con el 8.1 por defecto, WP-CLI no ve el memcached y la web sigue con opciones viejas
wp() { /opt/alt/php83/usr/bin/php /usr/local/bin/wp --path="$RUTA" "$@"; }

echo "== idioma, hora y portada sin indexar"
wp language core install es_CO --activate
wp option update timezone_string America/Bogota
wp option update date_format 'j \d\e F \d\e Y'
wp option update time_format 'g:i a'
wp option update blog_public 0                 # staging: que Google no lo indexe
wp option update blogname 'Tour Armenia Mantequilla'
# sin LiteSpeed: el CDN guardaba la respuesta del enlace privado sin la cookie woo-share
wp plugin is-active litespeed-cache && { wp litespeed-purge all || true; wp plugin deactivate litespeed-cache; }
wp rewrite structure '/%postname%/' --hard

echo "== tema padre, WooCommerce y tema hijo"
wp theme is-installed hello-elementor || wp theme install hello-elementor
wp plugin is-installed woocommerce || wp plugin install woocommerce
wp plugin activate woocommerce
wp language plugin install woocommerce es_CO
rm -rf "$RUTA/wp-content/themes/tourmantequilla-child"   # solo la carpeta de nuestro tema
unzip -q "$TMP/tourmantequilla-child.zip" -d "$RUTA/wp-content/themes"
wp theme activate tourmantequilla-child

echo "== tienda: COP, Colombia, checkout clásico"
wp option update woocommerce_currency COP
wp option update woocommerce_currency_pos left
wp option update woocommerce_price_thousand_sep .
wp option update woocommerce_price_decimal_sep ,
wp option update woocommerce_price_num_decimals 0
wp option update woocommerce_default_country CO:ANT
wp option update woocommerce_calc_taxes no
wp option update woocommerce_enable_guest_checkout yes
wp option update woocommerce_checkout_phone_field required
wp option update woocommerce_onboarding_profile '{"skipped":true}' --format=json

# el sitio solo se ve con el enlace privado o con sesión iniciada
wp option update woocommerce_coming_soon yes
wp option update woocommerce_store_pages_only no
wp option update woocommerce_private_link yes
[ -n "$(wp option get woocommerce_share_key 2>/dev/null || true)" ] || wp option update woocommerce_share_key "$(openssl rand -hex 16)"

wp eval '
$pag = function ($slug, $titulo, $contenido) {
  $p = get_page_by_path($slug);
  $datos = ["post_type" => "page", "post_status" => "publish", "post_name" => $slug, "post_title" => $titulo, "post_content" => $contenido];
  if ($p) { $datos["ID"] = $p->ID; wp_update_post($datos); return $p->ID; }
  return wp_insert_post($datos);
};
update_option("woocommerce_cart_page_id", $pag("cart", "Tu reserva", "<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->"));
update_option("woocommerce_checkout_page_id", $pag("checkout", "Confirma tu reserva", "<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->"));
$inicio = $pag("inicio", "Inicio", "");
update_post_meta($inicio, "_wp_page_template", "templates/landing-tour.php");
update_option("show_on_front", "page");
update_option("page_on_front", $inicio);

// pago de prueba: reservas sin cobrar mientras llega OnePay
update_option("woocommerce_cheque_settings", ["enabled" => "yes", "title" => "Pago de prueba (validación)", "description" => "Sitio de validación: no se cobra nada. En producción va OnePay.", "instructions" => ""]);

// el producto del tour (mismos datos que en Local)
$p = get_page_by_path("tour-mantequilla", OBJECT, "product");
$prod = $p ? wc_get_product($p->ID) : new WC_Product_Simple();
$prod->set_name("Tour Mantequilla: café, queso y atardecer");
$prod->set_slug("tour-mantequilla");
$prod->set_status("publish");
$prod->set_regular_price("440000");
$prod->set_virtual(true);
$prod->set_manage_stock(false);
$prod->set_stock_status("instock");
$id = $prod->save();
if (!get_post_meta($id, "_tour_dates", true)) {  // no pisar cupos ya reservados
  update_post_meta($id, "_tour_dates", [
    ["date" => "2026-12-05", "cupo" => 15, "booked" => 0],
    ["date" => "2026-12-12", "cupo" => 15, "booked" => 0],
    ["date" => "2026-12-19", "cupo" => 15, "booked" => 0],
  ]);
  update_post_meta($id, "_tour_next_date", "2026-12-05");
}
update_post_meta($id, "_tour_punto", "Medellín o San Antonio de Prado");
update_post_meta($id, "_tour_hora", "entre las 7:00 y las 8:00 a. m.");
update_post_meta($id, "_tour_duracion", "Todo el día (7:00 a. m. a 8:30 p. m.)");
echo "producto #$id, portada #$inicio\n";
'

rm -rf "$TMP"
echo "== enlace privado para Julio:"
echo "$(wp option get home)/?woo-share=$(wp option get woocommerce_share_key)"
REMOTO
