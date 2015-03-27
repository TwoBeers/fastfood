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

<?php fastfood_hook_content_before(); ?>

<div id="posts-content" class="posts_wide">

	<?php fastfood_hook_content_top(); ?>

	<div class="hentry">

		<h2 class="entry-title"><?php _e( 'Categories','fastfood' ); ?></h2>

		<div class="entry-content">

			<ul>
				<?php
					wp_list_categories( array(
						'show_count'	=> 1,
						'title_li'		=> '',
					) );
				?>
			</ul>

		</div>

	</div>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php fastfood_hook_content_after(); ?>

<?php get_footer(); ?>
