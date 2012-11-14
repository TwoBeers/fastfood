<?php
/**
 * mobile subtheme -> main index
 *
 * @package fastfood
 * @since fastfood 0.31
 */


?>
<?php locate_template( array( 'mobile/header-mobile.php' ), true, false ); ?>
<?php
	$sw_strtype = __( 'Posts', 'fastfood' );
	if ( is_category() )	{ $sw_strtype = sprintf( __( 'Category', 'fastfood' ) . ' : %s', wp_title( '',false ) ); }
	elseif ( is_tag() )		{ $sw_strtype = sprintf( __( 'Tag', 'fastfood' ) . ' : %s', wp_title( '',false ) ); }
	elseif ( is_date() )	{ $sw_strtype = sprintf( __( 'Archives', 'fastfood' ) . ' : %s', wp_title( '',false ) ); }
	elseif (is_author()) 	{ $sw_strtype = sprintf( __( 'Posts by %s', 'fastfood'), wp_title( '',false ) ); }
	elseif ( is_search() )	{ $sw_strtype = sprintf( __( 'Search results for &#8220;%s&#8221;', 'fastfood' ), esc_html( get_search_query() ) ); }
?>
<?php if ( have_posts() ) { ?>
	<?php echo fastfood_mobile_seztitle( 'before' ) . $sw_strtype . fastfood_mobile_seztitle( 'after' ); ?>
	<ul class="tbm-group">
	<?php while ( have_posts() ) {
		the_post(); ?>
		<?php $tbm_alter_style = ( !isset($tbm_alter_style) || $tbm_alter_style == 'tbm-odd' ) ? 'tbm-even' : 'tbm-odd'; ?>
		<li class="<?php echo $tbm_alter_style; ?>">
			<a href="<?php the_permalink() ?>" rel="bookmark">
				<span class="tbm-format f-<?php echo get_post_format( $post->ID ); ?>"></span>
				<?php the_title(); ?>
				<br>
				<span class="tbm-details"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php comments_number('(0)', '(1)','(%)'); ?></span>
			</a>
		</li>
	<?php } ?>
	</ul>
	<?php if ( $wp_query->max_num_pages > 1 ) { ?>
		<?php //num of pages
		global $paged;
		if ( !$paged ) { $paged = 1; }
		?>
		<?php printf( fastfood_mobile_seztitle( 'before' ) . __( 'page %1$s of %2$s', 'fastfood' ) . fastfood_mobile_seztitle( 'after' ), $paged, $wp_query->max_num_pages ); ?>
		<div class="tbm-index-navi">
			<?php if ( function_exists( 'wp_pagenavi' ) ) { ?>
				<?php wp_pagenavi(); ?>
			<?php } else { ?>
						<?php previous_posts_link( __( 'Previous page', 'fastfood' ) ); ?>
						<?php next_posts_link( __( 'Next page', 'fastfood' ) ); ?>
			<?php } ?>
		</div>
	<?php } ?>
<?php } else { ?>
		<?php echo fastfood_mobile_seztitle( 'before' ) . $sw_strtype . fastfood_mobile_seztitle( 'after' ); ?>
		<p class="tbm-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'fastfood' );?></p>
<?php } ?>
<?php locate_template( array( 'mobile/footer-mobile.php' ), true, false ); ?>
