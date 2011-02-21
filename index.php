<?php get_header();
// search reminder
if ( is_category() ) {
	echo '<div class="wp-caption aligncenter"><p class="wp-caption-text"><strong>' . __( 'Categories','fastfood' ) . ': ';
	wp_title( '',true,'right' );
	echo ' </strong></p></div>';
} elseif ( is_tag() ) {
	echo '<div class="wp-caption aligncenter"><p class="wp-caption-text"><strong>' . __( 'Tags:','fastfood' ) . ' ';
	wp_title( '',true,'right' );
	echo ' </strong></p></div>';
} elseif ( is_date() ) {
	echo '<div class="wp-caption aligncenter"><p class="wp-caption-text"><strong>' . __( 'Archives','fastfood' ) . ': ';
	wp_title( '',true,'right' );
	echo ' </strong></p></div>';
}

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); //start post loop ?>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

				<h2 class="storytitle">
					<a href="<?php the_permalink() ?>" rel="bookmark">
						<?php
						$post_title = the_title_attribute( 'echo=0' );
						if ( !$post_title ) {
							_e( '(no title)','fastfood' );
						} else {
							echo $post_title;
						}
						?>
					</a>
				</h2>

				<div class="meta top_meta">

					<div class="metafield_trigger" style="left: 10px;"><?php _e( 'by','fastfood' ); ?> <?php the_author() ?></div>
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

				<div class="fixfloat">
					<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
				</div>

				<div class="fixfloat"> </div>

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
