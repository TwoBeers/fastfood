<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes( 'xhtml' ); ?>>

	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name = "viewport" content = "width = device-width" />
		<title><?php
			if ( is_front_page() ) {
				bloginfo( 'name' ); ?> - <?php bloginfo( 'description' );
			} else {
				wp_title( '&laquo;', true, 'right' );
				bloginfo( 'name' );
			}
			?></title>
		<?php global $fastfood_opt; ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_get_archives( 'type=monthly&format=link&limit=10' ); ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class( 'ff-no-js' ); ?>>
		<div id="ff_background">
			<div id="ff_body" class="pad_bg">
				<div id="ff_body_overlay"></div>
			</div>
		</div>
		<div id="main">

		<div id="content">
			<?php wp_nav_menu( array( 'container_class' => 'ff-menu', 'container_id' => 'secondary1', 'fallback_cb' => false, 'theme_location' => 'secondary1', 'depth' => 1 ) ); ?>
			<?php fastfood_hook_before_header(); ?>
			<?php if ( ( $fastfood_opt['fastfood_head_link'] == 1 ) && (  get_header_image() != '' ) ) { ?>
				<div id="img-head"><a href="<?php echo home_url(); ?>/"><img src="<?php esc_url ( header_image() ); ?>" /></a></div>
			<?php } else { ?>
				<div id="head">
					<?php fastfood_hook_before_site_title(); ?>
					<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
					<?php fastfood_hook_after_site_title(); ?>
					<div class="description"><?php bloginfo( 'description' ); ?></div>
				</div>
			<?php } ?>
			<?php fastfood_hook_after_header(); ?>
			<?php if ( $fastfood_opt['fastfood_primary_menu'] ) { ?>
			<div id="pages">
			<?php fastfood_hook_before_pages(); ?>
				<div id="rss_imglink"><a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php _e( 'Syndicate this site using RSS 2.0','fastfood' ); ?>"><img alt="rsslink" src="<?php echo get_template_directory_uri(); ?>/images/rss.png" /></a></div>
				<?php wp_nav_menu( array( 'menu_id' => 'mainmenu', 'fallback_cb' => 'fastfood_pages_menu', 'theme_location' => 'primary' ) ); ?>
				<div class="fixfloat"></div>
			<?php fastfood_hook_after_pages(); ?>
			</div>
			<?php } ?>
			
			<?php fastfood_get_sidebar( 'header' ); // show header widgets area ?>
			
			<?php if ( $fastfood_opt['fastfood_breadcrumb'] == 1 ) fastfood_breadcrumb(); // show breadcrumb ?>
