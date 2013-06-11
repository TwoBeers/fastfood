<?php
/**
 * attachment.php
 *
 * Template for attachment pages
 *
 * @package fastfood
 * @since 0.15
 */


get_header(); ?>

<?php fastfood_hook_content_before(); ?>

<div id="posts_content" class="posts_wide">

	<?php fastfood_hook_content_top(); ?>

	<?php if ( have_posts() ) {

		while ( have_posts() ) {

			the_post(); ?>

			<?php fastfood_hook_entry_before(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

				<?php fastfood_hook_entry_top(); ?>

				<?php fastfood_hook_post_title_before(); ?>

				<?php fastfood_featured_title( array( 'fallback' => sprintf ( __( 'attachment #%s', 'fastfood' ), get_the_ID() ) ) ); ?>

				<?php fastfood_hook_post_title_after(); ?>

				<?php fastfood_extrainfo( array( 'tags' => 0, 'cats' => 0 ) ); ?>

				<div class="storycontent">

					<div class="entry-attachment" style="text-align: center;">

						<?php fastfood_hook_attachment_before(); ?>

						<?php if ( wp_attachment_is_image() ) { ?>

							<p class="attachment">
								<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php _e( 'View full size', 'fastfood' ) ;  // link to Full size image ?>" rel="attachment"><?php
									echo wp_get_attachment_image( $post->ID, 'full' );
								?></a>
							</p>

						<?php } else { ?>

							<?php echo wp_get_attachment_link( $post->ID, 'thumbnail', 0, 1 ); ?> 

						<?php } ?>

						<?php fastfood_hook_attachment_after(); ?>

						<?php if ( ! empty( $post->post_excerpt ) ) the_excerpt(); ?>

						<?php if ( ! empty( $post->post_content ) ) the_content(); ?>


					</div><!-- .entry-attachment -->

				</div>

				<?php fastfood_hook_entry_bottom(); ?>

			</div>

			<?php fastfood_hook_entry_after(); ?>

			<?php fastfood_get_sidebar( 'singular' ); // show singular widgets area ?>

			<?php comments_template(); // Get wp-comments.php template ?>

		<?php } //end while

	} else {?>

		<?php get_template_part( 'loop/post-none' ); ?>

	<?php } ?>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php fastfood_hook_content_after(); ?>

<?php get_footer(); ?>
