<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 * @package fastfood
 * @since fastfood 0.23
 */
?>
<?php get_header(); ?>

<?php fastfood_hook_before_posts(); ?>
<div id="posts_content" class="posts_wide">

<?php if ( have_posts() ) {
	global $fastfood_is_printpreview;
	while ( have_posts() ) {
		the_post(); ?>
		<?php fastfood_hook_before_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php fastfood_hook_before_post_title(); ?>
			<?php fastfood_featured_title(); ?>
			<?php fastfood_hook_after_post_title(); ?>
			<?php fastfood_extrainfo( array( 'auth' => 0, 'date' => 0, 'tags' => 0, 'cats' => 0, 'hiera' => 1 ) ); ?>
			<?php fastfood_hook_before_post_content(); ?>
			<div class="storycontent">
				<?php the_content(); ?>
			</div>
			<?php fastfood_hook_after_post_content(); ?>
			<div class="fixfloat">
				<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
			</div>
		</div>
		<?php fastfood_hook_after_post(); ?>
		
		<?php comments_template(); // Get wp-comments.php template ?>

	<?php } //end while
} else {?>
	<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' );?></p>
<?php } //endif ?>

</div>
<?php fastfood_hook_after_posts(); ?>

<?php get_footer(); ?>
