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

<div class="featured-post">

	<?php echo wp_get_attachment_image( fastfood_get_the_thumb_id(), 'thumbnail' ); ?>

	<h2 class="entry-title"><?php
		echo fastfood_build_link( array(
			'href'		=> get_permalink(),
			'text'		=> get_the_title(),
			'title'		=> the_title_attribute( array( 'echo' => 0 ) ),
			'class'		=> 'entry-title-content',
		) );
	?></h2>

	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div>

</div>
