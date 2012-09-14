<?php get_header(); ?>
<?php global $fastfood_opt; ?>

<?php fastfood_hook_before_posts(); ?>
<div id="posts_content" class="posts_wide">
	<?php if ( have_posts() ) {
		while ( have_posts() ) {
			the_post(); ?>
			<?php fastfood_hook_before_post(); ?>
			<?php fastfood_I_like_it();?>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<?php fastfood_hook_before_post_title(); ?>
				<?php fastfood_featured_title( array( 'fallback' => sprintf ( __('attachment #%s','fastfood'), get_the_ID() ) ) ); ?>
				<?php fastfood_hook_after_post_title(); ?>
				<?php fastfood_extrainfo( array( 'tags' => 0, 'cats' => 0 ) ); ?>
				<?php fastfood_hook_before_post_content(); ?>
				<div class="storycontent">
					<div class="entry-attachment" style="text-align: center;">
						<?php if ( wp_attachment_is_image() ) { //from twentyten WP theme
							$ff_attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
							foreach ( $ff_attachments as $ff_k => $ff_attachment ) {
								if ( $ff_attachment->ID == $post->ID )
									break;
							}
							$ff_nextk = $ff_k + 1;
							$ff_prevk = $ff_k - 1;
							?>
							<div class="img-navi" style="text-align: center;">
				
							<?php if ( isset( $ff_attachments[ $ff_prevk ] ) ) { ?>
									<a class="size-thumbnail" title="" href="<?php echo get_attachment_link( $ff_attachments[ $ff_prevk ]->ID ); ?>">&laquo; <?php echo wp_get_attachment_image( $ff_attachments[ $ff_prevk ]->ID, array( 70, 70 ) ); ?></a>
							<?php } ?>
							<span class="img-navi-curimg"><?php echo wp_get_attachment_image( $post->ID, array( 70, 70 ) ); ?></span>
							<?php if ( isset( $ff_attachments[ $ff_nextk ] ) ) { ?>
									<a class="size-thumbnail" title="" href="<?php echo get_attachment_link( $ff_attachments[ $ff_nextk ]->ID ); ?>"><?php echo wp_get_attachment_image( $ff_attachments[ $ff_nextk ]->ID, array( 70, 70 ) ); ?> &raquo;</a>
							<?php } ?>
							</div>
							<p class="attachment"><a href="<?php echo wp_get_attachment_url(); ?>" title="<?php _e( 'View full size','fastfood' ) ;  // link to Full size image ?>" rel="attachment"><?php
								echo wp_get_attachment_image( $post->ID, 'full' );
							?></a></p>
							<?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?>
							<?php if ( !empty( $post->post_content ) ) the_content(); ?>
						<?php } else { ?>
							<?php echo wp_get_attachment_link( $post->ID,'thumbnail', 0,1 ); ?> 
							<?php fastfood_video_player(); ?>
							<div class="entry-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>
						<?php } ?>
					</div><!-- .entry-attachment -->
				</div>
				<?php fastfood_hook_after_post_content(); ?>
			</div>	
			<?php fastfood_hook_after_post(); ?>
			
			<?php fastfood_get_sidebar( 'singular' ); // show singular widgets area ?>
			
			<?php comments_template(); // Get wp-comments.php template ?>
			
		<?php	} //end while
	} else {?>
		
		<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' );?></p>
		
	<?php } ?>

</div>	
<?php fastfood_hook_after_posts(); ?>

<?php get_footer(); ?>
