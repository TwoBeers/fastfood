<?php
/**
 * custom-background.php
 *
 * The custom background setup.
 *
 * @package fastfood
 * @since fastfood 0.27
 */


/**
 * Add custom background support to Fastfood.
 */
function fastfood_custom_background_init() {

		$args = array(
			'wp-head-callback' => '__return_false',
		);
		add_theme_support( 'custom-background', $args );

}
add_action( 'after_setup_theme', 'fastfood_custom_background_init' );


/**
 * Register background schemes for Fastfood.
 *
 * Can be filtered with {@see 'fastfood_background_schemes'}.
 *
 * @since Fastfood 0.37
 *
 * @return array an associative array of background scheme options.
 */
function fastfood_get_background_schemes( $id = false ) {

	$backgrounds_folder = get_template_directory_uri() . '/images/backgrounds/';

	$background_schemes = apply_filters( 'fastfood_background_schemes', array(
		'0' => array(
			'label'			=> 'Default',
			'thumbnail'		=> $backgrounds_folder . 'default-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'default.png',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'bottom',
				'background_repeat'			=> 'repeat-x',
				'background_attachment'		=> 'fixed',
				'background_color'			=> '#FFFFFF',
				'background_icons_color'	=> '#404040',
			),
		),
		'fastfood' => array(
			'label'			=> 'Fastfood',
			'thumbnail'		=> $backgrounds_folder . 'default-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'default.png',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'bottom',
				'background_repeat'			=> 'repeat-x',
				'background_attachment'		=> 'fixed',
				'background_color'			=> '#FFFFFF',
				'background_icons_color'	=> '#404040',
			),
		),
		'exagon' => array(
			'label'			=> 'Exagon',
			'thumbnail'		=> $backgrounds_folder . 'exagon-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'exagon.png',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat',
				'background_attachment'		=> 'scroll',
				'background_color'			=> '#CE3D86',
				'background_icons_color'	=> '#F8F8F8',
			),
		),
		'circuitboard' 	=> array(
			'label'			=> 'Circuit Board',
			'thumbnail'		=> $backgrounds_folder . 'circuitboard-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'circuitboard.png',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat',
				'background_attachment'		=> 'scroll',
				'background_color'			=> '#00713D',
				'background_icons_color'	=> '#FFFFFF',
			),
		),
		'grid' => array(
			'label'			=> 'Grid',
			'thumbnail'		=> $backgrounds_folder . 'grid-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'grid.png',
				'background_position_x'		=> 'center',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat',
				'background_attachment'		=> 'fixed',
				'background_color'			=> '#DD3333',
				'background_icons_color'	=> '#F8F8F8',
			),
		),
		'aqua' => array(
			'label'			=> 'Aqua',
			'thumbnail'		=> $backgrounds_folder . 'aqua-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'aqua.jpg',
				'background_position_x'		=> 'center',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'no-repeat',
				'background_attachment'		=> 'fixed',
				'background_color'			=> '#F5FDFF',
				'background_icons_color'	=> '#61AFCC',
			),
		),
		'wood' => array(
			'label'			=> 'Wood',
			'thumbnail'		=> $backgrounds_folder . 'wood-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'wood.jpg',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat',
				'background_attachment'		=> 'fixed',
				'background_color'			=> '#343027',
				'background_icons_color'	=> '#FFFFFF',
			),
		),
		'violet' => array(
			'label'			=> 'Violet',
			'thumbnail'		=> $backgrounds_folder . 'violet-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'violet.jpg',
				'background_position_x'		=> 'right',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'no-repeat',
				'background_attachment'		=> 'fixed',
				'background_color'			=> '#190923',
				'background_icons_color'	=> '#BF92D5',
			),
		),
		'paper' => array(
			'label'			=> 'Paper',
			'thumbnail'		=> $backgrounds_folder . 'paper-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'paper.jpg',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat',
				'background_attachment'		=> 'scroll',
				'background_color'			=> '#EEE5D2',
				'background_icons_color'	=> '#404040',
			),
		),
		'squarednight' => array(
			'label'			=> 'Squared Night',
			'thumbnail'		=> $backgrounds_folder . 'squarednight-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'squarednight.jpg',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat-x',
				'background_attachment'		=> 'scroll',
				'background_color'			=> '#0E111A',
				'background_icons_color'	=> '#686A6F',
			),
		),
		'yellow' => array(
			'label'			=> 'Yellow',
			'thumbnail'		=> $backgrounds_folder . 'yellow-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'yellow.jpg',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat',
				'background_attachment'		=> 'scroll',
				'background_color'			=> '#F7DE5F',
				'background_icons_color'	=> '#B27629',
			),
		),
		'clouds' => array(
			'label'			=> 'Clouds',
			'thumbnail'		=> $backgrounds_folder . 'clouds-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'clouds.jpg',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat-x',
				'background_attachment'		=> 'fixed',
				'background_color'			=> '#DBE8F9',
				'background_icons_color'	=> '#566989',
			),
		),
		'graphite' => array(
			'label'			=> 'Graphite',
			'thumbnail'		=> $backgrounds_folder . 'graphite-thumbnail.jpg',
			'attributes'	=> array(
				'background_image'			=> $backgrounds_folder . 'graphite.jpg',
				'background_position_x'		=> 'left',
				'background_position_y'		=> 'top',
				'background_repeat'			=> 'repeat',
				'background_attachment'		=> 'scroll',
				'background_color'			=> '#212121',
				'background_icons_color'	=> '#999999',
			),
		),
	) );

	if ( ( $id !== false ) && isset( $background_schemes[$id] ) )
		return $background_schemes[$id];
	else
		return $background_schemes;

}


/**
 * Returns an array of background scheme choices registered.
 *
 * @since Fastfood 0.37
 *
 * @return array Array of background schemes thumbnails.
 */
function fastfood_get_background_schemes_thumbnails() {

	$background_schemes = fastfood_get_background_schemes();
	$background_scheme_control_options = array();

	foreach ( $background_schemes as $background_scheme => $value ) {
		$background_scheme_control_options[ $background_scheme ] = '<img src="' . $value['thumbnail'] . '" alt="' . esc_attr( $value['label'] ) . '"/>';
	}

	return $background_scheme_control_options;

}
