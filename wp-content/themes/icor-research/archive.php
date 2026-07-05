<?php
/**
 * Listado (archivo) para los tipos de contenido: Proyectos, Publicaciones,
 * Noticias, y archivos estándar.
 */
get_header();
$lang = icor_current_lang();
?>
<section class="section">
  <div class="wrap">
    <span class="section__kicker"><?php echo esc_html( 'es' === $lang ? 'ICOR Research & Innovation Center' : 'ICOR Research & Innovation Center' ); ?></span>
    <h2><?php echo esc_html( post_type_archive_title( '', false ) ? post_type_archive_title( '', false ) : get_the_archive_title() ); ?></h2>

    <?php if ( have_posts() ) : ?>
      <div class="cards" style="margin-top:36px">
        <?php while ( have_posts() ) : the_post(); ?>
          <article class="card">
            <?php if ( has_post_thumbnail() ) : ?>
              <a href="<?php the_permalink(); ?>" style="display:block;margin:-28px -26px 18px;border-radius:14px 14px 0 0;overflow:hidden">
                <?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%;height:180px;object-fit:cover;display:block' ) ); ?>
              </a>
            <?php endif; ?>
            <h3><a href="<?php the_permalink(); ?>" style="color:var(--navy-800)"><?php the_title(); ?></a></h3>
            <p style="font-size:0.82rem;color:var(--ink-600)"><?php echo esc_html( get_the_date() ); ?></p>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
          </article>
        <?php endwhile; ?>
      </div>
      <div style="margin-top:36px"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
    <?php else : ?>
      <p class="section__lead"><?php echo esc_html( 'es' === $lang ? 'Aún no hay contenido publicado en esta sección.' : 'No content published in this section yet.' ); ?></p>
    <?php endif; ?>
  </div>
</section>
<?php
get_footer();
