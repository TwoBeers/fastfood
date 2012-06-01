<?php global $fastfood_opt; ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php fastfood_hook_before_post_title(); ?>
	<?php
		switch ( $fastfood_opt['fastfood_post_formats_gallery_title'] ) {
			case 'post title':
				fastfood_featured_title( array( 'fallback' => sprintf ( __( 'gallery #%s','fastfood' ), get_the_ID() ) ) );
				break;
			case 'post date':
				fastfood_featured_title( array( 'alternative' => get_the_time( get_option( 'date_format' ) ), 'fallback' => sprintf ( __( 'gallery #%s','fastfood' ), get_the_ID() ) ) );
				break;
		}
	?>
	<?php fastfood_hook_after_post_title(); ?>
	
	<?php fastfood_extrainfo(); ?>
	
	<?php fastfood_hook_before_post_content(); ?>
	<div class="storycontent">
		<?php
			switch ( $fastfood_opt['fastfood_post_formats_gallery_content'] ) {
				case 'presentation':
					$ff_images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $ff_images ) {
						$ff_total_images = count( $ff_images );
						$ff_image = array_shift( $ff_images );
						?>
							<div class="gallery-thumb"><?php echo wp_get_attachment_image( $ff_image->ID, 'medium' ); ?></div><!-- .gallery-thumb -->
						<?php 
						$ff_otherimgs = array_slice( $ff_images, 0, 4 );
						foreach ($ff_otherimgs as $ff_image) {
							$ff_image_img_tag = wp_get_attachment_image( $ff_image->ID, array( 75, 75 ) );
							?>
								<div class="gallery-thumb"><?php echo $ff_image_img_tag; ?></div>
							<?php
						}
					?>
						<p style="float: left; white-space: nowrap;">
							<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $ff_total_images, 'fastfood' ),
								'href="' . get_permalink() . '" title="' . __( 'View gallery', 'fastfood' ) . '" rel="bookmark"',
								number_format_i18n( $ff_total_images )
								); ?></em>
						</p>
						<div class="fixfloat"> </div>
					<?php }
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
	<?php fastfood_hook_after_post_content(); ?>
	<div class="fixfloat"> </div>
	<?php if ( $fastfood_opt['fastfood_post_formats_gallery_content'] == 'content' ) wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
</div>
