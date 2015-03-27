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

<?php fastfood_hook_content_before(); ?>

<div id="posts-content" class="posts_wide">

	<?php fastfood_hook_content_top(); ?>

	<?php get_template_part( 'loop', 'singular' ); ?>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php fastfood_hook_content_after(); ?>

<?php get_footer(); ?>
