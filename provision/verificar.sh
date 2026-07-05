#!/usr/bin/env bash
#
# Diagnóstico del sitio research.icor.cl. No modifica nada.
# Uso: bash /opt/icorric/provision/verificar.sh
# Pega la salida completa en el chat si algo no funciona.
#
set -u

DOMAIN="${DOMAIN:-research.icor.cl}"
WP_PATH="${WP_PATH:-/var/www/${DOMAIN}}"

echo "===== DIAGNÓSTICO ${DOMAIN} — $(date) ====="

echo -e "\n--- Servidor web ---"
systemctl is-active nginx 2>/dev/null && echo "nginx activo"
systemctl is-active apache2 2>/dev/null && echo "apache2 activo"
ls -l /etc/nginx/sites-enabled/ 2>/dev/null | grep -i "$DOMAIN"
ls -l /etc/apache2/sites-enabled/ 2>/dev/null | grep -i "$DOMAIN"

echo -e "\n--- Carpeta del sitio ---"
ls -ld "$WP_PATH" 2>/dev/null || echo "NO existe ${WP_PATH}"
[ -f "${WP_PATH}/wp-config.php" ] && echo "wp-config.php: OK" || echo "wp-config.php: FALTA"

echo -e "\n--- WordPress ---"
if command -v wp >/dev/null 2>&1 && [ -f "${WP_PATH}/wp-load.php" ]; then
  wp --allow-root --path="$WP_PATH" core is-installed 2>/dev/null && echo "WP instalado: SÍ" || echo "WP instalado: NO"
  echo "URL: $(wp --allow-root --path="$WP_PATH" option get home 2>/dev/null)"
  echo "Tema activo: $(wp --allow-root --path="$WP_PATH" theme list --status=active --field=name 2>/dev/null)"
fi

echo -e "\n--- Certificado SSL ---"
certbot certificates 2>/dev/null | grep -A3 "$DOMAIN" || echo "Sin certificado para ${DOMAIN}"

echo -e "\n--- Respuesta HTTP local ---"
curl -sk -o /dev/null -w "http://localhost (Host ${DOMAIN}): %{http_code}\n" -H "Host: ${DOMAIN}" http://localhost/
curl -sk -o /dev/null -w "https://${DOMAIN}: %{http_code}\n" "https://${DOMAIN}/" 2>/dev/null || true

echo -e "\n--- DNS ---"
getent hosts "$DOMAIN" || true
curl -s ifconfig.me 2>/dev/null && echo "  <- IP pública de este droplet"

echo -e "\n===== FIN DIAGNÓSTICO ====="
