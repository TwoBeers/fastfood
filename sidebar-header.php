<?php
/**
 * sidebar-header.php
 *
 * Template part file that contains the header widget area
 *
 * @package fastfood
 * @since 0.15
 */
?>

<?php fastfood_hook_sidebars_before( 'header' ); ?>

<div id="header-widget-area" class="widget-area">

	<?php fastfood_hook_sidebar_top( 'header' ); ?>

	<?php dynamic_sidebar( 'header-widget-area' ); ?>

	<?php fastfood_hook_sidebar_bottom( 'header' ); ?>

</div><!-- #header-widget-area -->

<?php fastfood_hook_sidebars_after( 'header' ); ?>
