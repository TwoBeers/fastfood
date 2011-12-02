<?php
	global $fastfood_opt;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name = "viewport" content = "width = device-width">
		<title><?php
			if ( is_front_page() ) {
				bloginfo( 'name' ); ?> - <?php bloginfo( 'description' );
			} else {
				wp_title( '&laquo;', true, 'right' );
				bloginfo( 'name' );
			}
			?></title>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php wp_get_archives( 'type=monthly&format=link' ); ?>
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<div id="main">
			<div id="head">
				<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
			</div>
			<?php // search reminder
			if ( is_archive() ) { ?>
				<div class="ff-padded">
					<?php 
						if ( is_category() )	{ $ff_strtype = __( 'Category', 'fastfood' ) . ' : %s'; }
						elseif ( is_tag() )		{ $ff_strtype = __( 'Tag', 'fastfood' ) . ' : %s'; }
						elseif ( is_date() )	{ $ff_strtype = __( 'Archives', 'fastfood' ) . ' : %s'; }
						elseif (is_author()) 	{ $ff_strtype = __( 'Posts by %s', 'fastfood') ; }
					?>
					<?php printf( $ff_strtype, '<strong>' . wp_title( '',false ) . '</strong>'); ?>
				</div>
			<?php } elseif ( is_search() ) { ?>
				<div class="ff-padded">
					<?php printf( __( 'Search results for &#8220;%s&#8221;', 'fastfood' ), '<strong>' . esc_html( get_search_query() ) . '</strong>' ); ?>
				</div>
			<?php } ?>
			<?php if ( have_posts() ) { ?>
				<h2 class="ff-seztit"><span><?php _e( 'Posts', 'fastfood' ); ?></span> <a href="#themecredits">&#8744;</a></h2>
				<ul class="ff-group">
				<?php while ( have_posts() ) {
					the_post(); ?>
					<?php $ff_alter_style = ( !isset( $ff_alter_style ) || $ff_alter_style == 'ff-odd' ) ? 'ff-even' : 'ff-odd'; ?>
					<li class="<?php echo $ff_alter_style; ?>">
						<a href="<?php the_permalink() ?>" rel="bookmark"><?php 
							$ff_post_title = the_title( '','',false );
							if ( !$ff_post_title ) {
								_e( '(no title)', 'fastfood' );
							} else {
								echo $ff_post_title;
							}
							?><br /><span class="ff-details"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php comments_number('(0)', '(1)','(%)'); ?></span>
						</a>
					</li>
				<?php } ?>
				</ul>
				<?php //num of pages
				global $paged;
				if ( !$paged ) { $paged = 1; }
				?>
				<h2 class="ff-seztit"><a href="#head">&#8743;</a> <span><?php printf( __( 'page %1$s of %2$s', 'fastfood' ), $paged, $wp_query->max_num_pages ); ?></span> <a href="#themecredits">&#8744;</a></h2>
				<div class="ff-navi halfsep">
						<span class="ff-halfspan ff-prev"><?php previous_posts_link( __( 'Previous page', 'fastfood' ) ); ?></span>
						<span class="ff-halfspan ff-next"><?php next_posts_link( __( 'Next page', 'fastfood' ) ); ?></span>
						<div class="fixfloat"> </div>
				</div>
			<?php } else { ?>
				<p class="ff-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'fastfood' );?></p>
			<?php } ?>
			<h2 class="ff-seztit"><a href="#head">&#8743;</a> <span><?php _e( 'Search', 'fastfood' ); ?></span> <a href="#themecredits">&#8744;</a></h2>
			<div>
				<form id="search" action="<?php echo home_url(); ?>" method="get">
					<div>
						<input type="text" name="s" id="s" inputmode="predictOn" value="" />
						<input type="submit" name="submit_button" value="Search" />
					</div>
				</form>
			</div>
			<h2 class="ff-seztit"><a href="#head">&#8743;</a> <span><?php _e( 'Pages', 'fastfood' ); ?></span> <a href="#themecredits">&#8744;</a></h2>
			<?php wp_nav_menu( array( 'menu_class' => 'ff-group', 'menu_id' => 'mainmenu', 'fallback_cb' => 'fastfood_pages_menu_mobile', 'theme_location' => 'primary', 'depth' => 1 ) ); //main menu ?>
			<h2 class="ff-seztit"><a href="#head">&#8743;</a> <span>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?></span></h2>
			<p id="themecredits">
				<?php if ( $fastfood_opt['fastfood_tbcred'] == 1 ) { ?>
					Powered by <a href="http://wordpress.org"><strong>WordPress</strong></a> and <a href="http://www.twobeers.net/"><strong>Fastfood</strong></a>. 
				<?php } ?>
				<?php wp_loginout(); wp_register(' | ', ''); ?>
			</p>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>