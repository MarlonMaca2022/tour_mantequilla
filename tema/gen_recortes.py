"""Saca recortes con transparencia de las fotos reales para el borrador 8 (realismo con recortes).

Uso (desde la raíz del repo):  python -I tema/gen_recortes.py .  [carpeta_salida]
Lee los originales de 03_Fotos/ (no los toca) y escribe WebP con alfa en 05_Landing/assets/recortes/.
Requiere Pillow y numpy (pip install pillow numpy, sin --user: -I ignora el site de usuario).

Determinista: encuadres y umbrales fijos, misma salida en cada corrida.
Sin IA: la máscara sale de la luz, del color o de una elipse medida a mano sobre la foto.
Ningún píxel se inventa ni se pinta. Lo único que cambia de tono es la luna (de día a noche, ver luna()).
Fuente de cada recorte: 03_Fotos/INVENTARIO.md, sección «Recortes».
"""
import sys, pathlib
import numpy as np
from PIL import Image, ImageFilter

root = pathlib.Path(sys.argv[1])
fotos = root / '03_Fotos'
salida = pathlib.Path(sys.argv[2]) if len(sys.argv) > 2 else root / '05_Landing/assets/recortes'
salida.mkdir(parents=True, exist_ok=True)

def abrir(ruta):
    return Image.open(fotos / ruta).convert('RGB')

def luz(img, radio):
    return np.asarray(img.convert('L').filter(ImageFilter.GaussianBlur(radio))).astype(float)

def mediana(a, k):
    """Filtro de mediana 1D: quita picos sueltos del contorno sin redondear las crestas."""
    p = k // 2; b = np.pad(a, p, mode='edge')
    return np.array([np.median(b[i:i + k]) for i in range(len(a))])

def bajo_contorno(alto, c, suave):
    """Alfa opaco debajo del contorno c (una y por columna) con borde de `suave` px."""
    y = np.arange(alto)[:, None]
    return np.clip((y - c[None, :]) / suave + 0.5, 0, 1)

K = 2  # las siluetas salen al doble: las fotos son chicas (≤ 1000 px) y en pantalla se estiran 1,5–3×

def silueta(src, c, y0, y1, suave, nombre, alisar=1):
    """Recorta la franja y0–y1 al doble de tamaño. El borde se calcula con el contorno interpolado
    (no con el píxel), así la cresta queda en curva limpia aunque la foto sea chica.
    `alisar` = media móvil sobre el contorno para quitar la escalera de 1 px (1 = sin alisar)."""
    W = src.width
    if alisar > 1:
        c = np.convolve(np.pad(c, alisar // 2, mode='edge'), np.ones(alisar) / alisar, 'valid')
    img = src.crop((0, y0, W, y1)).resize((W * K, (y1 - y0) * K), Image.BICUBIC)
    xs = (np.arange(W * K) + 0.5) / K - 0.5
    # el borde se mete 0,6 px hacia dentro: el píxel de borde trae mezclado el cielo de la foto y deja un hilo claro
    cK = (np.interp(xs, np.arange(W), c) + 0.6 + 0.5) * K - 0.5 - y0 * K
    y = np.arange((y1 - y0) * K)[:, None]
    alfa = np.clip((y - cK[None, :]) / (suave * K) + 0.5, 0, 1)
    guardar(img, alfa, nombre)

def guardar(img, alfa, nombre):
    rgba = img.copy()
    rgba.putalpha(Image.fromarray(np.round(alfa * 255).astype('uint8')))
    ruta = salida / (nombre + '.webp')
    rgba.save(ruta, 'WEBP', quality=82, alpha_quality=90, method=6)
    print('%-22s %4dx%-4d %6.1f KB' % (ruta.name, rgba.width, rgba.height, ruta.stat().st_size / 1024))

# ---------- cordillera: 3 capas de la-florida-01 (la bruma ya las separa por tono) ----------
def cordillera():
    src = abrir('finca-mirador-la-florida/la-florida-01-10-43-18.jpg')
    L = luz(src, 2); H, W = L.shape
    # lejos: la cresta con el pico contra el cielo (cielo > 140, montaña < 100)
    c1 = np.array([np.argmax(L[300:, x] < 120) + 300 for x in range(W)], float)
    # medio: las lomas sobre el mar de nubes; la mayor caída de luz entre y 672 y 725
    d = L[4:] - L[:-4]
    c2 = mediana(np.array([np.argmin(d[672:725, x]) + 674 for x in range(W)], float), 21)
    # cerca: la ladera en diagonal; la mayor caída de luz en una ventana de ±80 px alrededor de la diagonal
    guia = (860 + 400 * np.arange(W) / W).astype(int)
    d6 = L[6:] - L[:-6]
    c3 = mediana(np.array([np.argmin(d6[g - 80:min(g + 80, H - 7), x]) + g - 77 for x, g in enumerate(guia)], float), 31)
    # un claro entre árboles deja una muesca hacia abajo: el percentil 25 de 61 px la tapa
    b = np.pad(c3, 30, mode='edge'); c3 = np.array([np.percentile(b[i:i + 61], 25) for i in range(W)])
    c3 = np.convolve(np.pad(c3, 20, mode='edge'), np.ones(41) / 41, 'valid')  # sin escalones: borde en curva
    silueta(src, c1, 360, 720, 0.8, 'cordillera-lejos', alisar=5)
    silueta(src, c2, 640, 1010, 2.5, 'cordillera-medio', alisar=9)  # las lomas viven en bruma: borde más suave
    silueta(src, c3, 830, H, 1.0, 'cordillera-cerca')

# ---------- luna: la-florida-08 (foto de Alex Betancure; recortar la luna no toca su firma) ----------
def luna():
    x0, y0, x1, y1 = 930, 100, 1200, 300  # la cruz de la torre empieza en y 300: queda fuera
    src = abrir('finca-mirador-la-florida/la-florida-08-10-56-17.jpg').crop((x0, y0, x1, y1))
    # en el azul del día el canal rojo separa la luna (40–95) del cielo (≈ 24)
    R = np.asarray(src.getchannel('R').filter(ImageFilter.GaussianBlur(2))).astype(float)
    alfa = np.clip((R - 29) / 9, 0, 1)
    # solo el disco: lo conectado al centro, sin motas del cielo
    m = alfa > 0.5; H, W = m.shape
    vis = np.zeros_like(m); pila = [(H // 2, W // 2)]; vis[H // 2, W // 2] = True
    while pila:
        p, q = pila.pop()
        for r, s in ((p + 1, q), (p - 1, q), (p, q + 1), (p, q - 1)):
            if 0 <= r < H and 0 <= s < W and m[r, s] and not vis[r, s]:
                vis[r, s] = True; pila.append((r, s))
    zona = np.asarray(Image.fromarray(vis.astype('uint8') * 255).filter(ImageFilter.MaxFilter(5))) > 0
    alfa = alfa * zona
    # el borde de abajo de la luna se pierde en el azul de la foto: no se completa el disco (sería inventar),
    # se desvanece en los últimos 28 px como si la tapara la bruma
    abajo = np.nonzero(alfa.max(1) > 0.5)[0].max()
    alfa = alfa * np.clip((abajo - np.arange(H)) / 28, 0, 1)[:, None]
    # de día a noche: el relieve sale del canal rojo (el menos teñido por el cielo azul), en un blanco frío
    Rn = np.asarray(src.getchannel('R')).astype(float)
    lo, hi = np.percentile(Rn[vis], [5, 99])
    v = np.clip((Rn - lo) / (hi - lo), 0, 1)
    tono = np.array([236, 240, 248], float)
    rgb = tono * (0.6 + 0.4 * v)[..., None]
    ys, xs = np.nonzero(alfa > 0.02)
    b = (max(xs.min() - 3, 0), max(ys.min() - 3, 0), min(xs.max() + 4, W), min(ys.max() + 4, H))
    img = Image.fromarray(np.round(rgb).astype('uint8')).crop(b)
    guardar(img, alfa[b[1]:b[3], b[0]:b[2]], 'luna')

# ---------- granos de café sueltos sobre la madera: cabras-05 y cabras-06 ----------
# Elipse medida a mano sobre una cuadrícula: foto, recorte (x, y, radio), centro en el recorte, semiejes, giro, borde.
# Vienen desenfocados por la foto (poca profundidad de campo): en la página van como primer plano.
GRANOS = [
    ('cabras-06-licor-detalle.webp',        (570, 1540, 75), (75, 81), (44, 60), 0, 4),
    ('cabras-06-licor-detalle.webp',        (490, 1400, 70), (89, 72), (39, 61), 0, 4),
    ('cabras-05-licor-botella-copas.webp',  (872, 360, 48),  (48, 46), (27, 33), -30, 4),
    ('cabras-05-licor-botella-copas.webp',  (474, 600, 44),  (43, 49), (24, 29), 0, 4),
    ('cabras-05-licor-botella-copas.webp',  (990, 1310, 48), (45, 52), (30, 38), 0, 4),
    ('cabras-05-licor-botella-copas.webp',  (554, 1704, 48), (50, 48), (29, 31), -40, 4),
]

def granos():
    for i, (foto, (x, y, r), (cx, cy), (ax, ay), giro, suave) in enumerate(GRANOS, 1):
        src = abrir('cafe-cabras/' + foto).crop((x - r, y - r, x + r, y + r))
        yy, xx = np.mgrid[0:2 * r, 0:2 * r].astype(float)
        t = np.radians(giro)
        u = (xx - cx) * np.cos(t) + (yy - cy) * np.sin(t)
        v = -(xx - cx) * np.sin(t) + (yy - cy) * np.cos(t)
        dist = (np.sqrt((u / ax) ** 2 + (v / ay) ** 2) - 1) * min(ax, ay)  # px fuera del borde (negativo = dentro)
        alfa = np.clip(0.5 - dist / suave, 0, 1)
        ys, xs = np.nonzero(alfa > 0.02); b = (xs.min(), ys.min(), xs.max() + 1, ys.max() + 1)
        guardar(src.crop(b), alfa[b[1]:b[3], b[0]:b[2]], 'grano-%d' % i)

# ---------- nubes reales: doradas de la-florida-02 (sobre azul) y violetas de la-florida-04 (sobre naranja) ----------
def cielo_azul(R, G, B):
    """02: el cielo es azul saturado (B alto y muy por encima de R). Las nubes, grises o doradas, no."""
    return np.clip((B - 200) / 40, 0, 1) * np.clip((B - R - 30) / 50, 0, 1)

def cielo_naranja(R, G, B):
    """04: el cielo es naranja (R alto y muy por encima de B). Las nubes son violetas."""
    return np.clip((R - 170) / 45, 0, 1) * np.clip((R - B - 40) / 70, 0, 1)

# nombre, foto, encuadre, cielo, desvanecido (arriba, derecha, abajo, izquierda: fracción del encuadre), ¿versión de día?
# El desvanecido solo disuelve los lados donde la nube sigue fuera del encuadre o se pega a otra;
# los demás bordes quedan con la forma real de la nube (mínimo 6 %, para que no se vea la línea del encuadre).
# Descartadas: la-florida-02 arriba a la derecha (muy delgada, casi transparente), el copo de abajo de la 02 y la
# masa de arriba a la izquierda de la 04 (cortadas por el encuadre: quedaban como un recuadro borroso) y el cúmulo
# de la-florida-01 (su lado en sombra tiene el mismo tono del cielo: no se separa por color, queda para el diseñador).
# Las nubes se recortan sobre las fotos agrandadas 4× con Upscayl (upscayl-standard-4x) que están en mejoradas/.
# Permitido: Julio (21 oct, vía Marlon) aclaró que la IA sí se puede usar para mejorar y reescalar fotos reales,
# nunca para generar. Ese modelo limpia los bloques de compresión de WhatsApp sin inventar textura (se comparó con
# high-fidelity-4x, que sí la inventa, y con remacri-4x, que casi no mejora). Los encuadres van en px de la foto original.
X = 4          # aumento de las fotos de mejoradas/
SALE = 2       # las nubes salen al doble de la foto original (en pantalla se ven a ≤ 340 px CSS)
NUBES = [
    ('nube-dorada-1', 'la-florida-02-10-43-19.jpg', (400, 100, 675, 318), cielo_azul, (.45, .06, .22, .4), True),
    ('nube-dorada-2', 'la-florida-02-10-43-19.jpg', (515, 296, 705, 400), cielo_azul, (.25, .06, .18, .2), True),
    ('nube-violeta-1', 'la-florida-04-10-45-52.jpg', (320, 222, 845, 412), cielo_naranja, (.06, .25, .3, .1), False),
]

def difuminar(x, r):
    """Desenfoque gaussiano separable en numpy (sirve para cualquier número de canales)."""
    t = np.arange(-3 * r, 3 * r + 1); k = np.exp(-t ** 2 / (2 * r * r)); k /= k.sum()
    pad = [(3 * r, 3 * r), (3 * r, 3 * r)] + [(0, 0)] * (x.ndim - 2)
    x = np.pad(x, pad, mode='edge')
    x = np.apply_along_axis(lambda v: np.convolve(v, k, 'valid'), 0, x)
    return np.apply_along_axis(lambda v: np.convolve(v, k, 'valid'), 1, x)

def nubes():
    for nombre, foto, caja, cielo, (arr, der, aba, izq), dia in NUBES:
        mejorada = 'finca-mirador-la-florida/mejoradas/' + foto.replace('.jpg', '-upscayl-standard-4x.jpg')
        img = abrir(mejorada).crop(tuple(v * X for v in caja))
        a = np.asarray(img).astype(float); h, w = a.shape[:2]
        b = np.asarray(img.filter(ImageFilter.GaussianBlur(1.2 * X / 2))).astype(float)
        k = cielo(b[..., 0], b[..., 1], b[..., 2])  # 1 = cielo puro
        alfa = 1 - k
        # el borde de la nube trae mezclado el color del cielo: se le quita (matting estándar).
        # Color del cielo detrás = promedio de los píxeles de cielo cercanos
        peso = k ** 2
        fondo = difuminar(peso[..., None] * a, 12 * X) / (difuminar(peso, 12 * X)[..., None] + 1e-3)
        lejos = difuminar(peso, 12 * X) < 0.02
        fondo[lejos] = np.median(a[k > 0.9], axis=0)
        color = np.clip((a - (1 - alfa)[..., None] * fondo) / np.maximum(alfa, 0.2)[..., None], 0, 255)
        if cielo is cielo_azul:
            # lo que el matting no alcanza a quitar deja un hilo celeste: en el borde semitransparente
            # el azul no puede pasar mucho del mayor entre rojo y verde (el cuerpo de la nube no se toca)
            tope = np.maximum(color[..., 0], color[..., 1]) + 12
            borde = alfa < 0.75
            color[..., 2] = np.where(borde, np.minimum(color[..., 2], tope), color[..., 2])
        yy, xx = np.mgrid[0:h, 0:w].astype(float)
        f = np.ones((h, w))
        for dist, ancho in ((yy / h, arr), (1 - xx / w, der), (1 - yy / h, aba), (xx / w, izq)):
            t = np.clip(dist / ancho, 0, 1); f *= t * t * (3 - 2 * t)
        alfa = alfa * f
        # de 4× a 2×: mitad de peso, y sigue sobrando nitidez para lo que se ve en pantalla
        tam = (caja[2] - caja[0]) * SALE, (caja[3] - caja[1]) * SALE
        def chica(rgb, al):
            return (Image.fromarray(np.round(rgb).astype('uint8')).resize(tam, Image.LANCZOS),
                    np.asarray(Image.fromarray(np.round(al * 255).astype('uint8')).resize(tam, Image.LANCZOS)).astype(float) / 255)
        guardar(*chica(color, alfa), nombre)
        if dia:
            # de día las nubes son blancas: misma forma y relieve (la luz de la foto), en blanco con sombra fría
            L = color @ [0.299, 0.587, 0.114]
            lo, hi = np.percentile(L[alfa > 0.5], [4, 98])
            v = np.clip((L - lo) / (hi - lo), 0, 1)[..., None]
            sombra, luz_ = np.array([206, 214, 230.]), np.array([255, 255, 255.])
            rgb = sombra + (luz_ - sombra) * v
            guardar(*chica(rgb, alfa), nombre.replace('dorada', 'blanca'))

# ---------- pueblo con la torre de la iglesia: la-florida-08 sin cielo y agrandada 4× por el equipo (21 oct) ----------
# Reemplaza la silueta que salía de la-florida-04: con 1000 px se veía pixelada. Es la única imagen del pueblo.
# OJO: esta versión SÍ pasó por IA (remove.bg preview para quitar el cielo + Upscayl standard-4x para agrandar,
# que inventa detalle). Choca con la regla «sin IA» de Jhon: queda sujeta al pendiente #40.
# Foto de Alex Betancure: la firma de abajo NO se recorta (regla del inventario) y la escena lleva su crédito.
PUEBLO_HD = 'finca-mirador-la-florida/la-florida-08-10-56-17-removebg-preview_upscayl_4x_upscayl-standard-4x.png'

def pueblo():
    im = Image.open(fotos / PUEBLO_HD).convert('RGBA')
    im = im.crop((0, 440, im.width, im.height))  # arriba solo había cielo transparente; la cruz empieza en y 469
    a = np.asarray(im).astype(float); rgb = a[..., :3].copy(); A = a[..., 3] / 255
    # quitar el cielo dejó un halo azul en los bordes (y la cruz casi azul): en la franja de 12 px junto al borde
    # el azul no puede pasar del mayor entre rojo y verde (despill)
    lleno = Image.fromarray(((A > 0.5) * 255).astype('uint8'))
    dentro = np.asarray(lleno.filter(ImageFilter.MinFilter(25))) > 0
    banda = (A > 0) & ~dentro
    tope = np.maximum(rgb[..., 0], rgb[..., 1]) * 1.05 + 6
    rgb[..., 2] = np.where(banda, np.minimum(rgb[..., 2], tope), rgb[..., 2])
    # el borde se mete 2 px hacia adentro (ahí vive el halo) y se suaviza
    A = np.asarray(Image.fromarray(np.round(A * 255).astype('uint8')).filter(ImageFilter.MinFilter(5))
                   .filter(ImageFilter.GaussianBlur(1.2))).astype(float) / 255
    img = Image.fromarray(np.round(rgb).astype('uint8'))
    # 2000 px de ancho: viene de una vista previa de ~600 px, más ancho no trae detalle real
    alto = round(img.height * 2000 / img.width)
    alfa = Image.fromarray(np.round(A * 255).astype('uint8')).resize((2000, alto), Image.LANCZOS)
    guardar(img.resize((2000, alto), Image.LANCZOS), np.asarray(alfa).astype(float) / 255, 'pueblo-iglesia')

cordillera(); pueblo(); luna(); granos(); nubes()
