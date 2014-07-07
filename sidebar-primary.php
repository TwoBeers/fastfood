<?php
/**
 * sidebar-primary.php
 *
 * Template part file that contains the primary sidebar content
 *
 * @package fastfood
 * @since 0.15
 */
?>

<!-- begin sidebar -->

<?php fastfood_hook_sidebars_before( 'primary' ); ?>

<div id="primary-widget-area" class="widget-area">

	<?php fastfood_hook_sidebar_top( 'primary' ); ?>

	<?php if ( !dynamic_sidebar( 'primary-widget-area' ) ) fastfood_default_widgets(); //if the widget area is empty, we print some standard wigets ?>

	<?php fastfood_hook_sidebar_bottom( 'primary' ); ?>

</div>

<?php fastfood_hook_sidebars_after( 'primary' ); ?>

<!-- end sidebar -->