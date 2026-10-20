<?php
/**
 * Checkout y carrito CLÁSICOS de WooCommerce (shortcodes [woocommerce_checkout] /
 * [woocommerce_cart], no bloques): igual que Dalia Dorada y lo más compatible con
 * pasarelas de terceros (OnePay). Base: Dalia functions.php, sección CHECKOUT.
 * Es presentación/UX: no toca la lógica de fechas ni de cupo (inc/reservas.php).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Campos mínimos. En carrito de solo-tour (una reserva no se envía) quedan
 * Nombre y apellido / Teléfono / Correo / País.
 * ⛔ Jhon factura todas las inscripciones: confirmar si necesita cédula o NIT (brief §Pagos).
 */
add_filter('woocommerce_checkout_fields', function ($fields) {
    if (empty($fields['billing'])) {
        return $fields;
    }
    $b = &$fields['billing'];
    unset($b['billing_postcode'], $b['billing_company'], $b['billing_address_2'], $b['billing_last_name']);

    if (isset($b['billing_first_name'])) {
        $b['billing_first_name']['label'] = __('Nombre y apellido', 'tourmantequilla');
        $b['billing_first_name']['class'] = array('form-row-wide');
        $b['billing_first_name']['priority'] = 10;
        $b['billing_first_name']['autocomplete'] = 'name';
    }
    if (isset($b['billing_phone'])) {
        $b['billing_phone']['label'] = __('Celular (WhatsApp)', 'tourmantequilla');
        $b['billing_phone']['priority'] = 20;
        $b['billing_phone']['required'] = true;
        $b['billing_phone']['class'] = array('form-row-wide');
    }
    if (isset($b['billing_email'])) {
        $b['billing_email']['description'] = __('Aquí te llega la confirmación de la reserva.', 'tourmantequilla');
        $b['billing_email']['priority'] = 30;
        $b['billing_email']['class'] = array('form-row-wide');
    }
    if (isset($b['billing_country'])) {
        $b['billing_country']['priority'] = 40;
    }
    if (dd_cart_is_tour_only()) {
        unset($b['billing_state'], $b['billing_city'], $b['billing_address_1']);
    }
    if (isset($fields['order']['order_comments'])) {
        $fields['order']['order_comments']['label'] = __('¿Algo que debamos saber?', 'tourmantequilla');
        $fields['order']['order_comments']['placeholder'] = __('Alergias, dieta o necesidades de movilidad de alguien del grupo.', 'tourmantequilla');
    }
    return $fields;
}, 20);

/**
 * Teléfono obligatorio y antes del correo. En WooCommerce 11 el teléfono es un campo
 * de "locale": address-i18n.js le reescribe label/required/orden al cargar el país,
 * así que se fija aquí (en código, no como ajuste en la BD).
 */
add_filter('pre_option_woocommerce_checkout_phone_field', function () {
    return 'required';
});
add_filter('woocommerce_get_country_locale_default', function ($locale) {
    $locale['phone'] = array_merge(isset($locale['phone']) ? $locale['phone'] : array(), array(
        'label' => __('Celular (WhatsApp)', 'tourmantequilla'),
        'required' => true,
        'priority' => 20,
    ));
    return $locale;
});

/**
 * Títulos del checkout con la voz del tour (la traducción es_CO dice "Detalles de
 * facturación", etc.). Clave = string original de WooCommerce. Solo en checkout.
 */
add_action('template_redirect', function () {
    if (!function_exists('is_checkout') || !is_checkout()) {
        return;
    }
    add_filter('gettext', function ($translated, $text, $domain) {
        static $map = array(
            'Billing details' => 'Tus datos',
            'Additional information' => 'Para el día del tour',
            'Your order' => 'Tu reserva',
            'Email address' => 'Correo electrónico',
        );
        return ($domain === 'woocommerce' && isset($map[$text])) ? $map[$text] : $translated;
    }, 999, 3);
});

/** Botón: acción + monto ("Confirmar reserva · $880.000"). */
add_filter('woocommerce_order_button_text', function ($text) {
    if (!dd_cart_is_tour_only()) {
        return $text;
    }
    $total = (function_exists('WC') && WC()->cart) ? trim(wp_strip_all_tags(html_entity_decode(WC()->cart->get_total()))) : '';
    return $total !== '' ? sprintf(__('Confirmar reserva · %s', 'tourmantequilla'), $total) : __('Confirmar reserva', 'tourmantequilla');
});

/**
 * Texto de privacidad: WooCommerce lo guarda en inglés en la BD al instalarse y no
 * pasa por gettext. Se reemplaza el texto final sin tocar el enlace (igual que Dalia).
 * ⛔ El texto legal definitivo (datos personales, cancelación) está pendiente (#28).
 */
add_filter('woocommerce_get_privacy_policy_text', function ($text) {
    return str_ireplace(
        'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our',
        'Usamos tus datos para gestionar tu reserva y contactarte sobre el tour, como se explica en nuestra',
        $text
    );
}, 20);

// Sin cupones: el tour tiene un solo precio y el aviso "¿Tienes un cupón?" solo distrae.
add_filter('woocommerce_coupons_enabled', '__return_false');

/** Confianza bajo el botón: solo cosas verdaderas del flujo (sin nombrar medios de pago aún). */
add_action('woocommerce_review_order_after_submit', function () {
    echo '<ul class="tm-checkout-confianza">'
        . '<li>' . esc_html__('Pago en línea seguro', 'tourmantequilla') . '</li>'
        . '<li>' . esc_html__('Confirmación y comprobante al correo', 'tourmantequilla') . '</li>'
        . '</ul>';
}, 20);

/** Encabezado del checkout: paso real + volver a las fechas. */
add_action('woocommerce_before_checkout_form', function () {
    echo '<header class="tm-paso">'
        . '<p class="tm-paso-eyebrow">' . esc_html__('Paso 2 de 2 · Datos y pago', 'tourmantequilla') . '</p>'
        . '<a class="tm-paso-volver" href="' . esc_url(home_url('/#fechas')) . '">' . esc_html__('← Cambiar fecha o personas', 'tourmantequilla') . '</a>'
        . '</header>';
}, 1);

/**
 * El carrito no es un paso del tour (reservar va directo al checkout). Si alguien
 * llega a /cart/ con un tour, lo mandamos al checkout; vacío, a las fechas.
 */
add_action('template_redirect', function () {
    if (!function_exists('is_cart') || !is_cart() || is_admin()) {
        return;
    }
    if (WC()->cart && WC()->cart->is_empty()) {
        wp_safe_redirect(home_url('/#fechas'));
        exit;
    }
    if (dd_cart_is_tour_only()) {
        wp_safe_redirect(wc_get_checkout_url());
        exit;
    }
});

// Checkout con carrito vacío → a las fechas (WooCommerce lo mandaría al carrito).
add_filter('woocommerce_checkout_redirect_empty_cart', '__return_false');
add_action('template_redirect', function () {
    if (function_exists('is_checkout') && is_checkout() && !is_wc_endpoint_url() && WC()->cart && WC()->cart->is_empty()) {
        wp_safe_redirect(home_url('/#fechas'));
        exit;
    }
});

/** Página de gracias: confirma QUÉ se reservó (fecha y personas) antes del detalle de Woo. */
add_filter('woocommerce_thankyou_order_received_text', function ($text, $order) {
    if (!$order) {
        return $text;
    }
    return sprintf(__('Listo, tu reserva está hecha. Te enviamos la confirmación a %s.', 'tourmantequilla'), esc_html($order->get_billing_email()));
}, 10, 2);

add_action('woocommerce_thankyou', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) {
        return;
    }
    $filas = array();
    foreach ($order->get_items() as $item) {
        $date = $item->get_meta('_dd_tour_date');
        if ($date) {
            $n = (int) $item->get_quantity();
            $filas[] = '<li><strong>' . esc_html(tm_fecha_larga($date)) . '</strong> · '
                . esc_html(sprintf(_n('%d persona', '%d personas', $n, 'tourmantequilla'), $n)) . '</li>';
        }
    }
    if (!$filas) {
        return;
    }
    echo '<section class="tm-gracias"><h2>' . esc_html__('Tu día en Armenia Mantequilla', 'tourmantequilla') . '</h2><ul>' . implode('', $filas) . '</ul>';
    // ⛔ Sitio y hora de recogida (#27): cuando estén, van aquí y en el correo.
    if (tm_ver_pendientes()) {
        echo '<p class="tm-falta">' . esc_html__('Pendiente: sitio y hora de recogida en Medellín y qué llevar (#27).', 'tourmantequilla') . '</p>';
    }
    $wa = tm_whatsapp_url(sprintf('Hola, hice la reserva #%s del Tour Mantequilla.', $order->get_order_number()));
    if ($wa !== '') {
        echo '<p><a href="' . esc_url($wa) . '" target="_blank" rel="noopener nofollow">' . esc_html__('¿Dudas? Escríbenos por WhatsApp', 'tourmantequilla') . '</a></p>';
    }
    echo '</section>';
}, 5);

/** Assets del checkout y la página de gracias: misma tipografía y color que la landing. */
add_action('wp_enqueue_scripts', function () {
    if (!function_exists('is_checkout') || !(is_checkout() || is_account_page())) {
        return;
    }
    $dir = get_stylesheet_directory();
    wp_enqueue_style('tm-fuentes', 'https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,700;0,800;1,700&family=Alegreya+Sans:wght@400;500;700&display=swap', array(), null);
    wp_enqueue_style('tm-checkout', get_stylesheet_directory_uri() . '/assets/css/checkout.css', array('tm-child'), filemtime($dir . '/assets/css/checkout.css'));
}, 20);
