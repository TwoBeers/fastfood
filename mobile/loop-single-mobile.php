<?php locate_template( array( 'mobile/header-mobile.php' ), true, false ); ?>
<?php if ( have_posts() ) { ?>
	<?php while ( have_posts() ) { 
		the_post(); ?>
		<div class="tbm-navi halfsep">
				<?php if ( get_next_post() ) { ?><span class="tbm-halfspan tbm-prev outset"><?php next_post_link('%link'); ?></span><?php } ?>
				<?php if ( get_previous_post() ) { ?><span class="tbm-halfspan tbm-next outset"><?php previous_post_link('%link'); ?></span><?php } ?>
				<div class="fixfloat"> </div>
		</div>
		<div <?php post_class( 'tbm-post tbm-padded' ) ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
			<?php the_content(); ?>
			<div class="commentmetadata fixfloat">
				<?php if ( is_attachment() ) { 
					fastfood_mobile_post_details( false, false, false, false );
				} else { 
					fastfood_mobile_post_details( true, true, true, true, false, 48 ); 
				} ?>
			</div>
			<?php wp_link_pages('before=<div class="tbm-pc-navi">' . __('Pages', 'fastfood') . ':&after=</div>'); ?>
		</div>
		<?php comments_template('/mobile/comments-mobile.php'); ?>
		<div class="tbm-navi halfsep">
				<?php if ( get_next_post() ) { ?><span class="tbm-halfspan tbm-prev outset"><?php next_post_link('%link'); ?></span><?php } ?>
				<?php if ( get_previous_post() ) { ?><span class="tbm-halfspan tbm-next outset"><?php previous_post_link('%link'); ?></span><?php } ?>
				<div class="fixfloat"> </div>
		</div>
	<?php } ?>
<?php } else { ?>
	<p class="tbm-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'fastfood' );?></p>
<?php } ?>
<?php locate_template( array( 'mobile/footer-mobile.php' ), true, false ); ?>
