<?php get_header(); ?>

<?php fastfood_hook_before_posts(); ?>
<div id="posts_content" class="posts_narrow">

<?php
global $fastfood_opt;

// search reminder
if ( is_category() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Categories','fastfood' ) . ': <strong>' . wp_title( '',false,'right' ) . '</strong></div>';
} elseif ( is_tag() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Tags','fastfood' ) . ': <strong>' . wp_title( '',false,'right' ) . '</strong></div>';
} elseif ( is_date() ) {
	echo '<div class="wp-caption aligncenter" style="padding-bottom: 5px;">' . __( 'Archives','fastfood' ) . ': <strong>' . wp_title( '',false,'right' ) . '</strong></div>';
} elseif (is_author()) {
	echo '<div class="wp-caption aligncenter vcard" style="padding-bottom: 5px;">' . __( 'Author','fastfood' ) . ': <span class="fn"><strong>' . wp_title( '',false,'right' ) . '</strong></span>';
	$ff_author = get_queried_object();
	// If a user has filled out their description, show a bio on their entries.
	if ( $ff_author->description ) { ?>
		<div id="entry-author-info">
			<?php echo get_avatar( $ff_author->user_email, 32, $default= get_template_directory_uri() . '/images/user.png','user-avatar' ); ?>
			<?php
				if ( $ff_author->twitter ) echo '<a class="url" title="' . sprintf( __('follow %s on Twitter', 'fastfood'), $ff_author->display_name ) . '" href="'.$ff_author->twitter.'"><img alt="twitter" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Twitter.png" /></a>';
				if ( $ff_author->facebook ) echo '<a class="url" title="' . sprintf( __('follow %s on Facebook', 'fastfood'), $ff_author->display_name ) . '" href="'.$ff_author->facebook.'"><img alt="facebook" class="avatar" width=32 height=32 src="' . get_template_directory_uri() . '/images/follow/Facebook.png" /></a>';
			?>
			<br />
			<?php echo $ff_author->description; ?>
		</div><!-- #entry-author-info -->
	<?php }
	echo '</div>';
}

//skip posts with aside/status format (via options)
if ( isset( $fastfood_opt['fastfood_post_view_aside'] ) && $fastfood_opt['fastfood_post_view_aside'] == 0	) $ff_terms[] = 'post-format-aside';
if ( isset( $fastfood_opt['fastfood_post_view_status'] ) && $fastfood_opt['fastfood_post_view_status'] == 0	) $ff_terms[] = 'post-format-status';
if ( isset( $ff_terms ) ) {
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
			) ? get_post_format( $post->ID ) : 'standard' ;
		} ?>
		
		<?php fastfood_hook_before_post(); ?>
		<?php get_template_part( 'loop/post', $ff_use_format ); ?>
		<?php fastfood_hook_after_post(); ?>
	
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
<?php fastfood_hook_after_posts(); ?>
<?php get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
