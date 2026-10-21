# Tour conjunto — Feria (Armenia Mantequilla)

Landing independiente, con pasarela de pagos (OnePay) y dominio propio, para un tour conjunto de 3 fechas (diciembre, grupos de 15, desde Medellín) con 4 empresarios locales. Acordado en la reunión «Informe FyGP» (16 oct 2026): costo fijado en **$440.000** (ver `00_Reuniones/`).

## Mapa
| Carpeta | Qué hay |
|---|---|
| `00_Reuniones/` | Notas de reunión |
| `01_WhatsApp/` | Textos originales de Jhon, sin editar, y audios de Julio con su transcripción literal |
| `02_Empresas/` | Una carpeta por empresa: `FICHA.md`, logo, documentos |
| `03_Fotos/` | Fotos por empresa + `INVENTARIO.md`. Atardeceres: enlace al álbum oficial en `atardeceres-dalia-dorada/FOTOS-OFICIALES.md` |
| `04_Contenido/` | Textos del tour (nombre, fechas, precio, itinerario) |
| `05_Landing/` | Brief y construcción |
| `06_Finanzas/` | Cuentas |

## Empresas participantes (4, confirmado en la reunión del 16 oct)
1. **Café Cabras** — café de origen, licor de café, taller de métodos. *(Caterine y Fernando)*
2. **Finca Mirador La Florida** — mirador, horno de barro, atardecer.
3. **Bonne Nature** — quesería artesanal colombo-francesa. *(Familia Mejía Mejía)*
4. **Atardeceres SAS / Dalia Dorada** — finca cafetera en Armenia Mantequilla, 1.750 m.s.n.m., «Primera fila al atardecer». *(Jhon Velásquez, fundador)* Parada 1: cultivos de café y taller de turismo. Logos y ficha en `02_Empresas/atardeceres-dalia-dorada/`.

*Ojo: en el audio la cuarta se llama «quesería de Mintí»; confirmar si es Bonne Nature.* Itinerario completo en `05_Landing/BRIEF-DISENO-Y-DESARROLLO.md`.

## Reglas
- Lo original no se edita; el trabajo va en archivos aparte.
- Cada dato lleva su fuente. Sin fuente, no se publica.
- Las afirmaciones de salud o regulatorias (leche cruda, sin conservantes, registro INVIMA, licor) se verifican antes de salir en la web.
- Fotos auténticas. IA solo para mejorar o reescalar fotos reales, nunca para generar imágenes (aclaración de Julio, 21 oct).

## Entrada de material nuevo
Lo que llegue por WhatsApp o correo se deja en `01_WhatsApp/` (texto) o se copia directo a `02_Empresas/<empresa>/` (logos, documentos) y `03_Fotos/<empresa>/` (fotos). No se crean carpetas de paso: cada archivo entra ya clasificado y registrado en la ficha o en `03_Fotos/INVENTARIO.md`.

## Código
Repo privado: https://github.com/softux-co/tourmantequilla (ramas `main` y `staging`).
Copia local: `/Volumes/Softux-Workspace-External/04-Repos-Dev/tourmantequilla`.
Base: copia limpia, sin historial, del child theme de Dalia Dorada. Qué se heredó y qué hay que depurar: `docs/BASE-HEREDADA.md` en el repo. **No hay scripts de deploy a propósito**: los de Dalia Dorada apuntan a su producción.

## Qué cambió (2026-10-19) — para quien llega nuevo
Fuente de todo: la transcripción de la reunión «Informe FyGP» (16 oct), `00_Reuniones/2026-10-16_informe-fygp_TRANSCRIPCION.md`. Trae errores de voz; lo dudoso va marcado ⛔ en el brief.

**Lo que sabemos del tour**
- **4 empresas, mismo peso visual:** Café Cabras, Finca Mirador La Florida, una quesería (⛔ nombre: en el audio «quesería de Mintí»; ¿Bonne Nature?) y **Atardeceres SAS / Dalia Dorada** (parada 1, no anfitriona).
- **Un día:** Medellín → La Florida → Atardeceres (cultivos de café y taller de turismo) → Café Cabras (almuerzo, café y digestivo) → taller de quesos → ~5 p. m. pizza y atardecer en La Florida.
- **3 fechas en diciembre** (⛔ exactas), grupos de **15**, **$440.000** (⛔ ¿por persona?). Pago por **OnePay** (⛔ confirmar). Jhon factura.
- **QR** hacia la info y las fechas para el estand de la feria (29 a 31 oct).

**Qué se agregó o corrigió en esta carpeta**
- `00_Reuniones/`: la **transcripción literal** (nueva) y las notas corregidas con lo que no traía el resumen del correo.
- `02_Empresas/atardeceres-dalia-dorada/`: **nueva**, con `FICHA.md` y `logo/` (letrero, finca, lema, 1.750, Dalia Dorada).
- `03_Fotos/atardeceres-dalia-dorada/FOTOS-OFICIALES.md`: **nuevo**, único lugar con el enlace al álbum de iCloud (aún no descargado). Anotado también en `INVENTARIO.md`.
- `04_Contenido/CONTENIDO-BASE.md` y `05_Landing/BRIEF-DISENO-Y-DESARROLLO.md` (borrador 2): itinerario, 4 empresas, cupo, OnePay, QR.
- `PENDIENTES.md`: ítems 3, 10 y 13 reescritos; nuevos 14 a 18.

**Dónde mirar primero:** `05_Landing/BRIEF-DISENO-Y-DESARROLLO.md`, luego `PENDIENTES.md`. El código está en el repo `softux-co/tourmantequilla` (PR #1 abierto con estos mismos cambios).
