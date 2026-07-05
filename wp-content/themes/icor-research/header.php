<?php
/**
 * Cabecera del sitio. Los textos del menú dependen del idioma activo
 * (EN en la portada, ES en la página /es/).
 */
$lang    = icor_current_lang();
$strings = icor_strings( $lang );
$is_land = is_front_page() || is_page_template( 'page-templates/template-es.php' );
$prefix  = $is_land ? '' : esc_url( $lang === 'es' ? home_url( '/es/' ) : home_url( '/' ) );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
  <div class="site-header__inner">
    <a class="brand" href="<?php echo esc_url( $lang === 'es' ? home_url( '/es/' ) : home_url( '/' ) ); ?>">
      <span class="brand__name">ICOR<span>&nbsp;R&amp;I</span></span>
      <span class="brand__descriptor">Research &amp; Innovation Center</span>
    </a>
    <nav class="site-nav" aria-label="<?php echo esc_attr( $strings['nav_aria'] ); ?>">
      <a href="<?php echo $prefix; ?>#about"><?php echo esc_html( $strings['nav_about'] ); ?></a>
      <a href="<?php echo $prefix; ?>#research"><?php echo esc_html( $strings['nav_research'] ); ?></a>
      <a href="<?php echo $prefix; ?>#team"><?php echo esc_html( $strings['nav_team'] ); ?></a>
      <a href="<?php echo $prefix; ?>#collaboration"><?php echo esc_html( $strings['nav_collab'] ); ?></a>
      <a href="<?php echo $prefix; ?>#contact"><?php echo esc_html( $strings['nav_contact'] ); ?></a>
      <?php
      // Selector de idioma: usa el de Polylang si hay 2+ idiomas configurados;
      // si no, cae al toggle propio (/ ↔ /es/).
      $switch = '';
      if ( function_exists( 'pll_the_languages' ) ) {
          $pll = pll_the_languages( array( 'raw' => 1, 'hide_if_no_translation' => 0 ) );
          if ( is_array( $pll ) && count( $pll ) >= 2 ) {
              foreach ( $pll as $l ) {
                  if ( empty( $l['current_lang'] ) ) {
                      $switch .= '<a class="lang-switch" href="' . esc_url( $l['url'] ) . '">' . esc_html( strtoupper( $l['slug'] ) ) . '</a>';
                  }
              }
          }
      }
      if ( '' === $switch ) {
          $switch = '<a class="lang-switch" href="' . esc_url( $lang === 'es' ? home_url( '/' ) : home_url( '/es/' ) ) . '">' . ( $lang === 'es' ? 'EN' : 'ES' ) . '</a>';
      }
      echo $switch; // Enlaces construidos con esc_url/esc_html arriba.
      ?>
    </nav>
  </div>
</header>
