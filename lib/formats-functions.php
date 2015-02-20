<?php
/**
 * formats-functions.php
 *
 * This file contains functions related to post formats (eg. quote, image, gallery, ...) and post types (eg. attachments)
 *
 * @package Fastfood
 * @since 0.15
 */


/**
 * Custom actions - theme hooks
 */
add_action( 'fastfood_hook_attachment_after'		, 'fastfood_attachment_preview' );
add_action( 'fastfood_hook_attachment_before'		, 'fastfood_navigate_images' );


/**
 * Custom filters - WP hooks
 */
add_filter( 'embed_oembed_html'						, 'fastfood_wmode_transparent', 10, 3);
add_filter( 'the_content'							, 'fastfood_quote_content' );
add_filter( 'img_caption_shortcode_width'			, 'fastfood_img_caption_shortcode_width', 10, 2 );
add_filter( 'shortcode_atts_gallery'				, 'fastfood_shortcode_atts_gallery', 10, 3 );
add_filter( 'wp_get_attachment_link'				, 'fastfood_get_attachment_link', 10, 6 );


/**
 * Return the metadata of an image (if any)
 * 
 * @param	none
 * @return	array	Array containing the metadata, each one is an array of 'label','original','readable'
 */
function fastfood_exif_details(){

	$m = wp_get_attachment_metadata();

	// convert the shutter speed retrieve from database to fraction
	if ( $m['image_meta']['shutter_speed'] && ( 1 / $m['image_meta']['shutter_speed'] ) > 1 ) {
		if ( ( number_format( ( 1 / $m['image_meta']['shutter_speed'] ), 1 ) ) == 1.3
		or number_format( ( 1 / $m['image_meta']['shutter_speed'] ), 1) == 1.5
		or number_format( ( 1 / $m['image_meta']['shutter_speed'] ), 1) == 1.6
		or number_format( ( 1 / $m['image_meta']['shutter_speed'] ), 1) == 2.5 ){
			$shutter_speed = "1/" . number_format( ( 1 / $m['image_meta']['shutter_speed'] ), 1, '.', '' );
		} else {
			$shutter_speed = "1/" . number_format( ( 1 / $m['image_meta']['shutter_speed'] ), 0, '.', '' );
		}
	}

	$uploaddir = wp_upload_dir();
	$imagesize = size_format( filesize( $uploaddir['basedir'] . '/' . $m['file'] ) );

	// array( LABEL, ORIGINAL_VALUE, READABLE_VALUE )

	if ( $imagesize )
		$image_meta['filesize'] = array(
			'label'		=> __( 'File Size', 'fastfood' ),
			'original'	=> $imagesize,
			'readable'	=> $imagesize,
		);

	if ( $m['width'] )
		$image_meta['width'] = array(
			'label'		=> __( 'Width', 'fastfood' ),
			'original'	=> $m['width'],
			'readable'	=> $m['width'] . 'px',
		);

	if ( $m['height'] )
		$image_meta['height'] = array(
			'label'		=> __( 'Height', 'fastfood' ),
			'original'	=> $m['height'],
			'readable'	=> $m['height'] . 'px',
		);

	if ( $m['image_meta']['created_timestamp'] )
		$image_meta['created_timestamp'] = array(
			'label'		=> __( 'Date Taken', 'fastfood' ),
			'original'	=> $m['image_meta']['created_timestamp'],
			'readable'	=> date_i18n(get_option('date_format') . ' ' . get_option( 'time_format'), $m['image_meta']['created_timestamp'] ),
		);

	if ( $m['image_meta']['copyright'] )
		$image_meta['copyright'] = array(
			'label'		=> __( 'Copyright', 'fastfood' ),
			'original'	=> $m['image_meta']['copyright'],
			'readable'	=> $m['image_meta']['copyright'],
		);

	if ( $m['image_meta']['credit'] )
		$image_meta['credit'] = array(
			'label'		=> __( 'Credit', 'fastfood' ),
			'original'	=> $m['image_meta']['credit'],
			'readable'	=> $m['image_meta']['credit'],
		);

	if ( $m['image_meta']['title'] )
		$image_meta['title'] = array(
			'label'		=> __( 'Title', 'fastfood' ),
			'original'	=> $m['image_meta']['title'],
			'readable'	=> $m['image_meta']['title'],
		);

	if ( $m['image_meta']['caption'] )
		$image_meta['caption'] = array(
			'label'		=> __( 'Caption', 'fastfood' ),
			'original'	=> $m['image_meta']['caption'],
			'readable'	=> $m['image_meta']['caption'],
		);

	if ( $m['image_meta']['camera'] )
		$image_meta['camera'] = array(
			'label'		=> __( 'Camera', 'fastfood' ),
			'original'	=> $m['image_meta']['camera'],
			'readable'	=> $m['image_meta']['camera'],
		);

	if ( $m['image_meta']['focal_length'] )
		$image_meta['focal_length'] = array(
			'label'		=> __( 'Focal Length', 'fastfood' ),
			'original'	=> $m['image_meta']['focal_length'],
			'readable'	=> $m['image_meta']['focal_length'] . 'mm',
		);

	if ( $m['image_meta']['aperture'] )
		$image_meta['aperture'] = array(
			'label'		=> __( 'Aperture', 'fastfood' ),
			'original'	=> $m['image_meta']['aperture'],
			'readable'	=> 'f/' . $m['image_meta']['aperture'],
		);

	if ( $m['image_meta']['iso'] )
		$image_meta['iso'] = array(
			'label'		=> __( 'ISO', 'fastfood' ),
			'original'	=> $m['image_meta']['iso'],
			'readable'	=> $m['image_meta']['iso'],
		);

	if ( $m['image_meta']['shutter_speed'] )
		$image_meta['shutter_speed'] = array(
			'label'		=> __( 'Shutter Speed', 'fastfood' ),
			'original'	=> $m['image_meta']['shutter_speed'],
			'readable'	=> sprintf( __( '%s seconds', 'fastfood' ), $shutter_speed),
		);

	return  apply_filters( 'fastfood_exif_details', $image_meta );

}


/**
 * Return the first <img> in content
 *
 * The list of arguments is below:
 *     'id' (integer)       - The ID of the post.
 *     'filtered' (boolean) - Scan content after applying filters.
 *     'input' (string)     - Text to scan. If provided, the 'id' and 'filtered' arguments will be ignored
 *
 * @param string|array	$args	Optional. Override default arguments.
 * @return	array		Array containing the <img> attributes
 */
function fastfood_get_first_image( $args = '' ) {

	$defaults = array(
		'id'			=> null,
		'filtered'		=> false,
		'input'			=> '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( $args['input'] ) {
		$content = $args['input'];
	} else {
		$post = get_post( $args['id'] );
		$content = $args['filtered'] ? apply_filters( 'the_content', $post->post_content ): $post->post_content;
	}

	$first_image = array(
		'img'		=> '',
		'src'		=> '',
		'title'		=> '',
		'alt'		=> '',
		'width'		=> '',
		'height'	=> '',
		'class'		=> '',
	);

	//search the images in post content
	preg_match_all( '/<img[^>]+>/i', $content, $result );

	//grab the first one
	if ( isset( $result[0][0] ) ){

		foreach ( $first_image as $key => $value ) {

			switch ( $key ) {

				case 'img':
					$first_image['img'] = $result[0][0];
					break;

				default:
					preg_match_all( '/(' . $key . ')=["|\']([^"|\']*)["|\']/i', $result[0][0], $attribute );

					$first_image[$key] = isset( $attribute[2][0] ) ? $attribute[2][0] : $first_image[$key];

			}

		}

		return apply_filters( 'fastfood_get_first_image', $first_image );

	} else {

		return false;

	}

}


/**
 * Return the first <a> in content
 * 
 * The list of arguments is below:
 *     'id' (integer)       - The ID of the post.
 *     'filtered' (boolean) - Scan content after applying filters.
 *     'input' (string)     - Text to scan. If provided, the 'id' and 'filtered' arguments will be ignored
 *
 * @param string|array	$args	Optional. Override default arguments.
 * @return	array		Array containing the <a> attributes
 */
function fastfood_get_first_link( $args = '' ) {

	$defaults = array(
		'id'			=> null,
		'filtered'		=> false,
		'input'			=> '',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( $args['input'] ) {
		$content = $args['input'];
	} else {
		$post = get_post( $args['id'] );
		$content = $args['filtered'] ? apply_filters( 'the_content', $post->post_content ): $post->post_content;
	}

	$first_link = array(
		'a'				=> '',
		'href'			=> '',
		'title'			=> '',
		'text'			=> '',
		'rel'			=> '',
		'target'		=> '',
		'media'			=> '', //html5 attribute
		'download'		=> '', //html5 attribute
		'class'			=> '',
	);

	//search the link in post content
	preg_match_all( '/<a\b[^>]*>(.*?)<\/a>/i', $content, $result );

	//grab the first one
	if ( isset( $result[0][0] ) ){

		foreach ( $first_link as $key => $value ) {

			switch ( $key ) {

				case 'a':
					$first_link['a'] = $result[0][0];
					break;

				case 'text':
					$first_link['text'] = isset( $result[1][0] ) ? $result[1][0] : $first_link['text'];
					break;

				default:
					preg_match_all( '/(' . $key . ')=["|\']([^"|\']*)["|\']/i', $result[0][0], $attribute );

					$first_link[$key] = isset( $attribute[2][0] ) ? $attribute[2][0] : $first_link[$key];

			}

		}

		return apply_filters( 'fastfood_get_first_link', $first_link );

	} else {

		return false;

	}

}


/**
 * Return the first <a> in content
 * 
 * The list of arguments is below:
 *     'id' (integer)             - The ID of the post.
 *     'filtered' (boolean)       - Scan content after applying filters.
 *     'input' (string)           - Text to scan. If provided, the 'id' and 'filtered' arguments will be ignored
 *     'excerpt_length' (integer) - The length of the excerpt.
 *
 * @param string|array	$args	Optional. Override default arguments.
 * @return	array		Array containing the <a> attributes
 */
function fastfood_get_blockquote( $args = '' ) {

	$defaults = array(
		'id'				=> null,
		'filtered'			=> false,
		'input'				=> '',
		'excerpt_length'	=> 5,
	);

	$args = wp_parse_args( $args, $defaults );

	if ( $args['input'] ) {
		$content = $args['input'];
	} else {
		$post = get_post( $args['id'] );
		$content = $args['filtered'] ? apply_filters( 'the_content', $post->post_content ): $post->post_content;
	}

	$first_quote = array(
		'blockquote'	=> '',
		'cite'			=> '',
		'title'			=> '',
		'text'			=> '',
		'excerpt'		=> '',
		'class'			=> '',
	);

	//search the blockquote in post content
	preg_match_all( '/<blockquote\b[^>]*>([\w\W]*?)<\/blockquote>/', $content, $result );

	//grab the first one
	if ( isset( $result[0][0] ) ){

		foreach ( $first_quote as $key => $value ) {

			switch ( $key ) {

				case 'blockquote':
					$first_quote['blockquote'] = $result[0][0];
					break;

				case 'text':
					$first_quote['text'] = isset( $result[1][0] ) ? $result[1][0] : $first_quote['text'];
					break;

				case 'excerpt':
					$first_quote['excerpt'] = isset( $result[1][0] ) ? strip_tags( $result[1][0] ) : $first_quote['excerpt'];
					$words = explode( ' ', $first_quote['excerpt'], $args['excerpt_length'] + 1 );
					if ( count( $words ) == ( $args['excerpt_length'] + 1 ) ) $words[$args['excerpt_length']] = '...';
					$first_quote['excerpt'] = implode( ' ', $words );
					break;

				default:
					preg_match_all( '/(' . $key . ')=["|\']([^"|\']*)["|\']/i', $result[0][0], $attribute );

					$first_quote[$key] = isset( $attribute[2][0] ) ? $attribute[2][0] : $first_quote[$key];

			}

		}

		return apply_filters( 'fastfood_get_blockquote', $first_quote );

	} else {

		return false;

	}

}


/**
 * Return the first [shortcode] attributes in content
 *
 * @param string	$content	The content to scan.
 * @param string	$shortcode	The shortcode tag
 * @return	array|boolean	Array containing the [shortcode] attributes or "false" if not found
 */
function fastfood_get_shortcode( $content = '', $shortcode = 'no_shortcode' ) {

	$pattern = get_shortcode_regex();

	if (   preg_match_all( '/'. $pattern .'/s', $content, $matches )
		&& array_key_exists( 2, $matches )
		&& in_array( $shortcode, $matches[2] ) ) // gallery shortcode is being used
	{
		$key = array_search( $shortcode, $matches[2] );
		$attrs = shortcode_parse_atts( $matches['3'][$key] );
		return $attrs;
	}

	return false;

}


/**
 * Get all attachments in a [gallery] shortcode
 * 
 * Based on \wp-includes\media.php -> gallery_shortcode().
 * 
 * @since 0.37
 *
 * @param array $attr {
 *     @type string $order      Order of the images in the gallery. Default 'ASC'. Accepts 'ASC', 'DESC'.
 *     @type string $orderby    The field to use when ordering the images. Default 'menu_order ID'.
 *                              Accepts any valid SQL ORDERBY statement.
 *     @type int    $id         Post ID.
 *     @type string $ids        A comma-separated list of IDs of attachments to display. Default empty.
 *     @type string $include    A comma-separated list of IDs of attachments to include. Default empty.
 *     @type string $exclude    A comma-separated list of IDs of attachments to exclude. Default empty.
 * }
 * @return	array	Array containing the attachments
 */
function fastfood_get_gallery_items( $attr ) {

	$post = get_post();

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'include'    => '',
		'exclude'    => '',
	), $attr, 'gallery' );

	if ( ! empty( $atts['include'] ) ) {

		$_attachments = get_posts( array(
			'include'			=> $atts['include'],
			'post_status'		=> 'inherit',
			'post_type'			=> 'attachment',
			'post_mime_type'	=> 'image',
			'order'				=> $atts['order'],
			'orderby'			=> $atts['orderby']
		) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}

	} elseif ( ! empty( $atts['exclude'] ) ) {

		$attachments = get_children( array(
			'post_parent'		=> intval( $atts['id'] ),
			'exclude'			=> $atts['exclude'],
			'post_status'		=> 'inherit',
			'post_type'			=> 'attachment',
			'post_mime_type'	=> 'image',
			'order'				=> $atts['order'],
			'orderby'			=> $atts['orderby']
		) );

	} else {

		$attachments = get_children( array(
			'post_parent'		=> intval( $atts['id'] ),
			'post_status'		=> 'inherit',
			'post_type'			=> 'attachment',
			'post_mime_type'	=> 'image',
			'order'				=> $atts['order'],
			'orderby'			=> $atts['orderby']
		) );

	}

	return $attachments;

}


/**
 * Print a gallery by merging all the galleries in the post.
 * If no [gallery] found, it creates a gallery using the images attached to post
 * 
 * Based on \wp-includes\media.php -> gallery_shortcode().
 * 
 * @since 0.37
 *
 * @param array $attr {
 *     @type int    $id         Post ID.
 *     @type string $class      Gallery class.
 *     @type int    $columns    Number of columns.
 *     @type int    $slice      Max number of items.
 * }
 * @return	int	The number of items in merged gallery
 */
function fastfood_gallery_merge( $args ) {
	global $post;

	$defaults = array(
		'id'		=> $post->ID,
		'class'		=> '',
		'columns'	=> 3,
		'slice'		=> 0,
	);

	$args = wp_parse_args( $args, $defaults );

	$galleries = get_post_galleries( $args['id'], false );

	$result = array();
	foreach ( $galleries as $gallery ) {
		$result = array_merge( $result, wp_list_pluck( fastfood_get_gallery_items( $gallery ), 'ID' ) );
	}

	if ( ! $result )
		$result = wp_list_pluck( get_children( array(
			'post_parent'		=> $args['id'],
			'post_status'		=> 'inherit',
			'post_type'			=> 'attachment',
			'post_mime_type'	=> 'image',
			'order'				=> 'ASC',
			'orderby'			=> 'menu_order ID',
		) ), 'ID' );

	if ( ! $count = count( $result ) ) return 0;

	if ( $args['slice'] ) {
		$result = array_slice( $result, 0, absint( $args['slice'] ) );
		$args['columns'] = count( $result ) - 1;
	}

	$args['class'] = esc_attr( join( ' ', array( 'gallery', 'gallery-columns-' . $args['columns'], $args['class'] ) ) );
	$output = "<div class='{$args['class']}'>";
	foreach ( $result as $key => $id ) {
		$size = $key ? 'thumbnail' : 'full';

		$image_output = wp_get_attachment_image( $id, $size, false );
		$image_output = $key ? $image_output : '<a href="' . get_permalink( $args['id'] ) . '">' . $image_output . '</a>';

		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) )
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';

		$output .= "<dl class='gallery-item'>";
		$output .= "
			<dt class='gallery-icon {$orientation}'>
				$image_output
			</dt>";
		$output .= "</dl>";
	}
	$output .= "</div>";

	echo $output;

	return $count;

}


/**
 * Display the preview for the gallery in post
 * 
 * @param	none
 * @return	none
 */
function fastfood_gallery_preview() {

	$count = fastfood_gallery_merge( array(
		'class'	=> 'gallery-preview',
		'slice'	=> FastfoodOptions::get_opt( 'fastfood_post_formats_gallery_preview_items' ),
	) );

	echo '
		<p class="gallery-info">
			' . sprintf( _n( 'This gallery contains <a %1$s><strong>%2$s</strong> image</a>', 'This gallery contains <a %1$s><strong>%2$s</strong> images</a>', $count, 'fastfood' ),
			'href="' . esc_url( get_permalink() ) . '" title="' . esc_attr__( 'View gallery', 'fastfood' ) . '" rel="bookmark"',
			number_format_i18n( $count )
			) . '
		</p>
		';

}


/**
 * return the previous and next image IDs
 *
 * @param	mixed	$post		(optional) the post (ID or object)
 * @return	array				the previous and next image IDs array('prev','next')
*/
function fastfood_get_prevnext_images( $post = null ) {

	$out = array( 'prev' => '', 'next' => '' );

	//return if unable to find the post
	if ( !$post = get_post( $post ) )
		return $out;

	//return if attachment is not an image
	if ( !wp_attachment_is_image( $post->ID ) )
		return $out;

	$attachments = array_values( get_children( array(
		'post_parent'		=> $post->post_parent,
		'post_status'		=> 'inherit',
		'post_type'			=> 'attachment',
		'post_mime_type'	=> 'image',
		'order'				=> 'ASC',
		'orderby'			=> 'menu_order ID',
	) ) );

	foreach ( $attachments as $key => $attachment ) {
		if ( $attachment->ID == $post->ID )
			break;
	}

	$prev_k = $key - 1;
	$next_k = $key + 1;

	if ( isset( $attachments[ $prev_k ] ) )
		$out['prev'] = $attachments[ $prev_k ]->ID;

	if ( isset( $attachments[ $next_k ] ) )
		$out['next'] = $attachments[ $next_k ]->ID;

	return $out;

}


/**
 * Display the navigation images
 *
 * @param	mixed	$post		(optional) the post (ID or object)
 * @return	none
*/
function fastfood_navigate_images( $post = null ) {

	if ( !$post = get_post( $post ) )
		return;

	if ( !wp_attachment_is_image( $post->ID ) )
		return;

	$images = fastfood_get_prevnext_images( $post->ID );

	if ( $images['prev'] )
		$images['prev'] = fastfood_build_link( array(
			'href'		=> get_attachment_link( $images['prev'] ),
			'text'		=> '<i class="el-icon-chevron-left"></i> ' . wp_get_attachment_image( $images['prev'], array( 70, 70 ) ),
			'class'		=> 'size-thumbnail',
			'rel'		=> 'prev',
		) );

	if ( $images['next'] )
		$images['next'] = fastfood_build_link( array(
			'href'		=> get_attachment_link( $images['next'] ),
			'text'		=> wp_get_attachment_image( $images['next'], array( 70, 70 ) ) . '<i class="el-icon-chevron-right"></i>',
			'class'		=> 'size-thumbnail',
			'rel'		=> 'next',
		) );

?>
	<div class="img-navi">

		<?php echo $images['prev']; ?>
		<span class="img-navi-curimg"><?php echo wp_get_attachment_image( $post->ID, array( 70, 70 ) ); ?></span>
		<?php echo $images['next']; ?>

	</div>
<?php

}


/**
 * Get the post format string.
 *
 * @param	int|object	$id Post ID or post object. Optional, default is the current post from the loop.
 * @return	none
*/
function fastfood_get_post_format( $id ) {

	if ( post_password_required() )
		$format = 'protected';
	else
		$format = ( FastfoodOptions::get_opt( 'fastfood_post_formats_' . get_post_format( $id ) ) ) ? get_post_format( $id ) : '' ;

	return $format;

}


/**
 * Add a fix for embed videos overlying html elements.
 * 
 * @see WP_Embed::shortcode()
*/
function fastfood_wmode_transparent( $html, $url = null, $attr = null ) {

	if ( strpos( $html, '<embed ' ) !== false ) {

		$html = str_replace( '</param><embed', '</param><param name="wmode" value="transparent"></param><embed', $html);
		$html = str_replace( '<embed ', '<embed wmode="transparent" ', $html);

	} elseif ( strpos ( $html, 'feature=oembed' ) !== false ) {

		$html = str_replace( 'feature=oembed', 'feature=oembed&wmode=transparent', $html );

	}

	return $html;

}


/**
 * Filters the post content. If no <blockquote> tag found, it'll be wholly wrapped inside a <blockquote>
 * 
 * @param	string	$content Post content.
 * @return	string	Filtered post content.
*/
function fastfood_quote_content( $content ) {

	/* Check if we're displaying a 'quote' post. */
	if ( has_post_format( 'quote' ) && FastfoodOptions::get_opt( 'fastfood_post_formats_quote' ) ) {

		/* Match any <blockquote> elements. */
		preg_match( '/<blockquote.*?>/', $content, $matches );

		/* If no <blockquote> elements were found, wrap the entire content in one. */
		if ( empty( $matches ) )
			$content = "<blockquote>{$content}</blockquote>";
	}

	return $content;

}


/**
 * Filters the width of captions.
 * 
 * @see img_caption_shortcode()
*/
function fastfood_img_caption_shortcode_width( $caption_width, $atts ) {

	return $atts['width'];

}


/**
 * Filters the 'link' attributes for galleries. (required for the Thickbox feature)
 * 
 * @see shortcode_atts()
*/
function fastfood_shortcode_atts_gallery( $out, $pairs, $atts ) {

	if (   FastfoodOptions::get_opt( 'fastfood_jsani' )
		&& FastfoodOptions::get_opt( 'fastfood_gallery_preview' )
		&& FastfoodOptions::get_opt( 'fastfood_force_link_to_image' )
		&& isset( $out['link'] )
		&& $out['link'] !=='none'
		)
		$out['link'] = 'file';

	return $out;

}


/**
 * Add attachment description. (required for the Thickbox feature)
 * 
 * @see wp_get_attachment_link()
*/
function fastfood_get_attachment_link( $markup = '', $id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false ) {

	if ( !FastfoodOptions::get_opt( 'fastfood_jsani' ) || !FastfoodOptions::get_opt( 'fastfood_gallery_preview' ) )
		return $markup;

	$id = intval( $id );
	$_post = get_post( $id );

	if ( empty( $_post ) || ( 'attachment' != $_post->post_type ) || !$url = wp_get_attachment_url( $_post->ID ) )
		return __( 'Missing Attachment','fastfood' );

	if ( $permalink )
		$url = get_attachment_link( $_post->ID );

	$post_title = esc_attr( $_post->post_excerpt ? $_post->post_excerpt : $_post->post_title );

	if ( $text )
		$link_text = $text;
	elseif ( $size && 'none' != $size )
		$link_text = wp_get_attachment_image( $id, $size, $icon );
	else
		$link_text = '';

	if ( trim( $link_text ) == '' )
		$link_text = $_post->post_title;

	return "<a href='$url' title='$post_title'>$link_text</a>";

}


/**
 * Return the mime_type of the post
 *
 * @param	int|object	$id Post ID or post object. Optional, default is the current post from the loop.
 * @return	array		Array {
 *     @type string $type    Type (eg. audio, video, text)
 *     @type string $subtype Subtype (eg. jpeg, quicktime, plain, ... )
 * }
*/
function fastfood_get_post_mime_type( $id = null ) {

	$mime_type = explode( '/', get_post_mime_type( $id ) );

	return array(
		'type'		=> isset( $mime_type[0] ) ? $mime_type[0] : '',
		'subtype'	=> isset( $mime_type[1] ) ? $mime_type[1] : '',
	);

}


/**
 * Show the attachment content
*/
function fastfood_attachment_preview() {

	$file = wp_get_attachment_url();
	$mime_type = fastfood_get_post_mime_type();

	switch ( $mime_type['type'] ) {

		case 'video':
			echo wp_video_shortcode( array(
				'src'		=> $file,
				'autoplay'	=> false,
				'loop'		=> true,
			) );
			break;

		case 'audio':
			echo wp_audio_shortcode( array(
				'src'		=> $file,
			) );
			break;

		default:
			do_action( 'fastfood_attachment_preview', $file, $mime_type );

	}

}

