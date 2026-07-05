#!/usr/bin/env bash
#
# Instalación limpia de WordPress para ICOR Research & Innovation Center
# en el subdominio research.icor.cl (droplet DigitalOcean, Ubuntu).
#
# USO (como root, p. ej. desde la consola web de DigitalOcean):
#   curl -fsSL https://raw.githubusercontent.com/gireaud/icorric/main/provision/install.sh -o /tmp/icor-install.sh
#   bash /tmp/icor-install.sh
#
# SEGURIDAD / ALCANCE:
#   - NO toca /var/www/html ni ninguna carpeta de otros sitios.
#   - Solo crea: /var/www/research.icor.cl, una base de datos nueva
#     (icor_research), un vhost nuevo para research.icor.cl y /opt/icorric.
#   - Es idempotente: puede ejecutarse varias veces sin romper nada.
#
set -euo pipefail

# ---------------------------------------------------------------- variables
DOMAIN="${DOMAIN:-research.icor.cl}"
WP_PATH="${WP_PATH:-/var/www/${DOMAIN}}"
DB_NAME="${DB_NAME:-icor_research}"
DB_USER="${DB_USER:-icor_research}"
REPO_URL="${REPO_URL:-https://github.com/gireaud/icorric.git}"
BRANCH="${BRANCH:-main}"
REPO_DIR="/opt/icorric"
THEME_SLUG="icor-research"
SITE_TITLE="${SITE_TITLE:-ICOR Research & Innovation Center}"
ADMIN_USER="${ADMIN_USER:-icor_admin}"
ADMIN_EMAIL="${ADMIN_EMAIL:-jesus@hitosmedia.com}"
CONTACT_EMAIL="${CONTACT_EMAIL:-${ADMIN_EMAIL}}"
LE_EMAIL="${LE_EMAIL:-${ADMIN_EMAIL}}"
CRED_FILE="/root/icor-research-credenciales.txt"

log()  { echo -e "\n\033[1;36m==> $*\033[0m"; }
warn() { echo -e "\033[1;33m[AVISO] $*\033[0m"; }
die()  { echo -e "\033[1;31m[ERROR] $*\033[0m"; exit 1; }

[ "$(id -u)" -eq 0 ] || die "Ejecuta este script como root (o con sudo)."

# Cinturón de seguridad: jamás operar sobre las carpetas de otros sitios.
case "$WP_PATH" in
  /var/www/html|/var/www/html/*) die "WP_PATH no puede ser /var/www/html." ;;
esac

export DEBIAN_FRONTEND=noninteractive

# ------------------------------------------------- detectar servidor web
log "Detectando servidor web existente"
WEBSERVER=""
if systemctl is-active --quiet nginx 2>/dev/null; then
  WEBSERVER="nginx"
elif systemctl is-active --quiet apache2 2>/dev/null; then
  WEBSERVER="apache"
elif command -v nginx >/dev/null 2>&1; then
  WEBSERVER="nginx"
elif command -v apache2ctl >/dev/null 2>&1; then
  WEBSERVER="apache"
else
  warn "No se detectó nginx ni apache activos; se instalará nginx."
  apt-get update -qq
  apt-get install -y -qq nginx
  WEBSERVER="nginx"
fi
echo "Servidor web: ${WEBSERVER}"

# ------------------------------------------------------- paquetes base
log "Instalando dependencias (PHP, extensiones, git, unzip)"
apt-get update -qq
PKGS=(git curl unzip rsync ca-certificates)
if [ "$WEBSERVER" = "nginx" ]; then
  PKGS+=(php-fpm)
else
  PKGS+=(libapache2-mod-php)
fi
PKGS+=(php-mysql php-xml php-curl php-gd php-mbstring php-zip php-intl php-imagick)
apt-get install -y -qq "${PKGS[@]}" || apt-get install -y -qq "${PKGS[@]/php-imagick/}"

PHP_VERSION="$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')"
echo "PHP ${PHP_VERSION}"

# El metapaquete php-mysql puede resolver a una versión de PHP distinta a la
# CLI activa (p. ej. droplet con PHP 8.5). WP-CLI necesita la extensión mysqli
# en la CLI que ejecuta, así que la instalamos y verificamos por versión exacta.
if ! php -r 'exit(function_exists("mysqli_init") ? 0 : 1);' 2>/dev/null; then
  log "Instalando extensión mysqli para PHP ${PHP_VERSION}"
  apt-get install -y -qq "php${PHP_VERSION}-mysql" || true
  systemctl restart "php${PHP_VERSION}-fpm" 2>/dev/null || true
fi
php -r 'exit(function_exists("mysqli_init") ? 0 : 1);' 2>/dev/null \
  || die "La extensión mysqli de PHP ${PHP_VERSION} no está disponible. Instálala con: apt-get install php${PHP_VERSION}-mysql"

# --------------------------------------------------------- base de datos
log "Configurando base de datos MySQL/MariaDB"
command -v mysql >/dev/null 2>&1 || die "No se encontró el cliente mysql. ¿Este droplet tiene MySQL/MariaDB instalado?"

# Cómo autenticarse como root de MySQL: socket (por defecto en Ubuntu),
# archivo de mantenimiento de Debian, o credenciales de imágenes DO.
MYSQL_ROOT=(mysql -u root)
if ! "${MYSQL_ROOT[@]}" -e 'SELECT 1' >/dev/null 2>&1; then
  if mysql --defaults-file=/etc/mysql/debian.cnf -e 'SELECT 1' >/dev/null 2>&1; then
    MYSQL_ROOT=(mysql --defaults-file=/etc/mysql/debian.cnf)
  elif [ -f /root/.digitalocean_password ] && source /root/.digitalocean_password 2>/dev/null \
      && mysql -u root -p"${root_mysql_pass:-}" -e 'SELECT 1' >/dev/null 2>&1; then
    MYSQL_ROOT=(mysql -u root -p"${root_mysql_pass}")
  elif [ -n "${MYSQL_ROOT_PASSWORD:-}" ] && mysql -u root -p"${MYSQL_ROOT_PASSWORD}" -e 'SELECT 1' >/dev/null 2>&1; then
    MYSQL_ROOT=(mysql -u root -p"${MYSQL_ROOT_PASSWORD}")
  else
    die "No pude autenticarme como root de MySQL. Reintenta con: MYSQL_ROOT_PASSWORD='tu_clave' bash /tmp/icor-install.sh"
  fi
fi

DB_PASS=""
if [ -f "${WP_PATH}/wp-config.php" ]; then
  DB_PASS="$(grep -oP "define\(\s*'DB_PASSWORD',\s*'\K[^']+" "${WP_PATH}/wp-config.php" || true)"
fi
if [ -z "$DB_PASS" ]; then
  DB_PASS="$(openssl rand -base64 24 | tr -d '/+=' | cut -c1-24)"
fi

"${MYSQL_ROOT[@]}" <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL
echo "Base de datos '${DB_NAME}' lista."

# --------------------------------------------------------------- wp-cli
log "Instalando WP-CLI"
if ! command -v wp >/dev/null 2>&1; then
  curl -fsSL -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
  chmod +x /usr/local/bin/wp
fi
WP="wp --allow-root --path=${WP_PATH}"

# ------------------------------------------------------ vhost HTTP inicial
log "Creando carpeta del sitio y vhost para ${DOMAIN}"
mkdir -p "$WP_PATH"

if [ "$WEBSERVER" = "nginx" ]; then
  VHOST="/etc/nginx/sites-available/${DOMAIN}"
  PHP_SOCK="$(ls /run/php/php*-fpm.sock 2>/dev/null | head -1)"
  [ -n "$PHP_SOCK" ] || die "No se encontró el socket de PHP-FPM."
  if [ ! -f "$VHOST" ]; then
    cat > "$VHOST" <<NGINX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN};
    root ${WP_PATH};
    index index.php index.html;

    client_max_body_size 64M;

    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:${PHP_SOCK};
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff2?)\$ {
        expires 30d;
        access_log off;
    }

    location = /xmlrpc.php { deny all; }
    location ~ /\.ht { deny all; }
}
NGINX
  fi
  ln -sf "$VHOST" "/etc/nginx/sites-enabled/${DOMAIN}"
  nginx -t
  systemctl reload nginx
else
  VHOST="/etc/apache2/sites-available/${DOMAIN}.conf"
  if [ ! -f "$VHOST" ]; then
    cat > "$VHOST" <<APACHE
<VirtualHost *:80>
    ServerName ${DOMAIN}
    DocumentRoot ${WP_PATH}
    <Directory ${WP_PATH}>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog \${APACHE_LOG_DIR}/${DOMAIN}-error.log
    CustomLog \${APACHE_LOG_DIR}/${DOMAIN}-access.log combined
</VirtualHost>
APACHE
  fi
  # Permite el .htaccess de WordPress a nivel de Apache, de forma que siga
  # activo aunque certbot genere luego un vhost HTTPS aparte (evita 404 en
  # subrutas como /es/ o /proyectos/).
  cat > /etc/apache2/conf-available/icor-research.conf <<APACHE
<Directory ${WP_PATH}>
    AllowOverride All
    Require all granted
</Directory>
APACHE
  a2enmod rewrite >/dev/null
  a2enconf icor-research >/dev/null
  a2ensite "${DOMAIN}.conf" >/dev/null
  apache2ctl configtest
  systemctl reload apache2
fi

# ------------------------------------------------------------- firewall
if command -v ufw >/dev/null 2>&1 && ufw status 2>/dev/null | grep -q 'Status: active'; then
  log "UFW activo: asegurando puertos 80/443"
  ufw allow 'Nginx Full' >/dev/null 2>&1 || ufw allow 'Apache Full' >/dev/null 2>&1 || { ufw allow 80/tcp >/dev/null; ufw allow 443/tcp >/dev/null; }
fi

# -------------------------------------------------------- certificado SSL
log "Obteniendo certificado SSL (Let's Encrypt) para ${DOMAIN}"
SITE_SCHEME="http"
if command -v certbot >/dev/null 2>&1 || apt-get install -y -qq certbot "python3-certbot-${WEBSERVER}"; then
  if certbot --"${WEBSERVER}" -d "${DOMAIN}" --non-interactive --agree-tos -m "${LE_EMAIL}" --redirect --keep-until-expiring; then
    SITE_SCHEME="https"
  else
    warn "Certbot falló; el sitio quedará en HTTP. Puedes reintentar luego con: certbot --${WEBSERVER} -d ${DOMAIN}"
  fi
else
  warn "No se pudo instalar certbot; el sitio quedará en HTTP."
fi
SITE_URL="${SITE_SCHEME}://${DOMAIN}"

# -------------------------------------------------------- núcleo WordPress
if [ ! -f "${WP_PATH}/wp-load.php" ]; then
  log "Descargando WordPress (es_ES)"
  $WP core download --locale=es_ES
else
  log "WordPress ya descargado en ${WP_PATH} (se conserva)"
fi

if [ ! -f "${WP_PATH}/wp-config.php" ]; then
  log "Creando wp-config.php"
  $WP config create \
    --dbname="$DB_NAME" \
    --dbuser="$DB_USER" \
    --dbpass="$DB_PASS" \
    --dbhost=localhost \
    --locale=es_ES
  $WP config set DISALLOW_FILE_EDIT true --raw
fi

ADMIN_PASS=""
if ! $WP core is-installed 2>/dev/null; then
  log "Instalando WordPress"
  ADMIN_PASS="$(openssl rand -base64 18 | tr -d '/+=' | cut -c1-18)"
  $WP core install \
    --url="$SITE_URL" \
    --title="$SITE_TITLE" \
    --admin_user="$ADMIN_USER" \
    --admin_password="$ADMIN_PASS" \
    --admin_email="$ADMIN_EMAIL" \
    --skip-email

  cat > "$CRED_FILE" <<CRED
================ ICOR Research & Innovation Center ================
Sitio:      ${SITE_URL}
Admin:      ${SITE_URL}/wp-admin/
Usuario:    ${ADMIN_USER}
Contraseña: ${ADMIN_PASS}
Email:      ${ADMIN_EMAIL}

Base de datos: ${DB_NAME}
Usuario BD:    ${DB_USER}
Clave BD:      ${DB_PASS}
Ruta:          ${WP_PATH}
Generado:      $(date)
====================================================================
CRED
  chmod 600 "$CRED_FILE"
else
  log "WordPress ya estaba instalado (se conserva)"
  $WP option update siteurl "$SITE_URL" >/dev/null
  $WP option update home "$SITE_URL" >/dev/null
fi

# ----------------------------------------------------------- tema del repo
log "Descargando el tema desde ${REPO_URL} (rama ${BRANCH})"
CLONE_URL="$REPO_URL"
if [ -n "${GITHUB_TOKEN:-}" ]; then
  CLONE_URL="https://x-access-token:${GITHUB_TOKEN}@${REPO_URL#https://}"
fi
if [ -d "${REPO_DIR}/.git" ]; then
  git -C "$REPO_DIR" remote set-url origin "$CLONE_URL"
  git -C "$REPO_DIR" fetch origin "$BRANCH"
  git -C "$REPO_DIR" checkout "$BRANCH"
  git -C "$REPO_DIR" reset --hard "origin/${BRANCH}"
else
  git clone --branch "$BRANCH" --depth 1 "$CLONE_URL" "$REPO_DIR" \
    || die "No se pudo clonar el repo. Si es privado: hazlo público o ejecuta con GITHUB_TOKEN=<token> bash install.sh"
fi
git -C "$REPO_DIR" remote set-url origin "$REPO_URL"  # no dejar tokens en disco

rsync -a --delete "${REPO_DIR}/wp-content/themes/${THEME_SLUG}/" "${WP_PATH}/wp-content/themes/${THEME_SLUG}/"

log "Activando tema y configurando el sitio"
$WP theme activate "$THEME_SLUG"

# Página en español (/es/) con la plantilla ES.
if ! $WP post list --post_type=page --name=es --field=ID | grep -q .; then
  $WP post create \
    --post_type=page \
    --post_title='Español' \
    --post_name='es' \
    --post_status=publish \
    --page_template='page-templates/template-es.php' >/dev/null
fi

$WP option update blogdescription 'Research & Innovation in Customized Orthognathic Surgery' >/dev/null
$WP option update timezone_string 'America/Santiago' >/dev/null
$WP option update default_comment_status closed >/dev/null
$WP option update default_ping_status closed >/dev/null
$WP option update blog_public 1 >/dev/null
$WP option update icor_contact_email "$CONTACT_EMAIL" >/dev/null
$WP rewrite structure '/%postname%/' --hard >/dev/null
$WP plugin delete hello akismet >/dev/null 2>&1 || true

# Limpieza de contenido de ejemplo (solo en instalación nueva).
if [ -n "$ADMIN_PASS" ]; then
  $WP post delete 1 2 3 --force >/dev/null 2>&1 || true
fi

chown -R www-data:www-data "$WP_PATH"
find "$WP_PATH" -type d -exec chmod 755 {} \;
find "$WP_PATH" -type f -exec chmod 644 {} \;

# ------------------------------------------------------------------ final
log "¡Listo! ${SITE_URL} está instalado."
echo
echo "  Sitio:  ${SITE_URL}"
echo "  Admin:  ${SITE_URL}/wp-admin/"
if [ -n "$ADMIN_PASS" ]; then
  echo "  Usuario: ${ADMIN_USER}"
  echo "  Clave:   ${ADMIN_PASS}"
  echo
  echo "  (Credenciales guardadas en ${CRED_FILE})"
else
  echo "  (Instalación existente: credenciales en ${CRED_FILE} si fue creada por este script)"
fi
echo
echo "  Carpetas de otros sitios: NO fueron modificadas."
