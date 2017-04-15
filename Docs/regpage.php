<?php
/**
 * Template Name: Registration Page Template
 */
add_action ( 'wp_head', 'load_custom_issv_style' );

get_header ();
?>

<div id="primary" class="content-area column full">
	<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>

		</main>
	<!-- #main -->
</div>
<!-- #primary -->

<?php get_footer(); ?>
