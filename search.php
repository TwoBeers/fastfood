<?php get_header(); ?>

	<?php printf( '<div class="wp-caption aligncenter"><p class="wp-caption-text">' . __( 'Search results for &#8220;%s&#8221;','fastfood' ) . '</p></div>', '<strong>' . esc_html( get_search_query() ) . '</strong>' ); ?>

	<?php if ( have_posts() ) {
		while ( have_posts() ) {
			the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

				<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark">
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
						<?php the_excerpt(); ?>
				</div>

				<div class="fixfloat"> </div>

			</div>

			<?php } ?>

		<div class="w_title">
			<?php //num of pages
			global $paged;
			if ( !$paged ) { $paged = 1; }
			previous_posts_link( '&laquo; ' );
			printf( __( 'page %1$s of %2$s','fastfood' ), $paged, $wp_query->max_num_pages );
			next_posts_link( ' &raquo;' );
			?>
		</div>

	<?php } else { ?>

		<p><b><?php _e( 'Sorry, no posts matched your criteria.','fastfood' );?></b></p>

	<?php } ?>

<?php get_footer(); ?>
