<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes( 'xhtml' ); ?>>

	<head profile="http://gmpg.org/xfn/11">

		<?php fastfood_hook_head_top(); ?>

		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

		<meta name = "viewport" content = "width = device-width" />

		<title><?php wp_title( '&laquo;', true, 'right' ); ?></title>

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

		<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>

		<?php fastfood_hook_head_bottom(); ?>

		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<div id="ff_background"><div id="ff_body" class="pad_bg"><div id="ff_body_overlay"></div></div></div>

		<?php fastfood_hook_body_top(); ?>

		<div id="main">

			<div id="content">

				<?php wp_nav_menu( array( 'container_class' => 'ff-menu', 'container_id' => 'secondary1', 'fallback_cb' => false, 'theme_location' => 'secondary1', 'depth' => 1 ) ); ?>

				<?php fastfood_hook_header_before(); ?>

				<div id="header">

					<?php fastfood_hook_header_top(); ?>

					<?php echo fastfood_header(); ?>

					<?php fastfood_hook_header_bottom(); ?>

				</div>

				<?php fastfood_hook_header_after(); ?>

				<?php fastfood_get_sidebar( 'header' ); // show header widgets area ?>

				<?php fastfood_hook_breadcrumb_navigation(); ?>
