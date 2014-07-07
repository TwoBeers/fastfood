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

<div id="posts_content" class="posts_wide">

	<?php fastfood_hook_content_top(); ?>

	<div class="hentry" id="post-404-not-found">

		<p><?php _e( "Sorry, you&#39;re looking for something that isn&#39;t here" ,'fastfood' ); ?>:<span class="search-string"><?php echo home_url() . esc_html( $_SERVER['REQUEST_URI'] ); ?></span></p>

		<?php if ( is_active_sidebar( 'error404-widgets-area' ) ) { ?>

			<p><?php _e( 'You can try the following:','fastfood' ); ?></p>

			<?php fastfood_get_sidebar( 'error404' ); // show error404 widgets area ?>

		<?php } else { ?>

			<p><?php _e( 'There are several links scattered around the page, maybe you can find what you&#39;re looking for', 'fastfood' ); ?></p>

			<p><?php _e( 'Perhaps using the search form will help too...', 'fastfood' ); ?></p>

			<?php get_search_form(); ?>

		<?php } ?>

	</div>

	<?php fastfood_hook_content_bottom(); ?>

</div>

<?php get_footer(); ?>
