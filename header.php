<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

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
		global $fastfood_opt;

		//load style
		fastfood_stylesheet();
		?>
		
		<?php // custom header style and conditional comment for IE ?>
		<style type="text/css">
			#head {
				background: transparent url( '<?php esc_url ( header_image() ); ?>' ) right bottom no-repeat;
			}
			#head h1 a, #head .description, #head {
				color: #<?php echo get_theme_mod( 'header_textcolor', '404040' ); ?>;
			}
		</style>
		<!--[if lte IE 8]>
		<style type="text/css">
			.js-res {
				border:1px solid #333333 !important;
			}
			.menuitem_1ul > ul > li {
				margin-right:-2px;
			}
		</style>
		<![endif]-->


		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_get_archives( 'type=monthly&format=link' ); ?>
		<?php
			if ( $fastfood_opt['fastfood_jsani'] == 'active' ) { //load scripts if enabled
				wp_enqueue_script( 'mootools_core', get_bloginfo( 'stylesheet_directory' ) . '/js/mootools.js' ); //mootools core
				if ( !isset( $_GET['style'] ) ) { //script not to be loaded in print preview
					wp_enqueue_script( 'fastfoodscript', get_bloginfo( 'stylesheet_directory' ) . '/js/fastfoodscript.js' ); //fastfood js
				}
			}
			if ( is_singular() ) {
				wp_enqueue_script( 'comm-rep', get_bloginfo( 'stylesheet_directory' ) . '/js/comment-reply.min.js' ); //custom comment-reply pop-up box
			}
		?>
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>
		<div id="main">

		<div id="content">

			<div id="head">
				<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
				<div class="description"><?php bloginfo( 'description' ); ?></div>
			</div>

			<div id="pages">
				<div id="rss_imglink"><a href="<?php bloginfo( 'rss2_url' ); ?>" title="<?php _e( 'Syndicate this site using RSS 2.0' ); ?>"><img alt="rsslink" src="<?php bloginfo( 'stylesheet_directory' ); ?>/images/rss.png" /></a></div>
				<?php wp_nav_menu( array( 'menu_id' => 'mainmenu', 'fallback_cb' => 'fastfood_pages_menu', 'theme_location' => 'primary' ) ); ?>
				<div class="fixfloat"></div>
			</div>

			<div id="posts_content" <?php if ( is_singular() ) { echo 'class="posts_wide"'; } else { echo 'class="posts_narrow"'; } ?>>