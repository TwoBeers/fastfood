<?php get_header(); ?>

<div id="posts_content" class="posts_narrow">

	<?php printf( '<div class="wp-caption aligncenter"><p class="wp-caption-text">' . __( 'Search results for &#8220;%s&#8221;','fastfood' ) . '</p></div>', '<strong>' . esc_html( get_search_query() ) . '</strong>' ); ?>

	<?php if ( have_posts() ) {
		while ( have_posts() ) {
			the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h2 class="storytitle"><a href="<?php the_permalink(); ?>" rel="bookmark">
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
				<?php fastfood_extrainfo( true, true, true, true, true ); ?>
				<div class="storycontent">
						<?php the_excerpt(); ?>
				</div>
				<div class="fixfloat"> </div>
			</div>
		<?php } ?>
		<div class="w_title" id="ff-page-nav">
			<?php //num of pages
			global $paged;
			if ( !$paged ) { $paged = 1; }
			previous_posts_link( '&laquo;' );
			printf( __( 'page %1$s of %2$s','fastfood' ), $paged, $wp_query->max_num_pages );
			next_posts_link( '&raquo;' );
			?>
		</div>
	<?php } else { ?>
		<p><b><?php _e( 'Sorry, no posts matched your criteria.','fastfood' );?></b></p>
	<?php } ?>
</div>

<?php get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
