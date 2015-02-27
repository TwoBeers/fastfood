<?php
/**
 * sidebar-footer.php
 *
 * Template part file that contains the footer widget area
 *
 * @package fastfood
 * @since 0.15
 */
?>

<?php fastfood_hook_sidebars_before( 'footer' ); ?>

<div id="footer-widget-area" class="widget-area">

	<?php fastfood_hook_sidebar_top( 'footer' ); ?>

	<?php dynamic_sidebar( 'footer-widget-area' ); ?>

	<?php fastfood_hook_sidebar_bottom( 'footer' ); ?>

</div><!-- #footer-widget-area -->

<?php fastfood_hook_sidebars_after( 'footer' ); ?>
