<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * @package fastfood
 * @since fastfood 0.23
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

				<?php fastfood_featured_title(); ?>

				<?php fastfood_hook_post_title_after(); ?>

				<?php fastfood_extrainfo( array( 'auth' => 0, 'date' => 0, 'tags' => 0, 'cats' => 0, 'hiera' => 1 ) ); ?>

				<?php fastfood_hook_post_content_before(); ?>

				<div class="storycontent">
					<?php the_content(); ?>
				</div>

				<?php fastfood_hook_post_content_after(); ?>

				<?php fastfood_hook_entry_bottom(); ?>

			</div>

			<?php fastfood_hook_entry_after(); ?>

			<?php fastfood_get_sidebar( 'singular' ); // show singular widgets area ?>

			<?php comments_template(); // Get wp-comments.php template ?>

		<?php } //end while ?>

	<?php } else { ?>

		<?php get_template_part( 'loop/post-none' ); ?>

	<?php } //endif ?>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php fastfood_hook_content_after(); ?>

<?php get_footer(); ?>
