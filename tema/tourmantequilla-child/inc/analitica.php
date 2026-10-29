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
