#!/usr/bin/env bash
#
# Actualiza el tema del sitio research.icor.cl con la última versión del repo.
# Uso (como root en el droplet):  bash /opt/icorric/provision/update.sh
#
set -euo pipefail

DOMAIN="${DOMAIN:-research.icor.cl}"
WP_PATH="${WP_PATH:-/var/www/${DOMAIN}}"
REPO_DIR="${REPO_DIR:-/opt/icorric}"
BRANCH="${BRANCH:-main}"
THEME_SLUG="icor-research"

git -C "$REPO_DIR" fetch origin "$BRANCH"
git -C "$REPO_DIR" checkout "$BRANCH"
git -C "$REPO_DIR" reset --hard "origin/${BRANCH}"

rsync -a --delete "${REPO_DIR}/wp-content/themes/${THEME_SLUG}/" "${WP_PATH}/wp-content/themes/${THEME_SLUG}/"
chown -R www-data:www-data "${WP_PATH}/wp-content/themes/${THEME_SLUG}"

wp --allow-root --path="$WP_PATH" cache flush >/dev/null 2>&1 || true
# Refresca enlaces permanentes para que las rutas de los CPT (/proyectos/,
# /publicaciones/, /noticias/) queden activas.
wp --allow-root --path="$WP_PATH" rewrite flush --hard >/dev/null 2>&1 || true
echo "Tema actualizado en ${WP_PATH} desde la rama ${BRANCH}."
