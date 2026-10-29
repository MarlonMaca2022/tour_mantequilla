#!/usr/bin/env bash
# Sube el child theme a PRODUCCIÓN: https://tourmantequilla.com (Hostinger).
# Uso (desde la raíz del repo):
#   bash tema/deploy-hostinger.sh                   → muestra qué cambiaría, sin subir nada
#   bash tema/deploy-hostinger.sh --subir           → sube el tema
#   bash tema/deploy-hostinger.sh --subir --forzar  → sube aunque haya archivos editados en el servidor
#
# Qué hace al subir:
#   1. Compara cada archivo del servidor con el último commit. Si uno se editó allá (no coincide ni con
#      HEAD ni con tu copia), se detiene: primero hay que traer ese cambio al repo. Pasó el 2026-10-28
#      con templates/landing-tour.php.
#   2. Revisa la sintaxis de cada PHP con el PHP de la web (8.3) antes de tocar el sitio.
#   3. Respalda el tema del servidor en ~/tmp-tourmantequilla/respaldos/tema-FECHA.tar.gz.
#   4. Cambia la carpeta del tema de una vez (mv) y, si la portada no responde 200, vuelve a la anterior.
#
# La configuración inicial del sitio (idioma, WooCommerce, páginas, producto) vivía en este script cuando
# el sitio era staging (ver commit 2d55c3a). NO se corre en producción: ocultaba el sitio con
# «Próximamente», lo marcaba noindex, activaba el pago de prueba y devolvía el producto al nombre viejo.
# OJO: la cuenta tiene sitios de otros clientes (daliadorada.com incluido). Todo va con rutas fijas.
set -euo pipefail

HOST=u551250111@217.196.55.28
PORT=65002
KEY="$HOME/.ssh/tourmantequilla_deploy"
SITIO=tourmantequilla.com
TEMAS=/home/u551250111/domains/$SITIO/public_html/wp-content/themes
TMP=/home/u551250111/tmp-tourmantequilla
PHP=/opt/alt/php83/usr/bin/php

SUBIR=no
FORZAR=no
for arg in "$@"; do
  case "$arg" in
    --subir) SUBIR=si ;;
    --forzar) FORZAR=si ;;
    *) echo "Opción desconocida: $arg (usa --subir y, si hace falta, --forzar)"; exit 2 ;;
  esac
done

# arrays: el home de Windows tiene espacios («Marlon Hoyos»)
SSH=(ssh -i "$KEY" -o IdentitiesOnly=yes -p "$PORT" "$HOST")
SCP=(scp -i "$KEY" -o IdentitiesOnly=yes -P "$PORT")

DIR=$(cd "$(dirname "$0")" && pwd)
REPO=$(cd "$DIR/.." && pwd)
TRABAJO=$(mktemp -d)
trap 'rm -rf "$TRABAJO"' EXIT

echo "== comparando el tema de $SITIO con el repo"
"${SSH[@]}" "cd $TEMAS/tourmantequilla-child && find . -type f -exec md5sum {} +" > "$TRABAJO/remoto.txt"

# Por archivo: R = servidor, L = tu copia, H = último commit.
#   R = L → igual · R = H ≠ L → cambio tuyo (sube) · R ≠ L y R ≠ H → editado en el servidor (frena)
python -I - "$REPO" "$TRABAJO/remoto.txt" "$TRABAJO/plan.txt" <<'PY'
import hashlib, pathlib, subprocess, sys
repo, remoto_txt, plan_txt = pathlib.Path(sys.argv[1]), sys.argv[2], sys.argv[3]
base = 'tema/tourmantequilla-child'
md5 = lambda b: hashlib.md5(b).hexdigest()

remoto = {}
for linea in open(remoto_txt, encoding='utf-8'):
    h, ruta = linea.rstrip('\n').split('  ', 1)
    remoto[ruta[2:]] = h  # sin el «./»

local = {p.relative_to(repo / base).as_posix(): md5(p.read_bytes())
         for p in (repo / base).rglob('*') if p.is_file()}

# blobs del último commit en una sola llamada a git
rutas = sorted(set(remoto) | set(local))
pedido = ''.join(f'HEAD:{base}/{r}\n' for r in rutas).encode('utf-8')
salida = subprocess.run(['git', '-C', str(repo), 'cat-file', '--batch'], input=pedido, capture_output=True, check=True).stdout
head, i = {}, 0
for r in rutas:
    fin = salida.index(b'\n', i)
    cab = salida[i:fin].split()
    if cab[-1] == b'missing':
        i = fin + 1
        continue
    n = int(cab[2])
    head[r] = md5(salida[fin + 1:fin + 1 + n])
    i = fin + 1 + n + 1

filas = []
for r in rutas:
    R, L, H = remoto.get(r), local.get(r), head.get(r)
    if R == L:
        continue
    if R is None:
        filas.append(('nuevo', r))
    elif R == H:
        filas.append(('se borra' if L is None else 'cambia', r))
    else:
        filas.append(('EDITADO EN EL SERVIDOR', r))
with open(plan_txt, 'w', encoding='utf-8') as f:
    for estado, r in filas:
        f.write(f'{estado}\t{r}\n')
PY

if [ ! -s "$TRABAJO/plan.txt" ]; then
  echo "El servidor ya tiene exactamente tu copia del tema. Nada que subir."
  exit 0
fi
column -t -s $'\t' "$TRABAJO/plan.txt" | sed 's/^/   /'

if grep -q '^EDITADO EN EL SERVIDOR' "$TRABAJO/plan.txt" && [ "$FORZAR" != si ]; then
  echo
  echo "⛔ Hay archivos que alguien cambió directo en el servidor y no están en el repo."
  echo "   Trae esos cambios al repo (y a 05_Landing/diseno/prototipo.html si son de la landing),"
  echo "   o vuelve a correr con --forzar para pisarlos (quedan en el respaldo)."
  exit 1
fi

if [ "$SUBIR" != si ]; then
  echo
  echo "Vista previa: no se subió nada. Para subir: bash tema/deploy-hostinger.sh --subir"
  exit 0
fi

echo "== empaquetando el tema"
ZIP="$TRABAJO/tourmantequilla-child.zip"
# zip con «/» (Compress-Archive de PowerShell 5.1 escribe «\» y Linux no lo abre)
python -I -c "import sys,zipfile,pathlib; r=pathlib.Path(sys.argv[1]); z=zipfile.ZipFile(sys.argv[2],'w',zipfile.ZIP_DEFLATED); [z.write(f, f.relative_to(r.parent).as_posix()) for f in sorted(r.rglob('*')) if f.is_file()]; z.close()" "$DIR/tourmantequilla-child" "$ZIP"

"${SSH[@]}" "mkdir -p $TMP"
"${SCP[@]}" "$ZIP" "$HOST:$TMP/tourmantequilla-child.zip"

"${SSH[@]}" "TEMAS=$TEMAS TMP=$TMP PHP=$PHP SITIO=$SITIO bash -s" <<'REMOTO'
set -euo pipefail
NUEVO=$TMP/nuevo
VIEJO=$TMP/viejo
rm -rf "$NUEVO" "$VIEJO"
mkdir -p "$NUEVO" "$TMP/respaldos"
unzip -q "$TMP/tourmantequilla-child.zip" -d "$NUEVO"
rm -f "$TMP/tourmantequilla-child.zip"

echo "== sintaxis PHP (PHP 8.3, el de la web)"
find "$NUEVO" -name '*.php' -print0 | while IFS= read -r -d '' f; do
  "$PHP" -l "$f" >/dev/null || { echo "⛔ Error de sintaxis en ${f#$NUEVO/}. No se tocó el sitio."; exit 1; }
done
find "$NUEVO" -type d -exec chmod 755 {} +
find "$NUEVO" -type f -exec chmod 644 {} +   # sin 644 Hostinger responde 403

RESPALDO="$TMP/respaldos/tema-$(date +%Y%m%d-%H%M%S).tar.gz"
tar czf "$RESPALDO" -C "$TEMAS" tourmantequilla-child
echo "== respaldo: $RESPALDO"

mv "$TEMAS/tourmantequilla-child" "$VIEJO"
mv "$NUEVO/tourmantequilla-child" "$TEMAS/tourmantequilla-child"

CODIGO=$(curl -s -o /dev/null -w '%{http_code}' "https://$SITIO/?tm-deploy=$(date +%s)" || true)
if [ "$CODIGO" != 200 ]; then
  rm -rf "$TEMAS/tourmantequilla-child"
  mv "$VIEJO" "$TEMAS/tourmantequilla-child"
  echo "⛔ La portada respondió $CODIGO con el tema nuevo. Se volvió al anterior."
  exit 1
fi
rm -rf "$VIEJO" "$NUEVO"
echo "== listo: la portada responde 200 con el tema nuevo"
REMOTO

echo "Tema actualizado en https://$SITIO/"
echo "Si cambiaste la landing y no se ve: WordPress → LiteSpeed Cache → Purgar todo (solo este sitio)."
