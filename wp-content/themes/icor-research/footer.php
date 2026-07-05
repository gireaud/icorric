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
    <div style="align-self:flex-end">
      &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> ICOR Research &amp; Innovation Center
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
