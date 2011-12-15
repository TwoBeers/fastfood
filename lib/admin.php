<?php

global $fastfood_opt, $fastfood_current_theme;

//complete options array, with type, defaults values, description, infos and required option
function fastfood_get_coa( $option = false ) {
	$fastfood_coa = array(
		'fastfood_qbar' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'quickbar','fastfood' ),
			'info' => __( 'the sliding panel that is activated when the mouse rolls over the fixed buttons on the bottom left','fastfood' ),
			'req' => '',
			'sub' => array('fastfood_qbar_recpost','fastfood_qbar_cat','fastfood_qbar_reccom','fastfood_qbar_user','fastfood_qbar_minilogin')
		),
		'fastfood_qbar_user' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'user','fastfood' ),
			'info' => '',
			'req' => 'fastfood_qbar',
			'sub' => false
		),
		'fastfood_qbar_minilogin' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'mini login','fastfood' ),
			'info' => __( 'a small login form in the user panel','fastfood' ),
			'req' => 'fastfood_qbar_user',
			'sub' => false
		),
		'fastfood_qbar_reccom' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'recent comments','fastfood' ),
			'info' => '',
			'req' => 'fastfood_qbar',
			'sub' => false
		),
		'fastfood_qbar_cat' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'categories','fastfood' ),
			'info' => '',
			'req' => 'fastfood_qbar',
			'sub' => false
		),
		'fastfood_qbar_recpost' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'recent posts','fastfood' ),
			'info' => '',
			'req' => 'fastfood_qbar',
			'sub' => false
		),
		'fastfood_post_formats' => array(
			'group' => 'postformats',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'post formats support','fastfood' ),
			'info' => sprintf ( __( 'enable the %s feature','fastfood' ), '<a href="http://codex.wordpress.org/Post_Formats" target="_blank">Post Formats</a>'),
			'req' => ''
		),
		'fastfood_post_formats_gallery' => array(
			'group' => 'postformats',
			'type' => 'chk',
			'default' => 1,
			'description' => __( '"gallery" format','fastfood' ),
			'info' => '',
			'req' => 'fastfood_post_formats',
			'sub' => array('fastfood_post_formats_gallery_title','fastfood_post_formats_gallery_content')
		),
		'fastfood_post_formats_gallery_title' => array(
			'group' => 'postformats',
			'type' => 'sel',
			'default' => 'post title',
			'options' => array( 'post title', 'post date', 'none' ),
			'options_readable' => array( __( 'post title','fastfood' ), __( 'post date','fastfood' ), __( 'none','fastfood' ) ),
			'description' => __( 'title','fastfood' ),
			'info' => __( 'the title to show on indexes','fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_post_formats_gallery_content' => array(
			'group' => 'postformats',
			'type' => 'sel',
			'default' => 'presentation',
			'options'=>array( 'presentation', 'content', 'excerpt', 'none'),
			'options_readable'=>array( __( 'presentation','fastfood' ), __( 'content','fastfood' ), __( 'excerpt','fastfood' ), __( 'none','fastfood' ) ),
			'description' => __( 'content','fastfood' ),
			'info' => __( 'the content to show on indexes','fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_post_formats_aside' => array(
			'group' => 'postformats',
			'type' => 'chk',
			'default' => 1,
			'description' => __( '"aside" format','fastfood' ),
			'info' => '',
			'req' => 'fastfood_post_formats',
			'sub' => array('fastfood_post_view_aside')
		),
		'fastfood_post_formats_status' => array(
			'group' => 'postformats',
			'type' => 'chk',
			'default' => 1,
			'description' => __( '"status" format','fastfood' ),
			'info' => '',
			'req' => 'fastfood_post_formats',
			'sub' => array('fastfood_post_view_status')
		),
		'fastfood_post_formats_standard' => array(
			'group' => 'postformats',
			'type' => '',
			'default' => 1,
			'description' => __( 'standard format','fastfood' ),
			'info' => '',
			'req' => '',
			'sub' => array('fastfood_post_formats_standard_title','fastfood_postexcerpt')
		),
		'fastfood_postexcerpt' => array(
			'group' => 'postformats',
			'type' => 'sel',
			'default' => 0,
			'options' => array( 0, 1 ),
			'options_readable' => array( __( 'content','fastfood' ), __( 'excerpt','fastfood' ) ),
			'description' => __( 'content','fastfood' ),
			'info' => __( 'the content to show on indexes','fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_post_formats_standard_title' => array(
			'group' => 'postformats',
			'type' => 'sel',
			'default' => 'post title',
			'options' => array( 'post title', 'post date', 'none' ),
			'options_readable' => array( __( 'post title','fastfood' ), __( 'post date','fastfood' ), __( 'none','fastfood' )),
			'description' => __( 'title','fastfood' ),
			'info' => __( 'the title to show on indexes','fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_post_view_aside' => array(
			'group' => 'postformats',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'show on indexes','fastfood' ),
			'info' => __( 'by deselecting this option, the "aside" posts will be ignored and will not appear on indexes','fastfood' ),
			'req' => 'fastfood_post_formats_aside',
			'sub' => false
		),
		'fastfood_post_view_status' => array(
			'group' => 'postformats',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'show on indexes','fastfood' ),
			'info' => __( 'by deselecting this option, the "status" posts will be ignored and will not appear on indexes','fastfood' ),
			'req' => 'fastfood_post_formats_status',
			'sub' => false
		),
		'fastfood_share_this' => array(
			'group' => 'social',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'share this content','fastfood' ),
			'info' => sprintf ( __( 'show share links after the post content. also available as <a href="%s">widget</a>','fastfood' ), esc_url( admin_url( 'widgets.php' ) ) ),
			'req' => ''
		),
		'fastfood_I_like_it' => array(
			'group' => 'social',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'I like it','fastfood' ),
			'info' => __( 'show "like" badges beside the post content','fastfood' ),
			'req' => '',
			'sub' => array('fastfood_I_like_it_plus1','fastfood_I_like_it_twitter','fastfood_I_like_it_facebook','fastfood_I_like_it_linkedin','fastfood_I_like_it_stumbleupon')
		),
		'fastfood_I_like_it_plus1' => array(
			'group' => 'social',
			'type' => 'chk',
			'default' => 1,
			'description' => 'Google +1',
			'info' => '',
			'req' => 'fastfood_I_like_it',
			'sub' => false
		),
		'fastfood_I_like_it_twitter' => array(
			'group' => 'social',
			'type' => 'chk',
			'default' => 0,
			'description' => 'Twitter',
			'info' => '',
			'req' => 'fastfood_I_like_it',
			'sub' => false
		),
		'fastfood_I_like_it_facebook' => array(
			'group' => 'social',
			'type' => 'chk',
			'default' => 0,
			'description' => 'Facebook',
			'info' => '',
			'req' => 'fastfood_I_like_it',
			'sub' => false
		),
		'fastfood_I_like_it_linkedin' => array(
			'group' => 'social',
			'type' => 'chk',
			'default' => 0,
			'description' => 'LinkedIn',
			'info' => '',
			'req' => 'fastfood_I_like_it',
			'sub' => false
		),
		'fastfood_I_like_it_stumbleupon' => array(
			'group' => 'social',
			'type' => 'chk',
			'default' => 0,
			'description' => 'StumbleUpon',
			'info' => '',
			'req' => 'fastfood_I_like_it',
			'sub' => false
		),
		'fastfood_exif_info' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'images informations', 'fastfood' ),
			'info' => sprintf ( __( 'show informations (even EXIF, if present) in image attachments. also available as <a href="%s">widget</a>','fastfood' ), esc_url( admin_url( 'widgets.php' ) ) ),
			'req' => ''
		),
		'fastfood_xinfos_elements' => array(
			'group' => 'postinfo',
			'type' => '',
			'default' => 1,
			'description' => __( 'details', 'fastfood' ),
			'info' => '',
			'req' => '',
			'sub' => array('fastfood_xinfos_byauth','fastfood_xinfos_date','fastfood_xinfos_comm','fastfood_xinfos_tag','fastfood_xinfos_cat','fastfood_xinfos_hiera')
		),
		'fastfood_xinfos_on_list' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'in indexes', 'fastfood' ),
			'info' => __( 'show details (author, date, tags, etc) in posts overview (archives, search, main index...)', 'fastfood' ),
			'req' => ''
		),
		'fastfood_xinfos_on_page' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'in pages', 'fastfood' ),
			'info' => __( 'show details (hierarchy and comments) in pages', 'fastfood' ),
			'req' => ''
		),
		'fastfood_xinfos_on_post' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'in posts', 'fastfood' ),
			'info' => __( 'show details (author, date, tags, etc) in posts', 'fastfood' ),
			'req' => ''
		),
		'fastfood_xinfos_static' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 0,
			'description' => __( 'static info', 'fastfood' ),
			'info' => __( 'show details as a static list (not animated)', 'fastfood' ),
			'req' => ''
		),
		'fastfood_xinfos_byauth' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'author', 'fastfood' ),
			'info' => __( 'show author in POSTS details', 'fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_xinfos_date' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'date', 'fastfood' ),
			'info' => __( 'show date in POSTS details', 'fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_xinfos_comm' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'comments number', 'fastfood' ),
			'info' => __( 'show comments number in POSTS/PAGES details', 'fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_xinfos_tag' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'tags', 'fastfood' ),
			'info' => __( 'show tags in POSTS details', 'fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_xinfos_cat' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'categories', 'fastfood' ),
			'info' => __( 'show categories in POSTS details', 'fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_xinfos_hiera' => array(
			'group' => 'postinfo',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'hierarchy', 'fastfood' ),
			'info' => __( 'show hierarchy in PAGES details', 'fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_jsani' => array(
			'group' => 'javascript',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'javascript animations','fastfood' ),
			'info' => __( 'try disable animations if you encountered problems with javascript','fastfood' ),
			'req' => ''
		),
		'fastfood_post_expand' => array(
			'group' => 'javascript',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'post expander','fastfood' ),
			'info' => __( 'expands a post to show the full content when the reader clicks the "Read more..." link','fastfood' ),
			'req' => 'fastfood_jsani'
		),
		'fastfood_gallery_preview' => array(
			'group' => 'javascript',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'thickbox','fastfood' ),
			'info' => __( '(formerly <u>gallery preview</u>) use thickbox for showing images and galleries','fastfood' ),
			'req' => '',
			'sub' => array('fastfood_force_link_to_image')
		),
		'fastfood_force_link_to_image' => array(
			'group' => 'javascript',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'gallery links','fastfood' ),
			'info' => __( 'force galleries to use links to image instead of links to attachment','fastfood' ),
			'req' => '',
			'sub' => false
		),
		'fastfood_rsidebindexes' => array(
			'group' => 'sidebar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'in indexes','fastfood' ),
			'info' => __( 'show right sidebar in indexes (archives, search, main index...)','fastfood' ),
			'req' => ''
		),
		'fastfood_rsidebpages' => array(
			'group' => 'sidebar',
			'type' => 'chk',
			'default' => 0,
			'description' => __( 'in pages','fastfood' ),
			'info' => __( 'show right sidebar in pages','fastfood' ),
			'req' => ''
		),
		'fastfood_rsidebposts' => array(
			'group' => 'sidebar',
			'type' => 'chk',
			'default' => 0,
			'description' => __( 'in posts','fastfood' ),
			'info' => __( 'show right sidebar in posts','fastfood' ),
			'req' => ''
		),
		'fastfood_colors_link' => array(
			'group' => 'colors',
			'type' => 'col',
			'default' => '#D2691E',
			'description' => __( 'links','fastfood' ),
			'info' => __('default = #D2691E','fastfood' ),
			'req' => ''
		),
		'fastfood_colors_link_hover' => array(
			'group' => 'colors',
			'type' => 'col',
			'default' => '#FF4500',
			'description' => __( 'highlighted links','fastfood' ),
			'info' => __('default = #FF4500','fastfood' ),
			'req' => ''
		),
		'fastfood_colors_link_sel' => array(
			'group' => 'colors',
			'type' => 'col',
			'default' => '#CCCCCC',
			'description' => __( 'selected links','fastfood' ),
			'info' => __('default = #CCCCCC','fastfood' ),
			'req' => ''
		),
		'fastfood_cust_comrep' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'custom comment form','fastfood' ),
			'info' => __( 'custom floating form for posting comments','fastfood' ),
			'req' => 'fastfood_jsani'
		),
		'fastfood_breadcrumb' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'breadcrumb navigation','fastfood' ),
			'info' => '',
			'req' => ''
		),
		'fastfood_featured_title' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 0,
			'description' => __( 'featured titles','fastfood' ),
			'info' => sprintf( __( 'show the <a target="_blank" href="%s">thumbnail</a> (if available) beside the posts title','fastfood' ), 'http://codex.wordpress.org/Post_Thumbnails' ),
			'req' => '',
			'sub' => array('fastfood_featured_title_size')
		),
		'fastfood_featured_title_size' => array(
			'group' => 'other',
			'type' => 'sel',
			'default' => 50,
			'options' => array( 50, 75, 150 ),
			'options_readable' => array( '50px', '75px', '150px' ),
			'description' => __( 'thumbnail size','fastfood' ),
			'info' => '',
			'req' => '',
			'sub' => false
		),
		'fastfood_editor_style' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'editor style', 'fastfood' ),
			'info' => __( "add style to the editor in order to write the post exactly how it will appear in the site", 'fastfood' ),
			'req' => ''
		),
		'fastfood_mobile_css' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'mobile support','fastfood' ),
			'info' => __( 'use a dedicated style in mobile devices','fastfood' ),
			'req' => ''
		),
		'fastfood_wpadminbar_css' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'custom adminbar style','fastfood' ),
			'info' => __( 'style integration with the theme for admin bar','fastfood' ),
			'req' => ''
		),
		'fastfood_head_h' => array(
			'group' => 'other',
			'type' => 'sel',
			'default' => '120px',
			'options' => array( '120px', '180px', '240px', '300px' ),
			'options_readable' => array( '120px', '180px', '240px', '300px' ),
			'description' => __( 'Header height','fastfood' ),
			'info' => '',
			'req' => ''
		),
		'fastfood_head_link' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 0,
			'description' => __( 'linked header','fastfood' ),
			'info' => sprintf( __( 'use the header image as home link. The <a href="%s">header image</a> must be set. If enabled, the site title and description are hidden', 'fastfood' ), get_admin_url() . 'themes.php?page=custom-header' ),
			'req' => ''
		),
		'fastfood_font_family'=> array(
			'group' => 'other',
			'type' => 'sel',
			'default' => 'Verdana, Geneva, sans-serif',
			'description' => __( 'font family','fastfood' ),
			'info' => '',
			'options' => array('Arial, sans-serif','Comic Sans MS, cursive','Courier New, monospace','Georgia, serif','Helvetica, sans-serif','Lucida Console, Monaco, monospace','Lucida Sans Unicode, Lucida Grande, sans-serif','monospace','Palatino Linotype, Book Antiqua, Palatino, serif','Tahoma, Geneva, sans-serif','Times New Roman, Times, serif','Trebuchet MS, sans-serif','Verdana, Geneva, sans-serif'),
			'options_readable' => array('Arial, sans-serif','Comic Sans MS, cursive','Courier New, monospace','Georgia, serif','Helvetica, sans-serif','Lucida Console, Monaco, monospace','Lucida Sans Unicode, Lucida Grande, sans-serif','monospace','Palatino Linotype, Book Antiqua, Palatino, serif','Tahoma, Geneva, sans-serif','Times New Roman, Times, serif','Trebuchet MS, sans-serif','Verdana, Geneva, sans-serif'),
			'req' => '',
			'sub' => array('fastfood_font_size')
		),
		'fastfood_font_size' => array(
			'group' => 'other',
			'type' => 'sel',
			'default' => '11px',
			'options' => array('10px','11px','12px','13px','14px'),
			'options_readable' => array('10px','11px','12px','13px','14px'),
			'description' => __( 'font size','fastfood' ),
			'info' => '',
			'req' => '',
			'sub' => false
		),
		'fastfood_custom_bg' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'custom background','fastfood' ),
			'info' => sprintf( __( 'use the enhanced custom background page instead of the standard one. Disable it if the <a href="%s">custom background page</a> works weird', 'fastfood' ), get_admin_url() . 'themes.php?page=custom-background' ),
			'req' => ''
		),
		'fastfood_navbuttons' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'navigation buttons', 'fastfood' ),
			'info' => __( "the fixed navigation bar on the right. Note: Is strongly recommended to keep it enabled", 'fastfood' ),
			'req' => '',
			'sub' => array('fastfood_navbuttons_print','fastfood_navbuttons_comment','fastfood_navbuttons_feed','fastfood_navbuttons_trackback','fastfood_navbuttons_home','fastfood_navbuttons_nextprev','fastfood_navbuttons_newold','fastfood_navbuttons_topbottom')
		),
		'fastfood_navbuttons_print' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'print preview', 'fastfood' ),
			'info' => '',
			'req' => 'fastfood_navbuttons',
			'sub' => false
		),
		'fastfood_navbuttons_comment' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'leave a comment', 'fastfood' ),
			'info' => '',
			'req' => 'fastfood_navbuttons',
			'sub' => false
		),
		'fastfood_navbuttons_feed' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'RSS feed', 'fastfood' ),
			'info' => '',
			'req' => 'fastfood_navbuttons',
			'sub' => false
		),
		'fastfood_navbuttons_trackback' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'trackback', 'fastfood' ),
			'info' => '',
			'req' => 'fastfood_navbuttons',
			'sub' => false
		),
		'fastfood_navbuttons_home' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'home', 'fastfood' ),
			'info' => '',
			'req' => 'fastfood_navbuttons',
			'sub' => false
		),
		'fastfood_navbuttons_nextprev' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'next/previous post', 'fastfood' ),
			'info' => '',
			'req' => 'fastfood_navbuttons',
			'sub' => false
		),
		'fastfood_navbuttons_newold' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'newer/older posts', 'fastfood' ),
			'info' => '',
			'req' => 'fastfood_navbuttons',
			'sub' => false
		),
		'fastfood_navbuttons_topbottom' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'top/bottom', 'fastfood' ),
			'info' => '',
			'req' => 'fastfood_navbuttons',
			'sub' => false
		),
		'fastfood_statusbar' => array(
			'group' => 'quickbar',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'status bar', 'fastfood' ),
			'info' => __( "the fixed bar on bottom of page", 'fastfood' ),
			'req' => ''
		),
		'fastfood_custom_widgets' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'custom widgets', 'fastfood' ),
			'info' => __( 'add a lot of new usefull widgets', 'fastfood' ),
			'req' => ''
		),
		'fastfood_tbcred' => array(
			'group' => 'other',
			'type' => 'chk',
			'default' => 1,
			'description' => __( 'theme credits','fastfood' ),
			'info' => __( "please, don't hide theme credits",'fastfood' ),
			'req' => '' )
	);
	if ( $option )
		return $fastfood_coa[$option];
	else
		return $fastfood_coa;
}

// Add custom menus
add_action( 'admin_menu', 'fastfood_create_menu' );

// create theme option page
if ( !function_exists( 'fastfood_create_menu' ) ) {
	function fastfood_create_menu() {
		//create new top-level menu
		$pageopt = add_theme_page( __( 'Theme Options','fastfood' ), __( 'Theme Options','fastfood' ), 'edit_theme_options', 'tb_fastfood_functions', 'fastfood_edit_options' );
		//call register settings function
		add_action( 'admin_init', 'fastfood_register_tb_settings' );
		add_action( 'admin_print_styles-' . $pageopt, 'fastfood_theme_admin_styles' );
		add_action( 'admin_print_scripts-' . $pageopt, 'fastfood_theme_admin_scripts' );
		add_action( 'admin_print_styles-widgets.php', 'fastfood_widgets_style' );
		add_action( 'admin_print_scripts-widgets.php', 'fastfood_widgets_scripts' );
	}
}

if ( !function_exists( 'fastfood_theme_admin_scripts' ) ) {
	function fastfood_theme_admin_scripts() {
		global $fastfood_version;
		wp_enqueue_script( 'fastfood-options-script', get_template_directory_uri() . '/js/fastfood-options.dev.js',array( 'jquery','farbtastic' ),$fastfood_version, true ); //fastfood js
	}
}

if ( !function_exists( 'fastfood_widgets_style' ) ) {
	function fastfood_widgets_style() {
		//add custom stylesheet
		wp_enqueue_style( 'ff-widgets-style', get_template_directory_uri() . '/css/widgets.css', false, '', 'screen' );
	}
}

if ( !function_exists( 'fastfood_widgets_scripts' ) ) {
	function fastfood_widgets_scripts() {
		global $fastfood_version;
		wp_enqueue_script( 'ff-widgets-scripts', get_template_directory_uri() . '/js/fastfood-widgets.dev.js', array('jquery'), $fastfood_version, true );
	}
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'fastfood_admin_header_style' ) ) {
	function fastfood_admin_header_style() {
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/custom-header.css" />' . "\n";
		fastfood_header_switch();
	}
}

//script for the custom header image
if ( !function_exists( 'fastfood_header_switch' ) ) {
	function fastfood_header_switch() {
		global $_wp_default_headers;
		$default_headers = $_wp_default_headers;
		?>

<script type="text/javascript">
	/* <![CDATA[ */
	jQuery(document).ready( function($) {
	  $(".default-header input").click( function() {
		var def_header = $(this);
		switch( def_header.attr("value") )
		{
		<?php foreach ( $default_headers as $header_key => $header ) { ?>
			case "<?php echo esc_attr( $header_key ); ?>":
				$("#headimg").css({ 'background-image' : 'url(<?php printf( $header['url'], get_template_directory_uri(), get_stylesheet_directory_uri()); ?>)' });
				break;
		<?php } ?>
		}
	  });
	});
	/* ]]> */
</script>

		<?php
	}
}

//Add callbacks for background image display. based on WP theme.php -> add_custom_background()
if ( !function_exists( 'fastfood_add_custom_background' ) ) {
	function fastfood_add_custom_background( $header_callback = '', $admin_header_callback = '', $admin_image_div_callback = '' ) {
		if ( isset( $GLOBALS['custom_background'] ) )
			return;

		if ( empty( $header_callback ) )
			$header_callback = '_custom_background_cb';

		add_action( 'wp_head', $header_callback );

		add_theme_support( 'custom-background', array( 'callback' => $header_callback ) );

		if ( ! is_admin() )
			return;
		require_once( 'my-custom-background.php' );
		$GLOBALS['custom_background'] =& new Custom_Background( $admin_header_callback, $admin_image_div_callback );
		add_action( 'admin_menu', array( &$GLOBALS['custom_background'], 'init' ) );
	}
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'fastfood_admin_custom_bg_style' ) ) {
	function fastfood_admin_custom_bg_style() {
		echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/custom-bg.css" />' . "\n";
	}
}

if ( !function_exists( 'fastfood_register_tb_settings' ) ) {
	function fastfood_register_tb_settings() {
		//register fastfood settings
		register_setting( 'ff_settings_group', 'fastfood_options', 'fastfood_sanitaze_options' );
	}
}

// the custon header style - called only on your theme options page
if ( !function_exists( 'fastfood_theme_admin_styles' ) ) {
	function fastfood_theme_admin_styles() {
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style( 'ff-options-style', get_template_directory_uri() . '/css/ff-opt.css', false, '', 'screen' );
		?>
		<style type="text/css">
			#fastfood-infos-li div.wp-menu-image {
				background: url('<?php echo admin_url(); ?>/images/menu.png') no-repeat scroll -38px -39px transparent;
			}
			#fastfood-infos-li:hover div.wp-menu-image,
			#fastfood-infos-li.tab-selected div.wp-menu-image {
				background: url('<?php echo admin_url(); ?>/images/menu.png') no-repeat scroll -38px -7px transparent;
			}
		</style>
		<?php
	}
}

// print a reminder message for set the options after the theme is installed or updated
if ( !function_exists( 'fastfood_setopt_admin_notice' ) ) {
	function fastfood_setopt_admin_notice() {
		echo '<div class="updated"><p><strong>' . sprintf( __( 'Fastfood theme says: "Dont forget to set <a href="%s">my options</a> and the header image!"', 'fastfood' ), get_admin_url() . 'themes.php?page=tb_fastfood_functions' ) . '</strong></p></div>';
	}
}
if ( current_user_can( 'manage_options' ) && $fastfood_opt['version'] < $fastfood_current_theme['Version'] ) {
	add_action( 'admin_notices', 'fastfood_setopt_admin_notice' );
}

// sanitize options value
if ( !function_exists( 'fastfood_sanitaze_options' ) ) {
	function fastfood_sanitaze_options($input) {
		global $fastfood_current_theme;
		$fastfood_coa = fastfood_get_coa();
		// check for updated values and return 0 for disabled ones <- index notice prevention
		foreach ( $fastfood_coa as $key => $val ) {

			if( $fastfood_coa[$key]['type'] == 'chk' ) {
				if( !isset( $input[$key] ) ) {
					$input[$key] = 0;
				} else {
					$input[$key] = ( $input[$key] == 1 ? 1 : 0 );
				}
			} elseif( $fastfood_coa[$key]['type'] == 'sel' ) {
				if ( !in_array( $input[$key], $fastfood_coa[$key]['options'] ) ) $input[$key] = $fastfood_coa[$key]['default'];
			} elseif( $fastfood_coa[$key]['type'] == 'col' ) {
				$color = str_replace( '#' , '' , $input[$key] );
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $color );
				$input[$key] = '#' . $color;
			}
		}
		// check for required options
		foreach ( $fastfood_coa as $key => $val ) {
			if ( $fastfood_coa[$key]['req'] != '' ) { if ( $input[$fastfood_coa[$key]['req']] == 0 ) $input[$key] = 0; }
		}
		//$input['hidden_opt'] = 'default'; //this hidden option avoids empty $fastfood_options when updated
		$input['version'] = $fastfood_current_theme['Version']; // keep version number
		return $input;
	}
}

// check and set default options
function fastfood_default_options() {
		global $fastfood_current_theme;
		$fastfood_coa = fastfood_get_coa();
		$fastfood_opt = get_option( 'fastfood_options' );

		// if options are empty, sets the default values
		if ( empty( $fastfood_opt ) || !isset( $fastfood_opt ) ) {
			foreach ( $fastfood_coa as $key => $val ) {
				$fastfood_opt[$key] = $fastfood_coa[$key]['default'];
			}
			$fastfood_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'fastfood_options' , $fastfood_opt );
		} else if ( !isset( $fastfood_opt['version'] ) || $fastfood_opt['version'] < $fastfood_current_theme['Version'] ) {
			// check for unset values and set them to default value -> when updated to new version
			foreach ( $fastfood_coa as $key => $val ) {
				if ( !isset( $fastfood_opt[$key] ) ) $fastfood_opt[$key] = $fastfood_coa[$key]['default'];
			}
			$fastfood_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'fastfood_options' , $fastfood_opt );
		}
}

// the theme option page
if ( !function_exists( 'fastfood_edit_options' ) ) {
	function fastfood_edit_options() {
	  if ( !current_user_can( 'edit_theme_options' ) ) {
	    wp_die( 'You do not have sufficient permissions to access this page.' );
	  }
		global $fastfood_opt, $fastfood_current_theme;
		$fastfood_coa = fastfood_get_coa();

		// update version value when admin visit options page
		if ( $fastfood_opt['version'] < $fastfood_current_theme['Version'] ) {
			$fastfood_opt['version'] = $fastfood_current_theme['Version'];
			update_option( 'fastfood_options' , $fastfood_opt );
		}

		// options have been updated
		if ( isset( $_REQUEST['settings-updated'] ) ) {
			//return options save message
			echo '<div style="position: absolute;left: 50%;" id="message" class="updated fade"><p><strong>' . __( 'Options saved.','fastfood' ) . '</strong></p></div>';
		}

	?>
		<div class="wrap" id="ff-main-wrap">
			<div class="icon32" id="icon-themes"><br></div>
			<h2><?php echo get_current_theme() . ' - ' . __( 'Theme Options','fastfood' ); ?></h2>
			<ul id="ff-tabselector" class="hide-if-no-js">
				<li id="ff-selgroup-quickbar"><a href="#" onClick="fastfoodSwitchTab.set('quickbar'); return false;"><?php _e( 'Fixed bars' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-postinfo"><a href="#" onClick="fastfoodSwitchTab.set('postinfo'); return false;"><?php _e( 'Post/Page details' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-postformats"><a href="#" onClick="fastfoodSwitchTab.set('postformats'); return false;"><?php _e( 'Post formats' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-sidebar"><a href="#" onClick="fastfoodSwitchTab.set('sidebar'); return false;"><?php _e( 'Sidebar' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-javascript"><a href="#" onClick="fastfoodSwitchTab.set('javascript'); return false;"><?php _e( 'Javascript' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-social"><a href="#" onClick="fastfoodSwitchTab.set('social'); return false;"><?php _e( 'Social tools' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-other"><a href="#" onClick="fastfoodSwitchTab.set('other'); return false;"><?php _e( 'Other' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-colors"><a href="#" onClick="fastfoodSwitchTab.set('colors'); return false;"><?php _e( 'Colors' , 'fastfood' ); ?></a></li>
				<li id="ff-selgroup-info"><a target="_blank" href="<?php echo get_template_directory_uri() . '/readme.html'; ?>"><?php _e( 'Theme Info' , 'fastfood' ); ?></a></li>
			</ul>
			<ul id="selector" class="hide-if-js">
				<li id="fastfood-options-li"><a href="#fastfood-options"><?php _e( 'Options','fastfood' ); ?></a></li>
				<li id="fastfood-infos-li"><a target="_blank" href="<?php echo get_template_directory_uri() . '/readme.html'; ?>"><?php _e( 'Theme Info' , 'fastfood' ); ?></a></li>
			</ul>
			<div id="tabs-container">
				<div class="clear"></div>
				<form method="post" action="options.php">
					<div id="fastfood-options">
						<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Options','fastfood' ); ?></h2>
						<?php settings_fields( 'ff_settings_group' ); ?>
						<?php foreach ( $fastfood_coa as $key => $val ) { ?>
							<?php if ( isset( $fastfood_coa[$key]['sub'] ) && !$fastfood_coa[$key]['sub'] ) continue; ?>
							<div class="ff-tab-opt ff-tabgroup-<?php echo $fastfood_coa[$key]['group']; ?>">
								<span class="column-nam"><?php echo $fastfood_coa[$key]['description']; ?></span>
							<?php if ( !isset ( $fastfood_opt[$key] ) ) $fastfood_opt[$key] = $fastfood_coa[$key]['default']; ?>
							<?php if ( $fastfood_coa[$key]['type'] == 'chk' ) { ?>
								<input name="fastfood_options[<?php echo $key; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $fastfood_opt[$key] ); ?> />
							<?php } elseif ( $fastfood_coa[$key]['type'] == 'sel' ) { ?>
								<select name="fastfood_options[<?php echo $key; ?>]">
								<?php foreach( $fastfood_coa[$key]['options'] as $optionkey => $option ) { ?>
									<option value="<?php echo $option; ?>" <?php selected( $fastfood_opt[$key], $option ); ?>><?php echo $fastfood_coa[$key]['options_readable'][$optionkey]; ?></option>
								<?php } ?>
								</select>
							<?php } elseif ( $fastfood_coa[$key]['type'] == 'col' ) { ?>
								<div class="ff-col-tools">
									<input onclick="fastfoodShowMeColorPicker('<?php echo $key; ?>');" style="background-color:<?php echo $fastfood_opt[$key]; ?>;" class="color_preview_box" type="text" id="ff_box_<?php echo $key; ?>" value="" readonly="readonly" />
									<div class="ff_cp" id="ff_colorpicker_<?php echo $key; ?>"></div>
									<input class="ff_input" id="ff_input_<?php echo $key; ?>" type="text" name="fastfood_options[<?php echo $key; ?>]" value="<?php echo $fastfood_opt[$key]; ?>" />
									<a class="hide-if-no-js" href="#" onclick="fastfoodShowMeColorPicker('<?php echo $key; ?>'); return false;"><?php _e( 'Select a Color' , 'fastfood' ); ?></a>&nbsp;-&nbsp;
									<a class="hide-if-no-js" style="color:<?php echo $fastfood_coa[$key]['default']; ?>;" href="#" onclick="fastfoodPickColor('<?php echo $key; ?>','<?php echo $fastfood_coa[$key]['default']; ?>'); return false;"><?php _e( 'Default' , 'fastfood' ); ?></a>
								</div>
							<?php }	?>
								<div class="column-des"><?php echo $fastfood_coa[$key]['info']; ?></div>
							<?php if ( isset( $fastfood_coa[$key]['sub'] ) ) { ?>
									<div class="ff-sub-opt">
								<?php foreach ( $fastfood_coa[$key]['sub'] as $subkey => $subval ) { ?>
									<?php if ( !isset ($fastfood_opt[$subval]) ) $fastfood_opt[$subval] = $fastfood_coa[$subval]['default']; ?>
									<?php if ( $fastfood_coa[$subval]['type'] == 'chk' ) { ?>
										<input name="fastfood_options[<?php echo $subval; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $fastfood_opt[$subval] ); ?> />
										<span class="ff-sub-opt-nam"><?php echo $fastfood_coa[$subval]['description']; ?></span>
									<?php } elseif ( $fastfood_coa[$subval]['type'] == 'sel' ) { ?>
										<span class="ff-sub-opt-nam"><?php echo $fastfood_coa[$subval]['description']; ?></span> :
										<select name="fastfood_options[<?php echo $subval; ?>]">
										<?php foreach( $fastfood_coa[$subval]['options'] as $optionkey => $option ) { ?>
											<option value="<?php echo $option; ?>" <?php selected( $fastfood_opt[$subval], $option ); ?>><?php echo $fastfood_coa[$subval]['options_readable'][$optionkey]; ?></option>
										<?php } ?>
										</select>
									<?php }	?>
									<?php if ( $fastfood_coa[$subval]['info'] != '' ) { ?> - <span class="ff-sub-opt-des"><?php echo $fastfood_coa[$subval]['info']; ?></span><?php } ?>
									</br>
								<?php }	?>
									</div>
							<?php }	?>
								<?php if ( $fastfood_coa[$key]['req'] != '' ) { ?><div class="column-req"><?php echo '<u>' . __('requires','fastfood') . '</u>: ' . $fastfood_coa[$fastfood_coa[$key]['req']]['description']; ?></div><?php } ?>
							</div>
						<?php }	?>
					</div>
					<p>
						<input type="hidden" name="fastfood_options[hidden_opt]" value="default" />
						<input class="button-primary" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'fastfood' ); ?>" />
						<a style="font-size: 10px; text-decoration: none; margin-left: 10px; cursor: pointer;" href="themes.php?page=functions" target="_self"><?php _e( 'Undo Changes' , 'fastfood' ); ?></a>
					</p>
					<p class="stylediv">
						<small>
							<?php _e( 'If you like/dislike this theme, or if you encounter any issues using it, please let us know it.', 'fastfood' ); ?><br />
							<a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/tema-per-wordpress-fastfood' ); ?>" title="Fastfood theme" target="_blank"><?php _e( 'Leave a feedback', 'fastfood' ); ?></a>
						</small>
					</p>
					<p class="stylediv">
						<small>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/temi-wp/wordpress-themes-translations' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
					</p>
				</form>
				<div class="clear"></div>
			</div>
		</div>
	<?php
	}
}



?>