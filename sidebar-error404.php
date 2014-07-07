<?php
/**
 * sidebar-error404.php
 *
 * Template part file that contains the primary sidebar content
 *
 * @package fastfood
 * @since 0.37
 */
?>

<!-- begin sidebar -->

<?php fastfood_hook_sidebars_before( 'error404' ); ?>

<div id="error404-widgets-area" class="widget-area">

	<?php fastfood_hook_sidebar_top( 'error404' ); ?>

	<?php dynamic_sidebar( 'error404-widgets-area' ); ?>

	<?php fastfood_hook_sidebar_bottom( 'error404' ); ?>

</div>

<?php fastfood_hook_sidebars_after( 'error404' ); ?>

<!-- end sidebar -->