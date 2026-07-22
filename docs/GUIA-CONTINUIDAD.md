# Guía de continuidad — Web ICOR Research & Innovation Center

Bienvenido/a al proyecto. Esta guía te lleva de cero a poder **editar la web
por tu cuenta**, aunque nunca hayas usado git ni te hayas conectado a un
servidor. Tómalo con calma: cada sección es un paso.

---

## 0. Lo más importante primero: hay DOS formas de editar la web

Entender esto te ahorra el 80% del trabajo técnico:

| Qué quieres cambiar | Cómo se hace | ¿Necesitas git/servidor? |
|---|---|---|
| **Textos** de la portada (hero, about, equipo, etc.) | Panel de WordPress → menú **"Contenido de la web"** | ❌ No |
| **Proyectos, Publicaciones, Noticias** | Panel de WordPress → menús respectivos | ❌ No |
| **Diseño y estructura** (colores, tipografías, disposición, nuevas secciones) | Editando el **código del tema** en tu computador y publicándolo | ✅ Sí |

👉 **La mayoría del día a día (contenido) se hace directo en el panel de
WordPress en vivo**, entrando a `https://research.icor.cl/wp-admin/`. No
necesitas nada técnico para eso.

La parte de git + servidor de esta guía es **solo para cambios de diseño o
estructura del tema**. Si por ahora solo vas a cargar contenido, con la
Sección 2 (accesos) y saber entrar al panel te basta.

---

## 1. Panorama del proyecto

- **Qué es**: sitio institucional en WordPress del centro de investigación,
  distinto de la Clínica ICOR. Público objetivo: investigadores, universidades
  y socios (no pacientes).
- **Dirección pública**: `https://research.icor.cl`
- **Dónde vive el sitio**: un servidor (droplet) en DigitalOcean, en la carpeta
  `/var/www/research.icor.cl`. ⚠️ Hay otros sitios en ese mismo servidor: **no
  se debe tocar la carpeta `/var/www/html` ni ninguna otra que no sea la de
  este proyecto.**
- **Código**: repositorio de GitHub `gireaud/icorric`.
- **Tema a medida**: se llama `icor-research`. Es el "traje" visual del sitio.

**Dato clave**: el repositorio **no contiene un WordPress completo**, solo
contiene el **tema** (la parte que diseñamos) y unos **scripts de instalación**.
WordPress en sí (el motor) se instala aparte, tanto en el servidor como en tu
computador.

Estructura del repositorio:

```
icorric/
├── wp-content/themes/icor-research/   ← EL TEMA (aquí editas el diseño)
│   ├── style.css                      ← colores, tipografías, estilos
│   ├── functions.php                  ← configuración del tema
│   ├── front-page.php                 ← la portada
│   ├── parts/landing.php              ← las secciones de la portada
│   ├── inc/strings.php                ← textos por defecto (EN/ES)
│   ├── inc/admin-content.php          ← el panel "Contenido de la web"
│   ├── inc/post-types.php             ← Proyectos, Publicaciones, Noticias
│   ├── header.php / footer.php        ← cabecera y pie
│   └── archive.php / single.php / page.php
├── provision/                         ← scripts para el servidor
│   ├── install.sh                     ← instalación limpia (ya ejecutada)
│   ├── update.sh                      ← publicar cambios del tema
│   ├── reparar-permalinks.sh          ← arreglar enlaces si dan 404
│   └── instalar-polylang.sh           ← instalar el sistema bilingüe
├── README.md                          ← documentación general
└── docs/GUIA-CONTINUIDAD.md           ← esta guía
```

---

## 2. Accesos que debes pedir (a Jesús)

Antes de empezar, pide:

1. **Invitación al repositorio de GitHub** `gireaud/icorric` (te llega a tu
   correo; necesitas una cuenta de GitHub gratuita).
2. **Usuario administrador de WordPress** para `https://research.icor.cl/wp-admin/`
   (o que te creen uno en Usuarios → Añadir).
3. **Acceso al servidor en DigitalOcean** (para publicar cambios de diseño):
   que te agreguen al proyecto de DigitalOcean, o al menos las credenciales del
   droplet. Las credenciales de WordPress y de la base de datos quedaron
   guardadas en el servidor en `/root/icor-research-credenciales.txt`.

Si solo vas a cargar contenido, con el punto 2 basta.

---

## 3. Instala tus herramientas (una sola vez)

Para editar el **diseño** necesitas tres programas gratuitos:

1. **Local** (WordPress en tu computador) → https://localwp.com
   Crea sitios WordPress locales con un clic, sin saber de servidores.
2. **GitHub Desktop** (git con botones, sin línea de comandos) →
   https://desktop.github.com
   Con esto "bajas" y "subes" cambios sin escribir comandos.
3. **Visual Studio Code** (editor de código) → https://code.visualstudio.com

> ¿Por qué GitHub Desktop y no comandos? Porque hace lo mismo (bajar, guardar,
> subir cambios) con botones. Cuando te sientas cómodo puedes aprender los
> comandos, pero no es necesario para empezar.

---

## 4. Trae el código a tu computador

1. Abre **GitHub Desktop** e inicia sesión con tu cuenta de GitHub.
2. `File → Clone repository → gireaud/icorric`.
3. Elige una carpeta fácil de encontrar, por ejemplo
   `C:\Users\TU_USUARIO\Documentos\icorric` (Windows) o
   `~/Documentos/icorric` (Mac).
4. Clic en **Clone**. Ya tienes todo el proyecto en tu computador.

**Sobre las ramas (branches)**: una "rama" es una línea de trabajo. Pregúntale
a Jesús en qué rama debes trabajar. Lo ideal es que todo esté consolidado en la
rama **`main`**; si es así, en GitHub Desktop asegúrate de tener seleccionada
`main` (arriba, en "Current branch").

---

## 5. Monta el WordPress local con el tema

Esto crea una copia de la web en tu computador para probar cambios **sin
riesgo** antes de tocar la web real.

1. Abre **Local** y crea un sitio nuevo:
   - Clic en **Create a new site** → nómbralo `ICOR Research`.
   - Deja las opciones por defecto (PHP, MySQL, Nginx/Apache) y crea el sitio.
   - Anota el usuario y contraseña de administrador que te pida.
2. Conecta el tema del repositorio con este WordPress local. Tienes dos formas:

   **Forma A (recomendada) — enlace, para que git y WordPress usen el mismo tema:**
   - En Local, clic derecho en tu sitio → **Open site shell** (o "Reveal in
     Explorer/Finder" para ver la carpeta). La carpeta de temas está en:
     `.../ICOR Research/app/public/wp-content/themes/`
   - Crea un **enlace** desde esa carpeta hacia el tema del repositorio.
     - En **Windows**, abre "Símbolo del sistema" (cmd) como administrador y
       ejecuta (ajusta las rutas a las tuyas):
       ```
       mklink /D "C:\Users\TU_USUARIO\Local Sites\icor-research\app\public\wp-content\themes\icor-research" "C:\Users\TU_USUARIO\Documentos\icorric\wp-content\themes\icor-research"
       ```
     - En **Mac**, en la Terminal:
       ```
       ln -s "$HOME/Documentos/icorric/wp-content/themes/icor-research" "$HOME/Local Sites/icor-research/app/public/wp-content/themes/icor-research"
       ```

   **Forma B (más simple, sin enlaces) — copiar:**
   - Copia la carpeta `wp-content/themes/icor-research` del repositorio y pégala
     dentro de la carpeta de temas del sitio local.
   - ⚠️ Con esta forma, cuando termines de editar debes **copiar los archivos
     modificados de vuelta al repositorio** antes de subirlos. Por eso la Forma
     A es mejor: edita en un solo lugar.

3. En el panel del WordPress local (`Local` → botón **WP Admin**):
   - Ve a **Apariencia → Temas** y activa **"ICOR Research & Innovation Center"**.
   - Ve a **Ajustes → Enlaces permanentes** y pulsa **Guardar** (activa las URLs
     bonitas y las secciones de Proyectos/Publicaciones/Noticias).
   - Opcional: instala el plugin **Polylang** (Plugins → Añadir nuevo) si vas a
     probar cosas bilingües.

> Los textos que ves en local serán los "por defecto" del tema, no los que
> están cargados en la web real (esos viven en la base de datos del servidor).
> Para probar diseño da igual. Si necesitas una copia exacta del contenido,
> Local permite "importar" una copia del sitio; pídeme ayuda si llega a hacer
> falta.

---

## 6. Tu flujo de trabajo (editar → probar → publicar)

Cada vez que quieras hacer un cambio de **diseño/estructura**:

1. **Editar**: abre la carpeta del repositorio en VS Code y modifica los
   archivos del tema (ver el mapa en la Sección 7).
2. **Probar**: mira el resultado en tu WordPress local (Local → Open site).
   Repite hasta que quede bien. Aquí no hay riesgo: es tu copia.
3. **Guardar y subir a GitHub** (con GitHub Desktop):
   - Verás la lista de archivos cambiados.
   - Abajo a la izquierda, escribe un resumen corto (ej. "Cambio color del
     hero") y pulsa **Commit to main**.
   - Arriba, pulsa **Push origin**. Listo: tus cambios están en GitHub.
4. **Publicar en la web real** (desplegar): los cambios en GitHub **todavía no
   se ven en `research.icor.cl`**. Hay que "avisarle" al servidor. Para eso:
   - Entra a [DigitalOcean](https://cloud.digitalocean.com) → tu droplet →
     botón **Console** (una terminal en el navegador, ya conectada).
   - Pega este comando y pulsa Enter:
     ```
     bash /opt/icorric/provision/update.sh
     ```
   - Eso baja tu última versión del tema y la aplica al sitio real. Recarga
     `research.icor.cl` y verás el cambio.

> Regla mental: **GitHub guarda el cambio; `update.sh` lo publica.** Son dos
> pasos distintos.

---

## 7. Mapa del tema: qué archivo tocar

| Quiero cambiar… | Archivo |
|---|---|
| Colores, tipografías, espaciados, estilos | `wp-content/themes/icor-research/style.css` |
| Textos por defecto de la portada (EN/ES) | `wp-content/themes/icor-research/inc/strings.php` |
| Estructura/orden de las secciones de la portada | `wp-content/themes/icor-research/parts/landing.php` |
| La cabecera (logo, menú) | `header.php` |
| El pie de página | `footer.php` |
| El panel de edición "Contenido de la web" | `inc/admin-content.php` |
| Las secciones Proyectos/Publicaciones/Noticias | `inc/post-types.php` |
| El listado de una sección (grilla de tarjetas) | `archive.php` |
| Una entrada individual (proyecto, noticia…) | `single.php` |
| Configuración general del tema | `functions.php` |

Consejo: cambia una cosa a la vez y pruébala en local antes de seguir.

---

## 8. Reglas de oro (seguridad y buenas prácticas)

1. **Nunca toques `/var/www/html` ni otras carpetas del servidor.** Este
   proyecto vive solo en `/var/www/research.icor.cl`.
2. **Prueba siempre en local antes de desplegar.** Nunca edites archivos del
   tema directamente en el servidor.
3. **Textos → panel; código → git.** No pongas textos "quemados" en el código
   si pueden editarse desde "Contenido de la web".
4. **Haz un snapshot del droplet en DigitalOcean** antes de un cambio grande
   (es un respaldo de un clic; te salva si algo sale mal).
5. **Respeta la línea roja comunicacional** del manual del proyecto: nada de
   datos de pacientes, resultados clínicos no publicados, detalles patentables
   ni promesas médicas. Ante la duda, consulta con Ignacio antes de publicar.
6. Si un enlace tipo `/es/` o `/proyectos/` da "Not Found" tras un cambio,
   ejecuta en la consola del droplet:
   `bash /opt/icorric/provision/reparar-permalinks.sh`

---

## 9. Mini-glosario

- **Repositorio (repo)**: la carpeta del proyecto guardada en GitHub, con
  historial de todos los cambios.
- **Clonar**: bajar una copia del repo a tu computador.
- **Commit**: guardar un cambio en el historial (con un mensajito que lo
  describe).
- **Push**: subir tus commits a GitHub.
- **Pull**: bajar los cambios que otros subieron (hazlo antes de empezar a
  editar, para tener la última versión: en GitHub Desktop, "Fetch/Pull origin").
- **Rama (branch)**: una línea de trabajo. Trabaja en la que te indiquen (idealmente `main`).
- **Desplegar (deploy)**: publicar en el servidor real lo que ya está en GitHub
  (aquí, con `update.sh`).
- **Droplet**: el servidor en DigitalOcean donde vive el sitio.
- **Tema**: el "traje" visual de WordPress; aquí es `icor-research`.
- **CPT (custom post type)**: un tipo de contenido propio (Proyectos,
  Publicaciones, Noticias).

---

## 10. ¿Por dónde empiezo?

1. Pide los accesos (Sección 2).
2. Entra a `https://research.icor.cl/wp-admin/` y explora los menús
   **"Contenido de la web"**, Proyectos, Publicaciones y Noticias. Con eso ya
   puedes cargar/editar contenido hoy mismo.
3. Cuando quieras cambiar diseño, instala las herramientas (Sección 3) y sigue
   las Secciones 4–6.
4. Ante cualquier duda, revisa el `README.md` del repositorio o pregunta.

¡Éxito! 🚀
