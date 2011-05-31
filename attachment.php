<?php get_header(); ?>

<div id="posts_content" class="posts_wide">
	<?php if ( have_posts() ) {
		global $ff_is_printpreview;
		while ( have_posts() ) {
			the_post(); ?>
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<?php if ( $ff_is_printpreview ) { ?>
					<div id="close_preview">
						<a href="<?php the_permalink(); ?>" rel="bookmark"><?php _e( 'Close','fastfood' ); ?></a>
						<a href="javascript:window.print()" id="print_button"><?php _e( 'Print','fastfood' ); ?></a>
						<script type="text/javascript" defer="defer">
							/* <![CDATA[ */
							document.getElementById("print_button").style.display = "block"; // print button (available only with js active)
							/* ]]> */
						</script>
					</div>
				<?php } ?>
				<h2 class="storytitle">
					<a href="<?php the_permalink() ?>" rel="bookmark">
					<?php 
					$ff_post_title = the_title_attribute( 'echo=0' );
					if ( !$ff_post_title ) {
						_e( '(no title)','fastfood' );
					} else {
						echo $ff_post_title;
					}
					?>
					</a>
				</h2>
				<?php fastfood_extrainfo( false, false, true, false, false ); ?>
				<div class="storycontent">
					<div class="entry-attachment" style="text-align: center;">
						<?php if ( wp_attachment_is_image() ) { //from twentyten WP theme
							$ff_attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
							foreach ( $ff_attachments as $ff_k => $ff_attachment ) {
								if ( $ff_attachment->ID == $post->ID )
									break;
							}
							$ff_nextk = $ff_k + 1;
							$ff_prevk = $ff_k - 1;
							?>
							<div class="img-navi" style="text-align: center;">
				
							<?php if ( isset( $ff_attachments[ $ff_prevk ] ) ) { ?>
									<a class="size-thumbnail" title="" href="<?php echo get_attachment_link( $ff_attachments[ $ff_prevk ]->ID ); ?>">&laquo; <?php echo wp_get_attachment_image( $ff_attachments[ $ff_prevk ]->ID, array( 50, 50 ) ); ?></a>
							<?php } ?>
							<span class="img-navi-curimg"><?php echo wp_get_attachment_image( $post->ID, array( 50, 50 ) ); ?></span>
							<?php if ( isset( $ff_attachments[ $ff_nextk ] ) ) { ?>
									<a class="size-thumbnail" title="" href="<?php echo get_attachment_link( $ff_attachments[ $ff_nextk ]->ID ); ?>"><?php echo wp_get_attachment_image( $ff_attachments[ $ff_nextk ]->ID, array( 50, 50 ) ); ?> &raquo;</a>
							<?php } ?>
							</div>
							<p class="attachment"><a href="<?php echo wp_get_attachment_url(); ?>" title="<?php _e( 'View full size','fastfood' ) ;  // link to Full size image ?>" rel="attachment"><?php
								$ff_attachment_width  = apply_filters( 'fastfood_attachment_size', 1000 );
								$ff_attachment_height = apply_filters( 'fastfood_attachment_height', 1000 );
								echo wp_get_attachment_image( $post->ID, array( $ff_attachment_width, $ff_attachment_height ) ); // filterable image width with, essentially, no limit for image height.
							?></a></p>
							<?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?>
							<!-- Using WordPress functions to retrieve the extracted EXIF information from database -->
							<div class="exif-attachment-info">
								<h3><?php _e( 'Image Details', 'fastfood' ) ?></h3>
								<?php
								$ff_imgmeta = wp_get_attachment_metadata( $id );

								// Convert the shutter speed retrieve from database to fraction
								if ((1 / $ff_imgmeta['image_meta']['shutter_speed']) > 1) {
									if ((number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1)) == 1.3
									or number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1) == 1.5
									or number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1) == 1.6
									or number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1) == 2.5){
										$ff_pshutter = "1/" . number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 1, '.', '');
									} else {
										$ff_pshutter = "1/" . number_format((1 / $ff_imgmeta['image_meta']['shutter_speed']), 0, '.', '');
									}
								} else {
									$ff_pshutter = $ff_imgmeta['image_meta']['shutter_speed'];
								}

								// Start to display EXIF and IPTC data of digital photograph
								echo __("Width", "fastfood" ) . ": " . $ff_imgmeta['width']."px<br />";
								echo __("Height", "fastfood" ) . ": " . $ff_imgmeta['height']."px<br />";
								if ( $ff_imgmeta['image_meta']['created_timestamp'] ) echo __("Date Taken", "fastfood" ) . ": " . date("d-M-Y H:i:s", $ff_imgmeta['image_meta']['created_timestamp'])."<br />";
								if ( $ff_imgmeta['image_meta']['copyright'] ) echo __("Copyright", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['copyright']."<br />";
								if ( $ff_imgmeta['image_meta']['credit'] ) echo __("Credit", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['credit']."<br />";
								if ( $ff_imgmeta['image_meta']['title'] ) echo __("Title", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['title']."<br />";
								if ( $ff_imgmeta['image_meta']['caption'] ) echo __("Caption", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['caption']."<br />";
								if ( $ff_imgmeta['image_meta']['camera'] ) echo __("Camera", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['camera']."<br />";
								if ( $ff_imgmeta['image_meta']['focal_length'] ) echo __("Focal Length", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['focal_length']."mm<br />";
								if ( $ff_imgmeta['image_meta']['aperture'] ) echo __("Aperture", "fastfood" ) . ": f/" . $ff_imgmeta['image_meta']['aperture']."<br />";
								if ( $ff_imgmeta['image_meta']['iso'] ) echo __("ISO", "fastfood" ) . ": " . $ff_imgmeta['image_meta']['iso']."<br />";
								if ( $ff_pshutter ) echo __("Shutter Speed", "fastfood" ) . ": " . sprintf( '%s seconds', $ff_pshutter) . "<br />"
								?>
							</div>
							<?php if ( !empty( $post->post_content ) ) the_content(); ?>
						<?php } else { ?>
							<a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
							<div class="entry-caption"><?php if ( !empty( $post->post_excerpt ) ) the_excerpt(); ?></div>
						<?php } ?>
						</div><!-- .entry-attachment -->
					</div>
				<div class="fixfloat">
						<?php wp_link_pages( 'before=<div class="comment_tools">' . __( 'Pages','fastfood' ) . ':&after=</div><div class="fixfloat"></div>' ); ?>
				</div>
				<div class="fixfloat"> </div>
				<?php $ff_tmptrackback = get_trackback_url(); ?>
			</div>	
			
			<?php comments_template(); // Get wp-comments.php template ?>
			
		<?php	} //end while
	} else {?>
		
		<p><?php _e( 'Sorry, no posts matched your criteria.','fastfood' );?></p>
		
	<?php } ?>

</div>	

<?php get_footer(); ?>
