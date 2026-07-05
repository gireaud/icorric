#!/usr/bin/env bash
#
# Repara los enlaces permanentes (pretty permalinks) de research.icor.cl.
#
# Síntoma que corrige: la home carga, pero /es/, /proyectos/, /publicaciones/
# o /noticias/ devuelven "Not Found" de Apache. Ocurre cuando el vhost HTTPS
# generado por certbot no permite el .htaccess de WordPress (AllowOverride).
#
# USO (como root en el droplet):
#   curl -fsSL https://raw.githubusercontent.com/gireaud/icorric/claude/icor-research-wordpress-setup-tp0dsx/provision/reparar-permalinks.sh -o /tmp/reparar.sh && bash /tmp/reparar.sh
#
set -euo pipefail

DOMAIN="${DOMAIN:-research.icor.cl}"
WP_PATH="${WP_PATH:-/var/www/${DOMAIN}}"

log() { echo -e "\n\033[1;36m==> $*\033[0m"; }
die() { echo -e "\033[1;31m[ERROR] $*\033[0m"; exit 1; }

[ "$(id -u)" -eq 0 ] || die "Ejecuta este script como root (o con sudo)."
[ -f "${WP_PATH}/wp-load.php" ] || die "No se encontró WordPress en ${WP_PATH}."

WP="wp --allow-root --path=${WP_PATH}"

# 1) Permitir .htaccess para el docroot a nivel de Apache (no toca los vhosts
#    de certbot; aplica a HTTP y HTTPS por igual). Idempotente.
log "Permitiendo AllowOverride para ${WP_PATH}"
CONF="/etc/apache2/conf-available/icor-research.conf"
cat > "$CONF" <<APACHE
<Directory ${WP_PATH}>
    AllowOverride All
    Require all granted
</Directory>
APACHE
a2enmod rewrite >/dev/null
a2enconf icor-research >/dev/null

# 2) Regenerar el .htaccess de WordPress con las reglas de reescritura.
log "Regenerando .htaccess y enlaces permanentes"
$WP rewrite structure '/%postname%/' --hard >/dev/null
$WP rewrite flush --hard >/dev/null
if [ ! -f "${WP_PATH}/.htaccess" ]; then
  # Fallback: escribir las reglas estándar de WordPress a mano.
  cat > "${WP_PATH}/.htaccess" <<'HTA'
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
HTA
fi
chown www-data:www-data "${WP_PATH}/.htaccess"
chmod 644 "${WP_PATH}/.htaccess"

# 3) Asegurar que exista la página /es/ con la plantilla en español.
log "Verificando la página /es/"
ES_ID="$($WP post list --post_type=page --name=es --field=ID | head -1 || true)"
if [ -z "$ES_ID" ]; then
  ES_ID="$($WP post create --post_type=page --post_title='Español' --post_name='es' \
    --post_status=publish --page_template='page-templates/template-es.php' --porcelain)"
  echo "Página /es/ creada (ID ${ES_ID})."
else
  $WP post meta update "$ES_ID" _wp_page_template 'page-templates/template-es.php' >/dev/null
  echo "Página /es/ ya existe (ID ${ES_ID}); plantilla confirmada."
fi

# 4) Recargar Apache.
log "Recargando Apache"
apache2ctl configtest
systemctl reload apache2

# 5) Comprobación.
log "Comprobando rutas"
for path in "/" "/es/" "/proyectos/"; do
  code="$(curl -sko /dev/null -w '%{http_code}' "https://${DOMAIN}${path}" || echo '---')"
  echo "  https://${DOMAIN}${path} -> HTTP ${code}"
done
echo
echo "Listo. Si /es/ devuelve 200, el selector de idioma ya funciona."
