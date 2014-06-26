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

<div id="posts_content" class="<?php echo ( fastfood_use_sidebar() ) ? 'posts_narrow' : 'posts_wide'; ?>">

	<?php fastfood_hook_content_top(); ?>

	<?php get_template_part( 'loop', 'singular' ); ?>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php if ( fastfood_use_sidebar() ) fastfood_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
