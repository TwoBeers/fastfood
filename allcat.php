<?php
/**
 * allcat.php
 *
 * The template file used to display the whole category list 
 * as a page.
 *
 * @package fastfood
 * @since 0.15
 */


get_header(); ?>

<div id="posts_content" class="posts_wide">

	<?php fastfood_hook_content_top(); ?>

	<div class="hentry">

		<h2 class="entry-title"><?php _e( 'Categories','fastfood' ); ?></h2>

		<div class="entry-content">

			<ul>
				<?php wp_list_categories( 'title_li=' ); ?>
			</ul>

		</div>

	</div>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php get_footer(); ?>
