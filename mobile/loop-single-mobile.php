<?php
/**
 * The mobile theme - Post/Attachment/Page template
 *
 * @package fastfood
 * @subpackage mobile
 * @since 0.31
 */


locate_template( array( 'mobile/header-mobile.php' ), true, false ); ?>

<?php if ( have_posts() ) {

	while ( have_posts() ) {

		the_post(); ?>

		<?php do_action( 'fastfood_mobile_hook_entry_before' ); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
			<?php the_content(); ?>
			<?php wp_link_pages( 'before=<div class="tbm-pc-navi">' . __( 'Pages', 'fastfood' ) . ':&after=</div>' ); ?>
		</div>

		<?php comments_template( '/mobile/comments-mobile.php' ); ?>

		<?php do_action( 'fastfood_mobile_hook_entry_after' ); ?>

	<?php } ?>

<?php } else { ?>

	<p class="tbm-padded"><?php _e( 'Sorry, no posts matched your criteria.', 'fastfood' );?></p>

<?php } ?>

<?php locate_template( array( 'mobile/footer-mobile.php' ), true, false ); ?>
