"""Genera la cordillera de la escena del atardecer y la escribe en el prototipo.

Uso (desde la raíz del repo):  python -I tema/gen_cordillera.py .
Reemplaza el bloque <svg class="cordillera" ...>...</svg> de 05_Landing/diseno/prototipo.html.
Después: python -I tema/build_landing.py .  y  bash tema/sync-local.sh

Determinista (semilla fija): mismas montañas en cada corrida. Ilustración, no foto.
Capas de lejos a cerca: c5, c4, c3, c2, c1 (el JS mueve c4, c3 y c2 con el scroll).
"""
import math, random, re, sys, pathlib

ANCHO, ALTO = 1600, 600
random.seed(1750)  # 1.750 m.s.n.m.

def cresta(base, amp, onda, picos, paso=16):
    """Altura de la cresta (y) en cada x: ondas suaves + picos puntuales."""
    fases = [random.uniform(0, 2 * math.pi) for _ in range(3)]
    pk = [(random.uniform(0, ANCHO), random.uniform(.5, 1) * amp, random.uniform(70, 170)) for _ in range(picos)]
    pts = []
    for x in range(0, ANCHO + paso, paso):
        y = base
        y -= amp * .45 * math.sin(x / onda + fases[0])
        y -= amp * .25 * math.sin(x / (onda * .43) + fases[1])
        y -= amp * .12 * math.sin(x / (onda * .17) + fases[2])
        for (px, h, w) in pk:
            y -= h * math.exp(-((x - px) / w) ** 2)
        pts.append((x, round(y, 1)))
    return pts

def y_en(pts, x):
    for (x0, y0), (x1, y1) in zip(pts, pts[1:]):
        if x0 <= x <= x1:
            return y0 + (y1 - y0) * (x - x0) / (x1 - x0)
    return pts[-1][1]

def suave(pts):
    """Catmull-Rom → Bézier cúbica: una línea de cresta sin quiebres."""
    d = 'M%s %s' % pts[0]
    for i in range(len(pts) - 1):
        p0 = pts[max(i - 1, 0)]; p1 = pts[i]; p2 = pts[i + 1]; p3 = pts[min(i + 2, len(pts) - 1)]
        c1 = (p1[0] + (p2[0] - p0[0]) / 6, p1[1] + (p2[1] - p0[1]) / 6)
        c2 = (p2[0] - (p3[0] - p1[0]) / 6, p2[1] - (p3[1] - p1[1]) / 6)
        d += ' C%.1f %.1f %.1f %.1f %s %s' % (c1 + c2 + p2)
    return d

def capa(pts):
    return suave(pts) + ' V%d H0Z' % ALTO

def arbol(x, y, t, alto=False):
    """Silueta de árbol: copa de círculos sobre tronco corto, o árbol alto de copa en sombrilla."""
    if alto:
        h = t * 2.6
        return ('<path class="tronco" d="M%.1f %.1f v-%.1f"/>' % (x, y + 2, h)) + \
               '<ellipse cx="%.1f" cy="%.1f" rx="%.1f" ry="%.1f"/>' % (x, y - h, t * .9, t * .38)
    s = '<rect x="%.1f" y="%.1f" width="%.1f" height="%.1f"/>' % (x - t * .08, y - t * .5, t * .16, t * .6)
    for dx, dy, r in ((0, -t * .95, t * .5), (-t * .38, -t * .7, t * .38), (t * .4, -t * .68, t * .36)):
        s += '<circle cx="%.1f" cy="%.1f" r="%.1f"/>' % (x + dx, y + dy, r)
    return s

def arboleda(pts, desde, hasta, n, tmin, tmax, altos=.18):
    out = []
    for _ in range(n):
        x = random.uniform(desde, hasta)
        out.append(arbol(x, y_en(pts, x) + 1, random.uniform(tmin, tmax), random.random() < altos))
    return ''.join(out)

# --- capas (y base, amplitud, ondulación, picos) ---
c5 = cresta(262, 70, 260, 4)
c4 = cresta(318, 72, 210, 4)
c3 = cresta(376, 56, 170, 3)
c2 = cresta(440, 44, 140, 3)
c1 = cresta(505, 34, 120, 2)

colores = {  # (arriba, abajo): la bruma aclara lo lejano
    'c5': ('#9C8FB4', '#827AA4'), 'c4': ('#7A7099', '#625C86'), 'c3': ('#565478', '#45466C'),
    'c2': ('#353B5E', '#2A3154'), 'c1': ('#1B2440', '#121A30'),
}
defs = ''.join(
    '<linearGradient id="g-%s" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="%s"/><stop offset="1" stop-color="%s"/></linearGradient>' % (k, a, b)
    for k, (a, b) in colores.items())
defs += '<linearGradient id="g-bruma" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#FFD9C2" stop-opacity="0"/><stop offset=".45" stop-color="#FFD9C2" stop-opacity=".28"/><stop offset="1" stop-color="#FFD9C2" stop-opacity="0"/></linearGradient>'

def bruma(base, alto):
    return '<rect class="bruma" x="0" y="%d" width="%d" height="%d" fill="url(#g-bruma)"/>' % (base - alto // 3, ANCHO, alto)

# hileras de cafetal en la loma más cercana: siguen la cresta, punteadas como matas
hileras = ''.join(
    '<path d="%s" fill="none" stroke="#26335A" stroke-width="6" stroke-linecap="round" stroke-dasharray="0.1 9" opacity=".6"/>' %
    suave([(x, y + off + 5 * math.sin(x / 80)) for x, y in c1 if a <= x <= b])
    # cada hilera más corta que la de arriba y corrida: el cafetal sigue la forma de la loma
    for off, a, b in ((20, 420, 900), (31, 360, 980), (42, 330, 1040), (53, 380, 1010), (64, 470, 930)))

# luces: un pueblo lejano en c3 y fincas sueltas en c2; siempre por encima de la capa de adelante
def luces(capa_pts, delante, n, desde, hasta, rmin, rmax, prof):
    out = []
    while len(out) < n:
        x = random.uniform(desde, hasta)
        y = y_en(capa_pts, x) + random.uniform(8, prof)
        if y < y_en(delante, x) - 6:
            out.append('<circle cx="%.1f" cy="%.1f" r="%.1f"/>' % (x, y, random.uniform(rmin, rmax)))
    return ''.join(out)

luces_svg = ('<g class="luces" fill="#FFD27A">' + luces(c3, c2, 22, 980, 1180, 1.4, 2.4, 40)
             + luces(c2, c1, 9, 120, 1500, 1.8, 2.8, 30) + '</g>')

svg = (
    '<svg class="cordillera" viewBox="0 0 %d %d" preserveAspectRatio="xMidYMax slice" aria-hidden="true">' % (ANCHO, ALTO)
    + '<defs>' + defs + '</defs>'
    + '<path class="c5" fill="url(#g-c5)" d="%s"/>' % capa(c5) + bruma(330, 120)
    + '<path class="c4" fill="url(#g-c4)" d="%s"/>' % capa(c4) + bruma(385, 110)
    + '<g class="c3" fill="url(#g-c3)"><path d="%s"/></g>' % capa(c3) + bruma(445, 90)
    # de noche las capas lejanas se oscurecen (el JS la sube de 0 a .55; sin JS queda la noche)
    + '<clipPath id="clip-lejos"><path d="%s"/><path d="%s"/><path d="%s"/></clipPath>' % (capa(c5), capa(c4), capa(c3))
    + '<rect class="noche-lejos" x="0" y="0" width="%d" height="%d" fill="#121933" opacity=".55" clip-path="url(#clip-lejos)"/>' % (ANCHO, ALTO)
    + '<g class="c2" fill="#2F3657" stroke="#2F3657"><path fill="url(#g-c2)" stroke="none" d="%s"/>%s</g>' % (capa(c2), arboleda(c2, 0, ANCHO, 34, 7, 13))
    + luces_svg
    + '<g class="c1" fill="#17203A" stroke="#17203A"><path fill="url(#g-c1)" stroke="none" d="%s"/>%s%s</g>' % (capa(c1), arboleda(c1, 0, ANCHO, 26, 14, 26, .25), hileras)
    + '</svg>')

root = pathlib.Path(sys.argv[1])
proto = root / '05_Landing/diseno/prototipo.html'
html = proto.read_text(encoding='utf-8')
nuevo, n = re.subn(r'<svg class="cordillera".*?</svg>', lambda m: svg, html, count=1, flags=re.S)
assert n == 1, 'no encontré <svg class="cordillera">'
proto.write_text(nuevo, encoding='utf-8', newline='\n')
print('cordillera: %d caracteres' % len(svg))
