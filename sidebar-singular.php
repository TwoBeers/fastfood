<?php global $fastfood_opt; ?>
<!-- here should start the singular widget area -->
			<div id="post-widgets-area" class="fixfloat">
				<?php if ( !is_active_widget( false, false, 'ff-exif-details', true ) && $fastfood_opt['fastfood_exif_info'] == 1 ) {
					$ff_args =  array( 'before_widget' => '<div id="ff-exif-details-0" class="widget ff_Widget_exif_details">', 'after_widget' => '</div>', 'before_title' => '<div class="w_title">', 'after_title' => '</div>' ); 
					the_widget( 'fastfood_Widget_image_EXIF', 'title=' . __( 'Image details','fastfood' ), $ff_args ); 
				} ?>
				<?php if ( !is_active_widget( false, false, 'ff-share-this', true ) && $fastfood_opt['fastfood_share_this'] == 1 ) {
					$ff_args =  array( 'before_widget' => '<div id="ff-share-this-0" class="widget ff_Widget_share_this">', 'after_widget' => '</div>', 'before_title' => '<div class="w_title">', 'after_title' => '</div>' ); 
					the_widget( 'fastfood_Widget_share_this', 'title=' . __( 'Share this','fastfood' ), $ff_args ); 
				} ?>
				<?php if ( is_active_sidebar( 'post-widgets-area' ) ) dynamic_sidebar( 'post-widgets-area' ); ?>
				<div class="fixfloat"></div>
			</div><!-- #post-widgets-area -->
