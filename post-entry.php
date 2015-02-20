<?php
/**
 * post-entry.php
 *
 * The post entry template file, used to display content of single blog posts or pages.
 *
 * @package fastfood
 * @since 0.37
 */
?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php fastfood_hook_entry_top(); ?>

	<?php fastfood_hook_post_title_before(); ?>

	<?php fastfood_featured_title(); ?>

	<?php fastfood_hook_post_title_after(); ?>

	<?php fastfood_extrainfo(); ?>

	<?php fastfood_hook_post_content_before(); ?>

	<div class="entry-content">

		<?php the_content(); ?>

	</div>

	<?php fastfood_hook_post_content_after(); ?>

	<?php fastfood_hook_entry_bottom(); ?>

</div>
