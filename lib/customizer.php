<?php
/**
 * Fastfood Customizer functionality
 *
 * @since Fastfood 0.37
 */


class FastfoodCustomizer {

	/**
	 * Holds the theme options
	 * @access protected
	 * @var array
	 */
	protected $theme_options = array();

	/**
	 * Holds the theme mods
	 * @access protected
	 * @var array
	 */
	protected $theme_mods = array();

	/**
	 * Holds the theme options hierarchy
	 * @access protected
	 * @var array
	 */
	protected $options_hierarchy = array();

	/**
	 * Holds the theme options hierarchy
	 * @access protected
	 * @var array
	 */
	protected $controls_headers = array();

	/**
	 * Holds transport mode
	 * @access protected
	 * @var mixed
	 */
	protected $force_refresh = false;


	/**
	 * Pairs the render_type property to its control class
	 * @access protected
	 * @var array
	 */
	protected $render_type = array(
		'smart_radio'	=> 'Fastfood_Customize_Smart_Radio_Control',
		'slider'		=> 'Fastfood_Customize_Slider_Control',
		'color'			=> 'WP_Customize_Color_Control',
		'tl_checkbox'	=> 'Fastfood_Customize_Checkbox_Control',
		'background'	=> 'Fastfood_Customize_Background_Image_Control',
	);

	/**
	 * Constructor
	 */
	function __construct() {

		add_action( 'customize_register'						, array( $this, 'init' ) );
		add_action( 'customize_register'						, array( $this, 'add_theme_mods_controls' ), 99 );
		add_action( 'customize_register'						, array( $this, 'add_theme_options_controls' ), 99 );
		add_action( 'customize_controls_print_styles'			, array( $this, 'customize_stylesheet' ) );
		add_action( 'customize_controls_print_footer_scripts'	, array( $this, 'dynamic_css_template' ) );
		add_action( 'customize_controls_print_footer_scripts'	, array( $this, 'print_js_templates' ) );
		add_action( 'customize_controls_enqueue_scripts'		, array( $this, 'customize_control_js' ) );
		add_action( 'customize_preview_init'					, array( $this, 'customize_preview_js' ) );
		add_action( 'customize_save_after'						, array( $this, 'update_version' ) );

	}


	/**
	 * Initialize the class
	 *
	 * @since Fastfood 0.37
	 */
	function init() {

		$this->theme_options       = FastfoodOptions::get_coa();
		$this->theme_mods          = fastfood_register_theme_mods();
		$this->options_hierarchy   = FastfoodOptions::get_hierarchy();

		$this->prefix_global       = 'fastfood_options';
		$this->prefix_panel        = $this->prefix_global . '_panel_';
		$this->prefix_section      = $this->prefix_global . '_section_';

		if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<' ) )
			$this->force_refresh   = true;

	}


	/**
	 * Add new controls to the Customizer. (theme mods)
	 *
	 * @since Fastfood 0.37
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	function add_theme_mods_controls( $wp_customize ) {

		$wp_customize->get_section( 'header_image' )->description = __( 'If you changed the body width or the header height, press "save and publish" before adding a new image, otherwise it will be incorrectly cropped', 'fastfood' );

		$wp_customize->get_section( 'background_image' )->title = __( 'Background', 'fastfood' );

		foreach( $this->theme_mods as $m_key => $mod ) {

			$mod = wp_parse_args( $mod, array(
				'setting'			=> array(),
				'control'			=> array(),
			) );
			$mod['setting'] = wp_parse_args( $mod['setting'], array(
				'type'				=> 'theme_mod',
			) );
			$mod['control'] = wp_parse_args( $mod['control'], array(
				'require'			=> array(),
				'render_type'		=> 'text',
			) );

			if ( $mod['control']['require'] )
				$mod['control']['active_callback'] = array( $this, 'control_visibility' );

			if ( $this->force_refresh )
				$mod['setting']['transport'] = 'refresh';

			if ( $wp_customize->get_setting( $m_key ) ) {
				foreach( $mod['setting'] as $p_key => $property ) {
					$wp_customize->get_setting( $m_key )->$p_key = $property;
				}
			} else {
				$wp_customize->add_setting( $m_key, $mod['setting'] );
			}

			if ( isset( $this->render_type[$mod['control']['render_type']] ) ) {
				if ( $wp_customize->get_control( $m_key ) )
					$wp_customize->remove_control( $m_key );
				$wp_customize->add_control( new $this->render_type[$mod['control']['render_type']]( $wp_customize, $m_key, $mod['control'] ) );
			} else {
				if ( $wp_customize->get_control( $m_key ) ) {
					foreach( $mod['control'] as $p_key => $property ) {
						$wp_customize->get_control( $m_key )->$p_key = $property;
					}
				} else {
					$wp_customize->add_control( $m_key, $mod['control'] );
				}
			}
		}

	}


	/**
	 * Add new controls to the Customizer (theme options).
	 *
	 * @since Fastfood 0.37
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 */
	function add_theme_options_controls( $wp_customize ) {

		$section_count = 0;

		foreach( $this->options_hierarchy['group'] as $g_key => $group ) {

			$wp_customize->add_panel( $this->prefix_panel . $g_key, array(
				'priority'			=> 10,
				'title'				=> $group['label'],
				'type'				=> $this->prefix_global,
			) );

		}

		foreach( $this->options_hierarchy['field'] as $f_key => $field ) {

			$section_count++;

			$wp_customize->add_section( $this->prefix_section . $f_key, array(
				'priority'			=> $section_count,
				'title'				=> $field['label'],
				'description'		=> $field['description'],
				'type'				=> $field['parent'],
				'panel'				=> $this->prefix_panel . $this->options_hierarchy['section'][$field['parent']]['parent'],
				'active_callback'	=> isset( $field['active_callback'] ) ? $field['active_callback'] : '',
			) );

			if ( !array_key_exists( $field['parent'], $this->controls_headers ) )
				$this->controls_headers[$field['parent']] = array(
					'label' => $this->options_hierarchy['section'][$field['parent']]['label'],
					'description' => $this->options_hierarchy['section'][$field['parent']]['description'],
				);

			foreach( $field['options'] as $o_key ) {

				if ( !$o_key ) continue;

				$option = wp_parse_args( $this->theme_options[$o_key], array(
					'setting'			=> array(),
					'control'			=> array(),
				) );
				$option['setting'] = wp_parse_args( $option['setting'], array(
					'type'				=> 'option',
					'sanitize_callback'	=> 'FastfoodSanitize::theme_option',
				) );
				$option['control'] = wp_parse_args( $option['control'], array(
					'section'			=> $this->prefix_section . $f_key,
					'require'			=> array(),
					'render_type'		=> 'text',
				) );

				if ( $option['control']['require'] )
					$option['control']['active_callback'] = array( $this, 'control_visibility' );

				if ( $this->force_refresh )
					$option['setting']['transport'] = 'refresh';

				$wp_customize->add_setting( 'fastfood_options[' . $o_key . ']', $option['setting'] );

				if ( isset( $this->render_type[$option['control']['render_type']] ) )
					$wp_customize->add_control( new $this->render_type[$option['control']['render_type']]( $wp_customize, 'fastfood_options[' . $o_key . ']', $option['control'] ) );
				else
					$wp_customize->add_control( 'fastfood_options[' . $o_key . ']', $option['control'] );

			}

		}

	}


	/**
	 * Checks the requirements of a control and returns its visibility accordingly
	 *
	 * @since Fastfood 0.37
	 *
	 * @param WP_Customize_Control $wp_control WP_Customize_Control instance.
	 */
	function control_visibility( $wp_control ) {

		preg_match_all( '/fastfood_options\[(.+)\]/',$wp_control->id, $keys );

		if ( !isset( $keys[1][0] ) )
			$option = isset( $this->theme_mods[$wp_control->id] ) ? $this->theme_mods[$wp_control->id] : false;
		else
			$option = isset( $this->theme_options[$keys[1][0]] ) ? $this->theme_options[$keys[1][0]] : false;

		if ( !$option || !isset( $option['control']['require'] ) || !$option['control']['require'] )
			return true;

		foreach( (array)$option['control']['require'] as $option ) {

			preg_match_all( '/fastfood_options\[(.+)\]/',$option, $o_keys );

			if ( isset( $o_keys[1][0] ) && !FastfoodOptions::get_opt( $o_keys[1][0] ) )
				return false;
			elseif ( !isset( $o_keys[1][0] ) && !get_theme_mod( $option ) )
				return false;

		}

		return true;

	}


	/**
	 * Output an Underscore template for generating custom CSS.
	 *
	 * The template generates the css dynamically for instant display in the Customizer
	 * preview.
	 *
	 * @since Fastfood 0.37
	 */
	function dynamic_css_template() {

		$attributes = array(
			'background_color'				=> '{{ data.background_color }}',
			'background_repeat'				=> '{{ data.background_repeat }}',
			'background_attachment'			=> '{{ data.background_attachment }}',
			'background_position_x'			=> '{{ data.background_position_x }}',
			'background_position_y'			=> '{{ data.background_position_y }}',
			'background_image'				=> '{{ data.background_image }}',
			'background_icons_color'		=> '{{ data.background_icons_color }}',
			'fastfood_colors_link'			=> '{{ data.fastfood_colors_link }}',
			'fastfood_colors_link_hover'	=> '{{ data.fastfood_colors_link_hover }}',
			'fastfood_colors_link_sel'		=> '{{ data.fastfood_colors_link_sel }}',
			'fastfood_body_width'			=> '{{ data.fastfood_body_width }}',
			'fastfood_rsideb_width'			=> '{{ data.fastfood_rsideb_width }}',
			'fastfood_content_width'		=> '{{ data.fastfood_content_width }}',
			'fastfood_featured_title_size'	=> '{{ data.fastfood_featured_title_size }}',
		);
		?>

		<script type="text/html" id="tmpl-fastfood-dynamic-css">
			<?php echo fastfood_get_dynamic_css( $attributes ); ?>
		</script>

		<?php

	}


	/**
	 * This function adds some styles to the WordPress Customizer
	 * 
	 * @since Fastfood 0.37
	 */
	function customize_stylesheet() {

		wp_enqueue_style( 'fastfood-customizer',
			sprintf( '%1$s/css/customizer.css' , get_template_directory_uri() ),
			false,
			fastfood_get_info( 'version' ),
			'all'
		);

	}


	/**
	 * Binds JS listener to Customizer controls.
	 *
	 * Passes background scheme data as backgroundScheme global.
	 *
	 * @since Fastfood 0.37
	 */
	function customize_control_js() {

		wp_enqueue_script(
			'fastfood-customize-controls',
			sprintf( '%1$s/js/customize-controls.js' , get_template_directory_uri() ),
			array( 'customize-controls', 'iris', 'underscore', 'wp-util', 'jquery', 'jquery-ui-slider', 'jquery-ui-accordion' ),
			fastfood_get_info( 'version' ),
			true
		);

		apply_filters( 'fastfood_customize_controls_js_data', $data = array(
			'backgroundScheme' => fastfood_get_background_schemes(),
			'controls' => array(
				'background_color'				=> 'background_color',
				'background_repeat'				=> 'background_repeat',
				'background_attachment'			=> 'background_attachment',
				'background_position_x'			=> 'background_position_x',
				'background_position_y'			=> 'background_position_y',
				'background_image'				=> 'background_image',
				'background_icons_color'		=> 'background_icons_color',
				'fastfood_colors_link'			=> 'fastfood_options[fastfood_colors_link]',
				'fastfood_colors_link_hover'	=> 'fastfood_options[fastfood_colors_link_hover]',
				'fastfood_colors_link_sel'		=> 'fastfood_options[fastfood_colors_link_sel]',
				'fastfood_body_width'			=> 'fastfood_options[fastfood_body_width]',
				'fastfood_rsideb_width'			=> 'fastfood_options[fastfood_rsideb_width]',
				'fastfood_featured_title_size'	=> 'fastfood_options[fastfood_featured_title_size]',
			),
			'headers' => $this->controls_headers,
			'labels' => array(
				'remove'			=> __( 'Remove', 'fastfood' ),
			),
		) );

		wp_localize_script(
			'fastfood-customize-controls',
			'_fastfoodCustomizeControls',
			$data
		);

	}


	/**
	 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
	 * Refresh the global variable $fastfood_opt for getting the filtered options.
	 *
	 * @since Fastfood 0.37
	 */
	function customize_preview_js() {
		global $fastfood_opt;

		$fastfood_opt = get_option( 'fastfood_options' );

		if ( $this->force_refresh ) return;

		wp_enqueue_script(
			'fastfood-customize-preview',
			sprintf( '%1$s/js/customize-preview.js' , get_template_directory_uri() ),
			array( 'jquery', 'customize-preview' ),
			fastfood_get_info( 'version' ),
			true
		);
		wp_localize_script(
			'fastfood-customize-preview',
			'_fastfoodCustomizePreview',
			apply_filters( 'fastfood_customize_preview_js_data', array() )
		);

	}


	/**
	 * Renders the underscore templates.
	 *
	 * @since Fastfood 0.37
	 */
	function print_js_templates() {

		?>

			<script type="text/template" id="tmpl-customize-theme-header">
				<div id="customize-theme-header" class="theme-controls-header ready can-expand">
					<div class="theme-controls-header-title" data-panels="control-panel-fastfood_options"><span class="theme-logo"></span><?php printf( __( '%s theme', 'fastfood' ), '<strong>Fastfood</strong>' ); ?></div>
					<div class="theme-controls-header-description"><?php FastfoodAdmin::the_donation_link(); ?></div>
				</div>
			</script>

		<?php

	}


	/**
	 * Updates the version number in theme options
	 *
	 * @since Fastfood 0.37
	 */
	function update_version( $wp_customize_manager ) {

		$options = get_option( 'fastfood_options' );
		$options['version'] = fastfood_get_info( 'version' );
		update_option( $this->prefix_global , $options );

	}

}

new FastfoodCustomizer;


/**
 * Custom Controls for Fastfood.
 *
 * @since Fastfood 0.37
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * The "image radio" custom control
	 */
	class Fastfood_Customize_Smart_Radio_Control extends WP_Customize_Control {

		public function render() {
			$id		= 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
			$class	= 'customize-control customize-control-' . $this->type . ' customize-control-smart-radio';

			?><li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
				<?php $this->render_content(); ?>
			</li><?php
		}

		public function render_content() {

			if ( empty( $this->choices ) )
				return;

			$name = '_customize-radio-' . $this->id;

			if ( !empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif;
			if ( !empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description ; ?></span>
			<?php endif;

			foreach ( $this->choices as $value => $label ) :
				?>
				<label title="<?php echo esc_attr( $value ); ?>">
					<input class="image-radio" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); ?> />
					<?php
						echo wp_kses( $label, array(
							'span'	=> array(
								'id'	=> array(),
								'class'	=> array(),
							),
							'img'	=> array(
								'id'	=> array(),
								'class'	=> array(),
								'src'	=> array(),
								'alt'	=> array(),
							),
						) );
					?>
				</label>
				<?php
			endforeach;

		}

	}

	/**
	 * The "top level checkbox" custom control
	 */
	class Fastfood_Customize_Checkbox_Control extends WP_Customize_Control {

		public function render_content() {
			?>

			<?php if ( !empty( $this->label ) ) : ?>
				<span class="customize-control-title customize-control-top-level-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<label>
				<input type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
				<?php echo esc_html( $this->description ); ?>
			</label>

			<?php
		}

	}

	/**
	 * The custom background image control (supports predefined images)
	 */
	class Fastfood_Customize_Background_Image_Control extends WP_Customize_Background_Image_Control {

		/**
		 * Refresh the parameters passed to the JavaScript via JSON.
		 *
		 * @since 3.4.0
		 * @uses WP_Customize_Control::to_json()
		 */
		public function to_json() {
			parent::to_json();

			$value = $this->value();

			if ( is_object( $this->setting ) ) {

				$current_image = array(
					'id' => 1,
					'url' => $value,
					'type' => 'image',
					'icon' => wp_mime_type_icon( 'image' ),
					'title' => basename( $value ),
					'sizes' => array( 'full' => array( 'url' => $value ) ),
				);

				if ( $value ) {
					// Get the attachment model for the existing file.
					$attachment_id = attachment_url_to_postid( $value );
					if ( $attachment_id ) {
						$this->json['attachment'] = wp_prepare_attachment_for_js( $attachment_id );
					} else {
						$this->json['attachment'] = $current_image;
					}
				}
			}
		}

	}

	/**
	 * The "slider" custom control
	 */
	class Fastfood_Customize_Slider_Control extends WP_Customize_Control {
		public $type = 'tbslider';
		public $step = '';
		public $min = '';
		public $max = '';
		public $unit = 'px';

		public function render_content() {

			$this->step	= isset( $this->input_attrs['step'] ) ? $this->input_attrs['step'] : $this->step;
			$this->min	= isset( $this->input_attrs['min'] ) ? $this->input_attrs['min'] : $this->min;
			$this->max	= isset( $this->input_attrs['max'] ) ? $this->input_attrs['max'] : $this->max;
			$this->unit = isset( $this->input_attrs['unit'] ) ? $this->input_attrs['unit'] : $this->unit;

			?>

			<span class="customize-control-title">
				<?php if ( !empty( $this->label ) ) : ?>
					<span class="slider-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<div class="slider-display">
					<span class="slider-value">
						
					</span>
					<a class="slider-reset button button-small" href="#"><?php _e( 'Reset', 'fastfood' ); ?></a>
				</div>
			</span>

			<div class="slider-bar" 
				data-default-value="<?php echo absint( $this->setting->default ); ?>" 
				data-step="<?php echo absint( $this->step ); ?>" 
				data-unit="<?php echo esc_attr( $this->unit ); ?>" 
				data-min-range="<?php echo absint( $this->min ); ?>" 
				data-max-range="<?php echo absint( $this->max ); ?>">
			</div>

			<?php if ( !empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo $this->description ; ?></span>
			<?php endif; ?>

			<input class="slider-amount" type="hidden" value="<?php echo absint( $this->value() ); ?>" <?php $this->link(); ?> />

			<?php
		}

	}

}


/**
 * Register the mods for customizer
 *
 * @since Fastfood 0.37
 *
 * @return array an associative array of theme mods.
 */
function fastfood_register_theme_mods() {

	$default_background_scheme = fastfood_get_background_schemes( '0' );

	return apply_filters( 'fastfood_register_theme_mods', array(

		'display_header_slider' => array(
					'setting'	=> array(
						'default'			=> 0,
						'sanitize_callback'	=> 'FastfoodSanitize::theme_mod',
						'sanitize_method'	=> 'checkbox',
						'transport'			=> 'refresh',
					),
					'control'	=> array(
						'type'				=> 'checkbox',
						'render_type'		=> 'tl_checkbox',
						'label'				=> __( 'Slider', 'fastfood' ),
						'description'		=> __( 'create slideshow using the uploaded images', 'fastfood' ),
						'section'			=> 'header_image',
						'priority'			=> 10,
						'require'			=> array(
							'fastfood_options[fastfood_jsani]',
						),
					),
		),
		'header_slider_speed' => array(
					'setting'	=> array(
						'default'			=> 2000,
						'sanitize_callback'	=> 'FastfoodSanitize::theme_mod',
						'sanitize_method'	=> 'number',
						'transport'			=> 'refresh',
					),
					'control'	=> array(
						'type'				=> 'number',
						'render_type'		=> 'number',
						'label'				=> __( 'speed', 'fastfood' ),
						'description'		=> __( 'speed of transition (ms)', 'fastfood' ),
						'section'			=> 'header_image',
						'priority'			=> 11,
						'require'			=> array(
							'fastfood_options[fastfood_jsani]',
							'display_header_slider',
						),
					),
		),
		'header_slider_pause' => array(
					'setting'	=> array(
						'default'			=> 3000,
						'sanitize_callback'	=> 'FastfoodSanitize::theme_mod',
						'sanitize_method'	=> 'number',
						'transport'			=> 'refresh',
					),
					'control'	=> array(
						'type'				=> 'number',
						'render_type'		=> 'number',
						'label'				=> __( 'pause', 'fastfood' ),
						'description'		=> __( 'pause among transitions (ms)', 'fastfood' ),
						'section'			=> 'header_image',
						'priority'			=> 12,
						'require'			=> array(
							'fastfood_options[fastfood_jsani]',
							'display_header_slider',
						),
					),
		),
		'blogname' => array(
					'setting'	=> array(
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'render_type'		=> 'text',
					),
		),
		'blogdescription' => array(
					'setting'	=> array(
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'render_type'		=> 'text',
					),
		),
		'header_textcolor' => array(
					'setting'	=> array(
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'render_type'		=> 'checkbox',
						'section'			=> 'title_tagline',
					),
		),
		'header_text_background' => array(
					'setting'	=> array(
						'default'			=> '',
						'sanitize_callback'	=> 'FastfoodSanitize::theme_mod',
						'sanitize_method'	=> 'color',
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'render_type'		=> 'color',
						'label'				=> __( 'Header Background Color', 'fastfood' ),
						'section'			=> 'title_tagline',
						'priority'			=> 23,
					),
		),
		'background_scheme' => array(
					'setting'	=> array(
						'default'			=> 'default',
						'sanitize_callback'	=> '__return_zero',
						'sanitize_method'	=> 'radio',
						'theme_supports'	=> 'custom-background',
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'type'				=> 'radio',
						'render_type'		=> 'smart_radio',
						'label'				=> __( 'Suggested Schemes', 'fastfood' ),
						'section'			=> 'background_image',
						'choices'			=> fastfood_get_background_schemes_thumbnails(),
						'priority'			=> 9,
					),
		),
		'background_color' => array(
					'setting'	=> array(
						'default'			=> $default_background_scheme['attributes']['background_color'],
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'render_type'		=> 'color',
						'section'			=> 'background_image',
						'priority'			=> 11,
					),
		),
		'background_image' => array(
					'setting'	=> array(
						'default'			=> $default_background_scheme['attributes']['background_image'],
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'render_type'		=> 'background',
						'section'			=> 'background_image',
						'priority'			=> 10,
					),
		),
		'background_repeat' => array(
					'setting'	=> array(
						'default'			=> $default_background_scheme['attributes']['background_repeat'],
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'type'				=> 'select',
						'render_type'		=> 'select',
						'priority'			=> 12,
					),
		),
		'background_attachment' => array(
					'setting'	=> array(
						'default'			=> $default_background_scheme['attributes']['background_attachment'],
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'type'				=> 'select',
						'render_type'		=> 'select',
						'priority'			=> 13,
					),
		),
		'background_position_x' => array(
					'setting'	=> array(
						'default'			=> $default_background_scheme['attributes']['background_position_x'],
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'type'				=> 'select',
						'render_type'		=> 'select',
						'label'				=> __( 'Horizontal Position', 'fastfood' ),
						'priority'			=> 14,
					),
		),
		'background_position_y' => array(
					'setting'	=> array(
						'default'			=> $default_background_scheme['attributes']['background_position_y'],
						'sanitize_callback'	=> 'FastfoodSanitize::theme_mod',
						'sanitize_method'	=> 'select',
						'theme_supports'	=> 'custom-background',
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'type'				=> 'select',
						'render_type'		=> 'select',
						'label'				=> __( 'Vertical Position', 'fastfood' ),
						'section'			=> 'background_image',
						'choices'			=> array(
							'top'				=> __( 'Top', 'fastfood' ),
							'center'			=> __( 'Center', 'fastfood' ),
							'bottom'			=> __( 'Bottom', 'fastfood' ),
						),
						'priority'			=> 15,
					),
		),
		'background_icons_color' => array(
					'setting'	=> array(
						'default'			=> $default_background_scheme['attributes']['background_icons_color'],
						'sanitize_callback'	=> 'FastfoodSanitize::theme_mod',
						'sanitize_method'	=> 'color',
						'theme_supports'	=> 'custom-background',
						'transport'			=> 'postMessage',
					),
					'control'	=> array(
						'render_type'		=> 'color',
						'label'				=> __( 'Icons Color', 'fastfood' ),
						'section'			=> 'background_image',
						'priority'			=> 16,
					),
		),

	) );

}
