<?php
/**
 * Tour Mantequilla — child theme de Hello Elementor.
 * Motor de reservas: inc/reservas.php (copia del de Dalia Dorada).
 * Landing: templates/landing-tour.php + inc/landing.php.
 */

if (!defined('ABSPATH')) {
    exit;
}

/** Slug estable del producto-tour (no depende del ID, que cambia entre entornos). */
define('TM_TOUR_SLUG', 'tour-mantequilla');

add_action('wp_enqueue_scripts', function () {
    $dir = get_stylesheet_directory();
    $uri = get_stylesheet_directory_uri();
    wp_enqueue_style('hello-elementor-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('tm-child', $uri . '/style.css', array('hello-elementor-parent-style'), filemtime($dir . '/style.css'));
});

if (class_exists('WooCommerce') || in_array('woocommerce/woocommerce.php', (array) get_option('active_plugins', array()), true)) {
    require_once get_stylesheet_directory() . '/inc/reservas.php';
    require_once get_stylesheet_directory() . '/inc/checkout.php';
}
require_once get_stylesheet_directory() . '/inc/landing.php';
