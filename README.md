# ICOR Research & Innovation Center — Web institucional

Sitio WordPress para **research.icor.cl**, construido según el *Manual
corporativo para creación de página web institucional* (v1.0, mayo 2026,
Dirección General ICOR R&I).

## Qué contiene este repositorio

| Ruta | Descripción |
|---|---|
| `wp-content/themes/icor-research/` | Tema WordPress a medida (landing institucional bilingüe EN/ES) |
| `provision/install.sh` | Instalación automática y completa en el droplet (un solo comando) |
| `provision/update.sh` | Actualiza el tema en el droplet con la última versión del repo |

### El tema `icor-research`

Landing mínima viable con las 8 secciones del manual (§4): Hero, About,
*Connected but distinct*, Research & Innovation Areas, Team, Collaboration,
Contact y enlace a LinkedIn.

- **Bilingüe**: portada en inglés (`/`) y versión en español (`/es/`), con
  selector de idioma en el menú. Los textos viven en
  `wp-content/themes/icor-research/inc/strings.php` (fáciles de editar).
- **Identidad visual** según manual §6: paleta navy + teal, tipografía Inter,
  logo tipográfico "ICOR R&I / Research & Innovation Center".
- **Formulario de contacto sin plugins**: envía correo al email institucional
  y además guarda cada mensaje en el panel de WordPress
  (*Mensajes de contacto*), así no se pierde nada si el correo falla.
- **Personalizador** (Apariencia → Personalizar → *Contacto y redes*): correo
  de contacto y URL de LinkedIn institucional (los botones de LinkedIn
  aparecen automáticamente al configurarla).

## Cómo instalar en el droplet (sin saber SSH)

> El script **no toca** `/var/www/html` ni las carpetas de otros sitios.
> Crea todo nuevo: `/var/www/research.icor.cl`, base de datos `icor_research`
> y un vhost propio para `research.icor.cl`. Es idempotente (se puede
> ejecutar más de una vez).

1. Entra a [DigitalOcean](https://cloud.digitalocean.com) → **Droplets** →
   elige el droplet donde vive icor.cl.
2. Arriba a la derecha haz clic en **Console** (se abre una terminal negra en
   el navegador, ya conectada como `root`; no necesitas claves).
3. Pega este comando y presiona Enter:

   ```bash
   curl -fsSL https://raw.githubusercontent.com/gireaud/icorric/main/provision/install.sh -o /tmp/icor-install.sh && bash /tmp/icor-install.sh
   ```

4. Espera unos minutos. Al final verás la URL del sitio y el **usuario y
   contraseña de administrador** de WordPress (quedan guardados también en
   `/root/icor-research-credenciales.txt`).

**Requisito**: el repositorio debe ser público para que el droplet pueda
descargarlo (Settings → General → Danger Zone → *Change visibility*).
Alternativa si prefieres mantenerlo privado: ejecutar con un token
`GITHUB_TOKEN=github_pat_xxx bash /tmp/icor-install.sh`.

El script hace todo esto automáticamente:

1. Detecta el servidor web existente (nginx o Apache) y lo respeta.
2. Instala PHP y extensiones si faltan.
3. Crea la base de datos `icor_research` con contraseña aleatoria.
4. Crea el vhost de `research.icor.cl` (el DNS ya apunta al droplet).
5. Obtiene certificado SSL con Let's Encrypt (HTTPS + redirección).
6. Instala WordPress en español en `/var/www/research.icor.cl`.
7. Instala y activa el tema `icor-research`, crea la página `/es/`,
   configura zona horaria (Santiago), permalinks y desactiva comentarios.

## Editar el contenido tú mismo (sin código)

En el panel de WordPress aparece un menú **"Contenido de la web"**: desde ahí
editas **todos los textos de la landing en inglés y español** (hero, about,
áreas de investigación, equipo, colaboración, contacto, pie y meta descripción).

- Cada campo tiene su columna EN y ES.
- Si dejas un campo vacío, se usa el texto por defecto del manual.
- En los campos "una por línea": cada línea es un elemento. Donde se indica
  `|`, separa los datos con esa barra. Ejemplos:
  - **Áreas de investigación**: `Título | Descripción`
  - **Equipo**: `Nombre | Cargo | Bio`
  - **Colaboración**: un ítem por línea.

Los cambios se guardan en la base de datos y se ven al instante en la web. No
hace falta tocar código ni redesplegar para editar textos.

## Secciones de contenido: Proyectos, Publicaciones y Noticias

El tema registra tres tipos de contenido, cada uno con su menú propio en el
panel y editor de bloques (Gutenberg):

| Sección | URL pública | Menú del panel |
|---|---|---|
| Proyectos | `/proyectos/` | Proyectos |
| Publicaciones | `/publicaciones/` | Publicaciones |
| Noticias | `/noticias/` | Noticias |

Cada entrada admite título, contenido, **imagen destacada** y extracto. Los
listados usan el estilo del tema automáticamente. Los enlaces a estas
secciones aparecen en el pie de página en cuanto publicas la primera entrada
de cada una (para no mostrar secciones vacías en el lanzamiento).

## Cómo publicar cambios de diseño/estructura

Para **textos** usa el menú "Contenido de la web" (arriba). Para cambios de
**diseño o estructura** (estilos, plantillas):

1. Edita los archivos del tema en este repo (estilos: `style.css`;
   textos por defecto: `inc/strings.php`) y haz *merge* a `main`.
2. En la consola del droplet:

   ```bash
   bash /opt/icorric/provision/update.sh
   ```

## Checklist de aprobación antes de difundir (manual §10)

- [ ] Validar con Dirección General nombres y cargos del equipo
      (sección Team) — hoy usa los roles indicativos del manual §5.4.
- [ ] Definir y crear el correo institucional (ej. `research@icor.cl`) y
      configurarlo en Apariencia → Personalizar → Contacto y redes.
- [ ] Configurar la URL de LinkedIn institucional en el Personalizador.
- [ ] Probar el formulario de contacto (llega al correo y queda en
      *Mensajes de contacto* del panel).
- [ ] Revisión de ética / IP / claims por parte de Ignacio.
- [ ] Si el correo del formulario no llega bien, instalar un plugin SMTP
      (p. ej. *WP Mail SMTP*) con una casilla real.

## Fase 2 (post-lanzamiento)

El tema ya trae plantillas base (`page.php`, `single.php`) para crecer hacia
las páginas del mapa web fase 2 del manual (§4.1): Publications, Projects,
News, Innovation & Technology Transfer.
