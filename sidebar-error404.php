<?php
/**
 * sidebar-error404.php
 *
 * Template part file that contains the 'error404' page widget area
 *
 * @package fastfood
 * @since 0.37
 */
?>

<?php fastfood_hook_sidebars_before( 'error404' ); ?>

<div id="error404-widgets-area" class="widget-area">

	<?php fastfood_hook_sidebar_top( 'error404' ); ?>

	<?php dynamic_sidebar( 'error404-widgets-area' ); ?>

	<?php fastfood_hook_sidebar_bottom( 'error404' ); ?>

</div><!-- #error404-widgets-area -->

<?php fastfood_hook_sidebars_after( 'error404' ); ?>
