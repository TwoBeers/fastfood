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
		<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> 
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<div id="main">
			<div id="head">
				<h1><a href="<?php echo home_url(); ?>/"><?php bloginfo( 'name' ); ?></a></h1>
			</div>
			<?php if ( have_posts() ) { ?>
				<?php while ( have_posts() ) { 
					the_post(); ?>
					<div class="ff-navi halfsep">
							<span class="ff-halfspan ff-prev"><?php next_post_link('%link'); ?></span>
							<span class="ff-halfspan ff-next"><?php previous_post_link('%link'); ?></span>
							<div class="fixfloat"> </div>
					</div>
					<div <?php post_class( 'ff-post' ) ?> id="post-<?php the_ID(); ?>">
						<h2><?php 
							$ff_post_title = the_title( '','',false );
							if ( !$ff_post_title ) {
								_e( '(no title)', 'fastfood' );
							} else {
								echo $ff_post_title;
							}
							?>
						</h2>
						<?php the_content(); ?>
						<?php if ( ! is_page() ) { ?>
							<div class="commentmetadata fixfloat">
								<?php if ( is_attachment() ) { 
									fastfood_extrainfo( false, false, true, false, false, false, true );
								} else { 
									fastfood_extrainfo( true, true, true, true, true, false, true ); 
								} ?>
							</div>
						<?php } ?>
						<div class="ff-pc-navi">
							<?php wp_link_pages(); ?>
						</div>
					</div>
					<?php comments_template( '/mobile/mobile-comments.php' ); ?>
					<div class="ff-navi halfsep">
							<span class="ff-halfspan ff-prev"><?php next_post_link( '%link' ); ?></span>
							<span class="ff-halfspan ff-next"><?php previous_post_link( '%link' ); ?></span>
							<div class="fixfloat"> </div>
					</div>
					<?php if ( is_page() ) {
						$ff_args = array(
							'post_type' => 'page',
							'post_parent' => $post->ID,
							'order' => 'ASC',
							'orderby' => 'menu_order',
							'numberposts' => 0
							);
						$ff_sub_pages = get_posts( $ff_args ); // retrieve the child pages
					} else {
						$ff_sub_pages = '';
					}

					if ( !empty( $ff_sub_pages ) ) { ?>
						<h2 class="ff-seztit"><a href="#head">&#8743;</a> <span><?php _e( 'Child pages: ', 'fastfood' ); ?></span> <a href="#themecredits">&#8744;</a></h2>
						<ul class="ff-group">
							<?php 
							foreach ( $ff_sub_pages as $ff_children ) {
								echo '<li><a href="' . get_permalink( $ff_children ) . '" title="' . esc_attr( strip_tags( get_the_title( $ff_children ) ) ) . '">' . get_the_title( $ff_children ) . '</a></li>';
							}
							?>
						</ul>
						
					<?php } ?>
					<?php $ff_the_parent_page = $post->post_parent; // retrieve the parent page
					if ( $ff_the_parent_page ) {?>
						<h2 class="ff-seztit"><a href="#head">&#8743;</a> <span><?php _e( 'Parent page: ', 'fastfood' ); ?></span> <a href="#themecredits">&#8744;</a></h2>
						<ul class="ff-group">
								<li><a href="<?php echo get_permalink( $ff_the_parent_page ); ?>" title="<?php echo esc_attr( strip_tags( get_the_title( $ff_the_parent_page ) ) ); ?>"><?php echo get_the_title( $ff_the_parent_page ); ?></a></li>
						</ul>
					<?php } ?>
				<?php } ?>
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
			<?php if ( $fastfood_opt['fastfood_qbar_reccom'] == 1 ) { // recent comments menu ?>
				<h2 class="ff-seztit"><a href="#head">&#8743;</a> <span><?php _e( 'Recent Comments', 'fastfood' ); ?></span> <a href="#themecredits">&#8744;</a></h2>
				<ul id="ff-reccom">
					<?php fastfood_get_recentcomments(); ?>
				</ul>
			<?php } ?>
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