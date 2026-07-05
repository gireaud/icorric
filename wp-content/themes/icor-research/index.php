<?php
/**
 * Plantilla genérica (listados, archivo, fallback).
 */
get_header();
?>
<main class="entry">
  <div class="wrap">
    <?php if ( have_posts() ) : ?>
      <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class(); ?>>
          <h1><a href="<?php the_permalink(); ?>" style="color:inherit"><?php the_title(); ?></a></h1>
          <div class="entry-content"><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <h1><?php echo esc_html( 'es' === icor_current_lang() ? 'Nada por aquí todavía' : 'Nothing here yet' ); ?></h1>
    <?php endif; ?>
  </div>
</main>
<?php
get_footer();
