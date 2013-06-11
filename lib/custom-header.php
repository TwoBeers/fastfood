<?php
/**
 * custom-header.php
 *
 * The custom header support
 *
 * @package fastfood
 * @since fastfood 0.35
 */

class Fastfood_Custom_Header {

	function __construct() {

		add_action( 'after_setup_theme'							, array( &$this, 'custom_header_support' ) );
		add_action( 'custom_header_options'						, array( &$this, 'custom_header_background' ) );
		add_action( 'admin_init'								, array( &$this, 'save_theme_mod' ) );
		add_action( 'admin_head-appearance_page_custom-header'	, array( &$this, 'admin_scripts' ) );
		add_action( 'admin_head-appearance_page_custom-header'	, array( &$this, 'admin_style' ) );

	}


	// set up custom colors and header image
	function custom_header_support() {

		register_default_headers( array(
			'tree' => array(
				'url' => '%s/images/headers/tree.jpg',
				'thumbnail_url' => '%s/images/headers/tree-thumbnail.jpg',
				'description' => __( 'Ancient Tree', 'fastfood' )
			),
			'vector' => array(
				'url' => '%s/images/headers/vector.jpg',
				'thumbnail_url' => '%s/images/headers/vector-thumbnail.jpg',
				'description' => __( 'Vector Flowers', 'fastfood' )
			),
			'globe' => array(
				'url' => '%s/images/headers/globe.jpg',
				'thumbnail_url' => '%s/images/headers/globe-thumbnail.jpg',
				'description' => __( 'Globe', 'fastfood' )
			),
			'bamboo' => array(
				'url' => '%s/images/headers/bamboo.jpg',
				'thumbnail_url' => '%s/images/headers/bamboo-thumbnail.jpg',
				'description' => __( 'Bamboo Forest', 'fastfood' )
			),
			'stripes' => array(
				'url' => '%s/images/headers/stripes.jpg',
				'thumbnail_url' => '%s/images/headers/stripes-thumbnail.jpg',
				'description' => __( 'Orange stripes', 'fastfood' )
			),
			'abstract' => array(
				'url' => '%s/images/headers/abstract.jpg',
				'thumbnail_url' => '%s/images/headers/abstract-thumbnail.jpg',
				'description' => __( 'Abstract', 'fastfood' )
			),
			'carps' => array(
				'url' => '%s/images/headers/carps.jpg',
				'thumbnail_url' => '%s/images/headers/carps-thumbnail.jpg',
				'description' => __( 'Carps', 'fastfood' )
			)
		) );

		$args = array(
			'width'						=> 848, // Header image width (in pixels)
			'height'					=> ( fastfood_get_opt( 'fastfood_head_h' ) ? str_replace( 'px', '', fastfood_get_opt( 'fastfood_head_h' ) ) : 120 ), // Header image height (in pixels)
			'default-image'				=> get_template_directory_uri() . '/images/headers/tree.jpg', // Header image default
			'header-text'				=> true, // Header text display default
			'default-text-color'		=> '404040', // Header text color default
			'wp-head-callback'			=> array( $this, 'header_style_front' ),
			'admin-preview-callback'	=> array( $this, 'header_preview_admin' ),
		);

		$args = apply_filters( 'fastfood_filter_custom_header_args', $args );

		add_theme_support( 'custom-header', $args );

	}


	function custom_header_background() {

		$header_text_background = get_theme_mod( 'header_text_background', 'transparent' );

?>
	<table class="form-table">
		<tbody>
			<tr valign="top" class="header-text-background displaying-header-text">
				<th scope="row"><?php _e( 'Text Background', 'fastfood' ); ?></th>
				<td>
					<p>
						<select name="header-text-background" id="header-text-background">
							<option value="black" <?php selected( $header_text_background, 'black' ); ?>><?php _e( 'black', 'fastfood' ); ?></option>
							<option value="white" <?php selected( $header_text_background, 'white' ); ?>><?php _e( 'white', 'fastfood' ); ?></option>
							<option value="transparent" <?php selected( $header_text_background, 'transparent' ); ?>><?php _e( 'transparent', 'fastfood' ); ?></option>
						</select>
					</p>
				</td>
			</tr>
		</tbody>
	</table>

<?php

	}


	function save_theme_mod() {

		if( isset( $_POST['header-text-background'] ) ) {

			check_admin_referer( 'custom-header-options', '_wpnonce-custom-header-options' );

			if ( in_array( $_POST['header-text-background'], array( 'black', 'white', 'transparent' ) ) )
				set_theme_mod( 'header_text_background', $_POST['header-text-background'] );

		}

	}


	function admin_scripts() {

		if ( isset( $_GET['step'] ) &&  $_GET['step'] == 2 ) return;

?>
	<script type="text/javascript">
	/* <![CDATA[ */
	(function($){

		$(document).ready(function() {
			var header_text_background = $('#header-text-background');
			header_text_background.change( function() {
				$('#head-text').removeClass().addClass( header_text_background.val() );
				// check input ($(this).val()) for validity here
			});
		});

	})(jQuery);
	/* ]]> */
	</script>
<?php

	}


	/**
	 * Prints css code in the header admin page
	 *
	 * @return void
	 */
	function admin_style() {

		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/admin-custom_header.css" />' . "\n";

	}


	// included in the front head
	function header_style_front() {

		if ( fastfood_is_mobile() ) return;

		if ( ( 'blank' == get_header_textcolor() ) || fastfood_get_opt( 'fastfood_head_link' ) )
			$style = 'display:none;';
		else
			$style = 'color:#' . get_header_textcolor() . ';';

		$height = defined( 'HEADER_IMAGE_HEIGHT' ) ? HEADER_IMAGE_HEIGHT : 'auto';

?>
	<style type="text/css">
		#header {
			background: transparent url( '<?php header_image(); ?>' ) center no-repeat;
			height: <?php echo $height; ?>px;
		}
		#head h1 a,
		#head {
			<?php echo $style; ?>
		}
	</style>
<?php

	}


	// included in the admin head
	function header_preview_admin() {

		$custom_header = get_custom_header();
		$header_image_style = 'background-image:url(' . esc_url( get_header_image() ) . ');';
		if ( $custom_header->width )
			$header_image_style .= 'max-width:' . $custom_header->width . 'px;';
		if ( defined( 'HEADER_IMAGE_HEIGHT' ) )
			$header_image_style .= 'height:' . HEADER_IMAGE_HEIGHT . 'px;';
		$header_text_background = get_theme_mod( 'header_text_background', 'transparent' );

?>
	<div id="headimg" style="<?php echo $header_image_style; ?>">
		<?php
		if ( display_header_text() )
			$style = ' style="color:#' . get_header_textcolor() . ';"';
		else
			$style = ' style="display:none;"';
		?>
		<div id="head-text" class="displaying-header-text <?php echo $header_text_background; ?>" <?php echo $style; ?>>
			<h1><a id="name" class="displaying-header-text" <?php echo $style; ?> onclick="return false;" href="<?php bloginfo('url'); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<div id="desc" class="displaying-header-text" <?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		</div>
	</div>
<?php

	}


}

new Fastfood_Custom_Header;


