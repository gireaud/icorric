#!/usr/bin/env bash
#
# Instala y activa Polylang (gestión bilingüe EN/ES) en research.icor.cl.
# La configuración de idiomas se completa con el asistente de Polylang en el
# panel (es guiado y sencillo); este script solo deja el plugin listo.
#
# USO (como root en el droplet):
#   curl -fsSL https://raw.githubusercontent.com/gireaud/icorric/claude/icor-research-wordpress-setup-tp0dsx/provision/instalar-polylang.sh -o /tmp/polylang.sh && bash /tmp/polylang.sh
#
set -euo pipefail

DOMAIN="${DOMAIN:-research.icor.cl}"
WP_PATH="${WP_PATH:-/var/www/${DOMAIN}}"

log() { echo -e "\n\033[1;36m==> $*\033[0m"; }
die() { echo -e "\033[1;31m[ERROR] $*\033[0m"; exit 1; }

[ "$(id -u)" -eq 0 ] || die "Ejecuta este script como root (o con sudo)."
[ -f "${WP_PATH}/wp-load.php" ] || die "No se encontró WordPress en ${WP_PATH}."

WP="wp --allow-root --path=${WP_PATH}"

log "Instalando y activando Polylang"
$WP plugin install polylang --activate

# Refresca enlaces permanentes por si Polylang añade reglas de idioma.
$WP rewrite flush --hard >/dev/null 2>&1 || true

chown -R www-data:www-data "${WP_PATH}/wp-content/plugins" 2>/dev/null || true

log "Polylang instalado y activo."
cat <<'TXT'

-------------------------------------------------------------------
PASOS EN EL PANEL (https://research.icor.cl/wp-admin/)
-------------------------------------------------------------------
1) Aparecerá el ASISTENTE de Polylang (o entra a Idiomas → Idiomas):
   - Añade "English"  -> márcalo como idioma por defecto.
   - Añade "Español".

2) Idiomas → Ajustes → "Modificaciones de URL":
   - Activa "Ocultar la información de idioma para el idioma por defecto".
     Así: inglés queda en research.icor.cl/...  y español en
     research.icor.cl/es/...  (igual que el resto del sitio).

3) Idiomas → Ajustes → "Tipos de contenido personalizados y taxonomías":
   - Activa: Entradas, Páginas, Proyectos, Publicaciones y Noticias.
   - Guarda.

4) Si Polylang pide "asignar idioma al contenido existente", elige Inglés.

5) LIMPIEZA: en Páginas, envía a la papelera la página "Español" que creó
   la instalación inicial (ya no se necesita: ahora /es/ lo gestiona
   Polylang). El selector EN/ES del sitio pasa a usar Polylang solo.
-------------------------------------------------------------------
TXT
