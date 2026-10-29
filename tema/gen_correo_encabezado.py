"""Encabezado de los correos de WooCommerce: atardecer + cordillera de la landing + nombre del tour.

Uso (desde la raíz del repo):
    python -I tema/gen_correo_encabezado.py <carpeta-con-fuentes>

La carpeta debe tener Alegreya.ttf (variable, google/fonts ofl/alegreya) y
AlegreyaSans-Bold.ttf (ofl/alegreyasans). Las fuentes no van al repo.
Sale en tema/tourmantequilla-child/assets/img/correo/encabezado.png (1200×360, se ve a 600 px).
PNG y no WebP: Outlook y varios clientes de correo no muestran WebP.
"""

import sys
from pathlib import Path

from PIL import Image, ImageDraw, ImageFont

RAIZ = Path(__file__).resolve().parent / 'tourmantequilla-child' / 'assets' / 'img'
ANCHO, ALTO = 1200, 360

# el mismo atardecer de la escena final de la landing (landing.css)
CIELO = [(0.0, (0x5B, 0x43, 0x66)), (0.55, (0xC7, 0x7E, 0x5A)), (0.9, (0xE9, 0xA2, 0x3B)), (1.0, (0xE9, 0xA2, 0x3B))]
CAL = (0xFB, 0xFC, 0xFA)


def degradado():
    im = Image.new('RGB', (ANCHO, ALTO))
    px = im.load()
    for y in range(ALTO):
        t = y / (ALTO - 1)
        for (t0, c0), (t1, c1) in zip(CIELO, CIELO[1:]):
            if t0 <= t <= t1:
                k = (t - t0) / (t1 - t0) if t1 > t0 else 0
                color = tuple(round(a + (b - a) * k) for a, b in zip(c0, c1))
                break
        for x in range(ANCHO):
            px[x, y] = color
    return im


def capa(im, nombre, alto, opacidad=1.0):
    """Pega un recorte a lo ancho, apoyado en el borde de abajo."""
    rec = Image.open(RAIZ / 'recortes' / f'{nombre}.webp').convert('RGBA')
    rec = rec.resize((ANCHO, round(rec.height * ANCHO / rec.width)), Image.LANCZOS)
    rec = rec.crop((0, 0, ANCHO, min(rec.height, alto)))
    if opacidad < 1:
        rec.putalpha(rec.getchannel('A').point(lambda a: round(a * opacidad)))
    im.alpha_composite(rec, (0, ALTO - rec.height))


def main():
    fuentes = Path(sys.argv[1])
    serif = ImageFont.truetype(str(fuentes / 'Alegreya.ttf'), 104)
    serif.set_variation_by_name('ExtraBold')
    sans = ImageFont.truetype(str(fuentes / 'AlegreyaSans-Bold.ttf'), 40)

    im = degradado().convert('RGBA')
    capa(im, 'cordillera-lejos', 230, 0.85)
    capa(im, 'cordillera-medio', 190)
    capa(im, 'cordillera-cerca', 130)

    d = ImageDraw.Draw(im)
    sombra = (0x1E, 0x2B, 0x45, 70)
    for texto, fuente, y in (('Tour de la Mantequilla', serif, 58), ('Montaña, tradición, café y quesos', sans, 178)):
        d.text((64 + 1, y + 2), texto, font=fuente, fill=sombra)
        d.text((64, y), texto, font=fuente, fill=CAL)

    salida = RAIZ / 'correo' / 'encabezado.png'
    salida.parent.mkdir(exist_ok=True)
    im.convert('RGB').save(salida, optimize=True)
    print(salida, salida.stat().st_size // 1024, 'KB')


if __name__ == '__main__':
    main()
