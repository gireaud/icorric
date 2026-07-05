<?php
/**
 * Plantilla de página estándar (páginas internas futuras:
 * publicaciones, proyectos, noticias, etc.).
 */
get_header();
?>
<main class="entry">
  <div class="wrap">
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <div class="entry-content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  </div>
</main>
<?php
get_footer();
