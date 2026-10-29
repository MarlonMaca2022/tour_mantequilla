<?php
/**
 * Correos de WooCommerce con la voz y los colores del tour.
 * - Diseño: colores de la landing (no el morado de Woo), encabezado con atardecer y
 *   pie con las 4 casas. Va en código y no como ajuste en la BD: la fuente es el repo.
 * - Textos: asunto, título e introducción de cada correo hablan de "reserva", no de "pedido".
 *   Las introducciones de WooCommerce 11 (email improvements) no tienen traducción es_CO
 *   y salían en inglés; se reemplazan por string original solo mientras se arma un correo.
 * El encabezado sale de tema/gen_correo_encabezado.py.
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ── Diseño ────────────────────────────────────────────────────────────── */

/**
 * Remitente del tour (no de Softux ni de una sola casa). Sale por SMTP de Hostinger con
 * FluentSMTP; tourmantequilla.com tiene SPF y DKIM de Hostinger. Las respuestas de los
 * clientes van a quien atiende las reservas.
 */
define('TM_CORREO_REMITENTE', 'reservas@tourmantequilla.com');
define('TM_CORREO_RESPUESTA', 'julio@softux.co');

$tm_correo_opciones = array(
    'woocommerce_email_from_name' => 'Tour de la Mantequilla',
    'woocommerce_email_from_address' => TM_CORREO_REMITENTE,
    'woocommerce_email_reply_to_enabled' => 'yes',
    'woocommerce_email_reply_to_name' => 'Tour de la Mantequilla',
    'woocommerce_email_reply_to_address' => TM_CORREO_RESPUESTA,
    'woocommerce_email_base_color' => '#A3231B',            // --corredor: enlaces y botones
    'woocommerce_email_background_color' => '#F1F3F6',      // --gris-fondo
    'woocommerce_email_body_background_color' => '#FFFFFF',
    'woocommerce_email_text_color' => '#1E2B45',            // --montana
    'woocommerce_email_footer_text_color' => '#5E6F8C',     // --bruma
    'woocommerce_email_header_alignment' => 'left',
    'woocommerce_email_header_image_width' => '600',
    'woocommerce_email_auto_sync_with_theme' => 'no',
);
// pre_transient_: la vista previa de Ajustes → Correos lee transients antes que las opciones.
foreach ($tm_correo_opciones as $opcion => $valor) {
    foreach (array('pre_option_', 'pre_transient_') as $tm_filtro) {
        add_filter($tm_filtro . $opcion, function () use ($valor) {
            return $valor;
        });
    }
}

function tm_correo_encabezado()
{
    $ruta = '/assets/img/correo/encabezado.png';
    $archivo = get_stylesheet_directory() . $ruta;
    return file_exists($archivo) ? get_stylesheet_directory_uri() . $ruta . '?v=' . filemtime($archivo) : false;
}
add_filter('pre_option_woocommerce_email_header_image', 'tm_correo_encabezado');
add_filter('pre_transient_woocommerce_email_header_image', 'tm_correo_encabezado');

/** Pie: el tour, las 4 casas con el mismo peso (orden de la landing) y WhatsApp. */
add_filter('woocommerce_email_footer_text', function () {
    $txt = '<strong>Tour de la Mantequilla</strong> · Armenia Mantequilla, Antioquia<br />'
        . 'Atardeceres · Café Cabras · Bonne Nature · Finca Mirador La Florida';
    $wa = tm_whatsapp_url('Hola, tengo una pregunta sobre mi reserva del Tour de la Mantequilla.');
    if ($wa !== '') {
        $txt .= '<br /><a href="' . esc_url($wa) . '">¿Dudas? Escríbenos por WhatsApp</a>';
    }
    return $txt;
});

add_filter('woocommerce_email_styles', function ($css) {
    return $css . '
#template_header_image{padding:0}
#template_header_image img{display:block;width:600px;max-width:100%;height:auto}
h1,h2{font-family:Alegreya,Georgia,"Times New Roman",serif;letter-spacing:0}
h2.email-order-detail-heading span{font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif}
h1{font-size:34px;font-weight:800;line-height:115%}
h2{font-size:22px;font-weight:800}
#body_content a{color:#A3231B;font-weight:bold}
.tm-correo-dia{background:#FBF4E6;border-left:4px solid #E9A23B;border-radius:6px;padding:20px 24px;margin:0 0 32px}
.tm-correo-dia h2{margin:0 0 12px}
.tm-correo-dia p{margin:0 0 8px}
.tm-correo-dia .tm-correo-wa{display:inline-block;margin-top:8px;padding:12px 20px;border-radius:999px;background:#1E7A45;color:#FFFFFF !important;text-decoration:none}
#template_footer #credit a{color:#5E6F8C}
';
});

// El producto no tiene foto propia y la miniatura salía rota.
add_filter('woocommerce_email_order_items_args', function ($args) {
    $args['show_image'] = false;
    return $args;
});

/* ── Textos ────────────────────────────────────────────────────────────── */

/** Fecha y personas de la reserva (primera línea con fecha), para asuntos y la caja "Tu día". */
function tm_correo_reserva($order)
{
    if (!$order instanceof WC_Order) {
        return null;
    }
    foreach ($order->get_items() as $item) {
        $date = $item->get_meta('_dd_tour_date');
        if ($date) {
            $n = (int) $item->get_quantity();
            $fecha = tm_fecha_larga($date);
            return array(
                'fecha' => $fecha,
                'fecha_min' => mb_strtolower(mb_substr($fecha, 0, 1)) . mb_substr($fecha, 1),
                'personas' => sprintf(_n('%d persona', '%d personas', $n, 'tourmantequilla'), $n),
            );
        }
    }
    return null;
}

/**
 * Por correo: asunto con fecha, asunto sin fecha y título. %1$s = número,
 * %2$s = fecha ("sábado 5 de diciembre"), %3$s = personas. El asunto sin fecha cubre los
 * pedidos sin tour y la vista previa de WooCommerce (pedido de ejemplo): así nunca cae al
 * texto por defecto, que en WooCommerce 11 a veces viene en inglés.
 */
function tm_correo_textos()
{
    return array(
        'customer_processing_order' => array('Reserva confirmada: %2$s · Tour de la Mantequilla', 'Reserva confirmada · Tour de la Mantequilla', '¡Tu reserva está confirmada!'),
        'customer_on_hold_order' => array('Recibimos tu reserva para el %2$s', 'Recibimos tu reserva · Tour de la Mantequilla', 'Recibimos tu reserva'),
        'customer_completed_order' => array('Tu reserva del %2$s está completa', 'Tu reserva del Tour de la Mantequilla está completa', 'Tu reserva está completa'),
        'customer_cancelled_order' => array('Tu reserva #%1$s fue cancelada', 'Tu reserva #%1$s fue cancelada', 'Tu reserva fue cancelada'),
        'customer_failed_order' => array('Tu pago no se completó (reserva #%1$s)', 'Tu pago no se completó (reserva #%1$s)', 'Tu pago no se completó'),
        'customer_refunded_order' => array('Reembolso de tu reserva #%1$s', 'Reembolso de tu reserva #%1$s', 'Reembolso de tu reserva'),
        'customer_note' => array('Una nota sobre tu reserva #%1$s', 'Una nota sobre tu reserva #%1$s', 'Una nota sobre tu reserva'),
        'new_order' => array('Nueva reserva #%1$s: %3$s · %2$s', 'Nueva reserva #%1$s', 'Nueva reserva #%1$s'),
        'cancelled_order' => array('Reserva cancelada #%1$s: %3$s · %2$s', 'Reserva cancelada #%1$s', 'Reserva cancelada #%1$s'),
        'failed_order' => array('Pago fallido #%1$s: %3$s · %2$s', 'Pago fallido #%1$s', 'Pago fallido #%1$s'),
    );
}

foreach (tm_correo_textos() as $tm_id => $tm_t) {
    add_filter('woocommerce_email_subject_' . $tm_id, function ($texto, $order) use ($tm_t) {
        if (!$order instanceof WC_Order) {
            return $texto;
        }
        $r = tm_correo_reserva($order);
        return $r ? sprintf($tm_t[0], $order->get_order_number(), $r['fecha_min'], $r['personas']) : sprintf($tm_t[1], $order->get_order_number());
    }, 20, 2);
    add_filter('woocommerce_email_heading_' . $tm_id, function ($texto, $order) use ($tm_t) {
        return $order instanceof WC_Order ? sprintf($tm_t[2], $order->get_order_number()) : $texto;
    }, 20, 2);
}

// Correos de cuenta (no dependen de un pedido): asunto y título fijos.
$tm_correo_cuenta = array(
    'customer_reset_password' => array('Restablece tu contraseña · Tour de la Mantequilla', 'Restablece tu contraseña'),
    'customer_new_account' => array('Tu cuenta en el Tour de la Mantequilla', 'Tu cuenta quedó creada'),
);
foreach ($tm_correo_cuenta as $tm_id => $tm_t) {
    add_filter('woocommerce_email_subject_' . $tm_id, function () use ($tm_t) {
        return $tm_t[0];
    }, 20);
    add_filter('woocommerce_email_heading_' . $tm_id, function () use ($tm_t) {
        return $tm_t[1];
    }, 20);
}

/** Introducciones: string original de WooCommerce 11 → texto del tour. */
function tm_correo_gettext($traducido, $texto, $dominio)
{
    static $mapa = array(
        // comunes
        'Order #%s' => 'Reserva #%s',
        'Billing address' => 'Datos de contacto',
        // confirmada (pago aprobado)
        'Just to let you know &mdash; we’ve received your order, and it is now being processed.' => 'Recibimos tu pago y tu cupo quedó reservado. ¡Nos vemos en la montaña!',
        'Here’s a reminder of what you’ve ordered:' => 'Este es el resumen de tu reserva:',
        // en espera
        'We’ve received your order and it’s currently on hold until we can confirm your payment has been processed.' => 'Recibimos tu reserva y apartamos tu cupo. Te confirmamos por aquí apenas se acredite el pago.',
        // completada
        'We have finished processing your order.' => 'Tu reserva del Tour de la Mantequilla está completa.',
        // cancelada (cliente)
        'We’re getting in touch to let you know that your order #%1$s has been cancelled.' => 'Tu reserva #%1$s fue cancelada, así que ese cupo ya no está apartado a tu nombre. Si fue un error o quieres otra fecha, puedes reservar de nuevo en tourmantequilla.com.',
        // fallida (cliente)
        "Unfortunately, we couldn't complete your order due to an issue with your payment method." => 'El pago no se completó, así que tu reserva no quedó hecha.',
        "If you'd like to continue with your purchase, please return to %s and try a different method of payment." => 'Si quieres seguir, reserva de nuevo en tourmantequilla.com. Puedes intentar con otro medio de pago.',
        'Your order details are as follows:' => 'Estos eran los datos de la reserva:',
        // reembolso
        'Your order from %s has been refunded.' => 'Te devolvimos el dinero de tu reserva en %s.',
        'Your order from %s has been partially refunded.' => 'Te devolvimos parte del dinero de tu reserva en %s.',
        // nota
        'The following note has been added to your order:' => 'Te dejamos esta nota sobre tu reserva:',
        'As a reminder, here are your order details:' => 'Y este es el resumen de tu reserva:',
        // cuenta
        'If you didn’t make this request, just ignore this email. If you’d like to proceed, reset your password via the link below:' => 'Si no lo pediste tú, ignora este correo. Si quieres seguir, restablece tu contraseña con este enlace:',
        'Thanks for creating an account on %s. Here’s a copy of your user details.' => 'Gracias por crear tu cuenta en %s. Estos son tus datos de usuario.',
        'You can access your account area to view orders, change your password, and more via the link below:' => 'En tu cuenta puedes ver tus reservas, cambiar tu contraseña y más:',
        // admin
        'You’ve received a new order from %s:' => 'Nueva reserva a nombre de %s',
        'We’re getting in touch to let you know that order #%1$s from %2$s has been cancelled.' => 'La reserva #%1$s de %2$s fue cancelada.',
        'Unfortunately, the payment for order #%1$s from %2$s has failed. The order was as follows:' => 'El pago de la reserva #%1$s de %2$s no se completó. Estos eran los datos:',
    );
    return ($dominio === 'woocommerce' && isset($mapa[$texto])) ? $mapa[$texto] : $traducido;
}
add_action('woocommerce_email_header', function () {
    add_filter('gettext', 'tm_correo_gettext', 999, 3);
}, 1);
add_action('woocommerce_email_footer', function () {
    remove_filter('gettext', 'tm_correo_gettext', 999);
}, 99);

add_filter('woocommerce_email_order_details_heading', function ($titulo, $order, $email) {
    return ($email && $email->is_customer_email()) ? __('Tu reserva', 'tourmantequilla') : __('Resumen de la reserva', 'tourmantequilla');
}, 10, 3);

/**
 * Cierre de cada correo (el "Thanks again!…" de Woo salía en inglés). Al cliente: a quién
 * escribirle; al equipo: nada (ni "Enhorabuena por la venta" ni el anuncio de la app).
 */
add_action('woocommerce_email', function ($mailer) {
    if (isset($mailer->emails['WC_Email_New_Order'])) {
        remove_action('woocommerce_email_footer', array($mailer->emails['WC_Email_New_Order'], 'mobile_messaging'), 9);
    }
});
foreach (array_keys(tm_correo_textos()) as $tm_id) {
    add_filter('woocommerce_email_additional_content_' . $tm_id, function ($texto, $order, $email) {
        if (!$email || !$email->is_customer_email()) {
            return '';
        }
        return sprintf(__('¿Tienes dudas sobre tu reserva? Responde este correo o escríbenos a %s.', 'tourmantequilla'), TM_CORREO_RESPUESTA);
    }, 20, 3);
}

/**
 * Caja "Tu día en Armenia Mantequilla" arriba del resumen: fecha, personas, recogida y
 * WhatsApp. Solo en los correos al cliente de una reserva vigente.
 */
add_action('woocommerce_email_order_details', function ($order, $sent_to_admin, $plain_text, $email) {
    $correos = array('customer_processing_order', 'customer_on_hold_order', 'customer_completed_order');
    if ($sent_to_admin || $plain_text || !$email || !in_array($email->id, $correos, true)) {
        return;
    }
    $r = tm_correo_reserva($order);
    if (!$r) {
        return;
    }
    echo '<div class="tm-correo-dia"><h2>' . esc_html__('Tu día en Armenia Mantequilla', 'tourmantequilla') . '</h2>'
        . '<p><strong>' . esc_html($r['fecha']) . '</strong> · ' . esc_html($r['personas']) . '</p>';
    $recogida = tm_texto_recogida();
    if ($recogida !== '') {
        echo '<p>' . esc_html($recogida) . '</p>';
    }
    $wa = tm_whatsapp_url(sprintf('Hola, hice la reserva #%s del Tour de la Mantequilla.', $order->get_order_number()));
    if ($wa !== '') {
        echo '<a class="tm-correo-wa" href="' . esc_url($wa) . '">' . esc_html__('Escríbenos por WhatsApp', 'tourmantequilla') . '</a>';
    }
    echo '</div>';
}, 5, 4);
