<?php
/**
 * post.php
 *
 * standard post (without format) (list view)
 *
 * @package fastfood
 * @since fastfood 0.29
 */
?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php fastfood_featured_title( array( 'fallback' => sprintf ( __( 'post #%s','fastfood' ), get_the_ID() ) ) ); ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>

</div>
