<?php
$lang     = icor_current_lang();
$strings  = icor_strings( $lang );
$linkedin = get_option( 'icor_linkedin_url', '' );
$email    = icor_contact_email();
?>
<footer class="site-footer">
  <div class="wrap site-footer__inner">
    <div>
      <span class="brand__name">ICOR <span style="color:var(--cyan-300)">R&amp;I</span></span><br>
      <span class="brand__descriptor">Research &amp; Innovation Center</span>
      <p class="site-footer__note"><?php echo esc_html( $strings['footer_note'] ); ?></p>
    </div>
    <div>
      <strong style="color:#fff"><?php echo esc_html( $strings['footer_contact'] ); ?></strong><br>
      <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a><br>
      <?php if ( $linkedin ) : ?>
        <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener">LinkedIn</a>
      <?php endif; ?>
    </div>
    <?php
    // Enlaces a las secciones de fase 2 (aparecen solo cuando tienen contenido).
    $cpt_labels = array( 'proyecto' => 'Proyectos', 'publicacion' => 'Publicaciones', 'noticia' => 'Noticias' );
    $cpt_links  = array();
    foreach ( $cpt_labels as $pt => $label ) {
        $counts = wp_count_posts( $pt );
        if ( $counts && (int) $counts->publish > 0 ) {
            $cpt_links[] = '<a href="' . esc_url( get_post_type_archive_link( $pt ) ) . '">' . esc_html( $label ) . '</a>';
        }
    }
    if ( $cpt_links ) : ?>
      <div>
        <strong style="color:#fff"><?php echo esc_html( 'es' === $lang ? 'Secciones' : 'Sections' ); ?></strong><br>
        <?php echo implode( '<br>', $cpt_links ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
      </div>
    <?php endif; ?>
    <div style="align-self:flex-end">
      &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> ICOR Research &amp; Innovation Center
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
