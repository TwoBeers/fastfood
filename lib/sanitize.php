<?php
/**
 * sanitize.php
 *
 * the sanitization class
 *
 * @package fastfood
 * @since fastfood 0.37
 */

class FastfoodSanitize {

	/**
	 * Holds the theme options
	 * @access protected
	 * @var array
	 */
	private static $theme_options = array();

	/**
	 * Holds the theme mods
	 * @access protected
	 * @var array
	 */
	private static $theme_mods = array();


	public static function theme_mod( $value, $option ) {

		$_value = NULL;

		if ( !self::$theme_mods )
			self::$theme_mods = fastfood_register_theme_mods();

		$mod = isset( self::$theme_mods[$option->id] ) ? self::$theme_mods[$option->id] : false;

		if ( $mod ) {
			switch( $mod['setting']['sanitize_method'] ) {
				case 'checkbox'		: $_value = self::checkbox( $value, $mod ); break;
				case 'select'		: $_value = self::select( $value, $mod ); break;
				case 'radio'		: $_value = self::radio( $value, $mod ); break;
				case 'color'		: $_value = self::color( $value, $mod ); break;
				case 'url'			: $_value = self::url( $value, $mod ); break;
				case 'text'			: $_value = self::text( $value, $mod ); break;
				case 'number'		: $_value = self::number( $value, $mod ); break;
				case 'textarea'		: $_value = self::textarea( $value, $mod ); break;
			}
		}

		return $_value;

	}


	public static function theme_option( $value, $option ) {
		$keys = explode( '[', str_replace( ']', '', $option->id ) );
		$base = array_shift( $keys );
		$slug = array_shift( $keys );

		$_value = NULL;

		if ( !self::$theme_options )
			self::$theme_options = FastfoodOptions::get_coa();

		$coa = isset( self::$theme_options[$slug] ) ? self::$theme_options[$slug] : false;

		if ( $coa ) {
			switch( $coa['setting']['sanitize_method'] ) {
				case 'checkbox'		: $_value = self::checkbox( $value, $coa ); break;
				case 'select'		: $_value = self::select( $value, $coa ); break;
				case 'radio'		: $_value = self::radio( $value, $coa ); break;
				case 'color'		: $_value = self::color( $value, $coa ); break;
				case 'url'			: $_value = self::url( $value, $coa ); break;
				case 'text'			: $_value = self::text( $value, $coa ); break;
				case 'number'		: $_value = self::number( $value, $coa ); break;
				case 'textarea'		: $_value = self::textarea( $value, $coa ); break;
			}
		}

		return $_value;

	}


	/**
	 * checkbox
	*/
	public static function checkbox( $input, $option ) {

		if( $input ) {
			$input = 1;
		} else {
			$input = 0 ;
		}

		return $input;

	}

	/**
	 * select
	*/
	public static function select( $input, $option ) {

		if ( !array_key_exists( $input, $option['control']['choices'] ) )
			$input = $option['setting']['default'];

		return $input;

	}

	/**
	 * radio
	*/
	public static function radio( $input, $option ) {

		if ( !array_key_exists( $input, $option['control']['choices'] ) )
			$input = $option['setting']['default'];

		return $input;

	}

	/**
	 * color
	*/
	public static function color( $input, $option ) {

		$color = ltrim( $input, '#' );

		if ( '' === $color )
			return '';

		if ( in_array( $color, array( 'false', 'transparent' ) ) )
			return 'transparent';

		$color = '#' . $color;

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;

		return $option['setting']['default'];

	}

	/**
	 * url
	*/
	public static function url( $input, $option ) {

		if( $input ) {
			$input = esc_url( trim( wp_strip_all_tags( $input ) ) );
		} else {
			$input = '' ;
		}

		return $input;

	}

	/**
	 * text
	*/
	public static function text( $input, $option ) {

		if( $input ) {
			$input = trim( wp_strip_all_tags( $input ) );
		} else {
			$input = '' ;
		}

		return $input;

	}

	/**
	 * number
	*/
	public static function number( $input, $option ) {

		if ( $input === '' )
			$input = $option['setting']['default'];

		$input = (int) $input;

		if( isset( $option['control']['input_attrs']['min'] ) )
			$input = max( $input, $option['control']['input_attrs']['min'] );

		if( isset( $option['control']['input_attrs']['max'] ) )
			$input = min( $input, $option['control']['input_attrs']['max'] );

		return $input;

	}

	/**
	 * textarea
	*/
	public static function textarea( $input, $option ) {

		if( $input ) {
			$input = trim( wp_strip_all_tags( $input ) );
		} else {
			$input = '' ;
		}

		return $input;

	}

}
