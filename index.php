<?php get_header();
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
					<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
						$ff_post_title = the_title( '','',false );
						if ( !$ff_post_title ) {
							_e( '(no title)','fastfood' );
						} else {
							echo $ff_post_title;
						}
						?></a>
					</h2>
					<div class="storycontent">
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
					<div class="meta top_meta">

						<div class="metafield_trigger" style="left: 10px;"><?php _e( 'by', 'fastfood' ); ?> <?php printf( '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __('View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' ); ?></div>
						<div class="metafield">
							<div class="metafield_trigger mft_date no-mobile" style="right: 100px; width:16px"> </div>
							<div class="metafield_content">
								<?php printf( __( 'Published on: <b>%1$s</b>','fastfood' ), '' ); the_time( get_option( 'date_format' ) ); ?>
							</div>
						</div>

						<div class="metafield">
							<div class="metafield_trigger mft_cat no-mobile" style="right: 10px; width:16px"> </div>
							<div class="metafield_content">
								<?php echo __( 'Categories','fastfood' ) . ':'; ?>
								<?php the_category( ', ' ) ?>
							</div>
						</div>

						<div class="metafield">
							<div class="metafield_trigger mft_tag no-mobile" style="right: 40px; width:16px"> </div>
							<div class="metafield_content">
								<?php
								echo __('Tags','fastfood' ) . ': ';
								if ( !get_the_tags() ) {
									_e( 'No Tags','fastfood' );
								} else {
									the_tags( '', ', ', '' );
								}
								?>
							</div>
						</div>

						<div class="metafield">
							<div class="metafield_trigger mft_comm no-mobile" style="right: 70px; width:16px"> </div>
							<div class="metafield_content">
								<?php _e( 'Comments','fastfood' ); ?>:
								<?php comments_popup_link( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' )); // number of comments ?>
							</div>
						</div>

						<div class="edit_link metafield_trigger" style="right: 130px;"><?php edit_post_link( __( 'Edit','fastfood' ),'' ); ?></div>

					</div>
					
					<div class="storycontent">
						<?php
							$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
							if ( $images ) {
								$total_images = count( $images );
								$image = array_shift( $images );
						?>
							<div class="gallery-thumb" style="width: <?php echo get_option('medium_size_w'); ?>px;">
								<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $image->ID, 'medium' ); ?></a>
							</div><!-- .gallery-thumb -->
							<?php 
								$otherimgs = array_slice( $images, 0, 4 );
								foreach ($otherimgs as $image) {
									$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
									?>
										<div class="gallery-thumb" style="width: <?php echo floor( get_option('thumbnail_size_w')/2 ); ?>px;">
											<a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
										</div><!-- .gallery-thumb -->
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
					<div class="meta top_meta">

						<div class="metafield_trigger" style="left: 10px;"><?php _e( 'by', 'fastfood' ); ?> <?php printf( '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" title="' . sprintf( __('View all posts by %s', 'fastfood' ), esc_attr( get_the_author() ) ) . '">' . get_the_author() . '</a>' ); ?></div>
						<div class="metafield">
							<div class="metafield_trigger mft_date no-mobile" style="right: 100px; width:16px"> </div>
							<div class="metafield_content">
								<?php printf( __( 'Published on: <b>%1$s</b>','fastfood' ), '' ); the_time( get_option( 'date_format' ) ); ?>
							</div>
						</div>

						<div class="metafield">
							<div class="metafield_trigger mft_cat no-mobile" style="right: 10px; width:16px"> </div>
							<div class="metafield_content">
								<?php echo __( 'Categories','fastfood' ) . ':'; ?>
								<?php the_category( ', ' ) ?>
							</div>
						</div>

						<div class="metafield">
							<div class="metafield_trigger mft_tag no-mobile" style="right: 40px; width:16px"> </div>
							<div class="metafield_content">
								<?php
								echo __('Tags','fastfood' ) . ': ';
								if ( !get_the_tags() ) {
									_e( 'No Tags','fastfood' );
								} else {
									the_tags( '', ', ', '' );
								}
								?>
							</div>
						</div>

						<div class="metafield">
							<div class="metafield_trigger mft_comm no-mobile" style="right: 70px; width:16px"> </div>
							<div class="metafield_content">
								<?php _e( 'Comments','fastfood' ); ?>:
								<?php comments_popup_link( __( 'No Comments','fastfood' ), __( '1 Comment','fastfood' ), __( '% Comments','fastfood' )); // number of comments ?>
							</div>
						</div>

						<div class="edit_link metafield_trigger" style="right: 130px;"><?php edit_post_link( __( 'Edit','fastfood' ),'' ); ?></div>

					</div>

					<div class="storycontent">
						<?php the_content();	?>
					</div>
				<?php } ?>
				
				<div class="fixfloat"> </div>

				<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>

			</div>
		<?php } //end while ?>

	<div class="w_title">
		<?php //num of pages
		global $paged;
		if ( !$paged ) {
			$paged = 1;
		}
		printf( __( 'page %1$s of %2$s','fastfood' ), $paged, $wp_query->max_num_pages );
		?>
	</div>

<?php } else { ?>

	<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' ); ?></p>

<?php } //endif ?>

<?php get_footer(); ?>
