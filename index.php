<?php get_header(); ?>

<div id="posts_content" class="posts_narrow">

<?php
global $fastfood_opt;

// search reminder
if ( is_category() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Categories','fastfood' ) . ': <strong>' . wp_title( '',false,'right' ) . ' </strong></div>';
} elseif ( is_tag() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Tags','fastfood' ) . ': <strong>' . wp_title( '',false,'right' ) . ' </strong></div>';
} elseif ( is_date() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Archives','fastfood' ) . ': <strong>' . wp_title( '',false,'right' ) . ' </strong></div>';
} elseif (is_author()) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Author','fastfood' ) . ': <strong>' . wp_title( '',false,'right' ) . ' </strong>';
	$ff_author = get_queried_object();
	// If a user has filled out their description, show a bio on their entries.
	if ( $ff_author->description ) { ?>
		<div id="entry-author-info">
			<?php echo get_avatar( $ff_author->user_email, 32, $default= get_template_directory_uri() . '/images/user.png','user-avatar' ); ?>
			<?php
				if ( $ff_author->twitter ) echo '<a title="' . sprintf( __('follow %s on Twitter', 'fastfood'), $ff_author->display_name ) . '" href="'.$ff_author->twitter.'"><img alt="twitter" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Twitter.png" /></a>';
				if ( $ff_author->facebook ) echo '<a title="' . sprintf( __('follow %s on Facebook', 'fastfood'), $ff_author->display_name ) . '" href="'.$ff_author->facebook.'"><img alt="facebook" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Facebook.png" /></a>';
			?>
			<br />
			<?php echo $ff_author->description; ?>
		</div><!-- #entry-author-info -->
	<?php }
	echo '</div>';
}

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); //start post loop ?>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			
<?php // display locked posts ?>
				<?php if ( post_password_required() ) { ?>
					<h2 class="storytitle"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php 
						$ff_post_title = the_title( '','',false );
						if ( !$ff_post_title ) {
							_e( '(no title)','fastfood' );
						} else {
							echo $ff_post_title;
						}
						?></a>
					</h2>
					<div>
						<?php the_content(); ?>
					</div>
<?php // display posts of the Gallery format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'gallery' == get_post_format( $post->ID ) && $fastfood_opt['fastfood_post_formats_gallery'] == 1 ) { ?>
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
						$ff_post_title = the_title( '','',false );
						if ( !$ff_post_title ) {
							the_time( get_option( 'date_format' ) );
						} else {
							echo $ff_post_title;
						}
						?></a>
					</h2>
					
					<?php fastfood_extrainfo( true, true, true, true, true ); ?>
					
					<div class="storycontent">
						<?php
							$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
							if ( $images ) {
								$total_images = count( $images );
								$image = array_shift( $images );
						?>
							<div class="gallery-thumb" style="width: <?php echo get_option('medium_size_w'); ?>px; max-width: 100%;"><a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $image->ID, 'medium' ); ?></a></div><!-- .gallery-thumb -->
							<?php 
								$otherimgs = array_slice( $images, 0, 4 );
								foreach ($otherimgs as $image) {
									$image_img_tag = wp_get_attachment_image( $image->ID, array(75,75) );
									?>
										<div class="gallery-thumb" style="width: <?php echo floor( get_option('thumbnail_size_w')/2 ); ?>px;"><a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a></div><!-- .gallery-thumb -->
									<?php
								}
							?>
							<p style="float: left; white-space: nowrap;">
								<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $total_images, 'fastfood' ),
									'href="' . get_permalink() . '" title="' . __( 'View gallery', 'fastfood' ) . '" rel="bookmark"',
									number_format_i18n( $total_images )
									); ?></em>
							</p>
							<div class="fixfloat"> </div>
						<?php } ?>
						<?php the_excerpt(); ?>
					</div>

<?php // display posts of the Aside format ?>
				<?php } elseif ( function_exists( 'get_post_format' ) && 'aside' == get_post_format( $post->ID ) && $fastfood_opt['fastfood_post_formats_aside'] == 1 ) { ?>
					<div class="aside-cont">
						<?php the_content(); ?>
						<span style="font-size: 11px; font-style: italic; color: #404040;"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php comments_popup_link('(0)', '(1)','(%)'); ?></span>
					</div>
<?php // display any other post ?>
				<?php } else { ?>
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
						$ff_post_title = the_title( '','',false );
						if ( !$ff_post_title ) {
							_e( '(no title)','fastfood' );
						} else {
							echo $ff_post_title;
						}
						?></a>
					</h2>
					
					<?php fastfood_extrainfo( true, true, true, true, true ); ?>

					<div class="storycontent">
						<?php the_content();	?>
					</div>
				<?php } ?>
				
				<div class="fixfloat"> </div>

				<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>

			</div>
		<?php } //end while ?>

	<div class="w_title" id="ff-page-nav">
		<?php //num of pages
		global $paged;
		if ( !$paged ) {
			$paged = 1;
		}
		previous_posts_link( '&laquo;' );
		printf( __( 'page %1$s of %2$s','fastfood' ), $paged, $wp_query->max_num_pages );
		next_posts_link( '&raquo;' );
		?>
	</div>

<?php } else { ?>

	<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' ); ?></p>

<?php } //endif ?>

</div>
<?php get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
