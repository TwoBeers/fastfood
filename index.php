<?php get_header(); ?>
<?php
if( function_exists('FA_display_slider') ){
    FA_display_slider(1756);
}
?> 
<?php fastfood_hook_before_posts(); ?>
<div id="posts_content" class="<?php echo ( fastfood_use_sidebar() ) ? 'posts_narrow' : 'posts_wide'; ?>">

<?php
global $fastfood_opt;

// search reminder
if ( is_archive() && !$fastfood_opt['fastfood_breadcrumb'] ) {
	printf( '<div class="ff-search-reminder"><div class="ff-search-term">' . __( 'Archives for %s','fastfood' ) . ' <span class="ff-search-found">(' . $wp_query->found_posts . ')</span>' . '</div></div>', '<strong>' . wp_title( '',false,'right' ) . '</strong>' );
} elseif ( is_search() && !$fastfood_opt['fastfood_breadcrumb'] ) {
	printf( '<div class="ff-search-reminder ff-search-term">' . __( 'Search results for &#8220;%s&#8221;','fastfood' ) . ' <span class="ff-search-found">(' . $wp_query->found_posts . ')</span>' . '</div>', '<strong>' . esc_html( get_search_query() ) . '</strong>' );
}
if (is_author()) {
	$ff_author = get_queried_object();
	// If a user has filled out their description, show a bio on their entries.
	if ( $ff_author->description ) fastfood_post_details( array( 'author' => 1, 'date' => 0, 'tags' => 0, 'categories' => 0 ) );
}

//skip posts with aside/status format (via options)
if ( isset( $fastfood_opt['fastfood_post_view_aside'] ) && $fastfood_opt['fastfood_post_view_aside'] == 0	) $ff_terms[] = 'post-format-aside';
if ( isset( $fastfood_opt['fastfood_post_view_status'] ) && $fastfood_opt['fastfood_post_view_status'] == 0	) $ff_terms[] = 'post-format-status';
if ( isset( $ff_terms ) && !is_search() ) {
	global $query_string;
	parse_str( $query_string, $args );
	$args['tax_query'] = array(
		array(
			'taxonomy' => 'post_format',
			'terms' => $ff_terms,
			'field' => 'slug',
			'operator' => 'NOT IN',
		),
	);
	query_posts( $args );
}

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		<?php if ( post_password_required() ) {
			$ff_use_format = 'protected';
		} else {
			$ff_use_format = ( 
				function_exists( 'get_post_format' ) && 
				isset( $fastfood_opt['fastfood_post_formats_' . get_post_format( $post->ID ) ] ) && 
				$fastfood_opt['fastfood_post_formats_' . get_post_format( $post->ID ) ] == 1 
			) ? get_post_format( $post->ID ) : '' ;
		} ?>
		
		<?php fastfood_hook_before_post(); ?>
		<?php get_template_part( 'loop/post', $ff_use_format ); ?>
		<?php fastfood_hook_after_post(); ?>
	
	<?php } //end while ?>

	<div id="ff-page-nav">
		<?php if ( function_exists( 'wp_pagenavi' ) ) { ?>
			<?php wp_pagenavi(); ?>
		<?php } elseif ( function_exists( 'wp_paginate' ) ) { ?>
			<?php wp_paginate(); ?>
		<?php } else { ?>
			<?php //num of pages
			global $paged;
			if ( !$paged ) {
				$paged = 1;
			}
			previous_posts_link( '&laquo;' );
			printf( __( 'page %1$s of %2$s','fastfood' ), $paged, $wp_query->max_num_pages );
			next_posts_link( '&raquo;' );
			?>
		<?php } ?>
	</div>

<?php } else { ?>

	<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' ); ?></p>

<?php } //endif ?>

</div>
<?php fastfood_hook_after_posts(); ?>
<?php if ( fastfood_use_sidebar() ) fastfood_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
