<?php
/**
 * Landing institucional (compartida por la portada EN y la página ES).
 * Espera $lang definido por la plantilla que la incluye.
 */
$strings  = icor_strings( $lang );
$linkedin = get_option( 'icor_linkedin_url', '' );
$email    = icor_contact_email();
$initials = function ( $name ) {
	$parts = preg_split( '/\s+/', trim( wp_strip_all_tags( $name ) ) );
	$ini   = mb_substr( $parts[0], 0, 1 );
	if ( count( $parts ) > 1 ) {
		$ini .= mb_substr( end( $parts ), 0, 1 );
	}
	return mb_strtoupper( $ini );
};
?>

<section class="hero">
  <div class="wrap">
    <span class="hero__grid-mark"><?php echo esc_html( $strings['hero_kicker'] ); ?></span>
    <h1><?php echo esc_html( $strings['hero_title'] ); ?></h1>
    <p class="hero__sub"><?php echo esc_html( $strings['hero_sub'] ); ?></p>
    <div class="hero__actions">
      <a class="btn btn--primary" href="#research"><?php echo esc_html( $strings['hero_cta1'] ); ?></a>
      <a class="btn btn--ghost" href="#contact"><?php echo esc_html( $strings['hero_cta2'] ); ?></a>
      <?php if ( $linkedin ) : ?>
        <a class="btn btn--ghost" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $strings['hero_cta3'] ); ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>

<section id="about" class="section">
  <div class="wrap">
    <span class="section__kicker"><?php echo esc_html( $strings['about_kicker'] ); ?></span>
    <h2><?php echo esc_html( $strings['about_title'] ); ?></h2>
    <p class="section__lead"><?php echo esc_html( $strings['about_body'] ); ?></p>
  </div>
</section>

<section id="distinct" class="section distinct">
  <div class="wrap">
    <span class="section__kicker"><?php echo esc_html( $strings['distinct_kicker'] ); ?></span>
    <h2><?php echo esc_html( $strings['distinct_title'] ); ?></h2>
    <p class="section__lead" style="color:#c2d3e2"><?php echo esc_html( $strings['distinct_body'] ); ?></p>
    <div class="distinct__cols">
      <div class="distinct__col">
        <h3><?php echo esc_html( $strings['distinct_col1_title'] ); ?></h3>
        <p><?php echo esc_html( $strings['distinct_col1_body'] ); ?></p>
      </div>
      <div class="distinct__col">
        <h3><?php echo esc_html( $strings['distinct_col2_title'] ); ?></h3>
        <p><?php echo esc_html( $strings['distinct_col2_body'] ); ?></p>
      </div>
    </div>
  </div>
</section>

<section id="research" class="section section--soft">
  <div class="wrap">
    <span class="section__kicker"><?php echo esc_html( $strings['research_kicker'] ); ?></span>
    <h2><?php echo esc_html( $strings['research_title'] ); ?></h2>
    <p class="section__lead"><?php echo esc_html( $strings['research_lead'] ); ?></p>
    <div class="cards">
      <?php foreach ( $strings['research_areas'] as $i => $area ) : ?>
        <div class="card">
          <div class="card__icon"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
          <h3><?php echo esc_html( $area[0] ); ?></h3>
          <p><?php echo esc_html( $area[1] ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="team" class="section">
  <div class="wrap">
    <span class="section__kicker"><?php echo esc_html( $strings['team_kicker'] ); ?></span>
    <h2><?php echo esc_html( $strings['team_title'] ); ?></h2>
    <p class="section__lead"><?php echo esc_html( $strings['team_lead'] ); ?></p>
    <div class="team-grid">
      <?php foreach ( $strings['team'] as $member ) : ?>
        <div class="team-card">
          <div class="team-card__avatar"><?php echo esc_html( $initials( $member[0] ) ); ?></div>
          <h3><?php echo esc_html( $member[0] ); ?></h3>
          <span class="role"><?php echo esc_html( $member[1] ); ?></span>
          <p><?php echo esc_html( $member[2] ); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section id="collaboration" class="section section--soft">
  <div class="wrap">
    <span class="section__kicker"><?php echo esc_html( $strings['collab_kicker'] ); ?></span>
    <h2><?php echo esc_html( $strings['collab_title'] ); ?></h2>
    <p class="section__lead"><?php echo esc_html( $strings['collab_body'] ); ?></p>
    <ul class="collab-list">
      <?php foreach ( $strings['collab_items'] as $item ) : ?>
        <li><?php echo esc_html( $item ); ?></li>
      <?php endforeach; ?>
    </ul>
    <p style="margin-top:34px">
      <a class="btn btn--dark" href="#contact"><?php echo esc_html( $strings['collab_cta'] ); ?></a>
    </p>
  </div>
</section>

<section id="contact" class="section">
  <div class="wrap">
    <span class="section__kicker"><?php echo esc_html( $strings['contact_kicker'] ); ?></span>
    <h2><?php echo esc_html( $strings['contact_title'] ); ?></h2>
    <div class="contact-grid">
      <div class="contact-aside">
        <p><?php echo esc_html( $strings['contact_body'] ); ?></p>
        <h3><?php echo esc_html( $strings['contact_email_label'] ); ?></h3>
        <a class="contact-line" href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
        <?php if ( $linkedin ) : ?>
          <a class="contact-line" href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener">LinkedIn</a>
        <?php endif; ?>
      </div>
      <div>
        <?php icor_contact_form_notices( $strings ); ?>
        <form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
          <input type="hidden" name="action" value="icor_contact">
          <input type="hidden" name="icor_lang" value="<?php echo esc_attr( $lang ); ?>">
          <?php wp_nonce_field( 'icor_contact', 'icor_contact_nonce' ); ?>
          <p class="hp-field" aria-hidden="true">
            <label>Website<input type="text" name="icor_website" tabindex="-1" autocomplete="off"></label>
          </p>
          <label for="icor-name"><?php echo esc_html( $strings['form_name'] ); ?></label>
          <input id="icor-name" name="icor_name" type="text" required maxlength="150">
          <label for="icor-org"><?php echo esc_html( $strings['form_org'] ); ?></label>
          <input id="icor-org" name="icor_org" type="text" maxlength="200">
          <label for="icor-email"><?php echo esc_html( $strings['form_email'] ); ?></label>
          <input id="icor-email" name="icor_email" type="email" required maxlength="200">
          <label for="icor-type"><?php echo esc_html( $strings['form_type'] ); ?></label>
          <select id="icor-type" name="icor_type">
            <?php foreach ( $strings['form_type_opts'] as $opt ) : ?>
              <option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
            <?php endforeach; ?>
          </select>
          <label for="icor-message"><?php echo esc_html( $strings['form_message'] ); ?></label>
          <textarea id="icor-message" name="icor_message" required maxlength="5000"></textarea>
          <button type="submit" class="btn btn--primary"><?php echo esc_html( $strings['form_submit'] ); ?></button>
        </form>
      </div>
    </div>
  </div>
</section>
