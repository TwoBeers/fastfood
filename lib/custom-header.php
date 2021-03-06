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

		add_action( 'after_setup_theme'			, array( $this, 'setup'        ) );
		add_action( 'fastfood_hook_builder'		, array( $this, 'the_header'   ), 10, array( 'id' => 'custom_header', 'section' => 'header', 'priority' => 11, 'label' => __( 'Site header', 'fastfood' ) ) );
		add_filter( 'body_class'				, array( $this, 'body_classes' ) );

	}


	// set up custom colors and header image
	function setup() {

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
			'width'						=> absint( FastfoodOptions::get_opt( 'fastfood_body_width' ) ),
			'height'					=> absint( FastfoodOptions::get_opt( 'fastfood_head_h' ) ),
			'default-image'				=> get_template_directory_uri() . '/images/headers/tree.jpg',
			'header-text'				=> true,
			'default-text-color'		=> '404040',
			'wp-head-callback'			=> array( $this, 'header_style_front' ),
			'flex-height'				=> false,
			'flex-width'				=> false,
		);

		$args = apply_filters( 'fastfood_filter_custom_header_args', $args );

		add_theme_support( 'custom-header', $args );

	}


	// Add specific CSS class by filter
	function body_classes( $classes ) {

		if ( get_header_image() ) $classes[] = 'has-header-image';

		return $classes;

	}


	// the custom header (filterable)
	function the_header(){

		$text = '';
		$image = get_header_image();

		$text = '
			<div id="site-heading">
				<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . get_bloginfo( 'name' ) . '</a></h1>
				<div class="site-description">' . get_bloginfo( 'description' ) . '</div>
			</div>';

		if ( $image ) {
			$image = '<img src="' . esc_url( $image ) . '" />';
			if ( !display_header_text() )
				$image = '<a href="' . esc_url( home_url( '/' ) ) . '">' . $image . '</a>';
			$image = '<div id="site-image">' . $image . '</div>';
		}

		// Allow plugins/themes to override the default header.
		$output = apply_filters( 'fastfood_header', $text . $image );
		?>

			<div id="site-header" role="banner">

				<?php echo $output; ?>

			</div>

		<?php

	}


	// included in the front head
	function header_style_front() {

		if ( fastfood_is_mobile() ) return;

		if ( 'blank' == get_header_textcolor() )
			$style = 'display: none;';
		else
			$style = 'color: #' . get_header_textcolor() . ';';

		$header_text_background = ltrim( get_theme_mod( 'header_text_background', 'transparent' ), '#' );

		$header_text_background_rgba = $header_text_background;
		$filter = '';
		if ( $header_text_background_rgba && ( 'transparent' != $header_text_background_rgba ) ) {
			$header_text_background_rgba = fastfood_hex2rgb( $header_text_background_rgba );
			$header_text_background_rgba = vsprintf( 'rgba( %1$s, %2$s, %3$s, 0.7)', $header_text_background_rgba );
			$filter = "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b3$header_text_background', endColorstr='#b3$header_text_background',GradientType=0 ); /* IE6-9 */";
		}

?>
	<style type="text/css">
		#site-heading a,
		#site-heading {
			<?php echo $style; ?>
		}
		#site-image {
			max-height: <?php echo absint( FastfoodOptions::get_opt( 'fastfood_head_h' ) ); ?>px;
		}
		.has-header-image #site-heading {
			background-color: <?php echo $header_text_background_rgba; ?>;
			<?php echo $filter; ?>
		}
	</style>
<?php

	}

}

new Fastfood_Custom_Header;
