<?php
/**
 * post-entry-attachment.php
 *
 * Template for attachment pages
 *
 * @package fastfood
 * @since 0.37
 */
?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php fastfood_hook_entry_top(); ?>

	<?php fastfood_hook_post_title_before(); ?>

	<?php fastfood_featured_title( array( 'fallback' => sprintf ( __( 'attachment #%s', 'fastfood' ), get_the_ID() ) ) ); ?>

	<?php fastfood_hook_post_title_after(); ?>

	<?php fastfood_extrainfo( array( 'tags' => 0, 'cats' => 0 ) ); ?>

	<div class="entry-content entry-attachment">

		<?php fastfood_hook_attachment_before(); ?>

			<p class="attachment">
		<?php if ( wp_attachment_is_image() ) { ?>

				<?php
					echo fastfood_build_link( array(
						'href'		=> wp_get_attachment_url(),
						'text'		=> wp_get_attachment_image( $post->ID, 'full' ),
						'title'		=> __( 'View full size', 'fastfood' ),
						'rel'		=> 'attachment',
					) );
				?>

			<?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?>

		<?php } else { ?>

			<?php echo wp_get_attachment_link( $post->ID, 'thumbnail', 0, 1 ); ?>

		<?php } ?>
			</p>

		<?php fastfood_hook_attachment_after(); ?>

	</div>

	<?php fastfood_hook_entry_bottom(); ?>

</div>
