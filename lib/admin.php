<?php

global $fastfood_opt, $fastfood_version;

//complete options array, with type, defaults values, description, infos and required option
function fastfood_get_coa( $option = false ) {

	$fastfood_groups = array(
							'quickbar' => __( 'Elements' , 'fastfood' ),
							'postformats' => __( 'Post formats' , 'fastfood' ),
							'content' => __( 'Contents' , 'fastfood' ),
							'javascript' => __( 'Javascript' , 'fastfood' ),
							'social' => __( 'Social tools' , 'fastfood' ),
							'colors' => __( 'Style' , 'fastfood' ),
							'mobile' => __( 'Mobile' , 'fastfood' ),
							'other' => __( 'Other' , 'fastfood' )
	);

	$fastfood_groups = apply_filters( 'fastfood_options_groups', $fastfood_groups );

	$fastfood_coa = array(
		'fastfood_qbar' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'quickbar','fastfood' ),
							'info' => __( 'the sliding panel that is activated when the mouse rolls over the fixed buttons on the bottom left','fastfood' ),
							'req' => '',
							'sub' => array('fastfood_qbar_recpost','fastfood_qbar_cat','fastfood_qbar_reccom','fastfood_qbar_user','','fastfood_qbar_minilogin')
		),
		'fastfood_qbar_user' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'user','fastfood' ),
							'info' => '',
							'req' => 'fastfood_qbar',
							'sub' => false
		),
		'fastfood_qbar_minilogin' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'mini login','fastfood' ),
							'info' => __( 'a small login form in the user panel','fastfood' ),
							'req' => 'fastfood_qbar_user',
							'sub' => false
		),
		'fastfood_qbar_reccom' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'recent comments','fastfood' ),
							'info' => '',
							'req' => 'fastfood_qbar',
							'sub' => false
		),
		'fastfood_qbar_cat' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'categories','fastfood' ),
							'info' => '',
							'req' => 'fastfood_qbar',
							'sub' => false
		),
		'fastfood_qbar_recpost' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'recent posts','fastfood' ),
							'info' => '',
							'req' => 'fastfood_qbar',
							'sub' => false
		),
		'fastfood_post_formats' => 
						array(
							'group' => 'postformats',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'post formats support','fastfood' ),
							'info' => sprintf ( __( 'enable the %s feature','fastfood' ), '<a href="http://codex.wordpress.org/Post_Formats" target="_blank">Post Formats</a>'),
							'req' => ''
		),
		'fastfood_post_formats_gallery' => 
						array(
							'group' => 'postformats',
							'type' => 'chk',
							'default' => 1,
							'description' => __( '"gallery" format','fastfood' ),
							'info' => '',
							'req' => 'fastfood_post_formats',
							'sub' => array('fastfood_post_formats_gallery_title','fastfood_post_formats_gallery_content')
		),
		'fastfood_post_formats_gallery_title' => 
						array(
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
		'fastfood_post_formats_gallery_content' => 
						array(
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
		'fastfood_post_formats_aside' => 
						array(
							'group' => 'postformats',
							'type' => 'chk',
							'default' => 1,
							'description' => __( '"aside" format','fastfood' ),
							'info' => '',
							'req' => 'fastfood_post_formats',
							'sub' => array('fastfood_post_view_aside')
		),
		'fastfood_post_formats_status' => 
						array(
							'group' => 'postformats',
							'type' => 'chk',
							'default' => 1,
							'description' => __( '"status" format','fastfood' ),
							'info' => '',
							'req' => 'fastfood_post_formats',
							'sub' => array('fastfood_post_view_status')
		),
		'fastfood_post_formats_standard' => 
						array(
							'group' => 'postformats',
							'type' => '',
							'default' => 1,
							'description' => __( 'standard format','fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => array('fastfood_post_formats_standard_title','fastfood_postexcerpt')
		),
		'fastfood_postexcerpt' => 
						array(
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
		'fastfood_post_formats_standard_title' => 
						array(
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
		'fastfood_post_view_aside' => 
						array(
							'group' => 'postformats',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'show on indexes','fastfood' ),
							'info' => __( 'by deselecting this option, the "aside" posts will be ignored and will not appear on indexes','fastfood' ),
							'req' => 'fastfood_post_formats_aside',
							'sub' => false
		),
		'fastfood_post_view_status' => 
						array(
							'group' => 'postformats',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'show on indexes','fastfood' ),
							'info' => __( 'by deselecting this option, the "status" posts will be ignored and will not appear on indexes','fastfood' ),
							'req' => 'fastfood_post_formats_status',
							'sub' => false
		),
		'fastfood_share_this' => 
						array(
							'group' => 'social',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'share this content','fastfood' ),
							'info' => sprintf ( __( 'show share links after the post content. also available as <a href="%s">widget</a>','fastfood' ), esc_url( admin_url( 'widgets.php' ) ) ),
							'req' => ''
		),
		'fastfood_I_like_it' => 
						array(
							'group' => 'social',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'I like it','fastfood' ),
							'info' => __( 'show "like" badges beside the post content','fastfood' ),
							'req' => '',
							'sub' => array('fastfood_I_like_it_plus1','fastfood_I_like_it_twitter','fastfood_I_like_it_facebook','fastfood_I_like_it_linkedin','fastfood_I_like_it_stumbleupon','fastfood_I_like_it_pinterest')
		),
		'fastfood_I_like_it_plus1' => 
						array(
							'group' => 'social',
							'type' => 'chk',
							'default' => 1,
							'description' => 'Google +1',
							'info' => '',
							'req' => 'fastfood_I_like_it',
							'sub' => false
		),
		'fastfood_I_like_it_twitter' => 
						array(
							'group' => 'social',
							'type' => 'chk',
							'default' => 0,
							'description' => 'Twitter',
							'info' => '',
							'req' => 'fastfood_I_like_it',
							'sub' => false
		),
		'fastfood_I_like_it_facebook' => 
						array(
							'group' => 'social',
							'type' => 'chk',
							'default' => 0,
							'description' => 'Facebook',
							'info' => '',
							'req' => 'fastfood_I_like_it',
							'sub' => false
		),
		'fastfood_I_like_it_linkedin' => 
						array(
							'group' => 'social',
							'type' => 'chk',
							'default' => 0,
							'description' => 'LinkedIn',
							'info' => '',
							'req' => 'fastfood_I_like_it',
							'sub' => false
		),
		'fastfood_I_like_it_stumbleupon' => 
						array(
							'group' => 'social',
							'type' => 'chk',
							'default' => 0,
							'description' => 'StumbleUpon',
							'info' => '',
							'req' => 'fastfood_I_like_it',
							'sub' => false
		),
		'fastfood_I_like_it_pinterest' =>
						array(
							'group' => 'social',
							'type' => 'chk',
							'default' => 0,
							'description' => 'Pinterest',
							'info' => __( 'visible ONLY in attachments', 'fastfood' ),
							'req' => 'fastfood_I_like_it',
							'sub' => false
		),
		'fastfood_blank_title' =>
						array(
							'group' => 'content',
							'type' => 'txt',
							'default' => __( '(no title)', 'fastfood' ),
							'description' => __( 'blank titles', 'fastfood' ),
							'info' => __( 'set the standard text for blank titles. you may use these codes:<br /><code>%d</code> for post date<br /><code>%f</code> for post format (if any)', 'fastfood' ),
							'req' => ''
						),
		'fastfood_xinfos_global' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'post/page details', 'fastfood' ),
							'info' => __( 'show extra info (author, date, tags, etc)', 'fastfood' ),
							'req' => '',
							'sub' => array('fastfood_xinfos_byauth','fastfood_xinfos_date','fastfood_xinfos_comm','fastfood_xinfos_tag','fastfood_xinfos_cat','fastfood_xinfos_hiera','','fastfood_xinfos_on_list','fastfood_xinfos_on_page','fastfood_xinfos_on_post','fastfood_xinfos_on_front','','fastfood_xinfos_static')
		),
		'fastfood_xinfos_on_list' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'in indexes', 'fastfood' ),
							'info' => __( 'show details (author, date, tags, etc) in posts overview (archives, search, main index...)', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_on_page' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'in pages', 'fastfood' ),
							'info' => __( 'show details (hierarchy and comments) in pages', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_on_post' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'in posts', 'fastfood' ),
							'info' => __( 'show details (author, date, tags, etc) in posts', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_on_front' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'in front page', 'fastfood' ),
							'info' => __( 'show details (author, date, tags, etc) in front page', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_static' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'static info', 'fastfood' ),
							'info' => __( 'show details as a static list (not animated)', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_byauth' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'author', 'fastfood' ),
							'info' => __( 'show author in POSTS details', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_date' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'date', 'fastfood' ),
							'info' => __( 'show date in POSTS details', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_comm' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'comments number', 'fastfood' ),
							'info' => __( 'show comments number in POSTS/PAGES details', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_tag' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'tags', 'fastfood' ),
							'info' => __( 'show tags in POSTS details', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_cat' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'categories', 'fastfood' ),
							'info' => __( 'show categories in POSTS details', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_xinfos_hiera' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'hierarchy', 'fastfood' ),
							'info' => __( 'show hierarchy in PAGES details', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_jsani' => 
						array(
							'group' => 'javascript',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'javascript animations','fastfood' ),
							'info' => __( 'try disable animations if you encountered problems with javascript','fastfood' ),
							'req' => ''
		),
		'fastfood_post_expand' => 
						array(
							'group' => 'javascript',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'post expander','fastfood' ),
							'info' => __( 'expands a post to show the full content when the reader clicks the "Read more..." link','fastfood' ),
							'req' => 'fastfood_jsani'
		),
		'fastfood_gallery_preview' => 
						array(
							'group' => 'javascript',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'thickbox','fastfood' ),
							'info' => __( '(formerly <u>gallery preview</u>) use thickbox for showing images and galleries','fastfood' ),
							'req' => '',
							'sub' => array('fastfood_force_link_to_image')
		),
		'fastfood_force_link_to_image' => 
						array(
							'group' => 'javascript',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'gallery links','fastfood' ),
							'info' => __( 'force galleries to use links to image instead of links to attachment','fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_rsideb' => 
						array(
							'group' => 'quickbar',
							'type' => '',
							'default' => 1,
							'description' => __( 'sidebar','fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => array( 'fastfood_rsidebindexes', 'fastfood_rsidebpages', 'fastfood_rsidebposts' )
		),
		'fastfood_rsidebindexes' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'in indexes','fastfood' ),
							'info' => __( 'show right sidebar in indexes (archives, search, main index...)','fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_rsidebpages' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'in pages','fastfood' ),
							'info' => __( 'show right sidebar in pages','fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_rsidebposts' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'in posts','fastfood' ),
							'info' => __( 'show right sidebar in posts','fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_colors' => 
						array(
							'group' => 'colors',
							'type' => '',
							'default' => 1,
							'description' => __( 'links colors','fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => array( 'fastfood_colors_link', 'fastfood_colors_link_hover', 'fastfood_colors_link_sel' )
		),
		'fastfood_colors_link' => 
						array(
							'group' => 'colors',
							'type' => 'col',
							'default' => '#D2691E',
							'description' => __( 'links','fastfood' ),
							'info' => __('default = #D2691E','fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_colors_link_hover' => 
						array(
							'group' => 'colors',
							'type' => 'col',
							'default' => '#FF4500',
							'description' => __( 'highlighted links','fastfood' ),
							'info' => __('default = #FF4500','fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_colors_link_sel' => 
						array(
							'group' => 'colors',
							'type' => 'col',
							'default' => '#CCCCCC',
							'description' => __( 'selected links','fastfood' ),
							'info' => __('default = #CCCCCC','fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_cust_comrep' => 
						array(
							'group' => 'other',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'custom comment form','fastfood' ),
							'info' => __( 'custom floating form for posting comments','fastfood' ),
							'req' => 'fastfood_jsani'
		),
		'fastfood_quotethis'=>
						array( 
							'group'=>'javascript',
							'type'=>'chk',
							'default'=>1,
							'description'=>__( 'quote link', 'fastfood' ),
							'info'=>__( 'show a link for easily add the selected text as a quote inside the comment form', 'fastfood' ),
							'req'=>'fastfood_jsani' 
						),
		'fastfood_breadcrumb' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'breadcrumb navigation','fastfood' ),
							'info' => '',
							'req' => ''
		),
		'fastfood_primary_menu' =>
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'primary menu', 'fastfood' ),
							'info' => __( 'uncheck if you want to hide it','fastfood' ),
							'req' => ''
						),
		'fastfood_featured_title' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'featured titles','fastfood' ),
							'info' => sprintf( __( 'show the <a target="_blank" href="%s">thumbnail</a> (if available) beside the posts title','fastfood' ), 'http://codex.wordpress.org/Post_Thumbnails' ),
							'req' => '',
							'sub' => array('fastfood_featured_title_size')
		),
		'fastfood_featured_title_size' => 
						array(
							'group' => 'content',
							'type' => 'sel',
							'default' => 50,
							'options' => array( 50, 75, 150 ),
							'options_readable' => array( '50px', '75px', '150px' ),
							'description' => __( 'thumbnail size','fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
		),
		'fastfood_excerpt' =>
						array(
							'group' => 'content',
							'type' => '',
							'default' => 1,
							'description' => __( 'content summary', 'fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => array( 'fastfood_excerpt_lenght','', 'fastfood_excerpt_more_txt','', 'fastfood_excerpt_more_link' )
						),
		'fastfood_excerpt_lenght' =>
						array(
							'group' => 'content',
							'type' => 'int',
							'default' => 55,
							'description' => __( 'excerpt length', 'fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
						),
		'fastfood_excerpt_more_txt' =>
						array(
							'group' => 'content',
							'type' => 'txt',
							'default' => '[...]',
							'description' => __( '<em>excerpt more</em> string', 'fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
						),
		'fastfood_excerpt_more_link' =>
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 0,
							'description' => __( '<em>excerpt more</em> linked', 'fastfood' ),
							'info' => __( 'use the <em>excerpt more</em> string as a link to the full post', 'fastfood' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_more_tag' =>
						array(
							'group' => 'content',
							'type' => 'txt',
							'default' => __( '(more...)', 'fastfood' ),
							'description' => __( '"more" tag string', 'fastfood' ),
							'info' => __( 'only plain text. use <code>%t</code> as placeholder for the post title', 'fastfood' ) . ' (<a href="http://codex.wordpress.org/Customizing_the_Read_More" target="_blank">Codex</a>)',
							'req' => ''
						),
		'fastfood_exif_info' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'images informations', 'fastfood' ),
							'info' => sprintf ( __( 'show informations (even EXIF, if present) in image attachments. also available as <a href="%s">widget</a>','fastfood' ), esc_url( admin_url( 'widgets.php' ) ) ),
							'req' => ''
		),
		'fastfood_audio_player' => 
						array(
							'group' => 'content',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'audio player', 'fastfood' ),
							'info' => __( 'an audio player for all the linked audio files in post content', 'fastfood' ),
							'req' => ''
		),
		'fastfood_editor_style' => 
						array(
							'group' => 'other',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'editor style', 'fastfood' ),
							'info' => __( 'add style to the editor in order to write the post exactly how it will appear in the site', 'fastfood' ),
							'req' => ''
		),
		'fastfood_mobile_css' => 
						array(
							'group' => 'mobile',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'mobile support','fastfood' ),
							'info' => __( 'use a dedicated style in mobile devices','fastfood' ),
							'req' => '',
							'sub' => array('fastfood_mobile_css_color')
		),
		'fastfood_mobile_css_color' =>
						array(
							'group' => 'mobile',
							'type' => 'opt',
							'default' => 'light',
							'options' => array('light','dark'),
							'options_readable' => array('<img src="' . get_template_directory_uri() . '/images/mobile-light.png" alt="light" />','<img src="' . get_template_directory_uri() . '/images/mobile-dark.png" alt="dark" />'),
							'description' => __( 'colors', 'fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
						),
		'fastfood_wpadminbar_css' => 
						array(
							'group' => 'other',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'custom adminbar style','fastfood' ),
							'info' => __( 'style integration with the theme for admin bar','fastfood' ),
							'req' => ''
		),
		'fastfood_head' => 
						array(
							'group' => 'quickbar',
							'type' => '',
							'default' => 1,
							'description' => __( 'header', 'fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => array('fastfood_head_h','','fastfood_head_link')
		),
		'fastfood_head_h' => 
						array(
							'group' => 'other',
							'type' => 'sel',
							'default' => '120px',
							'options' => array( '120px', '180px', '240px', '300px' ),
							'options_readable' => array( '120px', '180px', '240px', '300px' ),
							'description' => __( 'Header height','fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
		),
		'fastfood_head_link' => 
						array(
							'group' => 'other',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'linked header','fastfood' ),
							'info' => sprintf( __( 'use the header image as home link. The <a href="%s">header image</a> must be set. If enabled, the site title and description are hidden', 'fastfood' ), get_admin_url() . 'themes.php?page=custom-header' ),
							'req' => '',
							'sub' => false
		),
		'fastfood_font_family' => 
						array(
							'group' => 'colors',
							'type' => 'sel',
							'default' => 'Verdana, Geneva, sans-serif',
							'description' => __( 'font family','fastfood' ),
							'info' => '',
							'options' => array('Arial, sans-serif','Comic Sans MS, cursive','Courier New, monospace','Georgia, serif','Helvetica, sans-serif','Lucida Console, Monaco, monospace','Lucida Sans Unicode, Lucida Grande, sans-serif','monospace','Palatino Linotype, Book Antiqua, Palatino, serif','Tahoma, Geneva, sans-serif','Times New Roman, Times, serif','Trebuchet MS, sans-serif','Verdana, Geneva, sans-serif'),
							'options_readable' => array('Arial, sans-serif','Comic Sans MS, cursive','Courier New, monospace','Georgia, serif','Helvetica, sans-serif','Lucida Console, Monaco, monospace','Lucida Sans Unicode, Lucida Grande, sans-serif','monospace','Palatino Linotype, Book Antiqua, Palatino, serif','Tahoma, Geneva, sans-serif','Times New Roman, Times, serif','Trebuchet MS, sans-serif','Verdana, Geneva, sans-serif'),
							'req' => '',
							'sub' => array('fastfood_font_size')
		),
		'fastfood_font_size' => 
						array(
							'group' => 'colors',
							'type' => 'sel',
							'default' => '11px',
							'options' => array('9px','10px','11px','12px','13px','14px','15px','16px'),
							'options_readable' => array('9px','10px','11px','12px','13px','14px','15px','16px'),
							'description' => __( 'font size','fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
		),
		'fastfood_google_font_family'=>
						array(
							'group' => 'colors',
							'type' => 'txt',
							'default' => '',
							'description' => __( 'Google web font', 'fastfood' ),
							'info' => __( 'Copy and paste <a href="http://www.google.com/webfonts" target="_blank"><strong>Google web font</strong></a> name here. Example: <code>Architects Daughter</code>', 'fastfood' ),
							'req' => '',
							'sub' => array( 'fastfood_google_font_body', 'fastfood_google_font_post_title', 'fastfood_google_font_post_content' )
						),
		'fastfood_google_font_body' =>
						array(
							'group' => 'colors',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'for whole site', 'fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
						),
		'fastfood_google_font_post_title' =>
						array(
							'group' => 'colors',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'for posts/pages title', 'fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
						),
		'fastfood_google_font_post_content' =>
						array(
							'group' => 'colors',
							'type' => 'chk',
							'default' => 0,
							'description' => __( 'for posts/pages content', 'fastfood' ),
							'info' => '',
							'req' => '',
							'sub' => false
						),
		'fastfood_custom_bg' => 
						array(
							'group' => 'other',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'custom background','fastfood' ),
							'info' => sprintf( __( 'use the enhanced custom background page instead of the standard one. Disable it if the <a href="%s">custom background page</a> works weird', 'fastfood' ), get_admin_url() . 'themes.php?page=custom-background' ),
							'req' => ''
		),
		'fastfood_navbuttons' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'navigation buttons', 'fastfood' ),
							'info' => __( 'the fixed navigation bar on the right. Note: Is strongly recommended to keep it enabled', 'fastfood' ),
							'req' => '',
							'sub' => array('fastfood_navbuttons_print','fastfood_navbuttons_comment','fastfood_navbuttons_feed','fastfood_navbuttons_trackback','fastfood_navbuttons_home','fastfood_navbuttons_nextprev','fastfood_navbuttons_newold','fastfood_navbuttons_topbottom')
		),
		'fastfood_navbuttons_print' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'print preview', 'fastfood' ),
							'info' => '',
							'req' => 'fastfood_navbuttons',
							'sub' => false
		),
		'fastfood_navbuttons_comment' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'leave a comment', 'fastfood' ),
							'info' => '',
							'req' => 'fastfood_navbuttons',
							'sub' => false
		),
		'fastfood_navbuttons_feed' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'RSS feed', 'fastfood' ),
							'info' => '',
							'req' => 'fastfood_navbuttons',
							'sub' => false
		),
		'fastfood_navbuttons_trackback' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'trackback', 'fastfood' ),
							'info' => '',
							'req' => 'fastfood_navbuttons',
							'sub' => false
		),
		'fastfood_navbuttons_home' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'home', 'fastfood' ),
							'info' => '',
							'req' => 'fastfood_navbuttons',
							'sub' => false
		),
		'fastfood_navbuttons_nextprev' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'next/previous post', 'fastfood' ),
							'info' => '',
							'req' => 'fastfood_navbuttons',
							'sub' => false
		),
		'fastfood_navbuttons_newold' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'newer/older posts', 'fastfood' ),
							'info' => '',
							'req' => 'fastfood_navbuttons',
							'sub' => false
		),
		'fastfood_navbuttons_topbottom' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'top/bottom', 'fastfood' ),
							'info' => '',
							'req' => 'fastfood_navbuttons',
							'sub' => false
		),
		'fastfood_statusbar' => 
						array(
							'group' => 'quickbar',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'status bar', 'fastfood' ),
							'info' => __( 'the fixed bar on bottom of page', 'fastfood' ),
							'req' => ''
		),
		'fastfood_custom_widgets' => 
						array(
							'group' => 'other',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'custom widgets', 'fastfood' ),
							'info' => __( 'add a lot of new usefull widgets', 'fastfood' ),
							'req' => ''
		),
		'fastfood_custom_css' =>
						array(
							'group' => 'colors',
							'type' => 'txtarea',
							'default' => '',
							'description' => __( 'custom CSS code', 'fastfood' ),
							'info' => __( '<strong>For advanced users only</strong>: paste here your custom css code. it will be added after the defatult style', 'fastfood' ) . ' (<a href="'. get_stylesheet_uri() .'" target="_blank">style.css</a>)',
							'req' => ''
		),
		'fastfood_tbcred' => 
						array(
							'group' => 'other',
							'type' => 'chk',
							'default' => 1,
							'description' => __( 'theme credits','fastfood' ),
							'info' => __( "please, don't hide theme credits",'fastfood' ),
							'req' => '' )
	);

	$fastfood_coa = apply_filters( 'fastfood_options_array', $fastfood_coa );

	if ( $option == 'groups' )
		return $fastfood_groups;
	elseif ( $option )
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
		$pageopt = add_theme_page( __( 'Theme Options','fastfood' ), __( 'Theme Options','fastfood' ), 'edit_theme_options', 'fastfood_theme_options', 'fastfood_edit_options' );
		//call register settings function
		add_action( 'admin_init', 'fastfood_register_tb_settings' );
		add_action( 'admin_print_styles-' . $pageopt, 'fastfood_theme_admin_custom_styles' );
		add_action( 'admin_print_scripts-' . $pageopt, 'fastfood_theme_admin_scripts' );
		add_action( 'admin_print_styles-widgets.php', 'fastfood_widgets_style' );
		add_action( 'admin_print_scripts-widgets.php', 'fastfood_widgets_scripts' );
	}
}

if ( !function_exists( 'fastfood_register_tb_settings' ) ) {
	function fastfood_register_tb_settings() {
		//register fastfood settings
		register_setting( 'fastfood_settings_group', 'fastfood_options', 'fastfood_sanitize_options' );
	}
}

// the custon header style - called only on your theme options page
if ( !function_exists( 'fastfood_theme_admin_custom_styles' ) ) {
	function fastfood_theme_admin_custom_styles() {
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_style( 'fastfood-options', get_template_directory_uri() . '/css/admin-options.css', false, '', 'screen' );
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

?>