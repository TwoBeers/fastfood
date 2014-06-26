<?php
/**
 * loop-singular.php
 *
 * The main loop for single blog posts.
 *
 * @package fastfood
 * @since 0.37
 */
?>

<?php if ( have_posts() ) {

	while ( have_posts() ) {

		the_post(); ?>

		<?php if ( !post_password_required() ) { ?>

			<?php fastfood_hook_entry_before(); ?>

			<?php get_template_part( 'post-entry', fastfood_get_context() ); ?>

			<?php fastfood_hook_entry_after(); ?>

			<?php fastfood_get_sidebar( 'singular' ); // show singular widgets area ?>

			<?php comments_template(); // Get wp-comments.php template ?>

		<?php } else { ?>

			<?php get_template_part( 'post', 'protected' ); ?>

		<?php } //endif ?>

	<?php } //end while ?>

<?php } else { ?>

	<?php get_template_part( 'post', 'none' ); ?>

<?php } //endif ?>
