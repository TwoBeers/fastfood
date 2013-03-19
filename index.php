<?php
/**
 * index.php
 *
 * This file is the master/default template file, used for Inedx/Archives/Search
 *
 * @package fastfood
 * @since 0.15
 */


get_header(); ?>

<?php fastfood_hook_content_before(); ?>

<div id="posts_content" class="<?php echo ( fastfood_use_sidebar() ) ? 'posts_narrow' : 'posts_wide'; ?>">

	<?php fastfood_hook_content_top(); ?>

	<?php get_template_part( 'loop', 'index' ); ?>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php fastfood_hook_content_after(); ?>

<?php if ( fastfood_use_sidebar() ) fastfood_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
