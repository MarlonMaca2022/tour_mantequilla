<?php
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
<a class="saltar" href="#fechas">Saltar a las fechas</a>

<header class="cab">
  <div class="contenedor">
    <a class="marca" href="#inicio">Tour Mantequilla <span class="falta">nombre oficial</span></a>
    <nav aria-label="Principal">
      <?php if (tm_ver_pendientes()) : ?>
      <button class="toggle-falta" type="button" aria-pressed="false" title="Ocultar o mostrar los pendientes marcados en amarillo">Pendientes</button>
      <?php endif; ?>
      <a class="solo-desk" href="#recorrido">El recorrido</a>
      <a href="#fechas">Fechas</a>
      <a class="btn" href="#fechas" data-evento="cab_reservar_fecha">Reservar mi fecha</a>
    </nav>
  </div>
</header>

<main id="inicio">
  <!-- HERO -->
  <section class="hero oscuro" aria-labelledby="h1">
    <div class="foto">
      <img src="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-01-10-43-18.webp" srcset="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-01-10-43-18-w480.webp 480w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-01-10-43-18.webp 912w" sizes="(min-width: 900px) 45vw, 100vw" width="912" height="1368" fetchpriority="high" alt="Capas de montañas azules entre la bruma, vistas desde el mirador de La Florida">
      <span class="credito doc-pendiente">Foto: <span class="falta">autor</span></span>
    </div>
    <div class="copy">
      <h1 id="h1">Tu día en Armenia Mantequilla, de la primera taza al último sol</h1>
      <p class="sub">Te recogemos en Medellín para un día de café de origen, quesos artesanales y atardecer en la montaña. Grupos de 15.</p>
      <p class="valor">Incluye almuerzo, taller de café y taller de quesos <span class="falta">confirmar qué cubre el precio</span></p>
      <p class="precio"><strong><?php echo esc_html($precio); ?></strong><span>por persona <span class="falta">¿impuestos incluidos?</span></span></p>
      <p class="fechas-linea">Salidas en fines de semana de diciembre <span class="falta">las fechas se definen en el motor de reservas (#26)</span></p>
      <div class="acciones">
        <a class="btn" href="#fechas" data-evento="hero_reservar_fecha">Reservar mi fecha</a>
        <p class="seguro">Pago en línea · confirmación al correo</p>
        <a <?php echo tm_whatsapp_attrs('Hola, quiero recibir la información del Tour Mantequilla.', 'whatsapp_recibir_info', 'enlace-wa'); ?>>Recibe la información por WhatsApp</a>
      </div>
    </div>
  </section>

  <!-- DATOS CLAVE -->
  <ul class="claves" aria-label="Datos clave del tour">
    <li><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg><span class="k">Duración</span><span class="v">Todo el día, de 7:00 a. m. a 8:30 p. m.</span></li>
    <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg><span class="k">Salida</span><span class="v">Medellín o San Antonio de Prado, entre 7:00 y 8:00 a. m.</span></li>
    <li><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M9 3v4M15 3v4"/></svg><span class="k">Fechas</span><span class="v">Fines de semana de diciembre</span></li>
    <li><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="9" r="3"/><circle cx="17" cy="10" r="2.3"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6M15 20c0-2.5 1-4.5 3.5-4.5S22 17.5 22 20"/></svg><span class="k">Grupo</span><span class="v">15 personas</span></li>
  </ul>


  <!-- FRANJA: las cuatro casas -->
  <div class="franja" aria-hidden="true"><div class="pista"><span class="item">Finca Atardeceres<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Café Cabras<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Bonne Nature<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Finca Mirador La Florida<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Finca Atardeceres<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Café Cabras<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Bonne Nature<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Finca Mirador La Florida<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Finca Atardeceres<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Café Cabras<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Bonne Nature<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span><span class="item">Finca Mirador La Florida<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="5"/><path d="M12 1.5v3M12 19.5v3M1.5 12h3M19.5 12h3M4.6 4.6l2.1 2.1M17.3 17.3l2.1 2.1M4.6 19.4l2.1-2.1M17.3 6.7l2.1-2.1"/></svg></span></div></div>

  <!-- RECORRIDO -->
  <section class="seccion recorrido" id="recorrido" aria-labelledby="rec-t">
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
      <symbol id="nube-a" viewBox="0 0 200 90"><g fill="currentColor"><circle cx="55" cy="58" r="28"/><circle cx="95" cy="40" r="36"/><circle cx="140" cy="55" r="30"/><circle cx="170" cy="66" r="20"/><rect x="27" y="58" width="163" height="28" rx="14"/></g></symbol>
      <symbol id="nube-b" viewBox="0 0 160 70"><g fill="currentColor"><circle cx="45" cy="45" r="25"/><circle cx="85" cy="32" r="30"/><circle cx="120" cy="46" r="22"/><rect x="20" y="44" width="125" height="24" rx="12"/></g></symbol>
      <symbol id="ave" viewBox="0 0 24 10"><path d="M1 7 Q6 1 12 7 Q18 1 23 7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    </svg>
    <div class="cielo-pista" aria-hidden="true"><div class="cielo-dia">
      <div class="nubes lejos">
        <svg viewBox="0 0 200 90" style="left:6%;top:9%;width:170px"><use href="#nube-a"/></svg>
        <svg viewBox="0 0 160 70" style="left:62%;top:20%;width:130px"><use href="#nube-b"/></svg>
        <svg viewBox="0 0 200 90" style="left:30%;top:58%;width:150px"><use href="#nube-a"/></svg>
        <svg viewBox="0 0 160 70" style="left:80%;top:76%;width:120px"><use href="#nube-b"/></svg>
      </div>
      <div class="astro-sol"></div>
      <div class="nubes medio">
        <svg viewBox="0 0 160 70" style="left:-4%;top:30%;width:220px"><use href="#nube-b"/></svg>
        <svg viewBox="0 0 200 90" style="left:70%;top:6%;width:240px"><use href="#nube-a"/></svg>
        <svg viewBox="0 0 160 70" style="left:44%;top:46%;width:200px"><use href="#nube-b"/></svg>
        <svg viewBox="0 0 200 90" style="left:8%;top:84%;width:230px"><use href="#nube-a"/></svg>
      </div>
      <div class="nubes cerca">
        <svg viewBox="0 0 200 90" style="left:72%;top:36%;width:330px"><use href="#nube-a"/></svg>
        <svg viewBox="0 0 160 70" style="left:-10%;top:62%;width:300px"><use href="#nube-b"/></svg>
        <svg viewBox="0 0 200 90" style="left:58%;top:88%;width:340px"><use href="#nube-a"/></svg>
      </div>
      <svg class="bandada" viewBox="0 0 200 80"><use href="#ave" x="0" y="34" width="26" height="11"/><use href="#ave" x="34" y="18" width="22" height="9"/><use href="#ave" x="62" y="40" width="28" height="12"/><use href="#ave" x="98" y="10" width="20" height="8"/><use href="#ave" x="120" y="30" width="24" height="10"/><use href="#ave" x="156" y="22" width="18" height="8"/></svg>
      <svg class="bandada" viewBox="0 0 200 80"><use href="#ave" x="10" y="20" width="22" height="9"/><use href="#ave" x="44" y="38" width="28" height="12"/><use href="#ave" x="80" y="14" width="20" height="8"/><use href="#ave" x="112" y="34" width="26" height="11"/><use href="#ave" x="150" y="24" width="22" height="9"/></svg>
    </div></div>
    <div class="contenedor">
      <h2 id="rec-t">Lo que vas a vivir</h2>
      <p class="lead">Llegar a un pueblo no es lo mismo que conocerlo. Este día lo arman quienes viven ahí: de la mañana en el mirador a la tarde que cae en una finca cafetera. <span class="falta">Orden de paradas según Julio; la transcripción dice otro (pendiente #23)</span></p>
      <ol class="capitulos" role="list">
        <li class="capitulo" style="--c:var(--florida)" data-hora="Mañana">
          <div>
            <span class="num" aria-hidden="true">01</span>
            <h3>El mirador de la mañana</h3>
            <p class="quien"><i aria-hidden="true"></i>Finca Mirador La Florida</p>
            <div class="anfitrion"><span class="retrato" aria-hidden="true"></span><div><p class="nombre"><span class="falta">Nombre de quien recibe</span></p></div></div>
            <span class="falta">Foto del anfitrión y una frase suya (pendiente #25)</span>
            <p class="texto">Una casa antioqueña con huerta de hierbas aromáticas, bosque nativo sembrado y un mirador hacia las montañas del Suroeste y el Occidente, el mismo desde donde se ve salir el sol sobre el pueblo. Del horno de barro y leña salen arepas de yuca, pan de masa madre y pizza.</p>
            <span class="falta bloque">Hora de llegada y qué se come aquí en este tour (pendiente #3)</span>
          </div>
          <div class="medios">
            <img class="revela" src="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-03-10-44-36.webp" srcset="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-03-10-44-36-w480.webp 480w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-03-10-44-36-w960.webp 960w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-03-10-44-36.webp 1033w" sizes="(min-width: 900px) 45vw, 100vw" width="1033" height="1251" loading="lazy" alt="Dos aves en un comedero con fruta en el bosque de La Florida">
            <img class="inset revela" src="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-05-10-46-04.webp" srcset="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-05-10-46-04-w480.webp 480w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-05-10-46-04-w960.webp 960w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-05-10-46-04.webp 1067w" sizes="(min-width: 900px) 30vw, 40vw" width="1067" height="1600" loading="lazy" alt="Horno de barro encendido con leña">
          </div>
        </li>
        <li class="capitulo" style="--c:var(--cabras)" data-hora="Mediodía">
          <div>
            <span class="num" aria-hidden="true">02</span>
            <h3>El café que nació en un kiosco de la plaza</h3>
            <p class="quien"><i aria-hidden="true"></i>Café Cabras</p>
            <div class="anfitrion"><span class="retrato" aria-hidden="true"></span><div><p class="nombre">Caterine y Fernando</p><blockquote class="frase">«Como las cabras, subimos por terreno difícil y llegamos a donde otros no se atreven.»</blockquote></div></div>
            <span class="falta">Foto de Caterine y Fernando (pendiente #25)</span>
            <p class="texto">Te reciben con una copa de su licor artesanal de café. Almuerzas fiambres caseros, pruebas su café de origen preparado de varias maneras y cierras con un digestivo.</p>
            <span class="falta bloque">Verificar registro y venta del licor (pendiente #7). No hay foto del almuerzo (pendiente #21)</span>
          </div>
          <div class="medios">
            <img class="revela" src="<?php echo $a; ?>/fotos-web/cafe-cabras/cabras-02-chemex-en-sala.webp" srcset="<?php echo $a; ?>/fotos-web/cafe-cabras/cabras-02-chemex-en-sala-w480.webp 480w, <?php echo $a; ?>/fotos-web/cafe-cabras/cabras-02-chemex-en-sala-w960.webp 960w, <?php echo $a; ?>/fotos-web/cafe-cabras/cabras-02-chemex-en-sala.webp 1271w" sizes="(min-width: 900px) 45vw, 100vw" width="1271" height="1571" loading="lazy" alt="Chemex con filtro y personas al fondo en Café Cabras">
            <img class="inset revela" src="<?php echo $a; ?>/fotos-web/cafe-cabras/cabras-05-licor-botella-copas.webp" srcset="<?php echo $a; ?>/fotos-web/cafe-cabras/cabras-05-licor-botella-copas-w480.webp 480w, <?php echo $a; ?>/fotos-web/cafe-cabras/cabras-05-licor-botella-copas-w960.webp 960w, <?php echo $a; ?>/fotos-web/cafe-cabras/cabras-05-licor-botella-copas.webp 1440w" sizes="(min-width: 900px) 30vw, 40vw" width="1440" height="1801" loading="lazy" alt="Botella de licor de café Cabras con dos copas">
          </div>
        </li>
        <li class="capitulo" style="--c:var(--bonne)" data-hora="Tarde">
          <div>
            <span class="num" aria-hidden="true">03</span>
            <h3>La quesera que aprendió el oficio cerca de Grenoble</h3>
            <p class="quien"><i aria-hidden="true"></i>Bonne Nature</p>
            <div class="anfitrion"><span class="retrato" aria-hidden="true"></span><div><p class="nombre">Luz Marina Mejía</p></div></div>
            <span class="falta">Foto de Luz Marina y una frase suya (pendiente #25)</span>
            <p class="texto">Su familia vivió 30 años en Francia. Desde 2014 hace en su pueblo quesos de receta francesa con ají, achiote, pimientas y hierbas de la cocina colombiana. Aquí haces el taller de quesos.</p>
            <span class="falta bloque">Confirmar que «la quesería» del audio es Bonne Nature (pendiente #3)</span>
          </div>
          <div class="medios"><img class="revela" src="<?php echo $a; ?>/fotos-web/bonne-nature/bonne-nature-02-foto-general-quesos.webp" srcset="<?php echo $a; ?>/fotos-web/bonne-nature/bonne-nature-02-foto-general-quesos-w480.webp 480w, <?php echo $a; ?>/fotos-web/bonne-nature/bonne-nature-02-foto-general-quesos-w960.webp 960w, <?php echo $a; ?>/fotos-web/bonne-nature/bonne-nature-02-foto-general-quesos.webp 2000w" sizes="(min-width: 900px) 45vw, 100vw" width="2000" height="1333" loading="lazy" alt="Quesos artesanales de Bonne Nature sobre tablas de madera"></div>
        </li>
      </ol>
    </div>
  </section>

  <!-- ATARDECER: el momento cinematográfico -->
  <section class="atardecer oscuro" aria-labelledby="atd-t">
    <div class="escena">
      <div class="cielo cielo--tarde" aria-hidden="true"></div>
      <div class="cielo cielo--ocaso" aria-hidden="true"></div>
      <div class="cielo cielo--noche" aria-hidden="true"></div>
      <div class="estrellas" aria-hidden="true"></div>
      <svg class="cordillera" viewBox="0 0 1200 600" preserveAspectRatio="none" aria-hidden="true">
        <path class="c4" fill="#6E6A8E" d="M0 330 L90 300 L170 318 L260 262 L330 290 L420 236 L470 250 L540 190 L585 214 L640 168 L700 226 L780 208 L860 252 L940 230 L1030 276 L1110 254 L1200 280 V600 H0Z"/>
        <path class="c3" fill="#4B4C72" d="M0 380 L110 352 L200 372 L300 330 L390 356 L480 318 L560 346 L650 306 L740 338 L830 312 L920 348 L1010 326 L1100 360 L1200 340 V600 H0Z"/>
        <path class="c2" fill="#2F3657" d="M0 440 L120 410 L230 432 L340 398 L450 424 L560 392 L680 430 L800 404 L910 436 L1020 412 L1120 440 L1200 424 V600 H0Z"/>
        <path class="c1" fill="#17203A" d="M0 500 L150 470 L300 492 L420 462 L560 488 L700 458 L860 494 L1000 470 L1200 496 V600 H0Z"/>
      </svg>
      <div class="velo" aria-hidden="true"></div>
      <div class="sol" aria-hidden="true"></div>
      <div class="luna" aria-hidden="true"></div>
      <div class="nubes-escena" aria-hidden="true">
        <svg viewBox="0 0 200 90" style="left:4%;top:18%;width:min(30vw,260px)"><use href="#nube-a"/></svg>
        <svg viewBox="0 0 160 70" style="left:64%;top:4%;width:min(26vw,220px)"><use href="#nube-b"/></svg>
        <svg viewBox="0 0 160 70" style="left:38%;top:52%;width:min(22vw,190px)"><use href="#nube-b"/></svg>
      </div>
      <svg class="aves-escena" viewBox="0 0 200 80" aria-hidden="true"><use href="#ave" x="0" y="30" width="24" height="10"/><use href="#ave" x="36" y="16" width="20" height="8"/><use href="#ave" x="66" y="36" width="26" height="11"/><use href="#ave" x="104" y="12" width="18" height="8"/><use href="#ave" x="130" y="28" width="22" height="9"/></svg>
      <div class="velo-texto" aria-hidden="true"></div>
      <div class="copy">
        <span class="num" aria-hidden="true">04</span>
        <h2 id="atd-t">Y el día se va con el sol</h2>
        <p class="quien"><i aria-hidden="true"></i>Finca Atardeceres</p>
        <div class="anfitrion"><span class="retrato" aria-hidden="true"></span><div><p class="nombre"><span class="falta">Nombre de quien recibe</span></p></div></div>
        <p class="texto">Recorres los cultivos de café de la finca y ves caer la tarde.</p>
        <span class="falta">Foto real del atardecer en la finca (#15) reemplaza este cielo ilustrado. Foto y frase del anfitrión (#25). «Taller de tu…»: ¿turismo o tueste?</span>
        <a class="btn" href="#fechas" data-evento="atardecer_reservar_fecha">Reservar mi fecha</a>
      </div>
    </div>
  </section>

  <!-- FECHAS -->
  <section class="seccion gris" id="fechas" aria-labelledby="fec-t">
    <div class="contenedor">
      <h2 id="fec-t">Elige tu fecha</h2>
      <p class="lead">Cada fecha sale con un grupo de 15 personas.</p>
      <ol class="pasos" role="list"><li><b>1</b>Elige tu fecha</li><li><b>2</b>Paga en línea</li><li><b>3</b>Te recogemos en Medellín o San Antonio de Prado</li></ol>
      <div class="avisos"><?php if (function_exists('wc_print_notices')) { wc_print_notices(); } ?></div>
      <?php tm_render_fechas(); ?>
      <p class="grupos doc-pendiente">¿Son 6 o más? <a <?php echo tm_whatsapp_attrs('Hola, somos un grupo de 6 o más personas y queremos reservar el Tour Mantequilla.', 'whatsapp_grupo', ''); ?>>Escríbenos por WhatsApp</a> <span class="falta">solo si Jhon confirma que se manejan grupos</span></p>
      <ul class="confianza">
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>Pago en línea</li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>Confirmación y comprobante al correo</li>
        <li class="doc-pendiente"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12a8 8 0 1 0 3-6.2"/><path d="M4 4v4h4"/></svg><a href="#">Política de cancelación</a> <span class="falta">texto pendiente (#28)</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19l1.4-4.2A7.5 7.5 0 1 1 9.2 17.6Z"/></svg><a <?php echo tm_whatsapp_attrs('Hola, tengo una duda sobre el Tour Mantequilla.', 'whatsapp_dudas', ''); ?>>¿Dudas? Escríbenos</a></li>
      </ul>
    </div>
  </section>

  <!-- ALIADOS -->
  <section class="seccion" aria-labelledby="ali-t">
    <div class="contenedor">
      <h2 id="ali-t">Quiénes te reciben</h2>
      <p class="lead">Cuatro negocios de Armenia Mantequilla que se juntaron para armar este día.</p>
      <ul class="aliados">
        <li class="aliado" style="--c:var(--atardeceres)">
          <div class="placa"><img src="<?php echo $a; ?>/logos/atardeceres-w288.webp" width="288" height="99" loading="lazy" alt=""></div>
          <div><h3>Finca Atardeceres</h3><p>Finca cafetera en Armenia Mantequilla, a 1.750 m.s.n.m. <span class="falta">verificar altura</span> Aquí recorres los cultivos de café y termina el día.</p></div>
        </li>
        <li class="aliado" style="--c:var(--cabras)">
          <div class="placa"><img src="<?php echo $a; ?>/logos/cafe-cabras-cafe-w192.webp" width="192" height="192" loading="lazy" alt=""></div>
          <div><h3>Café Cabras</h3><p>Café de origen que nació en un kiosco de la plaza del pueblo. Caterine y Fernando hacen catas, talleres y encuentros alrededor de la taza.</p></div>
        </li>
        <li class="aliado" style="--c:var(--bonne)">
          <div class="placa"><img src="<?php echo $a; ?>/logos/bonne-nature-w192.webp" width="192" height="192" loading="lazy" alt=""></div>
          <div><h3>Bonne Nature</h3><p>Quesería de la familia Mejía Mejía. Luz Marina aprendió el oficio cerca de Grenoble y desde 2014 hace quesos colombo-franceses en su pueblo.</p></div>
        </li>
        <li class="aliado" style="--c:var(--florida)">
          <div class="placa"><img src="<?php echo $a; ?>/logos/la-florida-w192.webp" width="192" height="192" loading="lazy" alt=""></div>
          <div><h3>Finca Mirador La Florida</h3><p>Casa antioqueña con vista a las montañas del Suroeste y el Occidente, huerta de aromáticas, bosque nativo sembrado y horno de barro.</p></div>
        </li>
      </ul>
    </div>
  </section>

  <!-- INCLUYE -->
  <section class="seccion gris" aria-labelledby="inc-t">
    <div class="contenedor">
      <h2 id="inc-t">Qué incluye</h2>
      <p class="lead"><span class="falta">Sale de los textos de los aliados. Falta que Jhon confirme qué cubren los <?php echo esc_html($precio); ?></span></p>
      <div class="listas">
        <div class="si"><h3>Incluye</h3><ul>
          <li>Transporte ida y vuelta desde Medellín o San Antonio de Prado</li>
          <li>Mañana en el mirador de La Florida</li>
          <li>Copa de bienvenida, almuerzo de fiambres y digestivo en Café Cabras</li>
          <li>Taller de métodos de preparación de café</li>
          <li>Taller de quesos</li>
          <li>Recorrido por los cultivos de café y atardecer en Finca Atardeceres</li>
          <li><span class="falta">¿Comida del horno de leña?</span></li>
        </ul></div>
        <div class="no"><h3>No incluye</h3><ul>
          <li>Hospedaje: es un tour de un día</li>
          <li class="doc-pendiente"><span class="falta">¿Bebidas adicionales y compras en las tiendas? En el tour de Atardeceres no se incluyen; confirmar con cada casa</span></li>
        </ul></div>
      </div>
    </div>
  </section>

  <!-- LLEGAR -->
  <section class="seccion" aria-labelledby="lle-t">
    <div class="contenedor llegar">
      <div>
        <h2 id="lle-t">Cómo llegar</h2>
        <p class="lead">Te recogemos en Medellín o San Antonio de Prado entre las 7:00 y las 8:00 a. m.; tú solo llegas al punto de encuentro. Armenia Mantequilla queda en el suroeste antioqueño, a unos 90 minutos. Salimos de vuelta hacia las 7:00 p. m. y llegamos a Medellín cerca de las 8:30 p. m.</p>
        <p class="lead">La ubicación exacta del punto de encuentro te la enviamos antes del tour.</p>
        <div class="bloques">
          <span class="falta bloque">Botón «Abrir en Google Maps» (enlace, no mapa incrustado)</span>
          <span class="falta bloque">Qué llevar. Sugerencia por validar: abrigo para el atardecer y zapatos para caminar</span>
        </div>
      </div>
      <div style="position:relative"><img class="revela" src="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-04-10-45-52.webp" srcset="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-04-10-45-52-w480.webp 480w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-04-10-45-52-w960.webp 960w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-04-10-45-52.webp 1000w" sizes="(min-width: 900px) 45vw, 100vw" width="1000" height="1407" loading="lazy" alt="La torre de la iglesia de Armenia Mantequilla contra un cielo rojo y morado"><span class="credito doc-pendiente">Foto: <span class="falta">autor</span></span></div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="seccion gris" aria-labelledby="faq-t">
    <div class="contenedor">
      <h2 id="faq-t">Preguntas frecuentes</h2>
      <div class="faq">
        <details><summary>¿El precio es por persona?</summary><div>Sí, <?php echo esc_html($precio); ?> por persona. <span class="falta">¿Incluye impuestos?</span></div></details>
        <details><summary>¿De dónde sale el tour?</summary><div>Te recogemos en Medellín o San Antonio de Prado entre las 7:00 y las 8:00 a. m. La ubicación exacta del punto de encuentro te la enviamos antes del tour. El regreso a Medellín es cerca de las 8:30 p. m.</div></details>
        <details><summary>¿Puedo quedarme a dormir?</summary><div>El tour es de un día y no incluye hospedaje. Si quieres quedarte, escríbenos y te recomendamos hoteles de la zona.</div></details>
        <details><summary>¿Cómo pago y qué recibo?</summary><div>Pagas en línea y te llega la confirmación y el comprobante al correo. <span class="falta">Medios exactos de la pasarela (la misma del sitio de Atardeceres) y si se entrega factura</span></div></details>
        <details class="sin-respuesta"><summary>¿Puedo cancelar o cambiar de fecha?</summary><div><span class="falta">Política de cancelación</span></div></details>
        <details class="sin-respuesta"><summary>¿Puedo ir solo o en grupo?</summary><div><span class="falta">Mínimo y máximo por reserva</span></div></details>
        <details class="sin-respuesta"><summary>¿Pueden ir niños?</summary><div><span class="falta">Edad mínima y qué reciben los menores en la bienvenida con licor</span></div></details>
        <details class="sin-respuesta"><summary>¿Es accesible para personas con movilidad reducida?</summary><div><span class="falta">Accesibilidad de cada parada</span></div></details>
        <details class="sin-respuesta"><summary>¿Qué pasa si llueve?</summary><div><span class="falta">Plan de lluvia, sobre todo para el atardecer</span></div></details>
      </div>
    </div>
  </section>

  <!-- CIERRE -->
  <section class="seccion cierre oscuro" aria-labelledby="cie-t">
    <div class="resplandor" aria-hidden="true"></div>
    <div class="estrellas" aria-hidden="true"></div>
    <div class="luna" aria-hidden="true"></div>
    <div class="contenedor">
      <h2 id="cie-t">Elige tu fecha</h2>
      <p class="lead">Un día para comer, aprender y ver caer la tarde con gente de aquí. <?php echo esc_html($precio); ?> por persona, salidas en fines de semana de diciembre, grupos de 15 personas.</p>
      <div class="acciones">
        <a class="btn" href="#fechas" data-evento="cierre_reservar_fecha">Reservar mi fecha</a>
        <a <?php echo tm_whatsapp_attrs('Hola, tengo una pregunta sobre el Tour Mantequilla.', 'whatsapp_cierre', 'btn wa'); ?>>Pregúntanos por WhatsApp</a>
      </div>
    </div>
  </section>
</main>

<footer class="pie oscuro">
  <div class="contenedor">
    <div class="placas">
      <div class="placa"><img src="<?php echo $a; ?>/logos/atardeceres-w288.webp" width="288" height="99" loading="lazy" alt="Finca Atardeceres"></div>
      <div class="placa"><img src="<?php echo $a; ?>/logos/cafe-cabras-cafe-w192.webp" width="192" height="192" loading="lazy" alt="Café Cabras"></div>
      <div class="placa"><img src="<?php echo $a; ?>/logos/bonne-nature-w192.webp" width="192" height="192" loading="lazy" alt="Bonne Nature"></div>
      <div class="placa"><img src="<?php echo $a; ?>/logos/la-florida-w192.webp" width="192" height="192" loading="lazy" alt="Mirador La Florida"></div>
    </div>
    <p>Finca Atardeceres, Café Cabras, Bonne Nature y Finca Mirador La Florida. Armenia Mantequilla, Antioquia.</p>
    <p style="margin-top:6px"><span class="falta">Número de WhatsApp, quién responde y si aparece M&amp;T Consulting</span></p>
    <nav aria-label="Legal" class="doc-pendiente"><a href="#">Términos y condiciones</a><a href="#">Datos personales</a><a href="#">Cancelación</a> <span class="falta">textos legales pendientes (#28)</span></nav>
    <p class="doc-pendiente" style="margin-top:8px">English? Write us on WhatsApp <span class="falta">solo si alguien responde en inglés</span></p>
  </div>
</footer>

<div class="barra" role="region" aria-label="Reservar">
  <div><span class="precio"><?php echo esc_html($precio); ?></span><small>por persona · diciembre</small></div>
  <a class="btn" href="#fechas" data-evento="barra_reservar_fecha">Reservar mi fecha</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
