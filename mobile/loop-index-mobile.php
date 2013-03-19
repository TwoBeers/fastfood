<?php
/**
 * The mobile theme - Index/Arcive/Search/404 template
 *
 * @package fastfood
 * @subpackage mobile
 * @since 0.31
 */


locate_template( array( 'mobile/header-mobile.php' ), true, false ); ?>

<?php if ( have_posts() ) { ?>

	<?php do_action( 'fastfood_mobile_hook_content_before' ); ?>

	<ul class="tbm-group">

	<?php while ( have_posts() ) {

		the_post(); ?>

		<li>
			<a href="<?php the_permalink() ?>" rel="bookmark">
				<span class="tb-thumb-format <?php echo get_post_format( $post->ID ); ?>"></span>
				<?php the_title(); ?>
				<br>
				<span class="tbm-details"><?php the_author(); ?> - <?php the_time( get_option( 'date_format' ) ); ?> - <?php comments_number('(0)', '(1)','(%)'); ?></span>
			</a>
		</li>

	<?php } ?>

	</ul>

	<?php do_action( 'fastfood_mobile_hook_content_after' ); ?>

<?php } else { ?>

		<p class="tbm-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'fastfood' );?></p>

<?php } ?>

<?php locate_template( array( 'mobile/footer-mobile.php' ), true, false ); ?>
