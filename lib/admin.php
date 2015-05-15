<?php
/**
 * admin.php
 *
 * admin stuff (options,settings,etc).
 *
 * @package fastfood
 * @since fastfood 0.30
 */


class FastfoodAdmin {

	//holds the option name
	var $option_name = 'fastfood_options';


	/**
	 * Constructor
	 */
	function __construct() {

		/* custom actions */
		add_action( 'admin_head'						, array( $this, 'post_manage_style' ) );
		add_action( 'manage_posts_custom_column'		, array( $this, 'add_extra_value' ), 10, 2 );
		add_action( 'manage_pages_custom_column'		, array( $this, 'add_extra_value' ), 10, 2 );
		add_filter( 'manage_posts_columns'				, array( $this, 'add_extra_column' ) );
		add_filter( 'manage_pages_columns'				, array( $this, 'add_extra_column' ) );
		add_action( 'admin_notices'						, array( $this, 'setopt_admin_notice' ) );
		add_action( 'admin_print_styles-widgets.php'	, array( $this, 'admin_widgets_style' ) );
		add_action( 'admin_print_scripts-widgets.php'	, array( $this, 'admin_widgets_script' ) );
		add_action( 'admin_print_styles-nav-menus.php'	, array( $this, 'admin_menus_style' ) );


		/* custom filters */
		add_filter( 'user_contactmethods'			, array( $this, 'new_contact_methods' ) );
		add_filter( 'avatar_defaults'				, array( $this, 'add_gravatar' ) );

	}


	/**
	 * Print the donation link
	 */
	public static function the_donation_link( $return = false ) {

		$output = '

			<p id="theme_donation">
				' . sprintf( __( '%1$s theme is created by %2$s.', 'fastfood' ), 'Fastfood', '<a target="_blank" href="http://www.twobeers.net/">TwoBeers</a>' ) . '
				<br />
				' . __( 'Our developers need coffee (and beer). How about a small donation?', 'fastfood' ) . '
				<br />
				<br />
				<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4MEDQV3CCZGC6">
					<img src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" alt="PayPal - The safer, easier way to pay online."/>
				</a>
			</p>

		';

		if ( $return )
			return $output;
		else
			echo $output;

	}


	/**
	 * Print a reminder message for set the options after the theme is installed or updated
	 */
	function setopt_admin_notice() {

		$screen = get_current_screen();

		if ( current_user_can( 'manage_options' ) && ( $screen->id !== 'appearance_page_fastfood_options' ) && version_compare( FastfoodOptions::get_opt( 'version', '0' ), fastfood_get_info( 'version' ), '<' ) ) {
			echo '<div class="update-nag"><strong>' . sprintf( __( '%1$s theme says: Dont forget to set <a href="%2$s">my options</a>!', 'fastfood' ), 'Fastfood', get_admin_url() . 'themes.php?page=' . $this->option_name ) . '</strong></div>';
		}

	}


	/**
	 * sanitize options value
	 */
	function sanitize_options( $input ) {

		$the_coa = FastfoodOptions::get_coa();

		foreach ( $the_coa as $key => $val ) {

			$_value = NULL;

			if( !isset( $input[$key] ) ) $input[$key] = NULL;

			switch( $the_coa[$key]['setting']['sanitize_method'] ) {
				case 'checkbox'		: $_value = FastfoodSanitize::checkbox( $input[$key], $the_coa[$key] ); break;
				case 'select'		: $_value = FastfoodSanitize::select( $input[$key], $the_coa[$key] ); break;
				case 'radio'		: $_value = FastfoodSanitize::radio( $input[$key], $the_coa[$key] ); break;
				case 'color'		: $_value = FastfoodSanitize::color( $input[$key], $the_coa[$key] ); break;
				case 'url'			: $_value = FastfoodSanitize::url( $input[$key], $the_coa[$key] ); break;
				case 'text'			: $_value = FastfoodSanitize::text( $input[$key], $the_coa[$key] ); break;
				case 'number'		: $_value = FastfoodSanitize::number( $input[$key], $the_coa[$key] ); break;
				case 'textarea'		: $_value = FastfoodSanitize::textarea( $input[$key], $the_coa[$key] ); break;
			}

			$input[$key] = $_value;
		}

		return $input;

	}


	/**
	 * Add new contact methods to author panel
	 */
	function new_contact_methods( $contactmethods ) {

		//add Twitter
		$contactmethods['twitter'] = 'Twitter';

		//add Facebook
		$contactmethods['facebook'] = 'Facebook';

		//add Google+
		$contactmethods['googleplus'] = 'Google+';

		return $contactmethods;

	}


	/**
	 * Add Thumbnail Column in Manage Posts/Pages List
	 */
	function add_extra_column( $cols ) {

		$cols['id'] = ucwords( 'ID' );
		$cols['thumbnail'] = ucwords( __( 'thumbnail', 'fastfood' ) );
		return $cols;

	}


	/**
	 * Add Thumbnails in Manage Posts/Pages List
	 */
	function add_extra_value( $column_name, $post_id ) {

		$width = (int) 60;
		$height = (int) 60;

		if ( 'thumbnail' == $column_name ) {
			// thumbnail of WP 2.9
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
			if ($thumbnail_id) $thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
			if ( isset($thumb) && $thumb ) {
				echo $thumb;
			} else {
				echo '';
			}
		}

		if ( 'id' == $column_name ) {
			echo $post_id;
		}

	}


	/**
	 * Add Thumbnail Column style in Manage Posts/Pages List
	 */
	function post_manage_style(){

	?>
		<style type="text/css">
		.fixed .column-thumbnail {
			width: 70px;
		}
		.fixed .column-id {
			width: 50px;
		}
		</style>
	<?php

	}


	/**
	 * add a default gravatar
	 */
	function add_gravatar( $avatar_defaults ) {

		$myavatar = get_template_directory_uri() . '/images/user.png';
		$avatar_defaults[$myavatar] = __( 'Fastfood Default Gravatar', 'fastfood' );

		return $avatar_defaults;

	}


	/**
	 * Add custom stylesheet for widgets page
	 */
	function admin_widgets_style() {

		wp_enqueue_style(
			'fastfood-widgets',
			sprintf( '%1$s/css/widgets.css' , get_template_directory_uri() ),
			false,
			'',
			'screen'
		);

	}


	/**
	 * Add custom script for widgets page
	 */
	function admin_widgets_script() {

		wp_enqueue_script(
			'fastfood-widgets',
			sprintf( '%1$s/js/widgets.js' , get_template_directory_uri() ),
			array( 'jquery' ),
			fastfood_get_info( 'version' ),
			true
		);

	}


	/**
	 * Add custom stylesheet for menus page
	 */
	function admin_menus_style() {

		//nop

	}

}

new FastfoodAdmin;