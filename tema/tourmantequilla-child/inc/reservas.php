<?php
/**
 * ── Módulo de reservas de tours (multi-fecha) ─────────────────────────────
 * Copia del motor de Dalia Dorada (Template-Dalia-Dorada-Coffee/functions.php,
 * ADR-004 de Dalia). Se conservan a propósito los nombres `dd_tour_*`, las metas
 * `_tour_*` y los shortcodes `[tour_*]` para que un arreglo en un proyecto se pueda
 * llevar al otro sin traducir nombres.
 *
 * Un producto-tour ofrece VARIAS fechas, cada una con su cupo. Modelo en
 * post_meta `_tour_dates` = array de ['date'=>'Y-m-d','cupo'=>int,'booked'=>int].
 * restante = cupo − booked (derivado). "Es tour" = tiene `_tour_dates` no vacío.
 * El cupo lo maneja este módulo, NO el stock nativo de Woo.
 *
 * Quitado respecto a Dalia (no aplica a este tour): tarifa doble Colombia/visitante,
 * "Momentos" (experiencia a elegir), copy fijo del widget, strings EN/TranslatePress.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Formatea una fecha (Y-m-d) como "Diciembre 5, 2026" con el locale del sitio.
 * Si la cadena no es una fecha parseable la devuelve tal cual.
 */
function dd_format_date_es($raw)
{
    $raw = trim((string) $raw);
    if ($raw === '') {
        return '';
    }
    $ts = strtotime($raw);
    if ($ts === false) {
        return $raw;
    }
    return ucfirst(date_i18n('F j, Y', $ts));
}

/** ¿El producto es un tour? (tiene fechas cargadas) */
function dd_tour_is_tour($product_id)
{
    $dates = get_post_meta($product_id, '_tour_dates', true);
    return is_array($dates) && !empty($dates);
}

/** Array de fechas del tour, normalizado. */
function dd_tour_get_dates($product_id)
{
    $dates = get_post_meta($product_id, '_tour_dates', true);
    return is_array($dates) ? $dates : array();
}

/** Cupo restante (>=0) de una entrada de fecha. */
function dd_tour_remaining($entry)
{
    $cupo = isset($entry['cupo']) ? (int) $entry['cupo'] : 0;
    $booked = isset($entry['booked']) ? (int) $entry['booked'] : 0;
    $rem = $cupo - $booked;
    return $rem > 0 ? $rem : 0;
}

/** Fechas futuras (>= hoy, tz del sitio), ordenadas ascendente. */
function dd_tour_future_dates($product_id)
{
    $today = current_time('Y-m-d');
    $out = array();
    foreach (dd_tour_get_dates($product_id) as $e) {
        if (!empty($e['date']) && $e['date'] >= $today) {
            $out[] = $e;
        }
    }
    usort($out, function ($a, $b) {
        return strcmp($a['date'], $b['date']);
    });
    return $out;
}

/** Próxima fecha futura (string Y-m-d) o '' si no hay. */
function dd_tour_next_date($product_id)
{
    $f = dd_tour_future_dates($product_id);
    return $f ? $f[0]['date'] : '';
}

/** Parsea el textarea "Y-m-d | cupo" (una por línea) → array de entradas (sin booked). */
function dd_tour_parse_textarea($raw)
{
    $out = array();
    $lines = preg_split('/\r\n|\r|\n/', (string) $raw);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $parts = array_map('trim', explode('|', $line));
        $date = isset($parts[0]) ? $parts[0] : '';
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date) {
            continue; // fecha inválida → se ignora la línea
        }
        $cupo = isset($parts[1]) ? (int) $parts[1] : 0;
        if ($cupo < 0) {
            $cupo = 0;
        }
        $out[$date] = array('date' => $date, 'cupo' => $cupo); // clave por fecha → dedup
    }
    return array_values($out);
}

/** Reconstruye el texto del textarea desde el array almacenado. */
function dd_tour_dates_to_textarea($dates)
{
    $lines = array();
    foreach ($dates as $e) {
        if (empty($e['date'])) {
            continue;
        }
        $lines[] = $e['date'] . ' | ' . (isset($e['cupo']) ? (int) $e['cupo'] : 0);
    }
    return implode("\n", $lines);
}

/** Líneas no vacías de un meta textarea del tour (itinerario / incluye). */
function dd_tour_lines($product_id, $meta_key)
{
    $raw = (string) get_post_meta($product_id, $meta_key, true);
    $out = array();
    foreach (preg_split('/\r\n|\r|\n/', $raw) as $line) {
        $line = trim($line);
        if ($line !== '') {
            $out[] = $line;
        }
    }
    return $out;
}

/** Número de WhatsApp del tour (solo dígitos) o '' si no hay. */
function dd_tour_whatsapp_number($product_id)
{
    $num = preg_replace('/\D+/', '', (string) get_post_meta($product_id, '_tour_whatsapp', true));
    if ($num === '') {
        $num = preg_replace('/\D+/', '', (string) apply_filters('dd_tour_whatsapp_default', ''));
    }
    return $num;
}

/* ── Admin: pestaña "Tour" ─────────────────────────────────────────────── */

add_filter('woocommerce_product_data_tabs', function ($tabs) {
    $tabs['dd_tour'] = array(
        'label' => __('Tour', 'tourmantequilla'),
        'target' => 'dd_tour_data',
        'class' => array(),
        'priority' => 22,
    );
    return $tabs;
});

add_action('woocommerce_product_data_panels', function () {
    global $post;
    $dates = dd_tour_get_dates($post->ID);
    echo '<div id="dd_tour_data" class="panel woocommerce_options_panel">';
    woocommerce_wp_textarea_input(array(
        'id' => '_tour_dates_text',
        'label' => __('Fechas y cupos', 'tourmantequilla'),
        'description' => __('Una por línea: AAAA-MM-DD | cupo. Ej: 2026-12-05 | 15', 'tourmantequilla'),
        'desc_tip' => true,
        'value' => dd_tour_dates_to_textarea($dates),
        'rows' => 6,
    ));
    if ($dates) {
        echo '<div class="options_group" style="padding:8px 12px 12px">';
        echo '<strong>' . esc_html__('Estado de cupos', 'tourmantequilla') . '</strong>';
        echo '<table class="widefat striped" style="margin-top:6px"><thead><tr>';
        echo '<th>' . esc_html__('Fecha', 'tourmantequilla') . '</th>';
        echo '<th>' . esc_html__('Cupo', 'tourmantequilla') . '</th>';
        echo '<th>' . esc_html__('Reservado', 'tourmantequilla') . '</th>';
        echo '<th>' . esc_html__('Restante', 'tourmantequilla') . '</th>';
        echo '<th>' . esc_html__('Estado', 'tourmantequilla') . '</th></tr></thead><tbody>';
        foreach ($dates as $e) {
            $rem = dd_tour_remaining($e);
            $estado = $rem <= 0
                ? '<span style="color:#c62828;font-weight:600">' . esc_html__('Agotado', 'tourmantequilla') . '</span>'
                : '<span style="color:#2e7d32;font-weight:600">' . esc_html__('Disponible', 'tourmantequilla') . '</span>';
            printf(
                '<tr><td>%s</td><td>%d</td><td>%d</td><td>%d</td><td>%s</td></tr>',
                esc_html(isset($e['date']) ? $e['date'] : ''),
                (int) (isset($e['cupo']) ? $e['cupo'] : 0),
                (int) (isset($e['booked']) ? $e['booked'] : 0),
                $rem,
                $estado
            );
        }
        echo '</tbody></table></div>';
    }
    woocommerce_wp_text_input(array(
        'id' => '_tour_hora',
        'label' => __('Hora', 'tourmantequilla'),
        'description' => __('Hora de salida. Ej: "6:00 a. m.".', 'tourmantequilla'),
        'desc_tip' => true,
    ));
    woocommerce_wp_text_input(array(
        'id' => '_tour_punto',
        'label' => __('Punto de encuentro', 'tourmantequilla'),
        'description' => __('Dónde se recoge al grupo en Medellín.', 'tourmantequilla'),
        'desc_tip' => true,
    ));
    woocommerce_wp_text_input(array(
        'id' => '_tour_duracion',
        'label' => __('Duración', 'tourmantequilla'),
        'description' => __('Ej: "Un día".', 'tourmantequilla'),
        'desc_tip' => true,
    ));
    woocommerce_wp_textarea_input(array(
        'id' => '_tour_itinerario',
        'label' => __('Itinerario', 'tourmantequilla'),
        'description' => __('Una línea por hito: HH:MM | actividad.', 'tourmantequilla'),
        'desc_tip' => true,
        'rows' => 8,
    ));
    woocommerce_wp_textarea_input(array(
        'id' => '_tour_incluye',
        'label' => __('Incluye', 'tourmantequilla'),
        'description' => __('Un ítem por línea.', 'tourmantequilla'),
        'desc_tip' => true,
        'rows' => 5,
    ));
    woocommerce_wp_text_input(array(
        'id' => '_tour_whatsapp',
        'label' => __('WhatsApp (reservas)', 'tourmantequilla'),
        'description' => __('Número con código de país, solo dígitos. Ej: 573001234567. Vacío = no se muestran los enlaces de WhatsApp.', 'tourmantequilla'),
        'desc_tip' => true,
    ));
    echo '</div>';
});

add_action('woocommerce_process_product_meta', function ($post_id) {
    // Fechas + cupos: parsear textarea, PRESERVANDO el `booked` de cada fecha existente.
    if (isset($_POST['_tour_dates_text'])) {
        $parsed = dd_tour_parse_textarea(wp_unslash($_POST['_tour_dates_text']));
        $booked_by_date = array();
        foreach (dd_tour_get_dates($post_id) as $e) {
            if (!empty($e['date'])) {
                $booked_by_date[$e['date']] = isset($e['booked']) ? (int) $e['booked'] : 0;
            }
        }
        $new = array();
        foreach ($parsed as $e) {
            $e['booked'] = isset($booked_by_date[$e['date']]) ? $booked_by_date[$e['date']] : 0;
            $new[] = $e;
        }
        update_post_meta($post_id, '_tour_dates', $new);
        update_post_meta($post_id, '_tour_next_date', dd_tour_next_date($post_id));
        // Es tour → el cupo por fecha manda; apagar stock nativo para no tener dos contadores.
        if (!empty($new)) {
            update_post_meta($post_id, '_manage_stock', 'no');
            update_post_meta($post_id, '_stock_status', 'instock');
            wc_delete_product_transients($post_id);
        }
    }
    foreach (array('_tour_hora', '_tour_punto', '_tour_duracion', '_tour_whatsapp') as $k) {
        if (isset($_POST[$k])) {
            update_post_meta($post_id, $k, sanitize_text_field(wp_unslash($_POST[$k])));
        }
    }
    foreach (array('_tour_itinerario', '_tour_incluye') as $k) {
        if (isset($_POST[$k])) {
            update_post_meta($post_id, $k, sanitize_textarea_field(wp_unslash($_POST[$k])));
        }
    }
});

/* ── Shortcodes ────────────────────────────────────────────────────────── */

// Próxima fecha del tour formateada "Diciembre 5, 2026"
add_shortcode('tour_fecha', function ($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts, 'tour_fecha');
    $product = wc_get_product($atts['id']);
    return $product ? dd_format_date_es(dd_tour_next_date($product->get_id())) : '';
});

// Listado de fechas con cupo/estado. Atts: id, ok, bajo, agotado, umbral.
add_shortcode('tour_dates', function ($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID(),
        'ok' => 'Cupos disponibles',
        'bajo' => '¡Casi lleno!',
        'agotado' => 'Agotado',
        'umbral' => (int) get_option('woocommerce_notify_low_stock_amount', 2),
    ), $atts, 'tour_dates');
    $product = wc_get_product($atts['id']);
    if (!$product) {
        return '';
    }
    $dates = dd_tour_future_dates($product->get_id());
    if (!$dates) {
        return '<p class="dd-tour-nodates">' . esc_html__('Próximamente nuevas fechas.', 'tourmantequilla') . '</p>';
    }
    $umbral = (int) $atts['umbral'];
    $out = '<ul class="dd-tour-dates">';
    foreach ($dates as $e) {
        $rem = dd_tour_remaining($e);
        if ($rem <= 0) {
            $label = $atts['agotado'];
            $class = 'dd-stock-agotado';
        } elseif ($rem <= $umbral) {
            $label = $atts['bajo'];
            $class = 'dd-stock-bajo';
        } else {
            $label = $atts['ok'];
            $class = 'dd-stock-ok';
        }
        $out .= '<li class="dd-tour-date">'
            . '<span class="dd-tour-date-label">' . esc_html(dd_format_date_es($e['date'])) . '</span> '
            . '<span class="dd-stock-status ' . esc_attr($class) . '">' . esc_html($label) . '</span>'
            . '</li>';
    }
    return $out . '</ul>';
});

// Cantidad de fechas futuras con cupo. Att: id.
add_shortcode('tour_dates_count', function ($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts, 'tour_dates_count');
    $product = wc_get_product($atts['id']);
    if (!$product) {
        return '';
    }
    return (string) count(array_filter(dd_tour_future_dates($product->get_id()), function ($e) {
        return dd_tour_remaining($e) > 0;
    }));
});

// Texto del tour: hora / punto de encuentro / duración
foreach (array(
    'tour_hora' => '_tour_hora',
    'tour_punto' => '_tour_punto',
    'tour_duracion' => '_tour_duracion',
) as $dd_tag => $dd_meta) {
    add_shortcode($dd_tag, function ($atts) use ($dd_tag, $dd_meta) {
        $atts = shortcode_atts(array('id' => get_the_ID()), $atts, $dd_tag);
        $product = wc_get_product($atts['id']);
        return $product ? esc_html(get_post_meta($product->get_id(), $dd_meta, true)) : '';
    });
}

// Precio del tour como widget suelto. Att: id.
add_shortcode('tour_precio', function ($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts, 'tour_precio');
    $product = wc_get_product($atts['id']);
    return $product ? '<span class="dd-tour-precio">' . $product->get_price_html() . '</span>' : '';
});

// Itinerario: <ol> con hora + actividad ("HH:MM | actividad"). Att: id.
add_shortcode('tour_itinerario', function ($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts, 'tour_itinerario');
    $product = wc_get_product($atts['id']);
    $lines = $product ? dd_tour_lines($product->get_id(), '_tour_itinerario') : array();
    if (!$lines) {
        return '';
    }
    $out = '<ol class="dd-tour-itinerario">';
    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line, 2));
        $hora = isset($parts[1]) ? $parts[0] : '';
        $act = isset($parts[1]) ? $parts[1] : $parts[0];
        $out .= '<li class="dd-tour-hito">';
        if ($hora !== '') {
            $out .= '<span class="dd-tour-hora">' . esc_html($hora) . '</span> ';
        }
        $out .= '<span class="dd-tour-act">' . esc_html($act) . '</span></li>';
    }
    return $out . '</ol>';
});

// Qué incluye: <ul>. Att: id.
add_shortcode('tour_incluye', function ($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts, 'tour_incluye');
    $product = wc_get_product($atts['id']);
    $lines = $product ? dd_tour_lines($product->get_id(), '_tour_incluye') : array();
    if (!$lines) {
        return '';
    }
    $out = '<ul class="dd-tour-incluye">';
    foreach ($lines as $line) {
        $out .= '<li>' . esc_html($line) . '</li>';
    }
    return $out . '</ul>';
});

// Botón de reserva por WhatsApp. Atts: id, texto.
add_shortcode('tour_whatsapp', function ($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID(),
        'texto' => __('Reservar por WhatsApp', 'tourmantequilla'),
    ), $atts, 'tour_whatsapp');
    $product = wc_get_product($atts['id']);
    $num = $product ? dd_tour_whatsapp_number($product->get_id()) : '';
    if ($num === '') {
        return '';
    }
    $msg = sprintf(__('Hola, quiero reservar el tour "%s".', 'tourmantequilla'), $product->get_name());
    $href = 'https://wa.me/' . rawurlencode($num) . '?text=' . rawurlencode($msg);
    return '<a class="dd-tour-whatsapp" href="' . esc_url($href) . '" target="_blank" rel="noopener nofollow">'
        . esc_html($atts['texto']) . '</a>';
});

/**
 * Formulario de reserva completo (fecha + personas + botón) para páginas a código o
 * Elementor Canvas, donde el formulario nativo de Woo no se renderiza.
 * Reconstruye woocommerce_template_single_add_to_cart() → dispara el selector de fecha.
 */
add_shortcode('tour_reservar', function ($atts) {
    $atts = shortcode_atts(array('id' => get_the_ID()), $atts, 'tour_reservar');
    $tour = wc_get_product($atts['id']);
    if (!$tour || !dd_tour_is_tour($tour->get_id())) {
        return '';
    }
    global $product;
    $prev = $product;
    $product = $tour;
    ob_start();
    echo '<div class="dd-tour-reservar">';
    woocommerce_template_single_add_to_cart();
    echo '</div>';
    $html = ob_get_clean();
    $html = str_replace('<form class="cart"', '<form class="cart dd-tour-shop"', $html);
    $product = $prev;
    return $html;
});

/* ── Front: selector de fecha en la página del producto ────────────────── */

add_action('woocommerce_before_add_to_cart_button', function () {
    global $product;
    if (!$product || !dd_tour_is_tour($product->get_id())) {
        return;
    }
    $future = dd_tour_future_dates($product->get_id());
    if (empty($future)) {
        echo '<div class="dd-tour-picker"><p class="dd-tour-soldout">'
            . esc_html__('Próximamente nuevas fechas.', 'tourmantequilla') . '</p></div>';
        return;
    }
    // Fecha preseleccionada si llega por URL (?fecha=AAAA-MM-DD), p. ej. desde la landing.
    $pre = isset($_GET['fecha']) ? sanitize_text_field(wp_unslash($_GET['fecha'])) : '';
    $umbral = (int) get_option('woocommerce_notify_low_stock_amount', 2);

    echo '<div class="dd-tour-picker">';
    echo '<div class="dd-tour-picker-head"><span class="dd-step-badge">1</span>'
        . '<span class="dd-step-text"><strong>' . esc_html__('Elige tu fecha', 'tourmantequilla') . '</strong>'
        . '<span class="dd-tour-picker-sub">' . esc_html__('Selecciona el día de tu visita', 'tourmantequilla') . '</span></span></div>';
    echo '<div class="dd-tour-dates-list" role="radiogroup" aria-label="' . esc_attr__('Fechas disponibles', 'tourmantequilla') . '">';
    foreach ($future as $e) {
        $rem = dd_tour_remaining($e);
        $cupo = (int) ($e['cupo'] ?? 0);
        $label = tm_fecha_larga($e['date']);
        if ($rem > 0) {
            // Escasez honesta: solo sale del `booked` real.
            if ($rem === $cupo) {
                $cls = 'dd-stock-ok';
                $txt = __('Cupos disponibles', 'tourmantequilla');
            } elseif ($rem > $umbral) {
                $cls = 'dd-stock-ok';
                $txt = sprintf(_n('Queda %d cupo', 'Quedan %d cupos', $rem, 'tourmantequilla'), $rem);
            } else {
                $cls = 'dd-stock-bajo';
                $txt = sprintf(_n('¡Solo queda %d!', '¡Solo quedan %d!', $rem, 'tourmantequilla'), $rem);
            }
            printf(
                '<label class="dd-tour-date-card"><span><input type="radio" name="dd_tour_date" value="%s"%s required>'
                . '<span class="dd-tour-date-main">%s</span></span><span class="dd-tour-date-cupo %s">%s</span></label>',
                esc_attr($e['date']),
                checked($pre, $e['date'], false),
                esc_html($label),
                esc_attr($cls),
                esc_html($txt)
            );
        } else {
            printf(
                '<div class="dd-tour-date-card is-agotado" aria-disabled="true">'
                . '<span class="dd-tour-date-main"><s>%s</s></span>'
                . '<span class="dd-tour-date-cupo dd-stock-agotado">%s</span></div>',
                esc_html($label),
                esc_html__('Agotado', 'tourmantequilla')
            );
        }
    }
    echo '</div></div>';
});

// Paso 2 · encabezado de la cantidad (= personas).
add_action('woocommerce_before_add_to_cart_quantity', function () {
    global $product;
    if (!$product || !dd_tour_is_tour($product->get_id())) {
        return;
    }
    echo '<div class="dd-tour-qty-head"><span class="dd-step-badge">2</span>'
        . '<span class="dd-step-text"><strong>' . esc_html__('¿Cuántas personas?', 'tourmantequilla') . '</strong>'
        . '<span class="dd-tour-qty-sub">' . esc_html__('Ajusta el número de asistentes', 'tourmantequilla') . '</span></span></div>';
});

// Señales de confianza (solo verdaderas) + WhatsApp secundario.
add_action('woocommerce_after_add_to_cart_button', function () {
    global $product;
    if (!$product || !dd_tour_is_tour($product->get_id())) {
        return;
    }
    echo '<ul class="dd-tour-trust">';
    echo '<li>' . esc_html__('Pago en línea', 'tourmantequilla') . '</li>';
    echo '<li>' . esc_html__('Confirmación por correo', 'tourmantequilla') . '</li>';
    echo '</ul>';
    $num = dd_tour_whatsapp_number($product->get_id());
    if ($num !== '') {
        $msg = sprintf(__('Hola, tengo una duda sobre el tour "%s".', 'tourmantequilla'), $product->get_name());
        echo '<p class="dd-tour-help"><a class="dd-tour-whatsapp-link" href="' . esc_url('https://wa.me/' . rawurlencode($num) . '?text=' . rawurlencode($msg))
            . '" target="_blank" rel="noopener nofollow">' . esc_html__('¿Dudas? Escríbenos por WhatsApp', 'tourmantequilla') . '</a></p>';
    }
});

// Tour sin fechas con cupo → no comprable.
add_filter('woocommerce_is_purchasable', function ($purchasable, $product) {
    if (dd_tour_is_tour($product->get_id())) {
        $available = array_filter(dd_tour_future_dates($product->get_id()), function ($e) {
            return dd_tour_remaining($e) > 0;
        });
        return !empty($available);
    }
    return $purchasable;
}, 10, 2);

// Cantidad = personas; tope flojo al mayor cupo disponible (el server valida por fecha).
add_filter('woocommerce_quantity_input_args', function ($args, $product) {
    if (is_object($product) && dd_tour_is_tour($product->get_id())) {
        $max = 0;
        foreach (dd_tour_future_dates($product->get_id()) as $e) {
            $max = max($max, dd_tour_remaining($e));
        }
        $args['min_value'] = 1;
        if ($max > 0) {
            $args['max_value'] = $max;
        }
    }
    return $args;
}, 10, 2);

/* ── Carrito + pedido: capturar y validar la fecha elegida ─────────────── */

/** Entrada de fecha (o null) dentro de _tour_dates, solo si es futura. */
function dd_tour_find_future_entry($product_id, $date)
{
    foreach (dd_tour_future_dates($product_id) as $e) {
        if ($e['date'] === $date) {
            return $e;
        }
    }
    return null;
}

/** Cantidad ya en el carrito para (producto, fecha). */
function dd_tour_qty_in_cart($product_id, $date)
{
    if (!function_exists('WC') || !WC()->cart) {
        return 0;
    }
    $qty = 0;
    foreach (WC()->cart->get_cart() as $item) {
        if ((int) $item['product_id'] === (int) $product_id
            && isset($item['dd_tour_date']) && $item['dd_tour_date'] === $date) {
            $qty += (int) $item['quantity'];
        }
    }
    return $qty;
}

/**
 * Una reserva por carrito: volver a reservar (otra fecha u otras personas) reemplaza la
 * anterior en vez de sumar líneas. Si la nueva no pasa el cupo, se restaura la anterior.
 */
add_filter('woocommerce_add_to_cart_validation', function ($passed, $product_id) {
    if ($passed && dd_tour_is_tour($product_id) && WC()->cart) {
        foreach (WC()->cart->get_cart() as $key => $item) {
            if (dd_tour_is_tour($item['product_id'])) {
                WC()->cart->remove_cart_item($key);
                $GLOBALS['tm_reserva_anterior'][] = $key;
            }
        }
    }
    return $passed;
}, 5, 2);
add_filter('woocommerce_add_to_cart_validation', function ($passed) {
    if (!$passed && !empty($GLOBALS['tm_reserva_anterior'])) {
        foreach ($GLOBALS['tm_reserva_anterior'] as $key) {
            WC()->cart->restore_cart_item($key);
        }
    }
    unset($GLOBALS['tm_reserva_anterior']);
    return $passed;
}, 99);

add_filter('woocommerce_add_to_cart_validation', function ($passed, $product_id, $quantity) {
    if (!dd_tour_is_tour($product_id)) {
        return $passed;
    }
    $date = isset($_POST['dd_tour_date']) ? sanitize_text_field(wp_unslash($_POST['dd_tour_date'])) : '';
    if ($date === '') {
        wc_add_notice(__('Elige una fecha para el tour antes de reservar.', 'tourmantequilla'), 'error');
        return false;
    }
    $entry = dd_tour_find_future_entry($product_id, $date);
    if (!$entry) {
        wc_add_notice(__('Esa fecha ya no está disponible. Elige otra.', 'tourmantequilla'), 'error');
        return false;
    }
    $remaining = dd_tour_remaining($entry);
    if ($remaining <= 0) {
        wc_add_notice(__('Esa fecha está agotada. Elige otra.', 'tourmantequilla'), 'error');
        return false;
    }
    $in_cart = dd_tour_qty_in_cart($product_id, $date);
    if (($in_cart + (int) $quantity) > $remaining) {
        wc_add_notice(sprintf(
            _n('Solo queda %d cupo para esa fecha.', 'Solo quedan %d cupos para esa fecha.', $remaining, 'tourmantequilla'),
            $remaining
        ), 'error');
        return false;
    }
    return $passed;
}, 10, 3);

add_filter('woocommerce_add_cart_item_data', function ($cart_item_data, $product_id) {
    if (dd_tour_is_tour($product_id) && !empty($_POST['dd_tour_date'])) {
        $date = sanitize_text_field(wp_unslash($_POST['dd_tour_date']));
        $cart_item_data['dd_tour_date'] = $date;
        // clave por fecha → cada fecha es una línea distinta en el carrito
        $cart_item_data['unique_key'] = md5('dd_tour_' . $product_id . '_' . $date);
    }
    return $cart_item_data;
}, 10, 2);

/**
 * Re-validación al cargar el carrito de la sesión: si el cupo de una fecha se agotó
 * mientras el cliente tenía el ítem en el carrito, se ajusta o se quita.
 */
add_action('woocommerce_check_cart_items', function () {
    foreach (WC()->cart->get_cart() as $key => $item) {
        if (empty($item['dd_tour_date'])) {
            continue;
        }
        $entry = dd_tour_find_future_entry($item['product_id'], $item['dd_tour_date']);
        $rem = $entry ? dd_tour_remaining($entry) : 0;
        if ($rem <= 0) {
            WC()->cart->remove_cart_item($key);
            wc_add_notice(__('Una de las fechas de tu carrito se agotó y la quitamos.', 'tourmantequilla'), 'error');
        } elseif ((int) $item['quantity'] > $rem) {
            WC()->cart->set_quantity($key, $rem);
            wc_add_notice(sprintf(
                _n('Solo queda %d cupo para esa fecha; ajustamos tu reserva.', 'Solo quedan %d cupos para esa fecha; ajustamos tu reserva.', $rem, 'tourmantequilla'),
                $rem
            ), 'error');
        }
    }
});

add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {
    if (!empty($cart_item['dd_tour_date'])) {
        $item_data[] = array(
            'key' => __('Fecha del tour', 'tourmantequilla'),
            'value' => tm_fecha_larga($cart_item['dd_tour_date']),
        );
    }
    return $item_data;
}, 10, 2);

add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    if (!empty($values['dd_tour_date'])) {
        // visible en admin del pedido y en correos
        $item->add_meta_data(__('Fecha del tour', 'tourmantequilla'), tm_fecha_larga($values['dd_tour_date']), true);
        // oculto, para el conteo de cupo
        $item->add_meta_data('_dd_tour_date', $values['dd_tour_date'], true);
    }
}, 10, 4);

/* ── Ciclo de cupo ─────────────────────────────────────────────────────────
 * Reserva (booked+) al pagar/confirmar; libera (booked-) al cancelar/reembolsar.
 * Idempotente por pedido (_dd_tour_counted). El read-modify-write de _tour_dates
 * va bajo un lock MySQL (GET_LOCK) para serializar reservas concurrentes.
 */

/** Ejecuta $fn con un lock MySQL por producto. Devuelve el retorno de $fn. */
function dd_tour_with_lock($product_id, $fn)
{
    global $wpdb;
    $name = 'dd_tour_' . (int) $product_id;
    $got = $wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, %d)', $name, 5));
    try {
        return $fn();
    } finally {
        if ((string) $got === '1') {
            $wpdb->query($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $name));
        }
    }
}

/**
 * Ajusta el `booked` de una fecha en +/-$delta (lock + lectura fresca de BD).
 * Devuelve true si al reservar quedó por encima del cupo (sobrecupo).
 */
function dd_tour_adjust_booked($product_id, $date, $delta)
{
    return dd_tour_with_lock($product_id, function () use ($product_id, $date, $delta) {
        wp_cache_delete($product_id, 'post_meta'); // evita valor cacheado stale bajo el lock
        $dates = dd_tour_get_dates($product_id);
        $oversold = false;
        $found = false;
        foreach ($dates as &$e) {
            if (isset($e['date']) && $e['date'] === $date) {
                $found = true;
                $cupo = isset($e['cupo']) ? (int) $e['cupo'] : 0;
                $booked = (isset($e['booked']) ? (int) $e['booked'] : 0) + $delta;
                if ($booked < 0) {
                    $booked = 0;
                }
                if ($delta > 0 && $booked > $cupo) {
                    $oversold = true;
                }
                $e['booked'] = $booked;
                break;
            }
        }
        unset($e);
        if ($found) {
            update_post_meta($product_id, '_tour_dates', $dates);
            update_post_meta($product_id, '_tour_next_date', dd_tour_next_date($product_id));
        }
        return $oversold;
    });
}

/** Cuenta ($sign=+1) o libera ($sign=-1) el cupo de todas las fechas del pedido. */
function dd_tour_count_order($order_id, $sign)
{
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }
    $counted = $order->get_meta('_dd_tour_counted') === 'yes';
    if ($sign > 0 && $counted) {
        return; // ya reservado → no doble-contar
    }
    if ($sign < 0 && !$counted) {
        return; // no estaba reservado → nada que liberar
    }
    $touched = false;
    foreach ($order->get_items() as $item) {
        $date = $item->get_meta('_dd_tour_date');
        if (!$date) {
            continue;
        }
        $touched = true;
        $oversold = dd_tour_adjust_booked($item->get_product_id(), $date, $sign * (int) $item->get_quantity());
        if ($sign > 0 && $oversold) {
            $order->add_order_note(sprintf(
                __('⚠ Sobrecupo en "%1$s" para %2$s: el cupo quedó excedido. Revisar manualmente.', 'tourmantequilla'),
                $item->get_name(),
                tm_fecha_larga($date)
            ));
        }
    }
    if ($touched) {
        $order->update_meta_data('_dd_tour_counted', $sign > 0 ? 'yes' : 'no');
        $order->save();
    }
}

// Reservar cupo cuando el pedido queda pagado/confirmado.
foreach (array('processing', 'completed', 'on-hold') as $dd_st) {
    add_action('woocommerce_order_status_' . $dd_st, function ($order_id) {
        dd_tour_count_order($order_id, 1);
    });
}
// Liberar cupo si el pedido se cancela / reembolsa / falla.
foreach (array('cancelled', 'refunded', 'failed') as $dd_st) {
    add_action('woocommerce_order_status_' . $dd_st, function ($order_id) {
        dd_tour_count_order($order_id, -1);
    });
}

/* ── Textos y flujo de compra ──────────────────────────────────────────── */

function dd_tour_add_to_cart_text($text = '', $product = null)
{
    if ($product && dd_tour_is_tour($product->get_id())) {
        return __('Reservar', 'tourmantequilla');
    }
    return $text;
}
add_filter('woocommerce_product_single_add_to_cart_text', 'dd_tour_add_to_cart_text', 10, 2);
add_filter('woocommerce_product_add_to_cart_text', 'dd_tour_add_to_cart_text', 10, 2);

// Reservar = ir directo al checkout.
add_filter('woocommerce_add_to_cart_redirect', function () {
    return wc_get_checkout_url();
});

/** ¿El carrito tiene SOLO tours (reservas, sin envío)? */
function dd_cart_is_tour_only()
{
    if (!function_exists('WC') || !WC()->cart) {
        return false;
    }
    $items = WC()->cart->get_cart();
    if (empty($items)) {
        return false;
    }
    foreach ($items as $item) {
        $pid = !empty($item['product_id']) ? $item['product_id'] : 0;
        if (!$pid || !dd_tour_is_tour($pid)) {
            return false;
        }
    }
    return true;
}
