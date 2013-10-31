<?php
/**
 * options.php
 *
 * the options array
 *
 * @package fastfood
 * @since fastfood 0.30
 */


//complete options array, with type, defaults values, description, infos and required option
function fastfood_get_coa( $option = false, $data = false ) {

	$hierarchy = array(
		'style' => array(
			'label'			=> __( 'Style', 'fastfood' ),
			'description'	=> '',
			'sub'			=> array(
				'colors' => array(
					'label'			=> __( 'Colors', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_colors',
					),
				),
				'fonts' => array(
					'label'			=> __( 'Fonts', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_font_family',
						'fastfood_google_font_family',
					),
				),
				'other' => array(
					'label'			=> __( 'Other', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_custom_css',
					),
				),
			),
		),
		'layout' => array(
			'label'			=> __( 'Layout', 'fastfood' ),
			'description'	=> '',
			'sub'			=> array(
				'sidebars' => array(
					'label'			=> __( 'Elements', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_qbar',
						'fastfood_rsideb',
						'fastfood_breadcrumb',
						'fastfood_primary_menu',
						'fastfood_head',
						'fastfood_navbuttons',
						'fastfood_statusbar',
					),
				),
				'mobile' => array(
					'label'			=> __( 'Mobile', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_responsive_layout',
						'fastfood_mobile_css',
					),
				),
			),
		),
		'contents' => array(
			'label'			=> __( 'Contents', 'fastfood' ),
			'description'	=> '',
			'sub'			=> array(
				'post_formats' => array(
					'label'			=> sprintf( '<a href="http://codex.wordpress.org/Post_Formats" target="_blank" title="' . esc_attr__( 'learn more about the post formats', 'fastfood' ) . '">%s</a>', __( 'Post formats', 'fastfood' ) ),
					'description'	=> __( 'the following options affect only the blog/index view, while in single posts the appearance will be the same', 'fastfood' ),
					'sub'			=> array(
						'fastfood_post_formats_standard',
						'fastfood_post_formats_aside',
						'fastfood_post_formats_gallery',
						'fastfood_post_formats_quote',
						'fastfood_post_formats_status',
					),
				),
				'titles' => array(
					'label'			=> __( 'Titles', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_manage_blank_title',
						'fastfood_hide_titles',
						'fastfood_featured_title',
					),
				),
				'other' => array(
					'label'			=> __( 'Other', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_xinfos_global',
						'fastfood_excerpt',
						'fastfood_the_more_tag',
					),
				),
			),
		),
		'features' => array(
			'label'			=> __( 'Features', 'fastfood' ),
			'description'	=> '',
			'sub'			=> array(
				'javascript' => array(
					'label'			=> __( 'Javascript', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_jsani',
						'fastfood_basic_animations',
						'fastfood_post_expand',
						'fastfood_gallery_preview',
						'fastfood_cust_comrep',
						'fastfood_quotethis',
						'fastfood_tinynav',
					),
				),
				'other' => array(
					'label'			=> __( 'Other', 'fastfood' ),
					'description'	=> '',
					'sub'			=> array(
						'fastfood_I_like_it',
						'fastfood_editor_style',
						'fastfood_custom_bg',
						'fastfood_custom_widgets',
						'fastfood_tbcred',
					),
				),
			),
		),
	);
	$hierarchy = apply_filters( 'fastfood_options_hierarchy', $hierarchy );

	$fastfood_coa = array(
		'fastfood_qbar' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'quickbar', 'fastfood' ),
							'info'				=> __( 'the sliding panel that is activated when the mouse rolls over the fixed buttons on the bottom left', 'fastfood' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_qbar_recpost', 'fastfood_qbar_cat', 'fastfood_qbar_reccom', 'fastfood_qbar_user', '', 'fastfood_qbar_minilogin' )
		),
		'fastfood_qbar_user' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'user', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_qbar',
							'sub'				=> false
		),
		'fastfood_qbar_minilogin' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'mini login', 'fastfood' ),
							'info'				=> __( 'a small login form in the user panel', 'fastfood' ),
							'req'				=> 'fastfood_qbar_user',
							'sub'				=> false
		),
		'fastfood_qbar_reccom' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'recent comments', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_qbar',
							'sub'				=> false
		),
		'fastfood_qbar_cat' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'categories', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_qbar',
							'sub'				=> false
		),
		'fastfood_qbar_recpost' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'recent posts', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_qbar',
							'sub'				=> false
		),
		'fastfood_post_formats_standard' => 
						array(
							'type'				=> 'lbl',
							'default'			=> 1,
							'description'		=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'standard' ) ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_post_formats_standard_title', 'fastfood_postexcerpt' )
		),
		'fastfood_postexcerpt' => 
						array(
							'type'				=> 'sel',
							'default'			=> 0,
							'options'			=> array( 0, 1 ),
							'options_readable'	=> array( __( 'content', 'fastfood' ), __( 'excerpt', 'fastfood' ) ),
							'description'		=> __( 'content', 'fastfood' ),
							'info'				=> __( 'the content to show on indexes', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_post_formats_standard_title' => 
						array(
							'type'				=> 'sel',
							'default'			=> 'post title',
							'options'			=> array( 'post title', 'post date', 'none' ),
							'options_readable'	=> array( __( 'post title', 'fastfood' ), __( 'post date', 'fastfood' ), __( 'none', 'fastfood' )),
							'description'		=> __( 'title', 'fastfood' ),
							'info'				=> __( 'the title to show on indexes', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_post_formats_gallery' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'gallery' ) ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_post_formats_gallery_title', 'fastfood_post_formats_gallery_content' )
		),
		'fastfood_post_formats_gallery_title' => 
						array(
							'type'				=> 'sel',
							'default'			=> 'post title',
							'options'			=> array( 'post title', 'post date', 'none' ),
							'options_readable'	=> array( __( 'post title', 'fastfood' ), __( 'post date', 'fastfood' ), __( 'none', 'fastfood' ) ),
							'description'		=> __( 'title', 'fastfood' ),
							'info'				=> __( 'the title to show on indexes', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_post_formats_gallery_content' => 
						array(
							'type'				=> 'sel',
							'default'			=> 'presentation',
							'options'			=>array( 'presentation', 'content', 'excerpt', 'none' ),
							'options_readable'	=>array( __( 'presentation', 'fastfood' ), __( 'content', 'fastfood' ), __( 'excerpt', 'fastfood' ), __( 'none', 'fastfood' ) ),
							'description'		=> __( 'content', 'fastfood' ),
							'info'				=> __( 'the content to show on indexes', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_post_formats_quote' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'quote' ) ),
							'info'				=> '',
							'req'				=> '',
		),
		'fastfood_post_formats_aside' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'aside' ) ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_post_view_aside' )
		),
		'fastfood_post_view_aside' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'show on indexes', 'fastfood' ),
							'info'				=> __( 'by deselecting this option, the "aside" posts will be ignored and will not appear on indexes', 'fastfood' ),
							'req'				=> 'fastfood_post_formats_aside',
							'sub'				=> false
		),
		'fastfood_post_formats_status' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> sprintf( __( '"%s" format', 'fastfood' ), get_post_format_string( 'status' ) ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_post_view_status' )
		),
		'fastfood_post_view_status' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'show on indexes', 'fastfood' ),
							'info'				=> __( 'by deselecting this option, the "status" posts will be ignored and will not appear on indexes', 'fastfood' ),
							'req'				=> 'fastfood_post_formats_status',
							'sub'				=> false
		),
		'fastfood_xinfos_global' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post/page details', 'fastfood' ),
							'info'				=> __( 'show extra info (author, date, tags, etc)', 'fastfood' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_xinfos_byauth', 'fastfood_xinfos_date', 'fastfood_xinfos_comm', 'fastfood_xinfos_tag', 'fastfood_xinfos_cat', 'fastfood_xinfos_hiera', '', 'fastfood_xinfos_on_list', 'fastfood_xinfos_on_page', 'fastfood_xinfos_on_post', 'fastfood_xinfos_on_front', '', 'fastfood_xinfos_static' )
		),
		'fastfood_xinfos_on_list' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in indexes', 'fastfood' ),
							'info'				=> __( 'show details (author, date, tags, etc) in posts overview (archives, search, main index...)', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_on_page' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in pages', 'fastfood' ),
							'info'				=> __( 'show details (hierarchy and comments) in pages', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_on_post' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in posts', 'fastfood' ),
							'info'				=> __( 'show details (author, date, tags, etc) in posts', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_on_front' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in front page', 'fastfood' ),
							'info'				=> __( 'show details (author, date, tags, etc) in front page', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_static' => 
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'static info', 'fastfood' ),
							'info'				=> __( 'show details as a static list (not animated)', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_byauth' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'author', 'fastfood' ),
							'info'				=> __( 'show author in POSTS details', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_date' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'date', 'fastfood' ),
							'info'				=> __( 'show date in POSTS details', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_comm' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'comments number', 'fastfood' ),
							'info'				=> __( 'show comments number in POSTS/PAGES details', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_tag' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'tags', 'fastfood' ),
							'info'				=> __( 'show tags in POSTS details', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_cat' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'categories', 'fastfood' ),
							'info'				=> __( 'show categories in POSTS details', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_xinfos_hiera' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'hierarchy', 'fastfood' ),
							'info'				=> __( 'show hierarchy in PAGES details', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_jsani' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'javascript animations', 'fastfood' ),
							'info'				=> __( 'try disable animations if you encountered problems with javascript', 'fastfood' ),
							'req'				=> ''
		),
		'fastfood_basic_animations' => 
						array(
							'type'				=> 'lbl',
							'default'			=> 1,
							'description'		=> __( 'basic animations', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_jsani',
							'sub'				=> array( 'fastfood_basic_animation_main_menu', 'fastfood_basic_animation_navigation_buttons', 'fastfood_basic_animation_quickbar_tools', 'fastfood_basic_animation_quickbar_panels', 'fastfood_basic_animation_entry_meta', 'fastfood_basic_animation_smooth_scroll', 'fastfood_basic_animation_captions' )
		),
		'fastfood_basic_animation_main_menu' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'main menu', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_basic_animation_navigation_buttons' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'navigation buttons', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_basic_animation_quickbar_tools' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'quickbar tools', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_basic_animation_quickbar_panels' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'quickbar panels', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_basic_animation_entry_meta' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'entry meta', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_basic_animation_smooth_scroll' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'smooth scroll', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_basic_animation_captions' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'caption slide', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_I_like_it' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'I like it', 'fastfood' ),
							'info'				=> __( 'show "like" badges beside the post content', 'fastfood' ),
							'req'				=> '',
		),
		'fastfood_post_expand' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'post expander', 'fastfood' ),
							'info'				=> __( 'expands a post to show the full content when the reader clicks the "Read more..." link', 'fastfood' ),
							'req'				=> 'fastfood_jsani'
		),
		'fastfood_gallery_preview' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'thickbox', 'fastfood' ),
							'info'				=> __( '(formerly <u>gallery preview</u>) use thickbox for showing images and galleries', 'fastfood' ),
							'req'				=> 'fastfood_jsani',
							'sub'				=> array( 'fastfood_force_link_to_image' )
		),
		'fastfood_force_link_to_image' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'gallery links', 'fastfood' ),
							'info'				=> __( 'force galleries to use links to image instead of links to attachment', 'fastfood' ),
							'req'				=> 'fastfood_gallery_preview',
							'sub'				=> false
		),
		'fastfood_cust_comrep' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'custom comment form', 'fastfood' ),
							'info'				=> __( 'custom floating form for posting comments', 'fastfood' ),
							'req'				=> ''
		),
		'fastfood_quotethis'=>
						array( 
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'quote link', 'fastfood' ),
							'info'				=> __( 'show a link for easily add the selected text as a quote inside the comment form', 'fastfood' ),
							'req'				=> 'fastfood_jsani' 
						),
		'fastfood_tinynav' =>
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> '<a href="https://github.com/viljamis/TinyNav.js">Tinynav</a>',
							'info'				=> __( 'convert the primary menu in a tiny navigation menu for small screens (less than 800px wide)', 'fastfood' ),
							'req'				=> 'fastfood_jsani'
						),
		'fastfood_rsideb' => 
						array(
							'type'				=> 'lbl',
							'default'			=> 1,
							'description'		=> __( 'sidebar', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_rsidebindexes', 'fastfood_rsidebpages', 'fastfood_rsidebposts' )
		),
		'fastfood_rsidebindexes' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in indexes', 'fastfood' ),
							'info'				=> __( '(archives, search, main index...)', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_rsidebpages' => 
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'in pages', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_rsidebposts' => 
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'in posts', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_colors' => 
						array(
							'type'				=> 'lbl',
							'default'			=> 1,
							'description'		=> __( 'links colors', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_colors_link', 'fastfood_colors_link_hover', 'fastfood_colors_link_sel' )
		),
		'fastfood_colors_link' => 
						array(
							'type'				=> 'col',
							'default'			=> '#D2691E',
							'description'		=> __( 'links', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_colors_link_hover' => 
						array(
							'type'				=> 'col',
							'default'			=> '#FF4500',
							'description'		=> __( 'highlighted links', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_colors_link_sel' => 
						array(
							'type'				=> 'col',
							'default'			=> '#CCCCCC',
							'description'		=> __( 'selected links', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_breadcrumb' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'breadcrumb navigation', 'fastfood' ),
							'info'				=> '',
							'req'				=> ''
		),
		'fastfood_primary_menu' =>
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'primary menu', 'fastfood' ),
							'info'				=> __( 'uncheck if you want to hide it', 'fastfood' ),
							'req'				=> ''
						),
		'fastfood_manage_blank_title' =>
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'blank titles', 'fastfood' ),
							'info'				=> __( 'set a standard text for blank titles', 'fastfood' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_blank_title' )
						),
		'fastfood_blank_title' =>
						array(
							'type'				=> 'txt',
							'default'			=> __( '(no title)', 'fastfood' ),
							'description'		=> __( 'format', 'fastfood' ),
							'info'				=> __( 'you may use these codes:<br><code>%d</code> for post date<br><code>%f</code> for post format (if any)<br><code>%n</code> for post ID', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_featured_title' => 
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'featured titles', 'fastfood' ),
							'info'				=> sprintf( __( 'show the <a target="_blank" href="%s">thumbnail</a> (if available) beside the posts title', 'fastfood' ), 'http://codex.wordpress.org/Post_Thumbnails' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_featured_title_size' )
		),
		'fastfood_featured_title_size' => 
						array(
							'type'				=> 'sel',
							'default'			=> 50,
							'options'			=> array( 50, 75, 150 ),
							'options_readable'	=> array( '50px', '75px', '150px' ),
							'description'		=> __( 'thumbnail size', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_hide_titles' =>
						array(
							'type'				=> 'lbl',
							'default'			=> 1,
							'description'		=> __( 'hide titles', 'fastfood' ),
							'info'				=> __( 'hide posts/pages title in single view', 'fastfood' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_hide_frontpage_title', 'fastfood_hide_pages_title', 'fastfood_hide_posts_title', 'fastfood_hide_selected_entries_title' )
						),
		'fastfood_hide_frontpage_title' =>
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'in front page (if a static page is selected)', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_hide_pages_title' =>
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'in every single page', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_hide_posts_title' =>
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'in every single post', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_hide_selected_entries_title' =>
						array(
							'type'				=> 'txt',
							'default'			=> '',
							'description'		=> __( 'in selected posts/pages', 'fastfood' ),
							'info'				=> __( 'comma-separated list of IDs ( eg. <em>23,86,120</em> )', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_excerpt' =>
						array(
							'type'				=> 'lbl',
							'default'			=> 1,
							'description'		=> __( 'content summary', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_excerpt_lenght', '', 'fastfood_excerpt_more_txt', '', 'fastfood_excerpt_more_link' )
						),
		'fastfood_excerpt_lenght' =>
						array(
							'type'				=> 'int',
							'default'			=> 55,
							'description'		=> __( 'excerpt length', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_excerpt_more_txt' =>
						array(
							'type'				=> 'txt',
							'default'			=> '[...]',
							'description'		=> __( '<em>excerpt more</em> string', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_excerpt_more_link' =>
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( '<em>excerpt more</em> linked', 'fastfood' ),
							'info'				=> __( 'use the <em>excerpt more</em> string as a link to the full post', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_the_more_tag' =>
						array(
							'type'				=> 'lbl',
							'default'			=> 1,
							'description'		=> '<a href="http://support.wordpress.com/splitting-content/more-tag/">' . __( 'The More Tag', 'fastfood' ) . '</a>',
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_more_tag', '', 'fastfood_more_tag_scroll', '', 'fastfood_more_tag_always' )
						),
		'fastfood_more_tag' =>
						array(
							'type'				=> 'txt',
							'default'			=> __( '(more...)', 'fastfood' ),
							'description'		=> __( 'text', 'fastfood' ),
							'info'				=> __( 'only plain text. use <code>%t</code> as placeholder for the post title', 'fastfood' ) . ' (<a href="http://codex.wordpress.org/Customizing_the_Read_More" target="_blank">Codex</a>)',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_more_tag_scroll' =>
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'prevent page scroll', 'fastfood' ),
							'info'				=> __( 'prevent scroll when clicking the more link', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_more_tag_always' =>
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'always visible', 'fastfood' ),
							'info'				=> __( 'show a link to the page/post even if the "more" tag is not present', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_editor_style' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'editor style', 'fastfood' ),
							'info'				=> __( 'add style to the editor in order to write the post exactly how it will appear in the site', 'fastfood' ),
							'req'				=> ''
		),
		'fastfood_mobile_css' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'mobile support', 'fastfood' ),
							'info'				=> __( 'use a dedicated style in mobile devices', 'fastfood' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_mobile_css_color' )
		),
		'fastfood_mobile_css_color' =>
						array(
							'type'				=> 'opt',
							'default'			=> 'light',
							'options'			=> array( 'light', 'dark' ),
							'options_readable'	=> array( '<img src="' . get_template_directory_uri() . '/images/mobile-light.png" alt="light" />', '<img src="' . get_template_directory_uri() . '/images/mobile-dark.png" alt="dark" />' ),
							'description'		=> __( 'colors', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_head' => 
						array(
							'type'				=> 'lbl',
							'default'			=> 1,
							'description'		=> __( 'header', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> array( 'fastfood_head_h', '', 'fastfood_head_link' )
		),
		'fastfood_head_h' => 
						array(
							'type'				=> 'sel',
							'default'			=> '120px',
							'options'			=> array( '120px', '180px', '240px', '300px' ),
							'options_readable'	=> array( '120px', '180px', '240px', '300px' ),
							'description'		=> __( 'Header height', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_head_link' => 
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'linked header', 'fastfood' ),
							'info'				=> sprintf( __( 'use the header image as home link. The <a href="%s">header image</a> must be set. If enabled, the site title and description are hidden', 'fastfood' ), get_admin_url() . 'themes.php?page=custom-header' ),
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_font_family' => 
						array(
							'type'				=> 'sel',
							'default'			=> 'Verdana, Geneva, sans-serif',
							'description'		=> __( 'font family', 'fastfood' ),
							'info'				=> '',
							'options'			=> array( 'Arial, sans-serif', 'Comic Sans MS, cursive', 'Courier New, monospace', 'Georgia, serif', 'Helvetica, sans-serif', 'Lucida Console, Monaco, monospace', 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'monospace', 'Palatino Linotype, Book Antiqua, Palatino, serif', 'Tahoma, Geneva, sans-serif', 'Times New Roman, Times, serif', 'Trebuchet MS, sans-serif', 'Verdana, Geneva, sans-serif' ),
							'options_readable'	=> array( 'Arial, sans-serif', 'Comic Sans MS, cursive', 'Courier New, monospace', 'Georgia, serif', 'Helvetica, sans-serif', 'Lucida Console, Monaco, monospace', 'Lucida Sans Unicode, Lucida Grande, sans-serif', 'monospace', 'Palatino Linotype, Book Antiqua, Palatino, serif', 'Tahoma, Geneva, sans-serif', 'Times New Roman, Times, serif', 'Trebuchet MS, sans-serif', 'Verdana, Geneva, sans-serif' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_font_size' )
		),
		'fastfood_font_size' => 
						array(
							'type'				=> 'sel',
							'default'			=> '11px',
							'options'			=> array( '9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px' ),
							'options_readable'	=> array( '9px', '10px', '11px', '12px', '13px', '14px', '15px', '16px' ),
							'description'		=> __( 'font size', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
		),
		'fastfood_google_font_family'=>
						array(
							'type'				=> 'txt',
							'default'			=> '',
							'description'		=> __( 'Google web font', 'fastfood' ),
							'info'				=> __( 'Copy and paste <a href="http://www.google.com/webfonts" target="_blank"><strong>Google web font</strong></a> name here. Example: <code>Architects Daughter</code>', 'fastfood' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_google_font_subset', '', 'fastfood_google_font_body', 'fastfood_google_font_post_title', 'fastfood_google_font_post_content' )
						),
		'fastfood_google_font_subset' =>
						array(
							'type'				=> 'txt',
							'default'			=> '',
							'description'		=> __( 'subset', 'fastfood' ),
							'info'				=> __( 'comma-separated list of subsets ( eg. "latin,latin-ext,cyrillic" )', 'fastfood' ),
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_google_font_body' =>
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'for whole site', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_google_font_post_title' =>
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'for posts/pages title', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_google_font_post_content' =>
						array(
							'type'				=> 'chk',
							'default'			=> 0,
							'description'		=> __( 'for posts/pages content', 'fastfood' ),
							'info'				=> '',
							'req'				=> '',
							'sub'				=> false
						),
		'fastfood_custom_bg' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'custom background', 'fastfood' ),
							'info'				=> sprintf( __( 'use the enhanced custom background page instead of the standard one. Disable it if the <a href="%s">custom background page</a> works weird', 'fastfood' ), get_admin_url() . 'themes.php?page=custom-background' ),
							'req'				=> ''
		),
		'fastfood_navbuttons' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'navigation buttons', 'fastfood' ),
							'info'				=> __( 'the fixed navigation bar on the right. Note: Is strongly recommended to keep it enabled', 'fastfood' ),
							'req'				=> '',
							'sub'				=> array( 'fastfood_navbuttons_print', 'fastfood_navbuttons_comment', 'fastfood_navbuttons_feed', 'fastfood_navbuttons_trackback', 'fastfood_navbuttons_home', 'fastfood_navbuttons_nextprev', 'fastfood_navbuttons_newold', 'fastfood_navbuttons_topbottom' )
		),
		'fastfood_navbuttons_print' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'print preview', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_navbuttons',
							'sub'				=> false
		),
		'fastfood_navbuttons_comment' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'leave a comment', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_navbuttons',
							'sub'				=> false
		),
		'fastfood_navbuttons_feed' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'RSS feed', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_navbuttons',
							'sub'				=> false
		),
		'fastfood_navbuttons_trackback' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'trackback', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_navbuttons',
							'sub'				=> false
		),
		'fastfood_navbuttons_home' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'home', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_navbuttons',
							'sub'				=> false
		),
		'fastfood_navbuttons_nextprev' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'next/previous post', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_navbuttons',
							'sub'				=> false
		),
		'fastfood_navbuttons_newold' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'newer/older posts', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_navbuttons',
							'sub'				=> false
		),
		'fastfood_navbuttons_topbottom' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'top/bottom', 'fastfood' ),
							'info'				=> '',
							'req'				=> 'fastfood_navbuttons',
							'sub'				=> false
		),
		'fastfood_statusbar' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'status bar', 'fastfood' ),
							'info'				=> __( 'the fixed bar on bottom of page', 'fastfood' ),
							'req'				=> ''
		),
		'fastfood_custom_widgets' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'custom widgets', 'fastfood' ),
							'info'				=> __( 'add a lot of new usefull widgets', 'fastfood' ),
							'req'				=> ''
		),
		'fastfood_responsive_layout' =>
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'responsive layout', 'fastfood' ),
							'info'				=> __( 'the theme fits to small screens (less than 1024px wide)', 'fastfood' ),
							'req'				=> ''
		),
		'fastfood_custom_css' =>
						array(
							'type'				=> 'txtarea',
							'default'			=> '',
							'description'		=> __( 'custom CSS code', 'fastfood' ),
							'info'				=> __( '<strong>For advanced users only</strong>: paste here your custom css code. it will be added after the defatult style', 'fastfood' ) . ' (<a href="'. get_stylesheet_uri() .'" target="_blank">style.css</a>)',
							'req'				=> ''
		),
		'fastfood_tbcred' => 
						array(
							'type'				=> 'chk',
							'default'			=> 1,
							'description'		=> __( 'theme credits', 'fastfood' ),
							'info'				=> __( 'It is completely optional, but if you like the Theme we would appreciate it if you keep the credit link at the bottom', 'fastfood' ),
							'req'				=> '' )
	);

	$fastfood_coa = apply_filters( 'fastfood_options_array', $fastfood_coa );

	if ( $option == 'hierarchy' )
		return $hierarchy;
	elseif ( $option )
		if ( $data )
			return isset( $fastfood_coa[$option][$data] ) ? $fastfood_coa[$option][$data] : false;
		else
			return isset( $fastfood_coa[$option] ) ? $fastfood_coa[$option] : false;
	else
		return $fastfood_coa;
}


// retrive the required option. If the option ain't set, the default value is returned
if ( !function_exists( 'fastfood_get_opt' ) ) {
	function fastfood_get_opt( $opt ) {
		global $fastfood_opt;

		if ( isset( $fastfood_opt[$opt] ) ) return apply_filters( 'fastfood_option_' . $opt, $fastfood_opt[$opt], $opt );

		$defopt = fastfood_get_coa( $opt );

		if ( ! $defopt ) return null;

		if ( ( $defopt['req'] == '' ) || ( fastfood_get_opt( $defopt['req'] ) ) )
			return $defopt['default'];
		else
			return null;

	}
}
