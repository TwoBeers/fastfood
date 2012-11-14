<?php
/**
 * standard post (without format) (list view)
 *
 * @package fastfood
 * @since fastfood 0.29
 */


global $fastfood_opt;
?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<?php fastfood_hook_before_post_title(); ?>
	<?php
		switch ( $fastfood_opt['fastfood_post_formats_standard_title'] ) {
			case 'post title':
				fastfood_featured_title( array( 'fallback' => sprintf ( __( 'post #%s','fastfood' ), get_the_ID() ) ) );
				break;
			case 'post date':
				fastfood_featured_title( array( 'alternative' => get_the_time( get_option( 'date_format' ) ), 'fallback' => sprintf ( __( 'post #%s','fastfood' ), get_the_ID() ) ) );
				break;
		}
	?>
	<?php fastfood_hook_after_post_title(); ?>
	
	<?php fastfood_extrainfo(); ?>

	<?php fastfood_hook_before_post_content(); ?>
	<div class="storycontent">
		<?php
			switch ( $fastfood_opt['fastfood_postexcerpt'] ) {
				case 0: //the content
					the_content();
					break;
				case 1: //the excerpt
					the_excerpt();
					break;
			}
		?>
	</div>
	<?php fastfood_hook_after_post_content(); ?>
	<div class="fixfloat"> </div>
	<?php if ( $fastfood_opt['fastfood_postexcerpt'] == 0 ) wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
</div>
