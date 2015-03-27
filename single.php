<?php
/**
 * single.php
 *
 * The single blog post template file, used to display single blog posts.
 *
 * @package fastfood
 * @since 0.15
 */


get_header(); ?>

<?php fastfood_hook_content_before(); ?>

<div id="posts-content"<?php fastfood_posts_content_class(); ?>>

	<?php fastfood_hook_content_top(); ?>

	<?php get_template_part( 'loop', 'singular' ); ?>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php fastfood_hook_content_after(); ?>

<?php fastfood_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
