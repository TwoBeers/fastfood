<?php
/**
 * header-image-slider.php
 *
 * Header Image Slider script.
 *
 * @package fastfood
 * @since fastfood 0.32
 */


class Fastfood_Header_Image_Slider {

	public function __construct() {

		add_filter( 'fastfood_header', array( $this, 'slider_filter' ) );

	}


	function slider_filter( $output ) {

		$display_header_slider = get_theme_mod( 'display_header_slider', '' );
		$slides = get_uploaded_header_images();
		$js_loaded = FastfoodOptions::get_opt( 'fastfood_jsani' );

		if( $display_header_slider && $slides && $js_loaded )
			$output = $this->build_slider( $slides );

		return $output;

	}


	function add_script() {

		echo "
		<script type='text/javascript'>
			/* <![CDATA[ */
			window.onload = function(){ fastfoodAnimations.headerSlider({speed:" . get_theme_mod( 'header_slider_speed', '2000' ) . ", pause:" . get_theme_mod( 'header_slider_pause', '3000' ) . "}); };
			/* ]]> */
		</script>
		";

	}


	function build_slider( $slides = array() ) {

		add_action( 'wp_footer', array( $this, 'add_script' ) );

		$text = '';
		$images = '';

		shuffle( $slides );

		$text = '
			<div id="site-heading">
				<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . get_bloginfo( 'name' ) . '</a></h1>
				<div class="site-description">' . get_bloginfo( 'description' ) . '</div>
			</div>';

		foreach( $slides as $key => $slide ) {
			$images .= '<img src="' . esc_url( $slide['url'] ) . '" alt="' . esc_attr( $key ) . '" />';
		}
		if ( $images ) {
			if ( !display_header_text() )
				$images = '<a href="' . esc_url( home_url( '/' ) ) . '">' . $images . '</a>';
			$images = '<div id="site-image" class="slider">' . $images . '</div>';
		}

		$output = $text . $images;
		return $output;

	}

}

new Fastfood_Header_Image_Slider();


