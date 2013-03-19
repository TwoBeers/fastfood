<?php
/**
 * sidebar.php
 *
 * Template part file that contains the default sidebar content
 *
 * @package fastfood
 * @since 0.15
 */
?>

<!-- begin sidebar -->

<div id="sidebardx">

	<?php fastfood_hook_sidebar_top(); ?>

	<?php if ( !dynamic_sidebar( 'primary-widget-area' ) ) { //if the widget area is empty, we print some standard wigets ?>

		<?php fastfood_default_widgets(); ?>

	<?php } ?>

	<br class="fixfloat">

	<?php fastfood_hook_sidebar_bottom(); ?>

</div>

<!-- end sidebar -->