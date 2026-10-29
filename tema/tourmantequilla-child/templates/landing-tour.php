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
<meta name="description" content="Tour de la Mantequilla: montaña, tradición, café y quesos. Un día en Armenia Mantequilla con cuatro casas del pueblo, con recogida en Medellín.">
<script>document.documentElement.classList.add('js')</script>
<?php wp_head(); ?>
</head>
<body <?php body_class('tm-landing'); ?>>
<?php wp_body_open(); ?>
<a class="saltar" href="#fechas">Saltar a las fechas</a>

<header class="cab">
  <div class="contenedor">
    <a class="marca" href="#inicio">Tour de la Mantequilla</a>
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
      <img src="<?php echo $a; ?>/fotos-web/prueba-ia/portada-julio.webp" srcset="<?php echo $a; ?>/fotos-web/prueba-ia/portada-julio-w480.webp 480w, <?php echo $a; ?>/fotos-web/prueba-ia/portada-julio-w960.webp 960w, <?php echo $a; ?>/fotos-web/prueba-ia/portada-julio.webp 1800w" sizes="(min-width: 900px) 45vw, 100vw" width="1800" height="1800" fetchpriority="high" alt="Un grupo de amigos con café en el prado de un mirador, frente al atardecer y al pueblo con su iglesia">
    </div>
    <div class="copy">
      <h1 id="h1">Tour de la Mantequilla: Montaña, tradición, café y quesos</h1>
      <p class="lema">Pausa tu semana en el balcón de la montaña antioqueña</p>
      <p class="sub">Te recogemos en Medellín para un día de café de origen, quesos artesanales, pizza de masa madre en horno de barro y una casa tradicional campesina frente al atardecer.</p>
      <p class="valor">Incluye almuerzo, talleres de café y quesos, y tu propia pizza en horno de barro <span class="falta">confirmar qué cubre el precio</span></p>
      <p class="precio"><strong><?php echo esc_html($precio); ?></strong><span>por persona <span class="falta">¿impuestos incluidos?</span></span></p>
      <p class="fechas-linea">Salidas en fines de semana de diciembre · grupos de 15 personas <span class="falta">las fechas se definen en el motor de reservas (#26)</span></p>
      <div class="acciones">
        <a class="btn" href="#fechas" data-evento="hero_reservar_fecha">Reservar mi fecha</a>
        <p class="seguro">Pago en línea · confirmación al correo</p>
        <a <?php echo tm_whatsapp_attrs('Hola, quiero recibir la información del Tour de la Mantequilla.', 'whatsapp_recibir_info', 'enlace-wa'); ?>>Recibe la información por WhatsApp</a>
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
      <symbol id="ave" viewBox="0 0 24 10"><path d="M1 7 Q6 1 12 7 Q18 1 23 7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></symbol>
    </svg>
    <div class="cielo-pista" aria-hidden="true"><div class="cielo-dia">
      <div class="nubes lejos">
        <span class="nube" style="left:6%;top:9%;width:170px"><img src="<?php echo $a; ?>/recortes/nube-blanca-1.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-1.webp" alt=""></span>
        <span class="nube espejo" style="left:62%;top:20%;width:130px"><img src="<?php echo $a; ?>/recortes/nube-blanca-2.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-2.webp" alt=""></span>
        <span class="nube" style="left:30%;top:58%;width:150px"><img src="<?php echo $a; ?>/recortes/nube-blanca-1.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-1.webp" alt=""></span>
        <span class="nube espejo" style="left:80%;top:76%;width:120px"><img src="<?php echo $a; ?>/recortes/nube-blanca-2.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-2.webp" alt=""></span>
      </div>
      <div class="astro-sol"></div>
      <div class="nubes medio">
        <span class="nube" style="left:-4%;top:30%;width:220px"><img src="<?php echo $a; ?>/recortes/nube-blanca-2.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-2.webp" alt=""></span>
        <span class="nube espejo" style="left:70%;top:6%;width:240px"><img src="<?php echo $a; ?>/recortes/nube-blanca-1.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-1.webp" alt=""></span>
        <span class="nube" style="left:44%;top:46%;width:200px"><img src="<?php echo $a; ?>/recortes/nube-blanca-2.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-2.webp" alt=""></span>
        <span class="nube espejo" style="left:8%;top:84%;width:230px"><img src="<?php echo $a; ?>/recortes/nube-blanca-1.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-1.webp" alt=""></span>
      </div>
      <div class="nubes cerca">
        <span class="nube" style="left:72%;top:36%;width:330px"><img src="<?php echo $a; ?>/recortes/nube-blanca-1.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-1.webp" alt=""></span>
        <span class="nube espejo" style="left:-10%;top:62%;width:300px"><img src="<?php echo $a; ?>/recortes/nube-blanca-2.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-2.webp" alt=""></span>
        <span class="nube" style="left:58%;top:88%;width:340px"><img src="<?php echo $a; ?>/recortes/nube-blanca-1.webp" alt=""><img class="tarde" src="<?php echo $a; ?>/recortes/nube-dorada-1.webp" alt=""></span>
      </div>
      <svg class="bandada" viewBox="0 0 200 80"><use href="#ave" x="0" y="34" width="26" height="11"/><use href="#ave" x="34" y="18" width="22" height="9"/><use href="#ave" x="62" y="40" width="28" height="12"/><use href="#ave" x="98" y="10" width="20" height="8"/><use href="#ave" x="120" y="30" width="24" height="10"/><use href="#ave" x="156" y="22" width="18" height="8"/></svg>
      <svg class="bandada" viewBox="0 0 200 80"><use href="#ave" x="10" y="20" width="22" height="9"/><use href="#ave" x="44" y="38" width="28" height="12"/><use href="#ave" x="80" y="14" width="20" height="8"/><use href="#ave" x="112" y="34" width="26" height="11"/><use href="#ave" x="150" y="24" width="22" height="9"/></svg>
    </div></div>
    <div class="contenedor">
      <h2 id="rec-t">Lo que vas a vivir</h2>
      <p class="lead">Llegar a un pueblo no es lo mismo que conocerlo. Este día lo arman quienes viven ahí: de la mañana entre cafetales al atardecer en el mirador.</p>
      <ol class="capitulos" role="list">
        <li class="capitulo" style="--c:var(--atardeceres)" data-hora="Mañana">
          <div>
            <span class="num" aria-hidden="true">01</span>
            <h3>La mañana entre cafetales</h3>
            <p class="quien"><i aria-hidden="true"></i>Finca Atardeceres</p>
            <div class="anfitrion"><span class="retrato" aria-hidden="true"></span><div><p class="nombre"><span class="falta">Nombre de quien recibe</span></p></div></div>
            <span class="falta">Foto del anfitrión y una frase suya (pendiente #25)</span>
            <p class="texto">El día empieza en una finca cafetera a 1.750 metros sobre el nivel del mar, recorriendo sus cultivos de café.</p>
            <span class="falta bloque">Verificar altura. «Taller de tu…»: ¿turismo o tueste? Qué incluye y cuánto dura (pendiente #3)</span>
          </div>
          <div class="medios"><div class="sin-foto">Foto de la finca y sus cultivos: falta descargar el álbum oficial de Atardeceres (#15)</div></div>
        </li>
        <li class="capitulo" style="--c:var(--cabras)" data-hora="Mediodía">
          <div>
            <span class="num" aria-hidden="true">02</span>
            <h3>El café que nació en un kiosco de la plaza</h3>
            <p class="quien"><i aria-hidden="true"></i>Café Cabras</p>
            <div class="anfitrion"><span class="retrato" aria-hidden="true"></span><div><p class="nombre">Caterine y Fernando</p><blockquote class="frase">«Como las cabras, subimos por terreno difícil y llegamos a donde otros no se atreven.»</blockquote></div></div>
            <span class="falta">Foto de Caterine y Fernando (pendiente #25)</span>
            <p class="texto">Te reciben con una copa de su licor artesanal de café. Almuerzas fiambres caseros, pruebas su café de origen preparado de varias maneras y cierras con un detalle sorpresa.</p>
            <span class="falta bloque">Verificar registro y venta del licor (pendiente #7). No hay foto del almuerzo (pendiente #21)</span>
          </div>
          <div class="medios">
            <img class="revela" src="<?php echo $a; ?>/fotos-web/cafe-cabras/cabras-02-chemex-en-sala.webp" srcset="<?php echo $a; ?>/fotos-web/cafe-cabras/cabras-02-chemex-en-sala-w480.webp 480w, <?php echo $a; ?>/fotos-web/cafe-cabras/cabras-02-chemex-en-sala-w960.webp 960w, <?php echo $a; ?>/fotos-web/cafe-cabras/cabras-02-chemex-en-sala.webp 1271w" sizes="(min-width: 900px) 45vw, 100vw" width="1271" height="1571" loading="lazy" alt="Chemex con filtro y personas al fondo en Café Cabras">
            <img class="inset revela" src="<?php echo $a; ?>/fotos-web/cafe-cabras/cabras-05-licor-botella-copas.webp" srcset="<?php echo $a; ?>/fotos-web/cafe-cabras/cabras-05-licor-botella-copas-w480.webp 480w, <?php echo $a; ?>/fotos-web/cafe-cabras/cabras-05-licor-botella-copas-w960.webp 960w, <?php echo $a; ?>/fotos-web/cafe-cabras/cabras-05-licor-botella-copas.webp 1440w" sizes="(min-width: 900px) 30vw, 40vw" width="1440" height="1801" loading="lazy" alt="Botella de licor de café Cabras con dos copas">
            <img class="grano g1" src="<?php echo $a; ?>/recortes/grano-1.webp" alt="" aria-hidden="true" data-prof="90">
            <img class="grano g2" src="<?php echo $a; ?>/recortes/grano-2.webp" alt="" aria-hidden="true" data-prof="70">
            <img class="grano g3" src="<?php echo $a; ?>/recortes/grano-3.webp" alt="" aria-hidden="true" data-prof="30">
            <img class="grano g4" src="<?php echo $a; ?>/recortes/grano-4.webp" alt="" aria-hidden="true" data-prof="25">
            <img class="grano g5" src="<?php echo $a; ?>/recortes/grano-5.webp" alt="" aria-hidden="true" data-prof="35">
            <img class="grano g6" src="<?php echo $a; ?>/recortes/grano-6.webp" alt="" aria-hidden="true" data-prof="20">
          </div>
        </li>
        <li class="capitulo" style="--c:var(--bonne)" data-hora="Tarde">
          <div>
            <span class="num" aria-hidden="true">03</span>
            <h3>Quesería artesanal colombo-francesa</h3>
            <p class="quien"><i aria-hidden="true"></i>Bonne Nature</p>
            <span class="falta">Foto del equipo de la quesería (pendiente #25)</span>
            <p class="texto">Quesos de receta francesa con ají, achiote, pimientas y hierbas de la cocina colombiana, hechos en el pueblo desde 2014 por mujeres del municipio. La familia Mejía Mejía vivió 30 años en Francia, donde Luz Marina aprendió el oficio. Aquí haces el taller de quesos.</p>
            <span class="falta bloque">Confirmar que el equipo son mujeres del municipio y el año 2014 (pendiente #6). Confirmar que «la quesería» del audio es Bonne Nature (pendiente #3)</span>
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
      <div class="cordillera" aria-hidden="true"><img class="capa c-lejos" src="<?php echo $a; ?>/recortes/cordillera-lejos.webp" width="1824" height="720" alt="" decoding="async"><img class="capa c-lejos nc" src="<?php echo $a; ?>/recortes/cordillera-lejos.webp" width="1824" height="720" alt="" decoding="async"><img class="capa c-medio" src="<?php echo $a; ?>/recortes/cordillera-medio.webp" width="1824" height="740" alt="" decoding="async"><img class="capa c-medio nc" src="<?php echo $a; ?>/recortes/cordillera-medio.webp" width="1824" height="740" alt="" decoding="async"><img class="capa c-cerca" src="<?php echo $a; ?>/recortes/cordillera-cerca.webp" width="1824" height="1076" alt="" decoding="async"><img class="capa c-cerca nc" src="<?php echo $a; ?>/recortes/cordillera-cerca.webp" width="1824" height="1076" alt="" decoding="async"><div class="capa c-pueblo"><img src="<?php echo $a; ?>/recortes/pueblo-iglesia.webp" width="2000" height="1025" alt="" decoding="async"><img class="nc" src="<?php echo $a; ?>/recortes/pueblo-iglesia.webp" width="2000" height="1025" alt="" decoding="async"><span class="luces"><i class="reloj" style="left:60.6%;top:49.8%"></i><i class="reloj" style="left:69%;top:49.8%"></i><i style="left:60.3%;top:62.9%"></i><i style="left:72%;top:67.4%"></i><i style="left:81%;top:64.5%"></i><i style="left:84.5%;top:64.5%"></i><i style="left:87%;top:75%"></i><i style="left:90.5%;top:69.3%"></i><i style="left:93%;top:78%"></i><i style="left:96%;top:70.3%"></i><i style="left:68%;top:86%"></i><i style="left:43%;top:85%"></i><i style="left:33%;top:83%"></i><i style="left:30%;top:89%"></i><i style="left:2%;top:84%"></i><i style="left:7.5%;top:86%"></i></span></div></div>
      <div class="velo" aria-hidden="true"></div>
      <div class="sol" aria-hidden="true"></div>
      <img class="luna" src="<?php echo $a; ?>/recortes/luna.webp" width="231" height="175" alt="" aria-hidden="true">
      <div class="nubes-escena" aria-hidden="true">
        <span class="nube" style="left:4%;top:18%;width:min(30vw,260px)"><img src="<?php echo $a; ?>/recortes/nube-dorada-1.webp" alt=""><img class="noche" src="<?php echo $a; ?>/recortes/nube-violeta-1.webp" alt=""></span>
        <span class="nube espejo" style="left:64%;top:4%;width:min(26vw,220px)"><img src="<?php echo $a; ?>/recortes/nube-dorada-2.webp" alt=""><img class="noche" src="<?php echo $a; ?>/recortes/nube-violeta-1.webp" alt=""></span>
        <span class="nube" style="left:38%;top:52%;width:min(22vw,190px)"><img src="<?php echo $a; ?>/recortes/nube-dorada-2.webp" alt=""><img class="noche" src="<?php echo $a; ?>/recortes/nube-violeta-1.webp" alt=""></span>
      </div>
      <svg class="aves-escena" viewBox="0 0 200 80" aria-hidden="true"><use href="#ave" x="0" y="30" width="24" height="10"/><use href="#ave" x="36" y="16" width="20" height="8"/><use href="#ave" x="66" y="36" width="26" height="11"/><use href="#ave" x="104" y="12" width="18" height="8"/><use href="#ave" x="130" y="28" width="22" height="9"/></svg>
      <div class="velo-texto" aria-hidden="true"></div>
      <div class="copy">
        <span class="num" aria-hidden="true">04</span>
        <h2 id="atd-t">Y el día se va con el sol</h2>
        <p class="quien"><i aria-hidden="true"></i>Finca Mirador La Florida</p>
        <div class="anfitrion"><span class="retrato" aria-hidden="true"></span><div><p class="nombre"><span class="falta">Nombre de quien recibe</span></p></div></div>
        <p class="texto">Cierras en La Florida, una casa tradicional campesina con mirador: armas tu propia pizza de masa madre, la horneas en el horno de barro y leña y ves caer el sol sobre Armenia Mantequilla, el Bello Balcón de Antioquia.</p>
        <span class="falta">Foto y frase del anfitrión (#25). Una foto real de la vista desde el mirador puede ir aquí (#46)</span>
        <a class="btn" href="#fechas" data-evento="atardecer_reservar_fecha">Reservar mi fecha</a>
        <p class="credito">Foto del pueblo y de la luna: Alex Betancure (@alex_betancure) <span class="falta">permiso pendiente (#19). Esta versión pasó por IA (#40)</span></p>
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
      <p class="grupos doc-pendiente">¿Son 6 o más? <a <?php echo tm_whatsapp_attrs('Hola, somos un grupo de 6 o más personas y queremos reservar el Tour de la Mantequilla.', 'whatsapp_grupo', ''); ?>>Escríbenos por WhatsApp</a> <span class="falta">solo si Jhon confirma que se manejan grupos</span></p>
      <ul class="confianza">
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>Pago en línea</li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>Confirmación y comprobante al correo</li>
        <li class="doc-pendiente"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12a8 8 0 1 0 3-6.2"/><path d="M4 4v4h4"/></svg><a href="#">Política de cancelación</a> <span class="falta">texto pendiente (#28)</span></li>
        <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 19l1.4-4.2A7.5 7.5 0 1 1 9.2 17.6Z"/></svg><a <?php echo tm_whatsapp_attrs('Hola, tengo una duda sobre el Tour de la Mantequilla.', 'whatsapp_dudas', ''); ?>>¿Dudas? Escríbenos</a></li>
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
          <div><h3>Finca Atardeceres</h3><p>Finca cafetera en Armenia Mantequilla, a 1.750 m.s.n.m. <span class="falta">verificar altura</span> Aquí empieza el día, entre los cultivos de café.</p></div>
        </li>
        <li class="aliado" style="--c:var(--cabras)">
          <div class="placa"><img src="<?php echo $a; ?>/logos/cafe-cabras-cafe-w192.webp" width="192" height="192" loading="lazy" alt=""></div>
          <div><h3>Café Cabras</h3><p>Café de origen que nació en un kiosco de la plaza del pueblo. Caterine y Fernando hacen catas, talleres y encuentros alrededor de la taza.</p></div>
        </li>
        <li class="aliado" style="--c:var(--bonne)">
          <div class="placa"><img src="<?php echo $a; ?>/logos/bonne-nature-w192.webp" width="192" height="192" loading="lazy" alt=""></div>
          <div><h3>Bonne Nature</h3><p>Quesería artesanal colombo-francesa de la familia Mejía Mejía, que vivió 30 años en Francia. Desde 2014 hacen sus quesos en el pueblo.</p></div>
        </li>
        <li class="aliado" style="--c:var(--florida)">
          <div class="placa"><img src="<?php echo $a; ?>/logos/la-florida-w192.webp" width="192" height="192" loading="lazy" alt=""></div>
          <div><h3>Finca Mirador La Florida</h3><p>Casa tradicional campesina con vista a las montañas del Suroeste y el Occidente, huerta de aromáticas, bosque nativo sembrado y horno de barro. Aquí termina el día: armas y horneas tu propia pizza mientras cae el sol.</p></div>
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
          <li>Recorrido por los cultivos de café en Finca Atardeceres</li>
          <li>Copa de bienvenida, almuerzo de fiambres y digestivo en Café Cabras</li>
          <li>Taller de métodos de preparación de café</li>
          <li>Cata de quesos</li>
          <li>Armar y hornear tu propia pizza de masa madre en horno de barro</li>
          <li>Atardecer en el mirador de La Florida</li>
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
      <div style="position:relative"><img class="revela" src="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-01-10-43-18-hd.webp" srcset="<?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-01-10-43-18-hd-w480.webp 480w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-01-10-43-18-hd-w960.webp 960w, <?php echo $a; ?>/fotos-web/finca-mirador-la-florida/la-florida-01-10-43-18-hd.webp 1824w" sizes="(min-width: 900px) 45vw, 100vw" width="1824" height="2736" loading="lazy" style="object-position:50% 40%" alt="Capas de montañas azules entre la bruma en el suroeste antioqueño, vistas desde el mirador de La Florida"><span class="credito doc-pendiente">Foto: <span class="falta">autor</span></span></div>
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
    <img class="luna" src="<?php echo $a; ?>/recortes/luna.webp" width="231" height="175" alt="" aria-hidden="true">
    <div class="contenedor">
      <h2 id="cie-t">Elige tu fecha</h2>
      <p class="lead">Un día para comer, aprender y ver caer la tarde con gente de aquí. <?php echo esc_html($precio); ?> por persona, salidas en fines de semana de diciembre, grupos de 15 personas.</p>
      <div class="acciones">
        <a class="btn" href="#fechas" data-evento="cierre_reservar_fecha">Reservar mi fecha</a>
        <a <?php echo tm_whatsapp_attrs('Hola, tengo una pregunta sobre el Tour de la Mantequilla.', 'whatsapp_cierre', 'btn wa'); ?>>Pregúntanos por WhatsApp</a>
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

<?php tm_render_barra(); ?>

<?php wp_footer(); ?>
</body>
</html>
