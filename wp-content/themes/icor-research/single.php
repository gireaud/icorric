<?php
/**
 * Entrada individual (noticias / hitos institucionales).
 */
get_header();
?>
<main class="entry">
  <div class="wrap">
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1><?php the_title(); ?></h1>
        <p style="color:var(--ink-600);font-size:0.9rem"><?php echo esc_html( get_the_date() ); ?></p>
        <?php if ( has_post_thumbnail() ) : ?>
          <div style="margin:24px 0;border-radius:14px;overflow:hidden">
            <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;display:block' ) ); ?>
          </div>
        <?php endif; ?>
        <div class="entry-content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  </div>
</main>
<?php
get_footer();
