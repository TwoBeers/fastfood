<?php
/**
 * options.php
 *
 * the options array
 *
 * @package fastfood
 * @since fastfood 0.30
 */

class FastfoodOptions {

	//holds the complete default options array
	private static $coa = array();

	//holds the options hierarchy (for builing the theme options page)
	private static $hierarchy = array();


	/**
	 * Initialize the default settings and the options hierarchy
	 *
	 * @return	none
	 */
	public static function init() {

		self::$coa = apply_filters( 'fastfood_options_array', array(

			'fastfood_qbar' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show the quickbar', 'fastfood' ),
									'description'		=> __( 'the sliding panel that is activated when the mouse rolls over the fixed buttons on the bottom left', 'fastfood' ),
								),
							),
			'fastfood_qbar_user' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'user', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_qbar]',
								),
							),
			'fastfood_qbar_minilogin' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'mini login', 'fastfood' ),
									'description'		=> __( 'a small login form in the user panel', 'fastfood' ),
									'require'			=> array( 'fastfood_options[fastfood_qbar]', 'fastfood_options[fastfood_qbar_user]' ),
								),
							),
			'fastfood_qbar_reccom' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'recent comments', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_qbar]',
								),
							),
			'fastfood_qbar_cat' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'categories', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_qbar]',
								),
							),
			'fastfood_qbar_recpost' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'recent posts', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_qbar]',
								),
							),
			'fastfood_postexcerpt' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'select',
								),
								'control'			=> array(
									'type'				=> 'select',
									'render_type'		=> 'select',
									'label'				=> __( 'content', 'fastfood' ),
									'description'		=> __( 'the content to show on indexes', 'fastfood' ),
									'choices'			=> array(
										0 => __( 'content', 'fastfood' ),
										1 => __( 'excerpt', 'fastfood' ),
									),
								),
							),
			'fastfood_post_formats_standard_title' =>
							array(
								'setting'			=> array(
									'default'			=> 'post title',
									'sanitize_method'	=> 'select',
								),
								'control'			=> array(
									'type'				=> 'select',
									'render_type'		=> 'select',
									'label'				=> __( 'title', 'fastfood' ),
									'description'		=> __( 'the title to show on indexes', 'fastfood' ),
									'choices'			=> array(
										'post title'	=> __( 'post title', 'fastfood' ),
										'post date'		=> __( 'post date', 'fastfood' ),
										'none'			=> __( 'none', 'fastfood' ),
									),
								),
							),
			'fastfood_post_formats_gallery' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> sprintf( __( 'support the "%s" format', 'fastfood' ), get_post_format_string( 'gallery' ) ),
									'description'		=> '',
								),
							),
			'fastfood_post_formats_gallery_title' =>
							array(
								'setting'			=> array(
									'default'			=> 'post title',
									'sanitize_method'	=> 'select',
								),
								'control'			=> array(
									'type'				=> 'select',
									'render_type'		=> 'select',
									'label'				=> __( 'title', 'fastfood' ),
									'description'		=> __( 'the title to show on indexes', 'fastfood' ),
									'choices'			=> array(
										'post title'	=> __( 'post title', 'fastfood' ),
										'post date'		=> __( 'post date', 'fastfood' ),
										'none'			=> __( 'none', 'fastfood' ),
									),
									'require'			=> 'fastfood_options[fastfood_post_formats_gallery]',
								),
							),
			'fastfood_post_formats_gallery_content' =>
							array(
								'setting'			=> array(
									'default'			=> 'presentation',
									'sanitize_method'	=> 'select',
								),
								'control'			=> array(
									'type'				=> 'select',
									'render_type'		=> 'select',
									'label'				=> __( 'content', 'fastfood' ),
									'description'		=> __( 'the content to show on indexes', 'fastfood' ),
									'choices'			=> array(
										'presentation'	=> __( 'presentation', 'fastfood' ),
										'content'		=> __( 'content', 'fastfood' ),
										'excerpt'		=> __( 'excerpt', 'fastfood' ),
										'none'			=> __( 'none', 'fastfood' ),
									),
									'require'			=> 'fastfood_options[fastfood_post_formats_gallery]',
								),
							),
			'fastfood_post_formats_gallery_preview_items' =>
							array(
								'setting'			=> array(
									'default'			=> 5,
									'sanitize_method'	=> 'number',
								),
								'control'			=> array(
									'type'				=> 'number',
									'render_type'		=> 'number',
									'label'				=> __( 'number of images', 'fastfood' ),
									'description'		=> sprintf( __( 'the number of images to show in "%s" view', 'fastfood' ), __( 'presentation', 'fastfood' ) ),
									'input_attrs'		=> array(
										'min'	=> 3,
										'max'	=> 9,
										'step'	=> 1,
									),
									'require'			=> 'fastfood_options[fastfood_post_formats_gallery]',
								),
							),
			'fastfood_post_formats_quote' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> sprintf( __( 'support the "%s" format', 'fastfood' ), get_post_format_string( 'quote' ) ),
									'description'		=> '',
								),
							),
			'fastfood_post_formats_aside' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> sprintf( __( 'support the "%s" format', 'fastfood' ), get_post_format_string( 'aside' ) ),
									'description'		=> '',
								),
							),
			'fastfood_post_view_aside' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show on indexes', 'fastfood' ),
									'description'		=> __( 'by deselecting this option, the "aside" posts will be ignored and will not appear on indexes', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_post_formats_aside]',
								),
							),
			'fastfood_post_formats_status' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> sprintf( __( 'support the "%s" format', 'fastfood' ), get_post_format_string( 'status' ) ),
									'description'		=> '',
								),
							),
			'fastfood_post_view_status' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show on indexes', 'fastfood' ),
									'description'		=> __( 'by deselecting this option, the "status" posts will be ignored and will not appear on indexes', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_post_formats_status]',
								),
							),
			'fastfood_xinfos_global' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show post/page details (author, date, tags, etc)', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_xinfos_on_list' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in indexes', 'fastfood' ),
									'description'		=> __( 'show details (author, date, tags, etc) in posts overview (archives, search, main index...)', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_on_page' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in pages', 'fastfood' ),
									'description'		=> __( 'show details (hierarchy and comments) in pages', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_on_post' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in posts', 'fastfood' ),
									'description'		=> __( 'show details (author, date, tags, etc) in posts', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_on_front' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in front page', 'fastfood' ),
									'description'		=> __( 'show details (author, date, tags, etc) in front page', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_static' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'static info', 'fastfood' ),
									'description'		=> __( 'show details as a static list (not animated)', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_byauth' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'author', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_date' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'date', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_comm' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'comments number', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_tag' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'tags', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_cat' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'categories', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_xinfos_hiera' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'hierarchy', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_xinfos_global]',
								),
							),
			'fastfood_jsani' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'use javascript animations and features', 'fastfood' ),
									'description'		=> __( 'try disable animations if you encountered problems with javascript', 'fastfood' ),
								),
							),
			'fastfood_basic_animation_main_menu' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'main menu', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_basic_animation_navigation_buttons' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'navigation buttons', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_basic_animation_quickbar_panels' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'quickbar panels', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_basic_animation_entry_meta' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'entry metadata', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_basic_animation_smooth_scroll' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'smooth scroll', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_basic_animation_captions' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'caption slide', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_post_expand' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'post expander', 'fastfood' ),
									'description'		=> __( 'expands a post to show the full content when the reader clicks the "Read more..." link', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_comments_navigation' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'comments navigation', 'fastfood' ),
									'description'		=> __( 'ajaxed navigation for comments', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_gallery_preview' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'use thickbox for showing images and galleries', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_force_link_to_image' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'force galleries to use links to image instead of links to attachment', 'fastfood' ),
									'description'		=> '',
									'require'			=> array( 'fastfood_options[fastfood_jsani]', 'fastfood_options[fastfood_gallery_preview]' ),
								),
							),
			'fastfood_cust_comrep' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'custom comment form', 'fastfood' ),
									'description'		=> __( 'custom floating form for posting comments', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_quotethis'=>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'quote link', 'fastfood' ),
									'description'		=> __( 'show a link for easily add the selected text as a quote inside the comment form', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_tinynav' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> 'Tinynav',
									'description'		=> __( 'convert the primary menu in a tiny navigation menu for small screens (less than 800px wide)', 'fastfood' ) . '<br />' . __( 'more info', 'fastfood') . ' : <a target="_blank" href="https://github.com/viljamis/TinyNav.js">Tinynav</a>',
									'require'			=> 'fastfood_options[fastfood_jsani]',
								),
							),
			'fastfood_rsidebindexes' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in indexes', 'fastfood' ),
									'description'		=> __( '(archives, search, main index...)', 'fastfood' ),
								),
							),
			'fastfood_rsidebpages' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in pages', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_rsidebposts' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in posts', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_rsidebattachments' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in attachments', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_rsideb_position' =>
							array(
								'setting'			=> array(
									'default'			=> 'right',
									'sanitize_method'	=> 'radio',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'type'				=> 'radio',
									'render_type'		=> 'smart_radio',
									'label'				=> __( 'position', 'fastfood' ),
									'description'		=> '',
									'choices'			=> array(
										'left'	=> '<span class="theme-option-sprite-image"></span>' . __( 'left', 'fastfood' ),
										'right'	=> '<span class="theme-option-sprite-image"></span>' . __( 'right', 'fastfood' ),
									),
								),
							),
			'fastfood_rsideb_width' =>
							array(
								'setting'			=> array(
									'default'			=> 284,
									'sanitize_method'	=> 'number',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'render_type'		=> 'slider',
									'label'				=> __( 'width (px)', 'fastfood' ),
									'description'		=> '[150-400]',
									'input_attrs'		=> array(
										'min'	=> 150,
										'max'	=> 400,
										'step'	=> 1,
									),
								),
							),
			'fastfood_colors_link' =>
							array(
								'setting'			=> array(
									'default'			=> '#2ea2cc',
									'sanitize_method'	=> 'color',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'render_type'		=> 'color',
									'label'				=> __( 'normal links', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_colors_link_hover' =>
							array(
								'setting'			=> array(
									'default'			=> '#d54e21',
									'sanitize_method'	=> 'color',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'render_type'		=> 'color',
									'label'				=> __( 'highlighted links', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_colors_link_sel' =>
							array(
								'setting'			=> array(
									'default'			=> '#aaaaaa',
									'sanitize_method'	=> 'color',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'render_type'		=> 'color',
									'label'				=> __( 'selected links', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_breadcrumb' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show breadcrumb navigation', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_primary_menu' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show primary menu', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_sticky_menu' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'stick the menu on top when the user scrolls down the page', 'fastfood' ),
									'description'		=> '',
									'require'			=> array( 'fastfood_options[fastfood_primary_menu]', 'fastfood_options[fastfood_jsani]' ),
								),
							),
			'fastfood_manage_blank_title' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'replace blank titles with a standard text', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_blank_title' =>
							array(
								'setting'			=> array(
									'default'			=> __( '(no title)', 'fastfood' ),
									'sanitize_method'	=> 'text',
								),
								'control'			=> array(
									'type'				=> 'text',
									'render_type'		=> 'text',
									'label'				=> __( 'format', 'fastfood' ),
									'description'		=> __( 'you may use these codes:<br><code>%d</code> for post date<br><code>%f</code> for post format (if any)<br><code>%n</code> for post ID', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_manage_blank_title]',
								),
							),
			'fastfood_featured_title' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show the thumbnail (if available) beside the posts title', 'fastfood' ),
									'description'		=> __( 'more info', 'fastfood' ) . ' : <a target="_blank" href="http://codex.wordpress.org/Post_Thumbnails">' . __( 'Post Thumbnails', 'fastfood' ) . '</a>',
								),
							),
			'fastfood_featured_title_size' =>
							array(
								'setting'			=> array(
									'default'			=> 50,
									'sanitize_method'	=> 'number',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'render_type'		=> 'slider',
									'label'				=> __( 'thumbnail size', 'fastfood' ) . ' (px)',
									'description'		=> '[50-150]',
									'input_attrs'		=> array(
										'min'	=> 50,
										'max'	=> 150,
										'step'	=> 1,
									),
									'require'			=> 'fastfood_options[fastfood_featured_title]',
								),
							),
			'fastfood_hide_frontpage_title' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in front page (if a static page is selected)', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_hide_pages_title' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in every single page', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_hide_posts_title' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'in every single post', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_hide_selected_entries_title' =>
							array(
								'setting'			=> array(
									'default'			=> '',
									'sanitize_method'	=> 'text',
								),
								'control'			=> array(
									'type'				=> 'text',
									'render_type'		=> 'text',
									'label'				=> __( 'in selected posts/pages', 'fastfood' ),
									'description'		=> __( 'comma-separated list of IDs ( eg. <em>23,86,120</em> )', 'fastfood' ),
								),
							),
			'fastfood_excerpt_lenght' =>
							array(
								'setting'			=> array(
									'default'			=> 55,
									'sanitize_method'	=> 'number',
								),
								'control'			=> array(
									'type'				=> 'number',
									'render_type'		=> 'number',
									'label'				=> __( 'excerpt length', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_excerpt_more_txt' =>
							array(
								'setting'			=> array(
									'default'			=> '[...]',
									'sanitize_method'	=> 'text',
								),
								'control'			=> array(
									'type'				=> 'text',
									'render_type'		=> 'text',
									'label'				=> __( '"excerpt more" string', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_excerpt_more_link' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'use the "excerpt more" string as a link to the full post', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_more_tag' =>
							array(
								'setting'			=> array(
									'default'			=> __( '(more...)', 'fastfood' ),
									'sanitize_method'	=> 'text',
								),
								'control'			=> array(
									'type'				=> 'text',
									'render_type'		=> 'text',
									'label'				=> __( 'text', 'fastfood' ),
									'description'		=> __( 'only plain text. use <code>%t</code> as placeholder for the post title', 'fastfood' ) . ' (<a href="http://codex.wordpress.org/Customizing_the_Read_More" target="_blank">Codex</a>)',
								),
							),
			'fastfood_more_tag_scroll' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'prevent scroll when clicking the "more" tag', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_more_tag_always' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show a link to the page/post even if the "more" tag is not present', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_editor_style' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'editor style', 'fastfood' ),
									'description'		=> __( 'add style to the editor in order to write the post exactly how it will appear in the site', 'fastfood' ),
								),
							),
			'fastfood_mobile_css' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'use a dedicated style in mobile devices', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_mobile_css_color' =>
							array(
								'setting'			=> array(
									'default'			=> 'light',
									'sanitize_method'	=> 'radio',
								),
								'control'			=> array(
									'type'				=> 'radio',
									'render_type'		=> 'smart_radio',
									'label'				=> __( 'colors', 'fastfood' ),
									'description'		=> '',
									'choices'			=> array(
										'light'	=> '<span class="theme-option-sprite-image"></span>' . __( 'light', 'fastfood' ),
										'dark'	=> '<span class="theme-option-sprite-image"></span>' . __( 'dark', 'fastfood' ),
									),
									'require'			=> 'fastfood_options[fastfood_mobile_css]',
								),
							),
			'fastfood_body_width' =>
							array(
								'setting'			=> array(
									'default'			=> 852,
									'sanitize_method'	=> 'number',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'render_type'		=> 'slider',
									'label'				=> __( 'width', 'fastfood' ) . ' (px)',
									'description'		=> '[640-1024]',
									'input_attrs'		=> array(
										'min'	=> 640,
										'max'	=> 1024,
										'step'	=> 1,
									),
								),
							),
			'fastfood_head_h' =>
							array(
								'setting'			=> array(
									'default'			=> 120,
									'sanitize_method'	=> 'number',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'render_type'		=> 'slider',
									'label'				=> __( 'header height', 'fastfood' ),
									'description'		=> '[100-480]',
									'input_attrs'		=> array(
										'min'	=> 100,
										'max'	=> 480,
										'step'	=> 1,
									),
								),
							),
			'fastfood_font_family' =>
							array(
								'setting'			=> array(
									'default'			=> 'Verdana, Geneva, sans-serif',
									'sanitize_method'	=> 'select',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'type'				=> 'select',
									'render_type'		=> 'select',
									'label'				=> __( 'font family', 'fastfood' ),
									'description'		=> '',
									'choices'			=> array(
										'Arial, sans-serif'									=> 'Arial, sans-serif',
										'Comic Sans MS, cursive'							=> 'Comic Sans MS, cursive',
										'Courier New, monospace'							=> 'Courier New, monospace',
										'Georgia, serif'									=> 'Georgia, serif',
										'Helvetica, sans-serif'								=> 'Helvetica, sans-serif',
										'Lucida Console, Monaco, monospace'					=> 'Lucida Console, Monaco, monospace',
										'Lucida Sans Unicode, Lucida Grande, sans-serif'	=> 'Lucida Sans Unicode, Lucida Grande, sans-serif',
										'monospace'											=> 'monospace',
										'Palatino Linotype, Book Antiqua, Palatino, serif'	=> 'Palatino Linotype, Book Antiqua, Palatino, serif',
										'Tahoma, Geneva, sans-serif'						=> 'Tahoma, Geneva, sans-serif',
										'Times New Roman, Times, serif'						=> 'Times New Roman, Times, serif',
										'Trebuchet MS, sans-serif'							=> 'Trebuchet MS, sans-serif',
										'Verdana, Geneva, sans-serif'						=> 'Verdana, Geneva, sans-serif',
									),
								),
							),
			'fastfood_font_size' =>
							array(
								'setting'			=> array(
									'default'			=> 12,
									'sanitize_method'	=> 'number',
									'transport'			=> 'postMessage',
								),
								'control'			=> array(
									'render_type'		=> 'slider',
									'label'				=> __( 'font size', 'fastfood' ) . ' (px)',
									'description'		=> '[9-16]',
									'input_attrs'		=> array(
										'min'	=> 9,
										'max'	=> 16,
										'step'	=> 1,
									),
								),
							),
			'fastfood_google_font_family'=>
							array(
								'setting'			=> array(
									'default'			=> '',
									'sanitize_method'	=> 'text',
								),
								'control'			=> array(
									'type'				=> 'text',
									'render_type'		=> 'text',
									'label'				=> __( 'font name', 'fastfood' ),
									'description'		=> sprintf( __( 'Copy and paste here the name of a font from the %1$s library . Example: %2$s', 'fastfood' ), '<a href="http://www.google.com/fonts" target="_blank"><strong>Google web font</strong></a>', '<code>Droid Sans</code>' ),
								),
							),
			'fastfood_google_font_subset' =>
							array(
								'setting'			=> array(
									'default'			=> '',
									'sanitize_method'	=> 'text',
								),
								'control'			=> array(
									'type'				=> 'text',
									'render_type'		=> 'text',
									'label'				=> __( 'subset', 'fastfood' ),
									'description'		=> __( 'comma-separated list of subsets ( eg. "latin,latin-ext,cyrillic" )', 'fastfood' ),
									'require'			=> 'fastfood_options[fastfood_google_font_family]',
								),
							),
			'fastfood_google_font_body' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'for whole site', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_google_font_family]',
								),
							),
			'fastfood_google_font_post_title' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'for posts/pages title', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_google_font_family]',
								),
							),
			'fastfood_google_font_post_content' =>
							array(
								'setting'			=> array(
									'default'			=> 0,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'for posts/pages content', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_google_font_family]',
								),
							),
			'fastfood_navbuttons' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show navigation buttons', 'fastfood' ),
									'description'		=> __( 'the fixed navigation bar on the right. Note: Is strongly recommended to keep it enabled', 'fastfood' ),
								),
							),
			'fastfood_navbuttons_print' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'print preview', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_navbuttons]',
								),
							),
			'fastfood_navbuttons_comment' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'leave a comment', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_navbuttons]',
								),
							),
			'fastfood_navbuttons_feed' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'RSS feed', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_navbuttons]',
								),
							),
			'fastfood_navbuttons_trackback' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'trackback', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_navbuttons]',
								),
							),
			'fastfood_navbuttons_home' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'home', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_navbuttons]',
								),
							),
			'fastfood_navbuttons_nextprev' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'next/previous post', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_navbuttons]',
								),
							),
			'fastfood_navbuttons_newold' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'newer/older posts', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_navbuttons]',
								),
							),
			'fastfood_navbuttons_topbottom' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'top/bottom', 'fastfood' ),
									'description'		=> '',
									'require'			=> 'fastfood_options[fastfood_navbuttons]',
								),
							),
			'fastfood_statusbar' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show status bar', 'fastfood' ),
									'description'		=> __( 'the fixed bar on bottom of page', 'fastfood' ),
								),
							),
			'fastfood_custom_widgets' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'custom widgets', 'fastfood' ),
									'description'		=> __( 'add a lot of new usefull widgets', 'fastfood' ),
								),
							),
			'fastfood_responsive_layout' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'the theme fits to small screens (less than 1024px wide)', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_allowed_tags' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'show the allowed tags list', 'fastfood' ),
									'description'		=> '',
								),
							),
			'fastfood_custom_css' =>
							array(
								'setting'			=> array(
									'default'			=> '',
									'sanitize_method'	=> 'textarea',
								),
								'control'			=> array(
									'type'				=> 'textarea',
									'render_type'		=> 'textarea',
									'label'				=> '',
									'description'		=> __( '<strong>For advanced users only</strong>: paste here your custom css code. it will be added after the defatult style', 'fastfood' ) . ' (<a href="'. get_stylesheet_uri() .'" target="_blank">style.css</a>)',
								),
							),
			'fastfood_tbcred' =>
							array(
								'setting'			=> array(
									'default'			=> 1,
									'sanitize_method'	=> 'checkbox',
								),
								'control'			=> array(
									'type'				=> 'checkbox',
									'render_type'		=> 'checkbox',
									'label'				=> __( 'theme credits', 'fastfood' ),
									'description'		=> __( 'It is completely optional, but if you like the Theme we would appreciate it if you keep the credit link at the bottom', 'fastfood' ),
								),
							),
			'version' =>
							array(
								'setting'			=> array(
									'default'			=> fastfood_get_info( 'version' ),
									'sanitize_method'	=> 'text',
								),
								'control'			=> array(
									'type'				=> 'hidden',
									'render_type'		=> 'hidden',
									'label'				=> '',
									'description'		=> '',
									'active_callback'	=> '__return_false',
								),
							),
		) );

		self::$hierarchy = apply_filters( 'fastfood_options_hierarchy', array(
			'group' => array(

				'style' => array(
					'label'			=> __( 'Style', 'fastfood' ),
					'description'	=> '',
					'sections'		=> array(
						'colors',
						'fonts',
						'other_a',
					),
				),

				'layout' => array(
					'label'			=> __( 'Layout', 'fastfood' ),
					'description'	=> '',
					'sections'		=> array(
						'elements',
						'mobile',
					),
				),

				'contents' => array(
					'label'			=> __( 'Contents', 'fastfood' ),
					'description'	=> '',
					'sections'		=> array(
						'post_formats',
						'titles',
						'other_b',
					),
				),

				'features' => array(
					'label'			=> __( 'Features', 'fastfood' ),
					'description'	=> '',
					'sections'		=> array(
						'javascript',
						'other_c',
					),
				),

			),

			'section' => array(

				'colors' => array(
					'label'			=> __( 'Colors', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'links_colors',
					),
				),

				'fonts' => array(
					'label'			=> __( 'Fonts', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'basic_font',
						'google_font',
					),
				),

				'other_a' => array(
					'label'			=> __( 'Other', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'custom_css',
					),
				),

				'elements' => array(
					'label'			=> __( 'Elements', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'body',
						'header',
						'primary_menu',
						'breadcrumb',
						'sidebar',
						'quickbar',
						'navbuttons',
						'statusbar',
						'comment_form',
					),
				),

				'mobile' => array(
					'label'			=> __( 'Mobile', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'responsive_layout',
						'mobile_theme',
					),
				),

				'post_formats' => array(
					'label'			=> __( 'Post formats', 'fastfood' ) . ' <a class="more-info-link" href="http://codex.wordpress.org/Post_Formats" target="_blank" title="' . esc_attr__( 'learn more about the post formats', 'fastfood' ) . '">?</a>',
					'description'	=> __( 'the following options affect only the blog/index view, while in single posts the appearance will be the same', 'fastfood' ),
					'fields'		=> array(
						'post_formats_standard',
						'post_formats_aside',
						'post_formats_gallery',
						'post_formats_quote',
						'post_formats_status',
					),
				),

				'titles' => array(
					'label'			=> __( 'Titles', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'blank_titles',
						'hide_titles',
						'featured_titles',
					),
				),

				'other_b' => array(
					'label'			=> __( 'Other', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'extra_info',
						'excerpt',
						'the_more_tag',
					),
				),

				'javascript' => array(
					'label'			=> __( 'Javascript', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'js_animations',
						'basic_animations',
						'advanced_js_features',
						'thickbox',
					),
				),

				'other_c' => array(
					'label'			=> __( 'Other', 'fastfood' ),
					'description'	=> '',
					'fields'		=> array(
						'hotchpotch',
					),
				),

			),

			'field' => array(

				'links_colors' => array(
					'label'			=> __( 'links colors', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_colors_link',
						'fastfood_colors_link_hover',
						'fastfood_colors_link_sel',
					),
					'require'		=> '',
				),

				'basic_font' => array(
					'label'			=> __( 'basic font', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_font_family',
						'fastfood_font_size',
					),
					'require'		=> '',
				),

				'google_font' => array(
					'label'			=> __( 'Google web font', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_google_font_family',
						'fastfood_google_font_subset',
						'',
						'fastfood_google_font_body',
						'fastfood_google_font_post_title',
						'fastfood_google_font_post_content',
					),
					'require'		=> '',
				),

				'custom_css' => array(
					'label'			=> __( 'custom CSS', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_custom_css',
					),
					'require'		=> '',
				),

				'body' => array(
					'label'			=> __( 'body', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_body_width',
					),
					'require'		=> '',
				),

				'header' => array(
					'label'			=> __( 'header', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_head_h',
					),
					'require'		=> '',
				),

				'primary_menu' => array(
					'label'			=> __( 'primary menu', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_primary_menu',
						'',
						'fastfood_sticky_menu',
					),
					'require'		=> '',
				),

				'breadcrumb' => array(
					'label'			=> __( 'breadcrumb', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_breadcrumb',
					),
					'require'		=> '',
				),

				'sidebar' => array(
					'label'			=> __( 'sidebar', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_rsideb_position',
						'',
						'fastfood_rsideb_width',
						'',
						'fastfood_rsidebindexes',
						'fastfood_rsidebpages',
						'fastfood_rsidebposts',
						'fastfood_rsidebattachments',
					),
					'require'		=> '',
				),

				'quickbar' => array(
					'label'			=> __( 'quickbar', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_qbar',
						'',
						'fastfood_qbar_recpost',
						'fastfood_qbar_cat',
						'fastfood_qbar_reccom',
						'fastfood_qbar_user',
						'',
						'fastfood_qbar_minilogin',
					),
					'require'		=> '',
				),

				'navbuttons' => array(
					'label'			=> __( 'navigation buttons', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_navbuttons',
						'',
						'fastfood_navbuttons_print',
						'fastfood_navbuttons_comment',
						'fastfood_navbuttons_feed',
						'fastfood_navbuttons_trackback',
						'fastfood_navbuttons_home',
						'fastfood_navbuttons_nextprev',
						'fastfood_navbuttons_newold',
						'fastfood_navbuttons_topbottom',
					),
					'require'		=> '',
				),

				'statusbar' => array(
					'label'			=> __( 'status bar', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_statusbar',
					),
					'require'		=> '',
				),

				'comment_form' => array(
					'label'			=> __( 'comment form', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_cust_comrep',
						'fastfood_allowed_tags',
					),
					'require'		=> '',
				),

				'responsive_layout' => array(
					'label'			=> __( 'responsive layout', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_responsive_layout',
					),
					'require'		=> '',
				),

				'mobile_theme' => array(
					'label'			=> __( 'mobile theme', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_mobile_css',
						'fastfood_mobile_css_color',
					),
					'require'		=> '',
				),

				'post_formats_standard' => array(
					'label'			=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'standard' ) ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_post_formats_standard_title',
						'fastfood_postexcerpt',
					),
					'require'		=> '',
				),

				'post_formats_aside' => array(
					'label'			=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'aside' ) ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_post_formats_aside',
						'',
						'fastfood_post_view_aside',
					),
					'require'		=> '',
				),

				'post_formats_gallery' => array(
					'label'			=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'gallery' ) ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_post_formats_gallery',
						'',
						'fastfood_post_formats_gallery_title',
						'fastfood_post_formats_gallery_content',
						'fastfood_post_formats_gallery_preview_items',
					),
					'require'		=> '',
				),

				'post_formats_quote' => array(
					'label'			=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'quote' ) ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_post_formats_quote',
					),
					'require'		=> '',
				),

				'post_formats_status' => array(
					'label'			=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'status' ) ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_post_formats_status',
						'',
						'fastfood_post_view_status',
					),
					'require'		=> '',
				),

				'blank_titles' => array(
					'label'			=> __( 'blank titles', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_manage_blank_title',
						'',
						'fastfood_blank_title',
					),
					'require'		=> '',
				),

				'hide_titles' => array(
					'label'			=> __( 'hide titles', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_hide_frontpage_title',
						'fastfood_hide_pages_title',
						'fastfood_hide_posts_title',
						'fastfood_hide_selected_entries_title',
					),
					'require'		=> '',
				),

				'featured_titles' => array(
					'label'			=> __( 'thumbnails', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_featured_title',
						'fastfood_featured_title_size',
					),
					'require'		=> '',
				),

				'extra_info' => array(
					'label'			=> __( 'post/page details', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_xinfos_global',
						'',
						'fastfood_xinfos_byauth',
						'fastfood_xinfos_date',
						'fastfood_xinfos_comm',
						'fastfood_xinfos_tag',
						'fastfood_xinfos_cat',
						'fastfood_xinfos_hiera',
						'',
						'fastfood_xinfos_on_list',
						'fastfood_xinfos_on_page',
						'fastfood_xinfos_on_post',
						'fastfood_xinfos_on_front',
						'',
						'fastfood_xinfos_static',
					),
					'require'		=> '',
				),

				'excerpt' => array(
					'label'			=> __( 'content summary', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_excerpt_lenght',
						'fastfood_excerpt_more_txt',
						'fastfood_excerpt_more_link',
					),
					'require'		=> '',
				),

				'the_more_tag' => array(
					'label'			=> __( 'The More Tag', 'fastfood' ),
					'description'	=> __( 'more info', 'fastfood') . ' : <a target="_blank" href="http://support.wordpress.com/splitting-content/more-tag/">' . __( 'The More Tag', 'fastfood' ) . '</a>',
					'options'		=> array(
						'fastfood_more_tag',
						'fastfood_more_tag_scroll',
						'fastfood_more_tag_always',
					),
					'require'		=> '',
				),

				'js_animations' => array(
					'label'			=> __( 'javascript support', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_jsani',
					),
					'require'		=> '',
				),

				'basic_animations' => array(
					'label'			=> __( 'basic animations', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_basic_animation_main_menu',
						'fastfood_basic_animation_navigation_buttons',
						'fastfood_basic_animation_quickbar_panels',
						'fastfood_basic_animation_entry_meta',
						'fastfood_basic_animation_smooth_scroll',
						'fastfood_basic_animation_captions',
					),
					'require'		=> 'fastfood_options[fastfood_jsani]',
				),

				'advanced_js_features' => array(
					'label'			=> __( 'advanced features', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_post_expand',
						'fastfood_comments_navigation',
						'fastfood_quotethis',
						'fastfood_tinynav',
					),
					'require'		=> 'fastfood_options[fastfood_jsani]',
				),

				'thickbox' => array(
					'label'			=> __( 'thickbox', 'fastfood' ),
					'description'	=> '',
					'options'		=> array(
						'fastfood_gallery_preview',
						'fastfood_force_link_to_image',
					),
					'require'		=> 'fastfood_options[fastfood_jsani]',
				),

				'hotchpotch' => array(
					'label'			=> __( 'hotchpotch', 'fastfood' ),
					'description'	=> __( 'other mixed options', 'fastfood' ),
					'options'		=> array(
						'fastfood_editor_style',
						'fastfood_custom_widgets',
						'fastfood_tbcred',
						'version',
					),
					'require'		=> '',
				),

			)

		) );

	}


	/**
	 * Get the complete default options array and return the full array or one option or an option value
	 *
	 * @param	string	$option		(optional) the option key
	 * @param	string	$data		(optional) the option subkey
	 * @return	array|mixed			the default options array, one option or an option value
	*/
	public static function get_coa( $option = false, $data = false ) {

		if ( $option )
			if ( $data )
				return isset( self::$coa[$option][$data[0]][$data[1]] ) ? self::$coa[$option][$data[0]][$data[1]] : NULL;
			else
				return isset( self::$coa[$option] ) ? self::$coa[$option] : NULL;
		else
			return self::$coa;
	}


	/**
	 * Return the options hierarchy
	 *
	 * @param	none
	 * @return	array	the hierarchy array
	*/
	public static function get_hierarchy( $level = false ) {

		if ( $level )
			return self::$hierarchy[$level];
		else
			return self::$hierarchy;

	}


	/**
	 * Return the selected options value
	 *
	 * if the option is set, before returning, its value is filtered.
	 * If the option is not set, return the default value.
	 * If the the default value doesn't exists, return a fallback value ('null' by default)
	 *
	 * @global	array	$fastfood_opt
	 * @param	string	$opt			(required) the option key
	 * @param	mixed	$alt			(optional) alternative (if option isn't set)
	 * @param	mixed	$fallback		(optional) a fallback value (ICE)
	 * @param	bool	$force_update	(optional) force update of the global variable $fastfood_opt
	 * @return	mixed					the option value
	 */
	public static function get_opt( $opt, $alt = NULL, $fallback = NULL, $force_update = false ) {
		global $fastfood_opt;

		if ( $force_update ) $fastfood_opt = get_option( 'fastfood_options' );

		if ( isset( $fastfood_opt[$opt] ) ) return apply_filters( 'fastfood_option_' . $opt, $fastfood_opt[$opt], $opt );

		if ( !is_null( $alt ) ) return $alt;

		if ( is_null( $defopt = self::get_coa( $opt, array( 'setting', 'default' ) ) ) )
			return $fallback;
		else
			return $defopt;

	}


	/**
	 * Return the default values
	 *
	 * @return	array	the default values
	 */
	public static function get_defaults() {

		$defaults = array();

		foreach( self::get_coa() as $key => $option ) {

			$defaults[$key] = $option['setting']['default'];

		}

		$defaults['version'] = ''; //it's empty for version checking purposes

		return $defaults;

	}

}
