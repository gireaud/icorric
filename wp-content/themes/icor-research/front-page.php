<?php
/**
 * Portada (versión en inglés — audiencia internacional primero,
 * según Manual Corporativo §3.6).
 */
// Con Polylang activo, icor_current_lang() devuelve 'es' en la home /es/
// y 'en' en /. Sin Polylang, front-page.php es siempre la home en inglés.
$lang = icor_current_lang();
get_header();
include get_template_directory() . '/parts/landing.php';
get_footer();
