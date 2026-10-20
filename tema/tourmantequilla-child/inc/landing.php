<?php
/**
 * Landing del tour (templates/landing-tour.php): helpers, assets y la sección
 * "Elige tu fecha", que se arma con el motor de reservas (inc/reservas.php).
 * Diseño y copy: 05_Landing/diseno/DIRECCION.md y prototipo.html.
 */

if (!defined('ABSPATH')) {
    exit;
}

const TM_LANDING_TEMPLATE = 'templates/landing-tour.php';

/** "Sábado 5 de diciembre" sin depender del idioma instalado en WordPress. */
function tm_fecha_larga($ymd)
{
    $ts = strtotime((string) $ymd);
    if ($ts === false) {
        return (string) $ymd;
    }
    $dias = array('domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado');
    $meses = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    $txt = $dias[(int) date('w', $ts)] . ' ' . (int) date('j', $ts) . ' de ' . $meses[(int) date('n', $ts) - 1];
    return mb_strtoupper(mb_substr($txt, 0, 1)) . mb_substr($txt, 1);
}

/** Producto-tour por slug, o null. */
function tm_tour()
{
    static $tour = false;
    if ($tour !== false) {
        return $tour;
    }
    $tour = null;
    if (function_exists('wc_get_product')) {
        $p = get_page_by_path(TM_TOUR_SLUG, OBJECT, 'product');
        $tour = $p ? wc_get_product($p->ID) : null;
    }
    return $tour;
}

/** Precio del tour como "$440.000" (COP sin decimales). */
function tm_precio($tour = null)
{
    $tour = $tour ?: tm_tour();
    $valor = $tour ? (float) $tour->get_price() : 440000;
    return '$' . number_format($valor, 0, ',', '.');
}

/** Enlace de WhatsApp con mensaje, o '' si el tour no tiene número cargado. */
function tm_whatsapp_url($msg)
{
    $tour = tm_tour();
    $num = ($tour && function_exists('dd_tour_whatsapp_number')) ? dd_tour_whatsapp_number($tour->get_id()) : '';
    return $num === '' ? '' : 'https://wa.me/' . rawurlencode($num) . '?text=' . rawurlencode($msg);
}

/**
 * Atributos de un enlace de WhatsApp. Sin número cargado queda como pendiente
 * (clase doc-pendiente → oculto para el público, visible para el equipo).
 */
function tm_whatsapp_attrs($msg, $evento, $class = '')
{
    $url = tm_whatsapp_url($msg);
    if ($url === '') {
        $class = trim($class . ' doc-pendiente');
        return 'href="#" class="' . esc_attr($class) . '" data-evento="' . esc_attr($evento) . '"';
    }
    return 'href="' . esc_url($url) . '" target="_blank" rel="noopener nofollow"'
        . ($class !== '' ? ' class="' . esc_attr($class) . '"' : '')
        . ' data-evento="' . esc_attr($evento) . '"';
}

/** ¿Se muestran los pendientes (amarillo)? Solo a quien edita el sitio. */
function tm_ver_pendientes()
{
    return current_user_can('edit_posts');
}

function tm_es_landing()
{
    return is_page_template(TM_LANDING_TEMPLATE);
}

add_action('wp_enqueue_scripts', function () {
    if (!tm_es_landing()) {
        return;
    }
    $dir = get_stylesheet_directory();
    $uri = get_stylesheet_directory_uri();
    // La landing trae su propio diseño: fuera los estilos del tema padre.
    foreach (array('hello-elementor', 'hello-elementor-theme-style', 'hello-elementor-header-footer', 'hello-elementor-parent-style', 'tm-child') as $h) {
        wp_dequeue_style($h);
    }
    wp_enqueue_style('tm-fuentes', 'https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,700;0,800;1,700&family=Alegreya+Sans:wght@400;500;700&display=swap', array(), null);
    wp_enqueue_style('tm-landing', $uri . '/assets/css/landing.css', array(), filemtime($dir . '/assets/css/landing.css'));
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), null, true);
    wp_enqueue_script('gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array('gsap'), null, true);
    wp_enqueue_script('tm-landing', $uri . '/assets/js/landing.js', array('gsap-st'), filemtime($dir . '/assets/js/landing.js'), true);
    wp_localize_script('tm-landing', 'tmTour', array('precio' => tm_tour() ? (float) tm_tour()->get_price() : 0));
}, 20);

/**
 * Sección "Elige tu fecha": una tarjeta por fecha futura, con estado real del motor
 * y un formulario que agrega al carrito (fecha fija + personas) y va al checkout.
 * Pasa por la misma validación de cupo del servidor que el formulario del producto.
 */
function tm_render_fechas()
{
    $tour = tm_tour();
    if (!$tour || !function_exists('dd_tour_future_dates')) {
        echo '<p class="lead"><span class="falta">Falta crear el producto del tour (slug «' . esc_html(TM_TOUR_SLUG) . '») en WooCommerce</span></p>';
        return;
    }
    $fechas = dd_tour_future_dates($tour->get_id());
    if (!$fechas) {
        echo '<p class="lead">' . esc_html__('Pronto publicamos nuevas fechas.', 'tourmantequilla') . '</p>';
        return;
    }
    $precio = tm_precio($tour);
    $umbral = (int) get_option('woocommerce_notify_low_stock_amount', 2);
    echo '<ul class="fechas">';
    foreach ($fechas as $i => $e) {
        $rem = dd_tour_remaining($e);
        $cupo = (int) ($e['cupo'] ?? 0);
        $nombre = tm_fecha_larga($e['date']);
        $id = 'fecha-' . $e['date'];
        $agotada = $rem <= 0;
        if ($agotada) {
            $estado = __('Agotado', 'tourmantequilla');
        } elseif ($rem === $cupo || $rem > $umbral) {
            $estado = __('Disponible', 'tourmantequilla');
        } else {
            // Escasez real: sale del `booked`, nunca inventada.
            $estado = sprintf(_n('Queda %d cupo', 'Quedan %d cupos', $rem, 'tourmantequilla'), $rem);
        }
        echo '<li class="fecha' . ($agotada ? ' agotada' : '') . '" id="' . esc_attr($id) . '">';
        echo '<div class="cabf"><h3><time datetime="' . esc_attr($e['date']) . '">' . esc_html($nombre) . '</time></h3>'
            . '<span class="estado">' . esc_html($estado) . '</span></div>';
        echo '<p class="detalle">' . sprintf(esc_html__('Grupo de %d personas', 'tourmantequilla'), $cupo)
            . ' · <span class="precio-f">' . esc_html($precio) . ' por persona</span></p>';
        if ($agotada) {
            $wa = tm_whatsapp_url(sprintf('Hola, el %s está agotado. ¿Hay lista de espera?', $nombre));
            if ($wa !== '') {
                echo '<a class="salida" href="' . esc_url($wa) . '" target="_blank" rel="noopener nofollow" data-evento="whatsapp_agotada">'
                    . esc_html__('Avísame si se libera un cupo', 'tourmantequilla') . '</a>';
            }
        } else {
            $qid = 'personas-' . $e['date'];
            echo '<form class="reserva" method="post" action="' . esc_url(home_url('/#fechas')) . '">';
            echo '<input type="hidden" name="add-to-cart" value="' . esc_attr($tour->get_id()) . '">';
            echo '<input type="hidden" name="dd_tour_date" value="' . esc_attr($e['date']) . '">';
            echo '<div class="personas"><label for="' . esc_attr($qid) . '">' . esc_html__('Personas', 'tourmantequilla') . '</label>'
                . '<div class="stepper"><button type="button" class="paso" data-paso="-1" aria-label="' . esc_attr__('Una persona menos', 'tourmantequilla') . '">−</button>'
                . '<input id="' . esc_attr($qid) . '" type="number" name="quantity" value="1" min="1" max="' . esc_attr($rem) . '" step="1" inputmode="numeric">'
                . '<button type="button" class="paso" data-paso="1" aria-label="' . esc_attr__('Una persona más', 'tourmantequilla') . '">+</button></div></div>';
            echo '<p class="total" aria-live="polite">' . esc_html__('Total', 'tourmantequilla') . ' <strong data-total>' . esc_html($precio) . '</strong></p>';
            echo '<button class="btn ancho" type="submit" data-evento="reservar_fecha_' . ($i + 1) . '">'
                . esc_html(sprintf(__('Reservar el %s', 'tourmantequilla'), mb_strtolower($nombre))) . '</button>';
            echo '</form>';
        }
        echo '</li>';
    }
    echo '</ul>';
}
