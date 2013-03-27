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

<?php fastfood_hook_sidebars_before(); ?>

<?php fastfood_hook_this_sidebar_before( 'singular' ); ?>

<div id="post-widgets-area" class="fixfloat">

	<div class="fixfloat">

		<?php fastfood_hook_sidebar_top(); ?>

		<?php fastfood_hook_this_sidebar_top( 'singular' ); ?>

	</div> 

	<div><?php dynamic_sidebar( 'post-widgets-area' ); ?></div>

	<div class="fixfloat">

		<?php fastfood_hook_this_sidebar_bottom( 'singular' ); ?>

		<?php fastfood_hook_sidebar_bottom(); ?>

	</div> 

</div><!-- #post-widgets-area -->

<?php fastfood_hook_this_sidebar_after( 'singular' ); ?>

<?php fastfood_hook_sidebars_after(); ?>
