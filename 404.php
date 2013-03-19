<?php
/**
 * 404.php
 *
 * This file is the Error 404 Page template file, which is output whenever
 * the server encounters a "404 - file not found" error.
 *
 * @package fastfood
 * @since 0.15
 */


get_header(); ?>

<?php fastfood_hook_content_before(); ?>

<div id="posts_content" class="posts_wide">

	<?php fastfood_hook_content_top(); ?>

	<div class="hentry" id="post-404-not-found">

		<div class="ff-search-reminder ff-search-term"><strong><?php _e( 'Error 404','fastfood' ); ?> - <?php _e( 'Page not found','fastfood' ); ?></strong></div>

		<p><?php _e( "Sorry, you&#39;re looking for something that isn&#39;t here" ,'fastfood' ); ?>: <u><?php echo home_url() . esc_html( $_SERVER['REQUEST_URI'] ); ?></u></p>

		<?php if ( is_active_sidebar( '404-widgets-area' ) ) { ?>

			<p><?php _e( 'You can try the following:','fastfood' ); ?></p>

			<?php fastfood_hook_sidebar_404_before(); ?>

			<div id="error404-widgets-area">

				<?php dynamic_sidebar( '404-widgets-area' ); ?>

			</div>

			<?php fastfood_hook_sidebar_404_after(); ?>

		<?php } else { ?>

			<p><?php _e( 'There are several links scattered around the page, maybe you can find what you&#39;re looking for', 'fastfood' ); ?></p>
			<p><?php _e( 'Perhaps using the search form will help...', 'fastfood' ); ?></p>
			<?php get_search_form(); ?>

		<?php } ?>

		<br class="fixfloat">

	</div>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php fastfood_hook_content_after(); ?>

<?php get_footer(); ?>
