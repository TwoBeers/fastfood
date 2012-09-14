<?php
/**
 * The gallery stuff
 *
 * @package fastfood
 * @since fastfood 0.30
 */

global $fastfood_opt;

// media select
add_action( 'template_redirect', 'fastfood_media' );
// Add custom menus
add_action( 'admin_menu', 'fastfood_add_gallery_menu' );

add_filter( 'post_gallery', 'fastfood_gallery_shortcode', 10, 2 );

// check if in media preview mode
$fastfood_is_galed_mediaselect = false;
if ( isset( $_GET['galed_media'] ) ) {
	$fastfood_is_galed_mediaselect = true;
}

// media preview
if ( !function_exists( 'fastfood_media' ) ) {
	function fastfood_media () {
		global $fastfood_is_galed_mediaselect;
		if ( $fastfood_is_galed_mediaselect ) {
			locate_template( array( 'lib/media-select.php' ), true, false );
			exit;
		}
	}
}

// create custom theme settings menu
if ( !function_exists( 'fastfood_add_gallery_menu' ) ) {
	function fastfood_add_gallery_menu() {
		$gallerypage = add_theme_page( __( 'Gallery Editor', 'fastfood' ), __( 'Gallery Editor', 'fastfood' ), 'edit_posts', 'fastfood_gallery_editor', 'fastfood_edit_gallery' );
		//call custom stylesheet function
		add_action( 'admin_print_styles-' . $gallerypage, 'fastfood_gallerypage_style' );
		add_action( 'admin_print_scripts-' . $gallerypage, 'fastfood_gallerypage_script' );
		add_action( 'admin_print_scripts', 'fastfood_movegallerypage_script' );
	}
}


if ( !function_exists( 'fastfood_gallerypage_style' ) ) {
	function fastfood_gallerypage_style() {
		//add custom stylesheet
		wp_enqueue_style( 'thickbox' ); //fastfood js
		wp_enqueue_style( 'fastfood-galed', get_stylesheet_directory_uri() . '/css/admin-gallery.css');
	}
}

if ( !function_exists( 'fastfood_gallerypage_script' ) ) {
	function fastfood_gallerypage_script() {
		global $fastfood_version;
		wp_enqueue_script( 'jquery' ); //fastfood js
		wp_enqueue_script( 'thickbox' ); //fastfood js
		wp_enqueue_script( 'fastfood-galed', get_stylesheet_directory_uri() . '/js/admin-gallery.dev.js', array('jquery','jquery-ui-sortable'), '', false );
		//wp_enqueue_script( 'farbtastic' ); //fastfood js
	}
}

if ( !function_exists( 'fastfood_movegallerypage_script' ) ) {
	function fastfood_movegallerypage_script() {
		global $fastfood_version;
		wp_enqueue_script( 'fastfood-galed-movegallerypage', get_template_directory_uri() . '/js/admin-movegallerypage.dev.js', array('jquery'), $fastfood_version, true );
	}
}

// the slideshow admin panel - here you can select posts to be included in slideshow
if ( !function_exists( 'fastfood_edit_gallery' ) ) {
	function fastfood_edit_gallery() {
		global $fastfood_current_theme;
		?>
	<div class="wrap">
			<div class="icon32" id="galed-icon"><br></div>
			<h2><?php echo $fastfood_current_theme . ' - ' . __( 'Gallery Editor', 'fastfood' ); ?></h2>
			<div class="hide-if-js error"><p><?php echo __( 'Javascript must be enabled in order to use this feature', 'fastfood' ); ?></p></div>
			<div>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="galed-ids"><?php echo __( 'attachments', 'fastfood' ); ?></label>
							</th>
							<td>
								<input type="hidden" value="" id="galed-ids" name="galed-ids">
								<?php 
									$galed_arr_params['galed_media'] = '1';
									$galed_arr_params['TB_iframe'] = 'true';
								?>
								<div id="galed-sortable-list"></div>
								<a id="galed-add-image" title="<?php echo __( 'Add Image', 'fastfood' ); ?>" href="javascript:void(0)" onClick="tb_show( '<?php echo __( 'Click an image to select', 'fastfood' ); ?>', '<?php echo add_query_arg( $galed_arr_params, home_url() ); ?>'); return false;">+</a>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-columns"><?php echo __( 'columns', 'fastfood' ); ?></label>
							</th>
							<td>
								<select id="galed-columns" name="columns">
									<option value="0">0</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3" selected="selected">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-size"><?php echo __( 'size', 'fastfood' ); ?></label>
							</th>
							<td>
								<select id="galed-size" name="size">
									<option value="thumbnail" selected="selected"><?php echo __( 'thumbnail', 'fastfood' ); ?></option>
									<option value="medium"><?php echo __( 'medium', 'fastfood' ); ?></option>
									<option value="large"><?php echo __( 'large', 'fastfood' ); ?></option>
									<option value="full"><?php echo __( 'full', 'fastfood' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-link"><?php echo __( 'link', 'fastfood' ); ?></label>
							</th>
							<td>
								<select id="galed-link" name="link">
									<option value="file" selected="selected"><?php echo __( 'file', 'fastfood' ); ?></option>
									<option value="attachments"><?php echo __( 'attachment', 'fastfood' ); ?></option>
									<option value="none"><?php echo __( 'none', 'fastfood' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-orderby"><?php echo __( 'order by', 'fastfood' ); ?></label>
							</th>
							<td>
								<select id="galed-orderby" name="orderby">
									<option value="none" selected="selected"><?php echo __( 'none', 'fastfood' ); ?></option>
									<option value="menu_order"><?php echo __( 'menu order', 'fastfood' ); ?></option>
									<option value="title"><?php echo __( 'title', 'fastfood' ); ?></option>
									<option value="date"><?php echo __( 'date', 'fastfood' ); ?></option>
									<option value="author"><?php echo __( 'author', 'fastfood' ); ?></option>
									<option value="comment_count"><?php echo __( 'comment count', 'fastfood' ); ?></option>
									<option value="rand"><?php echo __( 'random', 'fastfood' ); ?></option>
								</select>
								<small class="howto" style="display: block;"><?php echo __( 'Select "none" if you want the images to be ordered as above', 'fastfood' ); ?></small>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="galed-order"><?php echo __( 'order', 'fastfood' ); ?></label>
							</th>
							<td>
								<select id="galed-order" name="order">
									<option value="ASC" selected="selected"><?php echo __( 'ascending', 'fastfood' ); ?></option>
									<option value="DESC"><?php echo __( 'descending', 'fastfood' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<input type="submit" value="<?php _e('Generate Code','fastfood'); ?>" name="Submit" class="button-primary" onClick="galedGenerateCode(); return false;">
							</th>
							<td>
								<textarea id="galed-code" rows="3" readonly="readonly"></textarea>
								<small class="howto" id="galed-code-note"><?php _e('Copy this code and paste it where you want','fastfood') ?></small>
							</td>
						</tr>
					</tbody>
				</table>			
			</div>
		</div>

		<?php
	}
}

//custom gallery function
if ( !function_exists( 'fastfood_gallery_shortcode' ) ) {
	function fastfood_gallery_shortcode( $output, $attr ) {
		global $post, $wp_locale, $fastfood_opt;

		static $instance = 0;
		$instance++;

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}

		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => ''
		), $attr));

		if ( $fastfood_opt['fastfood_force_link_to_image'] == 1 ) $attr['link'] = 'file';

		$id = intval($id);
		if ( 'RAND' == $order )
			$orderby = 'none';

		if ( !empty($include) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
			
			if ( ( $orderby == 'none' ) && ( $order != 'RAND' ) && !is_feed() ) {
				unset( $_attachments );
				$_include = split( ',', $include );
				foreach ( $_include as $key => $val ) {
					if ( isset( $attachments[$val] ) )
						$_attachments[] = $attachments[$val];
				}
				$attachments = $_attachments;
			}
				
			
		} elseif ( !empty($exclude) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		if ( empty($attachments) )
			return '';

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment )
				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			return $output;
		}

		$itemtag = tag_escape($itemtag);
		$captiontag = tag_escape($captiontag);
		$columns = intval($columns);
		$float = is_rtl() ? 'right' : 'left';

		$selector = "gallery-{$instance}";

		$size_class = sanitize_html_class( $size );
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
		$output = $gallery_div;
		$itemwith_def = array( 'thumbnail' => get_option('thumbnail_size_w') . 'px', 'medium' => get_option('medium_size_w') . 'px', 'large' => get_option('large_size_w') . 'px', 'full' => 'auto' );
		$itemwidth = isset( $itemwith_def[$size] ) ? $itemwith_def[$size] : $itemwith_def['thumbnail'];
		$itemwidth = $columns > 0 ? floor(100/$columns) . '%' : $itemwidth;

		$i = 0;
		foreach ( $attachments as $key => $attachment ) {
			$id = $attachment->ID;
			$link = isset( $attr['link'] ) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link( $id, $size, true, false );

			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
				<{$icontag} class='gallery-icon'>
					$link
				</{$icontag}>";
			if ( $captiontag && trim( $attachment->post_excerpt ) ) {
				$output .= "
					<{$captiontag} class='wp-caption-text gallery-caption'>
					" . wptexturize( $attachment->post_excerpt ) . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}

		$output .= "
				<br style='clear: both;' />
			</div>\n";

		return $output;
	}
}

?>