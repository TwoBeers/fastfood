<?php
/**
 * header.php
 *
 * Template part file that contains the HTML document head and 
 * opening HTML body elements, as well as the site header and 
 * the "breadcrumb" bar.
 *
 *
 * @package fastfood
 * @since 0.15
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes( 'xhtml' ); ?>>

	<head profile="http://gmpg.org/xfn/11">

		<?php fastfood_hook_head_top(); ?>

		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

		<meta name="viewport" content="width=100%; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=no;" />

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>

		<?php fastfood_hook_head_bottom(); ?>

		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<div id="ff_background"></div>

		<?php fastfood_hook_body_top(); ?>

		<div id="main">

			<?php
				wp_nav_menu( array(
					'container_class'	=> 'menu-container',
					'menu_id'			=> 'menu-secondary1',
					'menu_class'		=> 'nav-menu one-level secondary',
					'fallback_cb'		=> false,
					'theme_location'	=> 'secondary1',
					'depth'				=> 1,
				) );
			?>

			<?php fastfood_hook_header_before(); ?>

			<div id="header">

				<?php fastfood_hook_header_top(); ?>

				<?php echo fastfood_header(); ?>

				<?php fastfood_hook_header_bottom(); ?>

			</div>

			<?php fastfood_hook_header_after(); ?>

			<?php fastfood_get_sidebar( 'header', true ); // show header widgets area ?>

			<?php fastfood_hook_breadcrumb_navigation(); ?>

			<?php fastfood_hook_content_before(); ?>

			<div id="content">
