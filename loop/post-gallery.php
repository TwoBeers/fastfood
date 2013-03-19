<?php
/**
 * post with 'gallery' format (list view)
 *
 * @package fastfood
 * @since fastfood 0.24
 */


global $fastfood_opt;
?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php fastfood_hook_entry_top(); ?>

	<?php fastfood_hook_post_title_before(); ?>

	<?php
		switch ( fastfood_get_opt( 'fastfood_post_formats_gallery_title' ) ) {
			case 'post title':
				fastfood_featured_title( array( 'fallback' => sprintf ( __( 'gallery #%s','fastfood' ), get_the_ID() ) ) );
				break;
			case 'post date':
				fastfood_featured_title( array( 'alternative' => get_the_time( get_option( 'date_format' ) ), 'fallback' => sprintf ( __( 'gallery #%s','fastfood' ), get_the_ID() ) ) );
				break;
		}
	?>

	<?php fastfood_hook_post_title_after(); ?>

	<?php fastfood_extrainfo(); ?>

	<?php fastfood_hook_post_content_before(); ?>

	<div class="storycontent">
		<?php
			switch ( fastfood_get_opt( 'fastfood_post_formats_gallery_content' ) ) {
				case 'presentation':
					if ( ! fastfood_gallery_preview() ) the_content();
					break;
				case 'content':
					the_content();
					break;
				case 'excerpt':
					the_excerpt();
					break;
			}
		?>
	</div>

	<?php fastfood_hook_post_content_after(); ?>

	<?php fastfood_hook_entry_bottom(); ?>

</div>
