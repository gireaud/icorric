#!/usr/bin/env bash
#
# Actualiza el tema del sitio research.icor.cl con la última versión del repo.
# Uso (como root en el droplet):  bash /opt/icorric/provision/update.sh
#
set -euo pipefail

DOMAIN="${DOMAIN:-research.icor.cl}"
WP_PATH="${WP_PATH:-/var/www/${DOMAIN}}"
REPO_DIR="${REPO_DIR:-/opt/icorric}"
THEME_SLUG="icor-research"

# Por defecto usa la rama que ya está desplegada en el droplet (la que clonó
# el instalador), no una fija. Así el update funciona aunque el repo sea un
# clon superficial de una sola rama. Puedes forzar otra con BRANCH=... .
DEFAULT_BRANCH="$(git -C "$REPO_DIR" symbolic-ref --short -q HEAD || echo 'claude/icor-research-wordpress-setup-tp0dsx')"
BRANCH="${BRANCH:-$DEFAULT_BRANCH}"

git -C "$REPO_DIR" fetch origin "$BRANCH"
git -C "$REPO_DIR" reset --hard FETCH_HEAD

rsync -a --delete "${REPO_DIR}/wp-content/themes/${THEME_SLUG}/" "${WP_PATH}/wp-content/themes/${THEME_SLUG}/"
chown -R www-data:www-data "${WP_PATH}/wp-content/themes/${THEME_SLUG}"

wp --allow-root --path="$WP_PATH" cache flush >/dev/null 2>&1 || true
# Refresca enlaces permanentes para que las rutas de los CPT (/proyectos/,
# /publicaciones/, /noticias/) queden activas.
wp --allow-root --path="$WP_PATH" rewrite flush --hard >/dev/null 2>&1 || true
echo "Tema actualizado en ${WP_PATH} desde la rama ${BRANCH}."
