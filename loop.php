<?php
/**
 * loop.php
 *
 * The main loop that displays posts.
 *
 *
 * @package fastfood
 * @since 0.34
 */
?>

<?php if ( have_posts() ) { ?>

	<?php fastfood_hook_loop_before(); ?>

	<?php while ( have_posts() ) {

		the_post(); ?>

		<?php fastfood_hook_entry_before(); ?>

		<?php get_template_part( 'loop/post', fastfood_get_post_format( $post->ID ) ); ?>

		<?php fastfood_hook_entry_after(); ?>

	<?php } //end while ?>

	<?php fastfood_hook_loop_after(); ?>

<?php } else { ?>

	<?php get_template_part( 'loop/post-none' ); ?>

<?php } //endif ?>
