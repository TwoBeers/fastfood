<?php get_header(); ?>

<?php fastfood_hook_before_posts(); ?>
<div id="posts_content" class="<?php echo ( fastfood_use_sidebar() ) ? 'posts_narrow' : 'posts_wide'; ?>">

<?php if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); ?>
		<?php fastfood_hook_before_post(); ?>
		<?php fastfood_I_like_it();?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php fastfood_hook_before_post_title(); ?>
			<?php fastfood_featured_title( array( 'fallback' => sprintf ( __('post #%s','fastfood'), get_the_ID() ) ) ); ?>
			<?php fastfood_hook_after_post_title(); ?>
			<?php fastfood_extrainfo(); ?>
			<?php fastfood_hook_before_post_content(); ?>
			<div class="storycontent entry-content">
				<?php the_content(); ?>
			</div>
			<?php fastfood_hook_after_post_content(); ?>
			
			<div class="fixfloat">
				<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
			</div>
			
			<?php $ff_tmptrackback = get_trackback_url(); ?>
			
		</div>	
		<?php fastfood_hook_after_post(); ?>
		
		<?php fastfood_get_sidebar( 'singular' ); // show singular widgets area ?>
		
		<?php comments_template(); // Get wp-comments.php template ?>
		
	<?php } //end while
} else {?>
	
	<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' );?></p>
	
<?php } ?>
</div><!-- posts_wide -->
<?php fastfood_hook_after_posts(); ?>

<?php if ( fastfood_use_sidebar() ) fastfood_get_sidebar(); // show sidebar ?>

<?php get_footer(); ?>
