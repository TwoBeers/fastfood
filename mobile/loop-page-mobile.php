<?php
/**
 * mobile subtheme -> single page
 *
 * @package fastfood
 * @since fastfood 0.31
 */


?>
<?php locate_template( array( 'mobile/header-mobile.php' ), true, false ); ?>
<?php if ( have_posts() ) { ?>
	<?php while ( have_posts() ) { 
		the_post(); ?>
		<div <?php post_class( 'tbm-post tbm-padded' ) ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
			<?php the_content(); ?>
			<?php wp_link_pages('before=<div class="tbm-pc-navi">' . __('Pages', 'fastfood') . ':&after=</div>'); ?>
		</div>
		<?php comments_template('/mobile/comments-mobile.php'); ?>
		<?php 
		$tbm_args = array(
			'post_type' => 'page',
			'post_parent' => $post->ID,
			'order' => 'ASC',
			'orderby' => 'menu_order',
			'numberposts' => 0
			);
		$tbm_sub_pages = get_posts( $tbm_args ); // retrieve the child pages

		if (!empty($tbm_sub_pages)) { ?>
			<?php echo fastfood_mobile_seztitle( 'before' ) . __( 'Child pages', 'fastfood' ) . fastfood_mobile_seztitle( 'after' ); ?>
			<ul class="tbm-group">
				<?php 
				foreach ( $tbm_sub_pages as $tbm_children ) {
					echo '<li class="outset"><a href="' . get_permalink( $tbm_children ) . '" title="' . esc_attr( strip_tags( get_the_title( $tbm_children ) ) ) . '">' . get_the_title( $tbm_children ) . '</a></li>';
				}
				?>
			</ul>
			
		<?php } ?>
		<?php $tbm_the_parent_page = $post->post_parent; // retrieve the parent page
		if ( $tbm_the_parent_page ) {?>
			<?php echo fastfood_mobile_seztitle( 'before' ) . __( 'Parent page', 'fastfood' ) . fastfood_mobile_seztitle( 'after' ); ?>
			<ul class="tbm-group">
					<li class="outset"><a href="<?php echo get_permalink( $tbm_the_parent_page ); ?>" title="<?php echo esc_attr( strip_tags( get_the_title( $tbm_the_parent_page ) ) ); ?>"><?php echo get_the_title( $tbm_the_parent_page ); ?></a></li>
			</ul>
		<?php } ?>
	<?php } ?>
<?php } else { ?>
	<p class="tbm-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'fastfood' );?></p>
<?php } ?>
<?php locate_template( array( 'mobile/footer-mobile.php' ), true, false ); ?>
