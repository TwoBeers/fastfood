<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * @package fastfood
 * @since fastfood 0.23
 */


get_header(); ?>

<div id="posts_content" class="posts_wide">

	<?php fastfood_hook_content_top(); ?>

	<?php get_template_part( 'loop', 'singular' ); ?>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php get_footer(); ?>
