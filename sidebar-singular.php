<?php
/**
 * sidebar-singular.php
 *
 * Template part file that contains the widget area for
 * single posts/pages
 *
 * @package fastfood
 * @since 0.15
 */
?>

<?php fastfood_hook_sidebars_before( 'singular' ); ?>

<div id="post-widgets-area" class="widget-area fixfloat">

	<?php fastfood_hook_sidebar_top( 'singular' ); ?>

	<?php dynamic_sidebar( 'post-widgets-area' ); ?>

	<?php fastfood_hook_sidebar_bottom( 'singular' ); ?>

</div><!-- #post-widgets-area -->

<?php fastfood_hook_sidebars_after( 'singular' ); ?>
