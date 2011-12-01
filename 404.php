<?php get_header(); ?>

<?php fastfood_hook_before_posts(); ?>
<div id="posts_content" class="posts_wide">
	<div class="hentry" id="post-404-not-found">
		<div class="ff-search-reminder ff-search-term"><strong>Error 404 - <?php _e( 'Page not found','fastfood' ); ?></strong></div>
		<p><?php _e( "Sorry, you're looking for something that isn't here" ,'fastfood' ); ?>: <u><?php echo home_url() . esc_html( $_SERVER['REQUEST_URI'] ); ?></u></p>
		<?php if ( is_active_sidebar( '404-widgets-area' ) ) { ?>
			<p><?php _e( 'You can try the following:','fastfood' ); ?></p>
			<?php fastfood_hook_before_404_sidebar(); ?>
			<div id="error404-widgets-area">
				<?php dynamic_sidebar( '404-widgets-area' ); ?>
			</div>
			<?php fastfood_hook_after_404_sidebar(); ?>
		<?php } else { ?>
			<p><?php _e( 'There are several links scattered around the page, maybe you can find what you\'re looking for', 'fastfood' ); ?></p>
			<p><?php _e( 'Perhaps using the search form will help...', 'fastfood' ); ?></p>
			<?php get_search_form(); ?>
		<?php } ?>
		<div class="fixfloat"> </div>
	</div>
</div>
<?php fastfood_hook_after_posts(); ?>

<?php get_footer(); ?>
