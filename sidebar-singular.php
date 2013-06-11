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

<!-- here should be the Single widget area -->
<?php
	/* The Single widget area is triggered if any of the areas have widgets. */
	if ( !is_active_sidebar( 'post-widgets-area'  ) )
		return;
?>

<?php fastfood_hook_sidebars_before( 'singular' ); ?>

<div id="post-widgets-area" class="fixfloat">

	<?php fastfood_hook_sidebar_top( 'singular' ); ?>

	<br class="fixfloat" />

	<?php dynamic_sidebar( 'post-widgets-area' ); ?>

	<br class="fixfloat" />

	<?php fastfood_hook_sidebar_bottom( 'singular' ); ?>

</div><!-- #post-widgets-area -->

<?php fastfood_hook_sidebars_after( 'singular' ); ?>
