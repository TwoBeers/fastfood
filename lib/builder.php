<?php
/**
 * builder.php
 *
 * @package fastfood
 * @since fastfood 0.37
 */

class Fastfood_Builder {

	/**
	 * Holds the actions order
	 * @access public
	 * @var array
	 */
	public static $order = array();


	/**
	 * Holds the supported sections
	 * @access public
	 * @var array
	 */
	public static $supported_sections = array();


	/**
	 * Instantiate.
	 *
	 * Create/Get the instance for the current class
	 *
	 * @static
	 * @access public
	 * @since Fastfood 0.37
	 */
	public static function init() {

		static $instance = NULL;

		if ( ! $instance ) {
			$instance = new Fastfood_Builder;
		}

		return $instance;

	}


	/**
	 * Constructor
	 */
	function __construct() {

		add_action( 'wp_loaded'															, array( $this, 'build'                    ), 31 );
		add_action( 'customize_render_control_fastfood_options[fastfood_builder_order]'	, array( $this, 'update_controls'          )     );

		add_filter( 'fastfood_options_array'											, array( $this, 'extra_options'            )     );
		add_filter( 'fastfood_options_hierarchy'										, array( $this, 'update_options_hierarchy' ), 99 );

	}


	/**
	 * The main method
	 */
	function build() {

		$this->add_predefined();
		$this->grab_sort_n_hang();

	}


	/**
	 * Grab the actions hooked to the 'fastfood_hook_builder' hook and add each one to its section
	 *
	 * @access private
	 * @since Fastfood 0.37
	 */
	private function grab_sort_n_hang() {
		global $wp_filter;

		if ( isset( $wp_filter['fastfood_hook_builder'] ) ) {

			$sorted = (array)json_decode( FastfoodOptions::get_opt( 'fastfood_builder_order' ) );

			foreach( $wp_filter['fastfood_hook_builder'][10] as $element ) {

				$id       = $element['accepted_args']['id'];
				$section  = $element['accepted_args']['section'];
				$callback = $element['function'];
				$label    = $element['accepted_args']['label'] ? $element['accepted_args']['label'] : $element['accepted_args']['id'];
				$priority = ( false === $offset = array_search( $id, $sorted ) ) ? 10 + absint( $element['accepted_args']['priority'] ) : 10 + $offset;

				if ( !isset( self::$supported_sections[$section] ) ) continue;

				self::$order[$section]['label'] = self::$supported_sections[$section];
				self::$order[$section]['elements'][$priority] = array(
					'id'       => $id,
					'callback' => $callback,
					'priority' => $priority,
					'label'    => $label,
				);

				add_action( "fastfood_hook_{$section}", $callback, $priority );

			}

			foreach( self::$order as $s_key => $section ) {
				ksort( $section['elements'] );
				self::$order[$s_key]['elements'] = $section['elements'];
			}

		}

	}


	/**
	 * Define the supported sections and add the predefined elements to the builder
	 *
	 * @access private
	 * @since Fastfood 0.37
	 */
	private function add_predefined() {

		self::$supported_sections = array(
			'header' => __( 'Header', 'fastfood' ),
			'footer' => __( 'Footer', 'fastfood' ),
		);

		$default_elements = array(
			'menu_primary' => array(
				'section'	=> 'header',
				'callback'	=> 'Fastfood_Nav_Menus::menu_primary',
				'priority'	=> 12,
				'label'		=> __( 'Primary menu', 'fastfood' ),
			),
			'menu_secondary_first' => array(
				'section'	=> 'header',
				'callback'	=> 'Fastfood_Nav_Menus::menu_secondary_first',
				'priority'	=> 10,
				'label'		=> __( 'Secondary menu #1', 'fastfood' ),
			),
			'menu_secondary_second' => array(
				'section'	=> 'footer',
				'callback'	=> 'Fastfood_Nav_Menus::menu_secondary_second',
				'priority'	=> 10,
				'label'		=> __( 'Secondary menu #2', 'fastfood' ),
			),
			'sidebar_header' => array(
				'section'	=> 'header',
				'callback'	=> 'fastfood_sidebar_header',
				'priority'	=> 14,
				'label'		=> __( 'Header widget area', 'fastfood' ),
			),
			'sidebar_footer' => array(
				'section'	=> 'footer',
				'callback'	=> 'fastfood_sidebar_footer',
				'priority'	=> 11,
				'label'		=> __( 'Footer widget area', 'fastfood' ),
			),
			'credits' => array(
				'section'	=> 'footer',
				'callback'	=> 'fastfood_credits',
				'priority'	=> 9,
				'label'		=> __( 'Site informations', 'fastfood' ),
			),
		);

		foreach( $default_elements as $id => $element) {

			add_action( 'fastfood_hook_builder', $element['callback'], 10, array( 'id' => $id, 'section' => $element['section'], 'priority' => $element['priority'], 'label' => $element['label'] ) );

		}

	}


	/**
	 * Add extra options
	 *
	 * @since Fastfood 0.37
	 */
	function extra_options( $coa ) {

		$coa['fastfood_builder_order'] = array(
			'setting'			=> array(
				'default'			=> '[]',
				'sanitize_method'	=> 'text',
			),
			'control'			=> array(
				'render_type'		=> 'tbsortable',
				'label'				=> '',
				'description'		=> '',
				'order'				=> array(),
			),
		);

		return $coa;

	}


	/**
	 * Update the options hierarchy
	 *
	 * @since Fastfood 0.37
	 */
	function update_options_hierarchy( $hierarchy ) {

		$hierarchy['field']['elements_order'] = array(
			'label'			=> __( 'Order', 'fastfood' ),
			'description'	=> __( 'Sort the available elements and change their position in page.<br />NOTE: some elements may be disabled, unset or empty (like menus, widget areas, etc.) therefore not visible in the preview. Please refer to their respective options', 'fastfood' ),
			'options'		=> array(
				'fastfood_builder_order',
			),
			'require'		=> '',
		);

		$hierarchy['section']['elements']['fields'][] = 'elements_order';

		return $hierarchy;

	}


	/**
	 * Update the control in customizer
	 *
	 * @since Fastfood 0.37
	 */
	function update_controls( $wp_customize_control ) {

		$wp_customize_control->order    = self::$order;

	}

}

Fastfood_Builder::init();

