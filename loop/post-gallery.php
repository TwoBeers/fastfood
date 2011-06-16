<?php global $fastfood_opt; ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

	<?php fastfood_hook_before_post_title(); ?>
	<h2 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php 
		$ff_post_title = the_title( '','',false );
		if ( !$ff_post_title ) {
			the_time( get_option( 'date_format' ) );
		} else {
			echo $ff_post_title;
		}
		?></a>
	</h2>
	<?php fastfood_hook_after_post_title(); ?>
	
	<?php fastfood_extrainfo( true, true, true, true, true ); ?>
	
	<?php fastfood_hook_before_post_content(); ?>
	<div class="storycontent">
		<?php if ( $fastfood_opt['fastfood_postexcerpt'] == 1 ) { ?>
			<?php
				$ff_images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
				if ( $ff_images ) {
					$ff_total_images = count( $ff_images );
					$ff_image = array_shift( $ff_images );
			?>
				<div class="gallery-thumb" style="width: <?php echo get_option('thumbnail_size_w'); ?>px; max-width: 100%;"><a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $ff_image->ID, 'thumbnail' ); ?></a></div><!-- .gallery-thumb -->
				<p style="float: left; white-space: nowrap;">
					<em><?php printf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $ff_total_images, 'fastfood' ),
						'href="' . get_permalink() . '" title="' . __( 'View gallery', 'fastfood' ) . '" rel="bookmark"',
						number_format_i18n( $ff_total_images )
						); ?></em>
				</p>
				<div class="fixfloat"> </div>
			<?php } ?>
		<?php } else { ?>
			<?php
				$ff_images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
				if ( $ff_images ) {
					$ff_total_images = count( $ff_images );
					$ff_image = array_shift( $ff_images );
			?>
				<div class="gallery-thumb" style="width: <?php echo get_option('medium_size_w'); ?>px; max-width: 100%;"><a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo wp_get_attachment_image( $ff_image->ID, 'medium' ); ?></a></div><!-- .gallery-thumb -->
				<?php 
					$ff_otherimgs = array_slice( $ff_images, 0, 4 );
					foreach ($ff_otherimgs as $ff_image) {
						$ff_image_img_tag = wp_get_attachment_image( $ff_image->ID, array(75,75) );
						?>
							<div class="gallery-thumb" style="width: <?php echo floor( get_option('thumbnail_size_w')/2 ); ?>px;"><a class="size-thumbnail" href="<?php the_permalink(); ?>"><?php echo $ff_image_img_tag; ?></a></div><!-- .gallery-thumb -->
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
			<?php } ?>
			<?php the_excerpt(); ?>
		<?php } ?>
	</div>
	<?php fastfood_hook_after_post_content(); ?>
	<div class="fixfloat"> </div>
	<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
</div>
