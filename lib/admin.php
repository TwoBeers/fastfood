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
		add_action( 'admin_init'					, array( $this, 'default_options' ) );
		add_action( 'admin_head'					, array( $this, 'post_manage_style' ) );
		add_action( 'manage_posts_custom_column'	, array( $this, 'add_extra_value' ), 10, 2 );
		add_action( 'manage_pages_custom_column'	, array( $this, 'add_extra_value' ), 10, 2 );
		add_filter( 'manage_posts_columns'			, array( $this, 'add_extra_column' ) );
		add_filter( 'manage_pages_columns'			, array( $this, 'add_extra_column' ) );
		add_action( 'admin_notices'					, array( $this, 'setopt_admin_notice' ) );
		add_action( 'admin_menu'					, array( $this, 'create_menu' ) );
		add_action( 'admin_bar_menu'				, array( $this, 'admin_bar_plus' ), 999 );


		/* custom filters */
		add_filter( 'user_contactmethods'			, array( $this, 'new_contact_methods' ) );
		add_filter( 'avatar_defaults'				, array( $this, 'fastfood_addgravatar' ) );

	}


	/**
	 * Print the donation link
	 */
	public static function the_donation_link( $return = false ) {

		$output = '

			<div id="theme_donation">
				' . sprintf( __( '%s theme is created by %s.', 'fastfood' ), 'Fastfood', '<a target="_blank" href="http://www.twobeers.net/">TwoBeers</a>' ) . '
				<br />
				' . __( 'Our developers need coffee (and beer). How about a small donation?', 'fastfood' ) . '
				<br />
				<br />
				<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4MEDQV3CCZGC6">
					<img src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" alt="PayPal - The safer, easier way to pay online."/>
				</a>
			</div>

		';

		if ( $return )
			return $output;
		else
			echo $output;

	}

	/**
	 * Check and set default options 
	 */
	function default_options() {

			$the_coa = FastfoodOptions::get_coa();
			$the_opt = get_option( $this->option_name );

			// if options are empty, sets the default values
			if ( empty( $the_opt ) || !isset( $the_opt ) ) {

				foreach ( $the_coa as $key => $val ) {
					$the_opt[$key] = $the_coa[$key]['setting']['default'];
				}
				$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
				update_option( $this->option_name , $the_opt );

			} else if ( !isset( $the_opt['version'] ) || $the_opt['version'] < fastfood_get_info( 'version' ) ) {

				// check for unset values and set them to default value -> when updated to new version
				foreach ( $the_coa as $key => $val ) {
					if ( !isset( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['setting']['default'];
				}
				$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
				update_option( $this->option_name , $the_opt );

			}

	}


	/**
	 * Print a reminder message for set the options after the theme is installed or updated
	 */
	function setopt_admin_notice() {

		if ( current_user_can( 'manage_options' ) && ( FastfoodOptions::get_opt( 'version' ) < fastfood_get_info( 'version' ) ) ) {
			echo '<div class="updated"><p><strong>' . sprintf( __( "%s theme says: Dont forget to set <a href=\"%s\">my options</a>!", 'fastfood' ), 'Fastfood', get_admin_url() . 'themes.php?page=' . $this->option_name ) . '</strong></p></div>';
		}

	}


	/**
	 * the custon header style - called only on your theme options page
	 */
	function fastfood_theme_admin_styles() {

		wp_enqueue_style( 'fastfood-options', get_template_directory_uri() . '/css/options.css', array(), '', 'screen' );

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

		$input['version'] = fastfood_get_info( 'version' ); // keep version number

		return $input;

	}


	/**
	 * the options page
	 */
	function the_options_page() {

		if ( !current_user_can( 'edit_theme_options' ) ) wp_die( 'You do not have sufficient permissions to access this page.' );

		global $fastfood_opt;

		$the_hierarchy = FastfoodOptions::get_hierarchy( 'group' );

		if ( isset( $_GET['erase'] ) ) {
			$_SERVER['REQUEST_URI'] = remove_query_arg( 'erase', $_SERVER['REQUEST_URI'] );
			delete_option( $this->option_name );
			$this->default_options();
			$fastfood_opt = get_option( $this->option_name );
		}

		// update version value when admin visit options page
		if ( $fastfood_opt['version'] < fastfood_get_info( 'version' ) ) {
			$fastfood_opt['version'] = fastfood_get_info( 'version' );
			update_option( $this->option_name , $fastfood_opt );
		}

		$the_opt = $fastfood_opt;

		// options have been updated
		if ( isset( $_REQUEST['settings-updated'] ) ) {
			//return options save message
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'Options saved.','fastfood' ) . '</strong></p></div>';
		}

		// options to defaults done
		if ( isset( $_GET['erase'] ) ) {
			echo '<div id="message" class="updated fade"><p><strong>' . __( 'Defaults values loaded.', 'fastfood' ) . '</strong></p></div>';
		}
?>

	<div class="wrap" id="main-wrap">
		<h2><?php echo fastfood_get_info( 'current_theme' ) . ' - ' . __( 'Theme Options','fastfood' ); ?></h2>

		<h2 id="tabselector" class="nav-tab-wrapper hide-if-no-js">
			<?php foreach( $the_hierarchy as $key => $item ) { ?>
				<a id="selgroup-<?php echo $key; ?>" class="nav-tab" href="#" onClick="fastfoodOptions.switchTab('<?php echo $key; ?>'); return false;"><?php echo $item['label']; ?></a>
			<?php } ?>
			<a id="selgroup-info" class="nav-tab" href="#" onClick="fastfoodOptions.switchTab('info'); return false;"><?php _e( 'Theme Info' , 'fastfood' ); ?></a>
		</h2>

		<div id="theme-options">
			<form method="post" action="options.php">

			<?php
				settings_fields( 'theme_settings_group' );
				do_settings_sections( $this->option_name );
			?>

				<div id="buttons">
					<input type="hidden" name="<?php echo $this->option_name; ?>[hidden_opt]" value="default" />
					<input class="button button-primary" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'fastfood' ); ?>" />
					<a class="button" href="themes.php?page=<?php echo $this->option_name; ?>" target="_self"><?php _e( 'Undo Changes' , 'fastfood' ); ?></a>
					<a class="button" id="to-defaults" href="themes.php?page=<?php echo $this->option_name; ?>&erase=1" target="_self"><?php _e( 'Back to defaults' , 'fastfood' ); ?></a>
				</div>
			</form>
			<div id="theme_bottom">
				<small>
					<?php _e( 'If you like/dislike this theme, or if you encounter any issues using it, please let us know it.', 'fastfood' ); ?> <a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/tema-per-wordpress-fastfood' ); ?>" title="fastfood theme" target="_blank"><?php _e( 'Leave a feedback', 'fastfood' ); ?></a>
				</small>
				<br />
				-
				<br />
				<small>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/wp-themes/themes-translations-wordpress' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
			</div>
		</div>

		<div id="theme-infos">
			<?php locate_template( 'readme.html',true ); ?>
		</div>

		<?php self::the_donation_link(); ?>

	</div>

<?php

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
	 * Create the options page
	 */
	function create_menu() {

		$pageopt = add_theme_page(
			__( 'Theme Options','fastfood' ),
			__( 'Theme Options','fastfood' ),
			'edit_theme_options',
			$this->option_name,
			array( $this, 'the_options_page' )
		);

		add_action( 'admin_init'						, array( $this, 'register_settings' ) );
		add_action( 'admin_print_styles-' . $pageopt	, array( $this, 'admin_theme_options_style' ) );
		add_action( 'admin_print_scripts-' . $pageopt	, array( $this, 'admin_theme_options_script' ) );
		add_action( 'admin_print_styles-widgets.php'	, array( $this, 'admin_widgets_style' ) );
		add_action( 'admin_print_scripts-widgets.php'	, array( $this, 'admin_widgets_script' ) );
		add_action( 'admin_print_styles-nav-menus.php'	, array( $this, 'admin_menus_style' ) );

	}


	/**
	 * Register settings
	 */
	function register_settings() {

		register_setting(
			'theme_settings_group',
			$this->option_name,
			array( $this, 'sanitize_options' )
		);

		$the_hierarchy = FastfoodOptions::get_hierarchy();

		foreach( $the_hierarchy['section'] as $key => $section ) {
			add_settings_section(
				$section['parent'] . '|' . $key,												// Unique identifier for the settings section
				$section['label'],																// Section title (we don't want one)
				array( $this, 'add_group_container' ),											// Section callback (we don't want anything)
				$this->option_name																// Menu slug, used to uniquely identify the page; see add_page()
			);
		}

		foreach( $the_hierarchy['field'] as $key => $field ) {
			// Register our individual settings fields.
			add_settings_field(
				$key,																			// Unique identifier for the field for this section
				$field['label'],																// Section title (we don't want one)
				array( $this, 'render_field' ),													// Function that renders the settings field
				$this->option_name,																// Menu slug, used to uniquely identify the page; see add_page()
				$the_hierarchy['section'][$field['parent']]['parent'] . '|' . $field['parent'],	// Unique identifier for the settings section
				$field['options']																// Arguments that are passed to the $callback function
			);
		}

	}


	/**
	 * Function that renders the settings field
	 */
	function render_field( $options ) {

		global $fastfood_opt;
		$the_coa = FastfoodOptions::get_coa();

		foreach ( $options as $i => $key ) {

			if ( !$key ) {
				echo '<hr />';
				continue;
			}

			$the_coa[$key] = wp_parse_args( $the_coa[$key], array(
				'setting'			=> array(),
				'control'			=> array(),
			) );
			$the_coa[$key]['setting'] = wp_parse_args( $the_coa[$key]['setting'], array(
				'default'			=> '',
			) );
			$the_coa[$key]['control'] = wp_parse_args( $the_coa[$key]['control'], array(
				'render_type'		=> 'text',
				'label'				=> '',
				'description'		=> '',
				'input_attrs'		=> array(),
			) );

			$value = ( isset ( $fastfood_opt[$key] ) ) ? $fastfood_opt[$key] : $the_coa[$key]['setting']['default'];

			$id = 'theme-option-control-' . $key;
			$class = 'theme-option-control theme-option-control-' . $the_coa[$key]['control']['render_type'];

			$this->print_option( $the_coa[$key], $value, $key, '<div class="' . $class . '" id="' . $id . '">', '</div>' );

		}

	}


	/**
	 * Print the options field
	 */
	function print_option( $option, $value, $key, $before = '', $after = '' ) {

		$control_label = $option['control']['label'] ? '<span>' . $option['control']['label'] . '</span>' : '';
		$control_description = $option['control']['description'] ? '<p class="description">' . $option['control']['description'] . '</p>' : '';
		$control_attributes = '';
		foreach( $option['control']['input_attrs'] as $attr_key => $attr_value )
			$control_attributes .= $attr_key . '="' . esc_attr( $attr_value ) . '" ';

		echo $before;

		switch ( $option['control']['render_type'] ) {
			case 'checkbox':
				?>
					<label>
						<input type="checkbox" name="<?php echo $this->option_name; ?>[<?php echo $key; ?>]" value="1" <?php checked( 1 , $value ); ?> />
						<?php echo $option['control']['label']; ?>
					</label>
					<?php echo $control_description; ?>
				<?php
				break;
			case 'select':
				?>
					<label>
						<select name="<?php echo $this->option_name; ?>[<?php echo $key; ?>]">
						<?php foreach( $option['control']['choices'] as $optionkey => $optionval ) { ?>
							<option value="<?php echo $optionkey; ?>" <?php selected( $value, $optionkey ); ?>><?php echo $optionval; ?></option>
						<?php } ?>
						</select>
					</label>
					<?php echo $control_label; ?>
					<?php echo $control_description; ?>
				<?php
				break;
			case 'radio':
			case 'smart_radio':
				?>
					<?php echo $control_label; ?>
					<?php foreach( $option['control']['choices'] as $optionkey => $optionval ) { ?>
						<label title="<?php echo $optionkey; ?>">
							<input name="<?php echo $this->option_name; ?>[<?php echo $key; ?>]" type="radio" <?php checked( $value, $optionkey ); ?> value="<?php echo $optionkey; ?>">
							<span><?php echo $optionval; ?></span>
						</label>
					<?php } ?>
					<?php echo $control_description; ?>
				<?php
				break;
			case 'color':
				?>
					<label>
						<input name="<?php echo $this->option_name; ?>[<?php echo $key; ?>]" class="theme_option_input fastfood_cp" type="text" value="<?php echo $value; ?>" data-default-color="<?php echo $option['setting']['default']; ?>" />
						<span class="description hide-if-js"><?php _e( 'Default' , 'fastfood' ); ?>: <?php echo $option['setting']['default']; ?></span>
					</label>
					<?php echo $control_label; ?>
					<?php echo $control_description; ?>
				<?php
				break;
			case 'textarea':
				?>
					<label>
						<textarea name="<?php echo $this->option_name; ?>[<?php echo $key; ?>]"><?php echo $value; ?></textarea>
					</label>
					<?php echo $control_label; ?>
					<?php echo $control_description; ?>
				<?php
				break;
			case 'number':
			case 'slider':
				?>
					<label>
						<input name="<?php echo $this->option_name; ?>[<?php echo $key; ?>]" <?php echo $control_attributes; ?>type="number" value="<?php echo $value; ?>" />
					</label>
					<?php echo $control_label; ?>
					<?php echo $control_description; ?>
				<?php
				break;
			default:
				?>
					<label>
						<input name="<?php echo $this->option_name; ?>[<?php echo $key; ?>]" <?php echo $control_attributes; ?>type="<?php echo $option['control']['render_type']; ?>" value="<?php echo $value; ?>" />
					</label>
					<?php echo $control_label; ?>
					<?php echo $control_description; ?>
				<?php
				break;
		}

		echo $after;

	}


	/**
	 * HERE GOES SOMETHING
	 */
	function add_group_container( $arg ) {
		$the_hierarchy = FastfoodOptions::get_hierarchy();
		$classes = explode( '|', $arg['id'] );
		$description = isset( $the_hierarchy['section'][$classes[1]]['description'] ) ? $the_hierarchy['section'][$classes[1]]['description'] : '';
		echo '<div class="group-' . esc_attr( $classes[0] ) . ' section-' . esc_attr( $classes[1] ) . '"><p class="description">'.$description.'</p></div>';
	}


	/**
	 * add links to admin bar
	 */
	function admin_bar_plus() {
		global $wp_admin_bar;
		if ( !current_user_can( 'edit_theme_options' ) || !is_admin_bar_showing() )
			return;
		$add_menu_meta = array(
			'target'	=> '_blank'
		);
		$wp_admin_bar->add_menu( array(
			'id'		=> 'ff_theme_options',
			'parent'	=> 'appearance',
			'title'		=> __( 'Theme Options', 'fastfood' ),
			'href'		=> get_admin_url() . 'themes.php?page=' . $this->option_name,
			'meta'		=> $add_menu_meta
		) );
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
	 * Add custom stylesheet for options page
	 */
	function admin_theme_options_style() {

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'fastfood-options', get_template_directory_uri() . '/css/admin-options.css', false, '', 'screen' );

	}


	/**
	 * Add custom script for options page
	 */
	function admin_theme_options_script() {

		wp_enqueue_script( 'fastfood-options', get_template_directory_uri().'/js/admin-options.js',array('jquery','thickbox','wp-color-picker'),fastfood_get_info( 'version' ), true ); //thebird js

		$data = array(
			'confirm_to_defaults' => esc_js( __( 'Are you really sure you want to set all the options to their default values?', 'fastfood' ) )
		);
		wp_localize_script( 'fastfood-options', 'fastfood_l10n', $data );

	}


	/**
	 * Add custom stylesheet for widgets page
	 */
	function admin_widgets_style() {

		wp_enqueue_style( 'fastfood-widgets-css', get_template_directory_uri() . '/css/admin-widgets.css', false, '', 'screen' );

	}


	/**
	 * Add custom script for widgets page
	 */
	function admin_widgets_script() {

		wp_enqueue_script( 'fastfood-widgets-js', get_template_directory_uri() . '/js/admin-widgets.js', array('jquery'), fastfood_get_info( 'version' ), true );

	}


	/**
	 * Add custom stylesheet for menus page
	 */
	function admin_menus_style() {

		//nop

	}

}

new FastfoodAdmin;