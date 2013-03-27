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

<?php fastfood_hook_sidebars_before(); ?>

<?php fastfood_hook_this_sidebar_before( 'primary' ); ?>

<div id="sidebardx">

	<div class="fixfloat">

		<?php fastfood_hook_sidebar_top(); ?>

		<?php fastfood_hook_this_sidebar_top( 'primary' ); ?>

	</div>

	<?php if ( !dynamic_sidebar( 'primary-widget-area' ) ) { //if the widget area is empty, we print some standard wigets ?>

		<?php fastfood_default_widgets(); ?>

	<?php } ?>

	<div class="fixfloat">

		<?php fastfood_hook_this_sidebar_bottom( 'primary' ); ?>

		<?php fastfood_hook_sidebar_bottom(); ?>

	</div> 

</div>

<?php fastfood_hook_this_sidebar_after( 'primary' ); ?>

<?php fastfood_hook_sidebars_after(); ?>

<!-- end sidebar -->