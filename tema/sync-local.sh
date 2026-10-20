#!/bin/bash
# Copia el child theme al sitio de Local (tourmantequilla.local). Correr después de cada cambio.
# Uso: bash tema/sync-local.sh
set -e
SRC="$(cd "$(dirname "$0")" && pwd)/tourmantequilla-child"
DST="$HOME/Local Sites/tourmantequilla/app/public/wp-content/themes/tourmantequilla-child"
rm -rf "$DST"
cp -r "$SRC" "$DST"
echo "Tema copiado a $DST"
