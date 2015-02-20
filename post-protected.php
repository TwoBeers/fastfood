<?php
/**
 * post-protected.php
 *
 * protected post (list view)
 *
 * @package fastfood
 * @since fastfood 0.24
 */
?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php fastfood_hook_entry_top(); ?>

	<?php fastfood_featured_title( array( 'featured' => false ) ); ?>

	<?php fastfood_extrainfo( array( 'comms' => 0, 'tags' => 0, 'cats' => 0 ) ); ?>

	<div class="entry-content">

		<?php the_content(); ?>

	</div>

	<br class="fixfloat" />

	<?php fastfood_hook_entry_bottom(); ?>

</div>
