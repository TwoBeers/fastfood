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

		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>

		<?php fastfood_hook_head_bottom(); ?>

		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<div id="ff_background"></div>

		<?php fastfood_hook_body_top(); ?>

		<div id="main">

			<?php fastfood_hook_site_header(); ?>

			<div id="content">
