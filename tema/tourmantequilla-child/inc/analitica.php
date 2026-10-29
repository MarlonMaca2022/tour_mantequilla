<?php
/**
 * Google Analytics 4 en todas las páginas (landing, checkout, gracias) vía wp_head.
 * Va aquí y no en el header.php de Hello Elementor: la landing arma su propio <head>
 * (templates/landing-tour.php) y el tema padre se pisa al actualizarse.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('TM_GA4_ID', 'G-5T8D2X9YHT');

add_action('wp_head', function () {
    if (is_customize_preview()) {
        return;
    }
    $id = esc_js(TM_GA4_ID);
    echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_attr(TM_GA4_ID) . '"></script>' . "\n"
        . "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{$id}');</script>\n";
}, 1);

/**
 * Evento `purchase` de GA4 para un pedido: valor real cobrado (con descuento), COP, cupón y
 * el tour como ítem con la fecha en item_variant. Devuelve el <script> sin efectos.
 */
function tm_ga4_purchase_script($order)
{
    $items = array();
    foreach ($order->get_items() as $item) {
        $date = $item->get_meta('_dd_tour_date');
        $items[] = array_filter(array(
            'item_id' => (string) $item->get_product_id(),
            'item_name' => $item->get_name(),
            'item_variant' => $date ? tm_fecha_larga($date) : null,
            'price' => (float) $order->get_item_total($item, false, true),
            'quantity' => (int) $item->get_quantity(),
        ), function ($v) {
            return $v !== null;
        });
    }
    $datos = array_filter(array(
        'transaction_id' => (string) $order->get_order_number(),
        'value' => (float) $order->get_total(),
        'currency' => $order->get_currency(),
        'coupon' => implode(',', $order->get_coupon_codes()),
        'items' => $items,
    ), function ($v) {
        return $v !== '';
    });
    return "<script>if(typeof gtag==='function'){gtag('event','purchase'," . wp_json_encode($datos) . ");}</script>\n";
}

/**
 * Se envía en la página de gracias solo con el pago aprobado (processing/completed) y una
 * vez por pedido. Con Wompi el cliente suele llegar con el pedido "pendiente": la página se
 * recarga sola (inc/checkout.php) y el evento sale cuando el banco aprueba.
 */
add_action('woocommerce_thankyou', function ($order_id) {
    $order = wc_get_order($order_id);
    if (!$order || !$order->has_status(array('processing', 'completed')) || $order->get_meta('_tm_ga4_purchase')) {
        return;
    }
    echo tm_ga4_purchase_script($order); // phpcs:ignore WordPress.Security.EscapeOutput -- JSON de wp_json_encode
    $order->update_meta_data('_tm_ga4_purchase', current_time('mysql'));
    $order->save_meta_data();
}, 20);
