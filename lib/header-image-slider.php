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

	function Fastfood_Header_Image_Slider() {

		add_action( 'admin_head-appearance_page_custom-header'	, array( &$this, 'admin_scripts' ), 99 );
		add_action( 'admin_head-appearance_page_custom-header'	, array( &$this, 'admin_style' ) );
		add_action( 'admin_init'								, array( &$this, 'remove_image' ) );
		add_action( 'admin_init'								, array( &$this, 'set_theme_mod' ) );
		add_filter( 'fastfood_header'							, array( &$this, 'slider_filter' ) );

	}


	/**
	 * Prints JavaScript codes in the header admin page, required to add the slider option
	 * and the remove image link.
	 *
	 * @return void
	 */
	function admin_scripts() {
		global $_wp_default_headers;

		$default_headers = $_wp_default_headers;
		$nonce = wp_create_nonce( 'fastfood_header_image_remove_nonce' );

?>
	<script>
		jQuery(function($){
		<?php
		$uploaded_headers = get_uploaded_header_images();
		if( ! empty( $uploaded_headers ) ) { ?>
			$('.available-headers').closest('form').find('table tr:first') // prevents showing remove links for default headers
			.find('div.default-header')
			.each(function(){
				$(this).append('<br/><a href="#" class="remove_header_image delete" style="padding-left: 25px;"><?php _e( 'Remove', 'fastfood' ) ?></a>');
			});
			$('a.remove_header_image').live('click', function(){
				thiz = $(this);
				if( window.confirm(commonL10n.warnDelete) ) {
					$.ajax({
						url: window.location.href,
						type: 'POST',
						data: {
							'header_image_remove': thiz.parent().find('img').attr('src'),
							_ajax_nonce: '<?php echo $nonce ?>'
						},
						success: function(data){
							thiz.parent().fadeOut('slow', function(){
								$(this).remove();
							});
						}
					});
				}
				return false;
			});
		<?php } ?>
			$('div.random-header').each(function(){
				if( $('input[name="default-header"]', this).val() == 'random-uploaded-image' ) {
		<?php $slider_input = "<div id='slider-settings' class='random-header'><label><input name='default-header' type='radio' value='fastfood-slider-uploaded' " . checked( 'fastfood-slider-uploaded', get_theme_mod( 'header_image', '' ), false ) . " id='fastfood-slider-uploaded'><strong>" . __( 'Slider', 'fastfood' ) . ":</strong></label><label>" . __( 'speed', 'fastfood' ) . " (ms)<input name='slider-speed' title='" . __( 'speed', 'fastfood' ) . "' type='text' value='" . get_theme_mod( 'header_slider_speed', '2000' ) . "' id='fastfood-slider-speed' /></label><label>" . __( 'pause', 'fastfood' ) . " (ms)<input name='slider-pause' title='" . __( 'pause', 'fastfood' ) . "' type='text' value='" . get_theme_mod( 'header_slider_pause', '3000' ) . "' id='fastfood-slider-pause' /></label></div>" ;?>
					$(this).after("<?php echo $slider_input; ?>");
				}
			});
		<?php if ( get_theme_mod( 'header_image', '' ) == 'fastfood-slider-uploaded' ) { ?>
			$("#headimg").addClass("slide").css({ 'background-image' : 'url(<?php echo get_template_directory_uri() . "/images/slider.png" ?>)' });
		<?php } ?>
			$(".default-header input,.random-header input:radio").click( function() {
				var def_header = $(this);
				switch( def_header.attr("value") ) {
					<?php foreach ( $default_headers as $header_key => $header ) { ?>
					case "<?php echo esc_attr( $header_key ); ?>":
						$("#headimg").removeClass("slide").css({ 'background-image' : 'url(<?php printf( $header['url'], get_template_directory_uri(), get_stylesheet_directory_uri()); ?>)' });
						break;
					<?php } ?>
					case "fastfood-slider-uploaded":
						$("#headimg").addClass("slide").css({ 'background-image' : 'url(<?php echo get_template_directory_uri() . "/images/slider.png" ?>)' });
						break;
					case "random-uploaded-image":
						$("#headimg").addClass("slide").css({ 'background-image' : 'url(<?php echo get_template_directory_uri() . "/images/random.png" ?>)' });
						break;
					case "random-default-image":
						$("#headimg").addClass("slide").css({ 'background-image' : 'url(<?php echo get_template_directory_uri() . "/images/random.png" ?>)' });
						break;
					default:
						def_header_img = def_header.next('img').attr("src");
						$("#headimg").removeClass("slide").css({ 'background-image' : 'url(' + def_header_img + ')' });
				}
			});
		});
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


	/**
	 * Remove header image
	 *
	 * @uses wp_delete_attachment
	 * @return void
	 */
	function remove_image() {
		global $wpdb;

		if( isset( $_POST['header_image_remove'] ) ) {

			check_ajax_referer( 'fastfood_header_image_remove_nonce' );

			if ( !current_user_can( 'manage_options' ) ) {
				wp_die( 'You do not have sufficient permissions to access this page.' );
			}

			if( ! $post = $wpdb->get_row( $wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_type = 'attachment' AND post_content = %s", $_POST['header_image_remove'] ) ) ) {
				return;
			}

			wp_delete_attachment( $post->ID, true ); // true: force_delete, bypass trash

		}

	}


	/**
	 * Manually set the header type as slider if user has chosen it
	 *
	 * @return void
	 */
	function set_theme_mod() {

		if( isset( $_POST['default-header'] ) && ( $_POST['default-header'] == 'fastfood-slider-uploaded' ) )
			set_theme_mod( 'header_image', 'fastfood-slider-uploaded' );

		if( isset( $_POST['slider-speed'] ) )
			set_theme_mod( 'header_slider_speed', (int)$_POST['slider-speed'] );

		if( isset( $_POST['slider-pause'] ) )
			set_theme_mod( 'header_slider_pause', (int)$_POST['slider-pause'] );

	}


	function slider_filter( $width = null, $height = null ) {
		$default = defined( 'HEADER_IMAGE' ) ? HEADER_IMAGE : '';
		$url = get_theme_mod( 'header_image', $default );
		$width = defined( 'HEADER_IMAGE_WIDTH' ) ? HEADER_IMAGE_WIDTH : $width;
		$height = defined( 'HEADER_IMAGE_HEIGHT' ) ? HEADER_IMAGE_HEIGHT : $height;

		$output = '';
		if( 'fastfood-slider-uploaded' == $url ) // slider
			$output = $this->build_slider( get_uploaded_header_images(), $width, $height );

		return $output;
	}


	function add_script() {

		echo "
		<script>
			window.onload = function(){ fastfoodAnimations.headerSlider({speed:" . get_theme_mod( 'header_slider_speed', '2000' ) . ", pause:" . get_theme_mod( 'header_slider_pause', '3000' ) . "}); };
		</script>
		";

	}


	function build_slider( $slides = array(), $width = null, $height = null ) {
		global $fastfood_opt;

		add_action( 'wp_footer', array( &$this, 'add_script' ) );

		if( $width )
			$width = "width: {$width}px;";
		if( $height )
			$height = "height: {$height}px";

		$output = '';

		shuffle( $slides );

		$count = count( $slides ) - 1;

		foreach( $slides as $key => $slide ) {
			$style = ( $count == $key ) ? '' : 'style="display:none;" ';
			if ( $fastfood_opt['fastfood_head_link'] == 1 )
				$output .= "<a href='" . home_url() . "'><img {$style}src='{$slide['url']}' alt='{$key}' /></a>";
			else
				$output .= "<img {$style}src='{$slide['url']}' alt='{$key}' />";
		}

		return "
			<div style='{$width} {$height}' id='slide-head'>
				{$output}
			</div>
			<div id='head'>
				<h1><a href='" . home_url() . "/'>" . get_bloginfo( 'name' ) . "</a></h1>
				<div class='description'>" . get_bloginfo( 'description' ) . "</div>
			</div>
		";

	}

}

new Fastfood_Header_Image_Slider();


