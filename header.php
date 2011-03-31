<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name = "viewport" content = "width = device-width">

		<title>
			<?php
			if ( is_front_page() ) {
				bloginfo( 'name' ); ?> - <?php bloginfo( 'description' );
			} else {
				wp_title( '&laquo;', true, 'right' );
				bloginfo( 'name' );
			}
			?>
		</title>

		<?php
			global $fastfood_opt, $ff_is_mobile_browser;
		?>

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_get_archives( 'type=monthly&format=link' ); ?>
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>
		<div id="ff_background" class="no-mobile">
			<div id="ff_body" class="pad_bg">
				<div id="ff_body_overlay"></div>
			</div>
		</div>
		<div id="main">

		<div id="content">
			<?php wp_nav_menu( array( 'container_class' => 'ff-menu', 'container_id' => 'secondary1', 'fallback_cb' => false, 'theme_location' => 'secondary1', 'depth' => 1 ) ); ?>
			<div id="head">
				<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
				<div class="description no-mobile"><?php bloginfo( 'description' ); ?></div>
			</div>

			<div id="pages">
				<?php if ( $ff_is_mobile_browser ) { ?>
					<div class="search-form">
						<form action="<?php echo home_url(); ?>" method="get">
							<input type="text" id="s" name="s" value="" />
							<input type="submit" name="submit_button" value="<?php _e( 'Search','fastfood' ) ?>" />
						</form>
					</div>
				<?php } ?>
				<div id="rss_imglink" class="no-mobile"><a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php _e( 'Syndicate this site using RSS 2.0','fastfood' ); ?>"><img alt="rsslink" src="<?php echo get_template_directory_uri(); ?>/images/rss.png" /></a></div>
				<?php wp_nav_menu( array( 'menu_id' => 'mainmenu', 'fallback_cb' => 'fastfood_pages_menu', 'theme_location' => 'primary' ) ); ?>
				<div class="fixfloat"></div>
			</div>
			
			<?php if ( ! $ff_is_mobile_browser ) get_sidebar( 'header' ); // show header widgets area ?>
			<?php
				$ff_postswidth = 'class="posts_narrow"';
				if ( ( is_page() && ( $fastfood_opt['fastfood_rsidebpages'] == 0 ) ) || ( is_single() && ( $fastfood_opt['fastfood_rsidebposts'] == 0 ) ) || is_attachment() ) {
					$ff_postswidth = 'class="posts_wide"';
				}else if ( ( is_page() && ( $fastfood_opt['fastfood_rsidebpages'] == 1 ) ) || ( is_single() && ( $fastfood_opt['fastfood_rsidebposts'] == 1 ) ) ) {
					$ff_postswidth = 'class="posts_narrow" style="padding-bottom: 310px;"';
				}
			?>
			<div id="posts_content" <?php echo $ff_postswidth; ?>>