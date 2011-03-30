<?php get_header();
// search reminder
if ( is_category() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Categories','fastfood' ) . ': <strong>';
	wp_title( '',true,'right' );
	echo ' </strong></div>';
} elseif ( is_tag() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Tags:','fastfood' ) . ' <strong>';
	wp_title( '',true,'right' );
	echo ' </strong></div>';
} elseif ( is_date() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Archives','fastfood' ) . ': <strong>';
	wp_title( '',true,'right' );
	echo ' </strong></div>';
} elseif (is_author()) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Author','fastfood' ) . ': <strong>';
	wp_title( '',true,'right' );
	echo ' </strong>';
	$ff_author = get_queried_object();
	// If a user has filled out their description, show a bio on their entries.
	if ( $ff_author->description ) { ?>
		<div id="entry-author-info">
			<?php echo get_avatar( $ff_author->user_email, 32, $default= get_template_directory_uri() . '/images/user.png','user-avatar' ); ?>
			<?php
				if ( $ff_author->twitter ) echo '<a title="' . sprintf( __('follow %s on Twitter', 'shiword'), $ff_author->display_name ) . '" href="'.$ff_author->twitter.'"><img alt="twitter" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Twitter.png" /></a>';
				if ( $ff_author->facebook ) echo '<a title="' . sprintf( __('follow %s on Facebook', 'shiword'), $ff_author->display_name ) . '" href="'.$ff_author->facebook.'"><img alt="facebook" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Facebook.png" /></a>';
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

				<h2 class="storytitle">
					<a href="<?php the_permalink() ?>" rel="bookmark">
						<?php
						$ff_post_title = the_title_attribute( 'echo=0' );
						if ( !$ff_post_title ) {
							_e( '(no title)','fastfood' );
						} else {
							echo $ff_post_title;
						}
						?>
					</a>
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
							_e( 'Tags:','fastfood' );
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
