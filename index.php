<?php
/**
 * index.php
 *
 * This file is the master/default template file, used for Index/Archives/Search
 *
 * @package fastfood
 * @since 0.15
 */


get_header(); ?>

<div id="posts-content"<?php fastfood_posts_content_class(); ?>>

	<?php fastfood_hook_content_top(); ?>

	<?php get_template_part( 'loop' ); ?>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php fastfood_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
