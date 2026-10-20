(function () {
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // pendientes visibles u ocultos (para revisar el diseño limpio)
  var toggle = document.querySelector('.toggle-falta');
  if (toggle) toggle.addEventListener('click', function () {
    var limpio = document.documentElement.classList.toggle('limpio');
    toggle.setAttribute('aria-pressed', String(limpio));
    if (window.ScrollTrigger) ScrollTrigger.refresh();
  });

  // cabecera: transparente sobre el hero, sólida después
  var cab = document.querySelector('.cab'), hero = document.querySelector('.hero'), fechas = document.getElementById('fechas');
  var barra = document.querySelector('.barra');
  var heroVisible = true, fechasVisible = false, escenaVisible = false;
  // la barra no aparece sobre el hero, ni en Fechas (ya hay botones), ni durante la escena del atardecer (taparía su botón)
  function pintarBarra() { barra.classList.toggle('visible', !heroVisible && !fechasVisible && !escenaVisible); }
  new IntersectionObserver(function (e) {
    heroVisible = e[0].isIntersecting;
    cab.classList.toggle('solida', !heroVisible);
    pintarBarra();
  }, { rootMargin: '-60px 0px 0px 0px' }).observe(hero);
  new IntersectionObserver(function (e) { fechasVisible = e[0].isIntersecting; pintarBarra(); }, { threshold: 0.15 }).observe(fechas);
  new IntersectionObserver(function (e) { escenaVisible = e[0].isIntersecting; pintarBarra(); }, { threshold: 0.01 }).observe(document.querySelector('.escena'));

  // prototipo: los enlaces que todavía no tienen destino no mueven la página
  document.querySelectorAll('a[href="#"]').forEach(function (a) { a.addEventListener('click', function (e) { e.preventDefault(); }); });

  // revelado de fotos
  var io = new IntersectionObserver(function (es) {
    es.forEach(function (x) { if (x.isIntersecting) { x.target.classList.add('visto'); io.unobserve(x.target); } });
  }, { rootMargin: '0px 0px -10% 0px' });
  document.querySelectorAll('.revela').forEach(function (el) { reduce ? el.classList.add('visto') : io.observe(el); });



  // reserva: personas −/+ y total en vivo (el servidor valida el cupo igual)
  var precio = (window.tmTour && +window.tmTour.precio) || 0;
  function cop(n) { return '$' + Math.round(n).toLocaleString('es-CO'); }
  document.querySelectorAll('form.reserva').forEach(function (f) {
    var q = f.querySelector('input[name="quantity"]'), out = f.querySelector('[data-total]');
    var menos = f.querySelector('[data-paso="-1"]'), mas = f.querySelector('[data-paso="1"]');
    var max = +q.max || 1;
    function pintar() {
      var v = Math.min(max, Math.max(1, parseInt(q.value, 10) || 1));
      q.value = v; menos.disabled = v <= 1; mas.disabled = v >= max;
      if (precio && out) out.textContent = cop(precio * v);
    }
    f.querySelectorAll('.paso').forEach(function (b) {
      b.addEventListener('click', function () { q.value = (parseInt(q.value, 10) || 1) + (+b.dataset.paso); pintar(); });
    });
    q.addEventListener('change', pintar);
    f.addEventListener('submit', function () { f.querySelector('button[type="submit"]').disabled = true; });
    pintar();
  });

  // medición: eventos clave (en el sitio van a GA4 / Meta Pixel)
  document.addEventListener('click', function (e) {
    var a = e.target.closest('[data-evento]'); if (!a) return;
    var q = new URLSearchParams(location.search);
    (window.dataLayer = window.dataLayer || []).push({ event: 'cta_click', cta: a.dataset.evento, origen: q.get('utm_source') || 'directo' });
  });

  // atardecer: escena atada al scroll. Sin JS o con movimiento reducido queda en su estado final (CSS)
  if (reduce || !window.gsap || !window.ScrollTrigger) { document.querySelector('.franja').classList.add('estatica'); return; }
  gsap.registerPlugin(ScrollTrigger);
  var mm = gsap.matchMedia();
  mm.add({ desk: '(min-width: 900px)', movil: '(max-width: 899px)' }, function (ctx) {
    var largo = ctx.conditions.desk ? 2 : 1.4; // menos recorrido en móvil; incluye la salida de la luna
    var esc = document.querySelector('.escena'), solE = esc.querySelector('.sol');
    var tl = gsap.timeline({
      defaults: { ease: 'none' },
      scrollTrigger: {
        trigger: '.atardecer', start: 'top top',
        end: function () { return '+=' + Math.round(window.innerHeight * largo); },
        scrub: 0.6, pin: true, anticipatePin: 1, invalidateOnRefresh: true
      }
    });
    // se anima el contenido de la escena, nunca la sección fijada.
    // fromTo con valores finales explícitos: el estado final coincide con el CSS (sin JS se ve así)
    // el sol arranca con su centro en el borde superior de la escena: ahí lo deja el sol del recorrido (relevo sin salto)
    tl.fromTo('.cielo--ocaso', { opacity: 0 }, { opacity: 1, duration: 0.45 }, 0)
      .fromTo('.cielo--noche', { opacity: 0 }, { opacity: 1, duration: 0.4 }, 0.45)
      .fromTo('.escena .estrellas', { opacity: 0 }, { opacity: 0.85, duration: 0.25 }, 0.7)
      .fromTo(solE, { y: function () { return -(esc.offsetHeight * 0.44) - solE.offsetHeight / 2; }, yPercent: 0 }, { y: 0, yPercent: 75, duration: 0.85 }, 0)
      .fromTo('.velo', { opacity: 0 }, { opacity: 1, duration: 0.6 }, 0.35)
      .fromTo('.cordillera .c4', { yPercent: 6 }, { yPercent: 0, duration: 1 }, 0)
      .fromTo('.cordillera .c3', { yPercent: 4 }, { yPercent: 0, duration: 1 }, 0)
      .fromTo('.cordillera .c2', { yPercent: 2 }, { yPercent: 0, duration: 1 }, 0)
      // nubes: del durazno de la tarde al violeta de la noche, derivando despacio
      .fromTo('.nubes-escena', { xPercent: -4, '--nube-e': '#F3B98A' }, { xPercent: 6, '--nube-e': '#3B3A5C', duration: 1.05 }, 0)
      // una bandada vuelve hacia la montaña mientras cae el sol
      .fromTo('.aves-escena', { x: function () { return esc.clientWidth * 1.05; }, y: function () { return esc.clientHeight * 0.18; }, opacity: 0 },
        { x: function () { return esc.clientWidth * 0.3; }, y: function () { return esc.clientHeight * 0.48; }, opacity: 1, duration: 0.45 }, 0.15)
      .to('.aves-escena', { opacity: 0, duration: 0.12 }, 0.55)
      // y sale la luna desde detrás de la cordillera
      .fromTo('.escena .luna', { yPercent: 260, opacity: 0 }, { yPercent: 0, opacity: 1, duration: 0.4 }, 0.68)
      .fromTo('.velo-texto', { opacity: 0 }, { opacity: 1, duration: 0.15 }, 0.95)
      .fromTo('.escena .copy > *', { opacity: 0, y: 20 }, { opacity: 1, y: 0, stagger: 0.03, duration: 0.18 }, 0.99);
    // con teclado: si el foco llega a la escena antes de que termine, se salta al final para que el botón se vea
    function alFinal() { var st = tl.scrollTrigger; if (st && st.progress < 0.98) { window.scrollTo({ top: st.end, behavior: 'instant' }); tl.progress(1); } }
    var btnEscena = document.querySelector('.escena .btn'); btnEscena.addEventListener('focus', alFinal);
    return function () { btnEscena.removeEventListener('focus', alFinal); tl.scrollTrigger && tl.scrollTrigger.kill(); tl.kill(); };
  });

  // ---------- motion elevated: entradas, profundidad y feedback ----------
  function partir(el) { // divide un título de solo texto en palabras con máscara
    if (!el || el.children.length) return [];
    var palabras = el.textContent.trim().split(/\s+/);
    el.setAttribute('aria-label', el.textContent.trim());
    el.innerHTML = palabras.map(function (p) { return '<span class="w" aria-hidden="true"><span>' + p + '</span></span>'; }).join(' ');
    return el.querySelectorAll('.w > span');
  }
  var ease = 'power3.out';

  // hero: una sola coreografía de carga (el botón queda listo antes de 1 s)
  var hl = partir(document.querySelector('.hero h1'));
  gsap.timeline({ defaults: { ease: ease } })
    .from('.hero .foto img', { scale: 1.12, duration: 1.6, ease: 'power2.out' }, 0)
    .from(hl, { yPercent: 105, duration: 0.9, stagger: 0.05 }, 0.1)
    .from('.hero .copy > :not(h1)', { opacity: 0, y: 14, duration: 0.6, stagger: 0.06 }, 0.35);
  // hero: la foto se queda atrás al bajar
  gsap.to('.hero .foto img', { yPercent: 10, ease: 'none', scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true } });

  // franja de las cuatro casas: avanza con el scroll (no se mueve sola)
  gsap.fromTo('.franja .pista', { xPercent: 0 }, { xPercent: -33.333, ease: 'none', scrollTrigger: { trigger: '.franja', start: 'top bottom', end: 'bottom top', scrub: 0.5 } });


  // recorrido: el fondo baja de la mañana a la tarde mientras avanzas (color atado al scroll)
  var rec = document.querySelector('.recorrido'); rec.classList.add('dinamico');
  var tonos = [
    { bg: '#F6F1EE', tx: '#1E2B45' },   // 01 mañana en La Florida
    { bg: '#F1D9A6', tx: '#1E2B45' },   // 02 mediodía en Café Cabras
    { bg: '#8E4728', tx: '#FBFCFA' }    // 03 tarde en la quesería; empalma con el cielo del atardecer
  ];
  // ojo: cada fromTo pinta su estado inicial al crearse; sin immediateRender:false el último en crearse
  // (dorado del paso 2) se queda aplicado al cargar, antes de llegar al recorrido
  var capsRec = rec.querySelectorAll('.capitulo');
  capsRec.forEach(function (c, i) {
    if (i === 0) return;
    var t = gsap.timeline({ scrollTrigger: { trigger: c, start: 'top 85%', end: 'top 35%', scrub: true } });
    if (tonos[i].tx === tonos[i - 1].tx) {
      t.fromTo(rec, { backgroundColor: tonos[i - 1].bg }, { backgroundColor: tonos[i].bg, ease: 'none', duration: 1, immediateRender: false }, 0);
    } else {
      // ningún tono intermedio sirve para texto oscuro y claro a la vez: el fondo baja hasta el último ocre
      // que aguanta texto oscuro (≥4.5:1), salta en un tramo mínimo al primero que aguanta texto claro, y sigue
      t.fromTo(rec, { backgroundColor: tonos[i - 1].bg, color: tonos[i - 1].tx }, { backgroundColor: '#C98A5A', ease: 'none', duration: 0.5, immediateRender: false }, 0)
       .set(rec, { backgroundColor: '#9C5530', color: tonos[i].tx }, 0.5)
       .to(rec, { backgroundColor: tonos[i].bg, ease: 'none', duration: 0.5 }, 0.5);
    }
  });

  // ---------- el día en el cielo: el sol acompaña el recorrido, nubes por profundidad, pájaros entre paradas ----------
  var cielo = rec.querySelector('.cielo-dia'), astro = cielo.querySelector('.astro-sol');
  var diaP = { p: 0 };
  function pintarSol() { // sale del horizonte a la izquierda, cima al mediodía y baja al centro, donde lo recibe la escena
    var w = cielo.clientWidth, h = cielo.clientHeight, s = astro.offsetWidth, p = diaP.p;
    gsap.set(astro, { x: w * (0.12 + 0.38 * p) - s / 2, y: h - h * 0.82 * Math.sin(Math.PI * p) - s / 2, scale: 0.55 + 0.45 * p * p });
  }
  gsap.to(diaP, { p: 1, ease: 'none', onUpdate: pintarSol, scrollTrigger: { trigger: rec, start: 'top top', end: 'bottom bottom', scrub: 0.6 } });
  pintarSol();
  ScrollTrigger.addEventListener('refresh', pintarSol);
  // color de la hora: mañana (al cargar) → mediodía (parada 2) → tarde (parada 3, igual al sol de la escena)
  var horas = [
    { '--sol-a': '#FFF8E6', '--sol-b': '#FFDF8A', '--sol-c': '#FFC266', '--sol-glow': 'rgba(255,196,110,.55)', '--nube': '#FFFFFF', '--ave': '#1E2B45' },
    { '--sol-a': '#FFFBEA', '--sol-b': '#FFE066', '--sol-c': '#FFC93C', '--sol-glow': 'rgba(255,205,70,.6)', '--nube': '#FFFFFF', '--ave': '#1E2B45' },
    { '--sol-a': '#FFF3D6', '--sol-b': '#FBD07A', '--sol-c': '#F1A447', '--sol-glow': 'rgba(251,208,122,.45)', '--nube': '#A65C38', '--ave': '#2A140C' }
  ];
  gsap.set(cielo, horas[0]);
  [1, 2].forEach(function (i) {
    if (!capsRec[i]) return;
    gsap.fromTo(cielo, horas[i - 1], Object.assign({}, horas[i], { ease: 'none', immediateRender: false, scrollTrigger: { trigger: capsRec[i], start: 'top 85%', end: 'top 35%', scrub: true } }));
  });
  // nubes: cada profundidad sube a su velocidad (lejos lento, cerca rápido) y deriva un poco
  [['lejos', 4, -9, -4], ['medio', 10, -22, 5], ['cerca', 20, -48, -9]].forEach(function (c) {
    gsap.fromTo(cielo.querySelector('.nubes.' + c[0]), { yPercent: c[1], xPercent: 0 },
      { yPercent: c[2], xPercent: c[3], ease: 'none', scrollTrigger: { trigger: rec, start: 'top bottom', end: 'bottom top', scrub: true } });
  });
  // las nubes de adelante van saliendo según avanzas
  gsap.fromTo(cielo.querySelectorAll('.nubes.cerca svg'), { scale: 1.25, opacity: 0 },
    { scale: 1, opacity: 1, ease: 'none', stagger: 0.3, scrollTrigger: { trigger: rec, start: 'top 60%', end: 'center center', scrub: true } });
  // pájaros: una bandada cruza en cada cambio de parada (01→02 de izquierda a derecha, 02→03 de vuelta)
  var bandadas = cielo.querySelectorAll('.bandada');
  [[1, -0.3, 0.6, 1.1, 0.22], [2, 1.1, 0.35, -0.35, 0.55]].forEach(function (b, i) {
    var el = bandadas[i], cap = capsRec[b[0]]; if (!el || !cap) return;
    gsap.timeline({ scrollTrigger: { trigger: cap, start: 'top bottom', end: 'top 15%', scrub: 0.8, invalidateOnRefresh: true } })
      .fromTo(el, { x: function () { return cielo.clientWidth * b[1]; }, y: function () { return cielo.clientHeight * b[2]; }, opacity: 0 },
        { x: function () { return cielo.clientWidth * b[3]; }, y: function () { return cielo.clientHeight * b[4]; }, opacity: 1, ease: 'none', duration: 1 }, 0)
      .to(el, { opacity: 0, duration: 0.15 }, 0.85);
  });

  // títulos de sección: revelado por palabra
  document.querySelectorAll('.seccion h2, .capitulo h3').forEach(function (h) {
    var p = partir(h); if (!p.length) return;
    gsap.from(p, { yPercent: 105, duration: 0.8, ease: ease, stagger: 0.04, scrollTrigger: { trigger: h, start: 'top 88%', once: true } });
  });

  // capítulos: texto entra; números, foto e inserto se mueven a distinta velocidad (profundidad)
  document.querySelectorAll('.capitulo').forEach(function (c) {
    gsap.from(c.querySelectorAll('.quien, .anfitrion, .texto, .falta'), { opacity: 0, y: 16, duration: 0.6, ease: ease, stagger: 0.06, scrollTrigger: { trigger: c, start: 'top 80%', once: true } });
    var st = { trigger: c, start: 'top bottom', end: 'bottom top', scrub: true };
    gsap.fromTo(c.querySelector('.num'), { y: 40 }, { y: -40, ease: 'none', scrollTrigger: st });
    var m = c.querySelector('.medios'); if (m) gsap.fromTo(m, { y: 30 }, { y: -30, ease: 'none', scrollTrigger: st });
    var ins = c.querySelector('.inset'); if (ins) gsap.fromTo(ins, { y: 60 }, { y: -50, ease: 'none', scrollTrigger: st });
  });

  // fechas y aliados: entran escalonados; el zócalo de color se pinta
  gsap.from('.fecha', { opacity: 0, y: 24, duration: 0.7, ease: ease, stagger: 0.08, scrollTrigger: { trigger: '.fechas', start: 'top 85%', once: true } });
  gsap.from('.confianza li', { opacity: 0, y: 10, duration: 0.5, ease: ease, stagger: 0.05, scrollTrigger: { trigger: '.confianza', start: 'top 92%', once: true } });
  gsap.from('.aliado', { opacity: 0, y: 24, duration: 0.7, ease: ease, stagger: 0.08, scrollTrigger: { trigger: '.aliados', start: 'top 85%', once: true } });
  document.querySelectorAll('.aliado').forEach(function (a, i) {
    gsap.fromTo(a, { '--zs': 0 }, { '--zs': 1, duration: 0.9, delay: 0.2 + i * 0.08, ease: 'power2.inOut', scrollTrigger: { trigger: '.aliados', start: 'top 85%', once: true } });
  });

  // cierre: el resplandor sube detrás del título
  gsap.fromTo('.cierre .resplandor', { yPercent: 30, opacity: 0.4 }, { yPercent: -10, opacity: 1, ease: 'none', scrollTrigger: { trigger: '.cierre', start: 'top bottom', end: 'bottom bottom', scrub: true } });

  // botones magnéticos (solo con mouse): responden al puntero, sin cambiar de lugar al tocar
  if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('.btn').forEach(function (b) {
      var xTo = gsap.quickTo(b, 'x', { duration: 0.4, ease: ease }), yTo = gsap.quickTo(b, 'y', { duration: 0.4, ease: ease });
      b.addEventListener('mousemove', function (e) { var r = b.getBoundingClientRect(); xTo((e.clientX - r.left - r.width / 2) * 0.18); yTo((e.clientY - r.top - r.height / 2) * 0.3); });
      b.addEventListener('mouseleave', function () { xTo(0); yTo(0); });
    });
  }
  ScrollTrigger.sort();
  window.addEventListener('load', function () { ScrollTrigger.sort(); ScrollTrigger.refresh(); });
})();
