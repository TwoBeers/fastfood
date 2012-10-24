<?php
/**
 * The core functions
 *
 * @package fastfood
 * @since fastfood 0.33
 */

/* custom actions */
add_action( 'admin_init', 'fastfood_default_options' ); // tell WordPress to run fastfood_default_options()
add_action( 'template_redirect', 'fastfood_allcat' ); // Add custom category page
add_action( 'admin_head', 'fastfood_post_manage_style' ); // column-thumbnail style
add_action( 'manage_posts_custom_column', 'fastfood_addthumbvalue', 10, 2 ); // column-thumbnail for posts
add_action( 'manage_pages_custom_column', 'fastfood_addthumbvalue', 10, 2 ); // column-thumbnail for pages

/* custom filters */
add_filter( 'get_comment_author_link', 'fastfood_add_quoted_on' );
add_filter( 'user_contactmethods','fastfood_new_contactmethods',10,1 );
add_filter( 'manage_posts_columns', 'fastfood_addthumbcolumn' ); // column-thumbnail for posts
add_filter( 'manage_pages_columns', 'fastfood_addthumbcolumn' ); // column-thumbnail for pages
add_filter( 'the_title', 'fastfood_title_tags_filter', 10, 2 );
add_filter( 'excerpt_length', 'fastfood_excerpt_length' );
add_filter( 'excerpt_mblength' , 'fastfood_excerpt_length' ); //WP Multibyte Patch support
add_filter( 'excerpt_more', 'fastfood_excerpt_more' );
add_filter( 'the_content_more_link', 'fastfood_more_link', 10, 2 );
add_filter( 'wp_title', 'fastfood_filter_wp_title' );

$fastfood_theme = wp_get_theme( 'fastfood' );
$fastfood_current_theme = wp_get_theme();
$fastfood_version = $fastfood_theme? $fastfood_theme['Version'] : '';

// check if in preview mode or not
$fastfood_is_printpreview = false;
if ( isset( $_GET['style'] ) && md5( $_GET['style'] ) == '8e77921d24c6f82c4bd783895e9d9cf1' ) {
	$fastfood_is_printpreview = true;
}

// check if in allcat view
$fastfood_is_allcat_page = false;
if( isset( $_GET['allcat'] ) && ( md5( $_GET['allcat'] ) == '415290769594460e2e485922904f345d' ) ) {
	$fastfood_is_allcat_page = true;
}

// show all categories list (redirect to allcat.php if allcat=y)
if ( !function_exists( 'fastfood_allcat' ) ) {
	function fastfood_allcat () {
		global $fastfood_is_allcat_page;
		if( $fastfood_is_allcat_page ) {
			get_template_part( 'allcat' );
			exit;
		}
	}
}

// check and set default options 
function fastfood_default_options() {
		global $fastfood_version;
		$the_coa = fastfood_get_coa();
		$the_opt = get_option( 'fastfood_options' );

		// if options are empty, sets the default values
		if ( empty( $the_opt ) || !isset( $the_opt ) ) {
			foreach ( $the_coa as $key => $val ) {
				$the_opt[$key] = $the_coa[$key]['default'];
			}
			$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'fastfood_options' , $the_opt );
		} else if ( !isset( $the_opt['version'] ) || $the_opt['version'] < $fastfood_version ) {
			// check for unset values and set them to default value -> when updated to new version
			foreach ( $the_coa as $key => $val ) {
				if ( !isset( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['default'];
			}
			$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'fastfood_options' , $the_opt );
		}
}

// print a reminder message for set the options after the theme is installed or updated
if ( !function_exists( 'fastfood_setopt_admin_notice' ) ) {
	function fastfood_setopt_admin_notice() {
		echo '<div class="updated"><p><strong>' . sprintf( __( "%s theme says: \"Dont forget to set <a href=\"%s\">my options</a>!\"", 'fastfood' ), 'Fastfood', get_admin_url() . 'themes.php?page=fastfood_theme_options' ) . '</strong></p></div>';
	}
}
if ( current_user_can( 'manage_options' ) && ( $fastfood_opt['version'] < $fastfood_version ) ) {
	add_action( 'admin_notices', 'fastfood_setopt_admin_notice' );
}

//Image EXIF details
if ( !function_exists( 'fastfood_exif_details' ) ) {
	function fastfood_exif_details(){
		global $post; ?>
		<div class="exif-attachment-info">
			<?php
			$imgmeta = wp_get_attachment_metadata();

			// Convert the shutter speed retrieve from database to fraction
			if ( $imgmeta['image_meta']['shutter_speed'] && (1 / $imgmeta['image_meta']['shutter_speed']) > 1) {
				if ((number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1)) == 1.3
				or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.5
				or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 1.6
				or number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1) == 2.5){
					$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 1, '.', '');
				} else {
					$pshutter = "1/" . number_format((1 / $imgmeta['image_meta']['shutter_speed']), 0, '.', '');
				}
			} else {
				$pshutter = $imgmeta['image_meta']['shutter_speed'];
			}

			// Start to display EXIF and IPTC data of digital photograph
			echo __("Width", "fastfood" ) . ": " . $imgmeta['width']."px<br />";
			echo __("Height", "fastfood" ) . ": " . $imgmeta['height']."px<br />";
			if ( $imgmeta['image_meta']['created_timestamp'] ) echo __("Date Taken", "fastfood" ) . ": " . date("d-M-Y H:i:s", $imgmeta['image_meta']['created_timestamp'])."<br />";
			if ( $imgmeta['image_meta']['copyright'] ) echo __("Copyright", "fastfood" ) . ": " . $imgmeta['image_meta']['copyright']."<br />";
			if ( $imgmeta['image_meta']['credit'] ) echo __("Credit", "fastfood" ) . ": " . $imgmeta['image_meta']['credit']."<br />";
			if ( $imgmeta['image_meta']['title'] ) echo __("Title", "fastfood" ) . ": " . $imgmeta['image_meta']['title']."<br />";
			if ( $imgmeta['image_meta']['caption'] ) echo __("Caption", "fastfood" ) . ": " . $imgmeta['image_meta']['caption']."<br />";
			if ( $imgmeta['image_meta']['camera'] ) echo __("Camera", "fastfood" ) . ": " . $imgmeta['image_meta']['camera']."<br />";
			if ( $imgmeta['image_meta']['focal_length'] ) echo __("Focal Length", "fastfood" ) . ": " . $imgmeta['image_meta']['focal_length']."mm<br />";
			if ( $imgmeta['image_meta']['aperture'] ) echo __("Aperture", "fastfood" ) . ": f/" . $imgmeta['image_meta']['aperture']."<br />";
			if ( $imgmeta['image_meta']['iso'] ) echo __("ISO", "fastfood" ) . ": " . $imgmeta['image_meta']['iso']."<br />";
			if ( $pshutter ) echo __("Shutter Speed", "fastfood" ) . ": " . sprintf( '%s seconds', $pshutter) . "<br />"
			?>
		</div>
		<?php
	}
}

//Display navigation to next/previous post when applicable
if ( !function_exists( 'fastfood_single_nav' ) ) {
	function fastfood_single_nav() {
		global $post, $fastfood_opt;
		if ( $fastfood_opt['fastfood_browse_links'] == 0 ) return;
		$next = get_previous_post();
		$prev = get_next_post();
		$next_title = get_the_title( $next ) ? get_the_title( $next ) : __( 'Previous Post', 'fastfood' );
		$prev_title = get_the_title( $prev ) ? get_the_title( $prev ) : __( 'Next Post', 'fastfood' );
	?>
		<div class="nav-single fixfloat">
			<?php if ( $prev ) { ?>
				<span class="nav-previous"><a rel="prev" href="<?php echo get_permalink( $prev ); ?>" title="<?php echo esc_attr(strip_tags( __( 'Next Post', 'fastfood' ) . ': ' . $prev_title ) ); ?>"><?php echo $prev_title; ?><?php echo fastfood_get_the_thumb( $prev->ID, 32, 32, 'tb-thumb-format' ); ?></a></span>
			<?php } ?>
			<?php if ( $next ) { ?>
				<span class="nav-next"><a rel="next" href="<?php echo get_permalink( $next ); ?>" title="<?php echo esc_attr(strip_tags( __( 'Previous Post', 'fastfood' ) . ': ' . $next_title ) ); ?>"><?php echo fastfood_get_the_thumb( $next->ID, 32, 32, 'tb-thumb-format' ); ?><?php echo $next_title; ?></a></span>
			<?php } ?>
		</div><!-- #nav-single -->
	<?php
	}
}

// print extra info for posts/pages
if ( !function_exists( 'fastfood_post_details' ) ) {
	function fastfood_post_details( $args = '' ) {
		global $post;

		$defaults = array( 'author' => 1, 'date' => 1, 'tags' => 1, 'categories' => 1, 'avatar_size' => 48, 'featured' => 0 );
		$args = wp_parse_args( $args, $defaults );

		?>
			<?php if ( $args['featured'] &&  has_post_thumbnail( $post->ID ) ) { echo '<div class="tb-post-details-thumb">' . get_the_post_thumbnail( $post->ID, 'thumbnail') . '</div>'; } ?>
			<?php if ( $args['author'] ) {
				$author = $post->post_author;
				
				$name = get_the_author_meta('nickname', $author);
				$alt_name = get_the_author_meta('user_nicename', $author);
				$avatar = get_avatar($author, $args['avatar_size'], 'Gravatar Logo', $alt_name.'-photo');
				$description = get_the_author_meta('description', $author);
				$author_link = get_author_posts_url($author);

				?>
				<div class="tb-author-bio vcard">
					<ul>
						<li class="author-avatar"><?php echo $avatar; ?></li>
						<li class="author-name"><a class="fn" href="<?php echo $author_link; ?>" ><?php echo $name; ?></a></li>
						<li class="author-description note"><?php echo $description; ?> </li>
						<li class="author-social">
							<?php if ( get_the_author_meta('twitter', $author) ) echo '<a target="_blank" class="url" title="' . esc_attr( sprintf( __('follow %s on Twitter', 'fastfood'), $name ) ) . '" href="'.get_the_author_meta('twitter', $author).'"><img alt="twitter" class="avatar" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/twitter.png" /></a>'; ?>
							<?php if ( get_the_author_meta('facebook', $author) ) echo '<a target="_blank" class="url" title="' . esc_attr( sprintf( __('follow %s on Facebook', 'fastfood'), $name ) ) . '" href="'.get_the_author_meta('facebook', $author).'"><img alt="facebook" class="avatar" width="24" height="24" src="' . get_template_directory_uri() . '/images/follow/facebook.png" /></a>'; ?>
						</li>
					</ul>
				</div>
			<?php } ?>
			<?php if ( $args['categories'] ) { echo '<span class="tb-post-details-cats">' . __( 'Categories', 'fastfood' ) . ': ' . '</span>'; the_category( ', ' ); echo '<br/>'; } ?>
			<?php if ( $args['tags'] ) { echo '<span class="tb-post-details-tags">' . __( 'Tags', 'fastfood' ) . ': ' . '</span>'; if ( !get_the_tags() ) { _e( 'No Tags', 'fastfood' ); } else { the_tags('', ', ', ''); } echo '<br/>'; } ?>
			<?php if ( $args['date'] ) { echo '<span class="tb-post-details-date">' . __( 'Published on', 'fastfood' ) . ': ' . '</span>'; echo '<b>' . get_the_time( get_option( 'date_format' ) ) . '</b>'; } ?>
		<?php
	}
}

//add share links to post/page
if ( !function_exists( 'fastfood_share_this' ) ) {
	function fastfood_share_this( $args = '' ){
		global $post;

		$defaults = array(
			'size' => 24, 
			'echo' => true,
			'compact' => false,
			'twitter' => 1,
			'facebook' => 1,
			'sina' => 1,
			'tencent' => 1,
			'qzone' => 1,
			'reddit' => 1,
			'stumbleupon' => 1,
			'digg' => 1,
			'orkut' => 1,
			'bookmarks' => 1,
			'blogger' => 1,
			'delicious' => 1,
			'linkedin' => 1,
			'tumblr' => 1,
			'mail' => 1
		);
		$args = wp_parse_args( $args, $defaults );

		$share = array();
		$pName = rawurlencode( get_the_title( $post->ID ) );
		$pHref = rawurlencode( home_url() . '/?p=' . $post->ID );
		$pLongHref = rawurlencode( get_permalink( $post->ID ) );
		$pPict = rawurlencode( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ) );
		$pSource = rawurlencode( get_bloginfo( 'name' ) );
		if ( !empty( $post->post_password ) )
			$pSum = '';
		elseif ( has_excerpt() )
			$pSum = rawurlencode( get_the_excerpt() );
		else
			$pSum = rawurlencode( wp_trim_words( $post->post_content ) );

		$share['twitter'] = array( 'Twitter', 'http://twitter.com/home?status=' . $pName . '%20-%20' . $pHref );
		$share['facebook'] = array( 'Facebook', 'http://www.facebook.com/sharer.php?u=' . $pHref. '&t=' . $pName );
		$share['sina'] = array( 'Weibo', 'http://v.t.sina.com.cn/share/share.php?url=' . $pHref );
		$share['tencent'] = array( 'Tencent', 'http://v.t.qq.com/share/share.php?url=' . $pHref . '&title=' . $pName . '&pic=' . $pPict );
		$share['qzone'] = array( 'Qzone', 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . $pHref );
		$share['reddit'] = array( 'Reddit', 'http://reddit.com/submit?url=' . $pHref . '&title=' . $pName );
		$share['stumbleupon'] = array( 'StumbleUpon', 'http://www.stumbleupon.com/submit?url=' . $pHref . '&title=' . $pName );
		$share['digg'] = array( 'Digg', 'http://digg.com/submit?url=' . $pHref . '&title=' . $pName );
		$share['orkut'] = array( 'Orkut', 'http://promote.orkut.com/preview?nt=orkut.com&tt=' . $pName . '&du=' . $pHref . '&tn=' . $pPict );
		$share['bookmarks'] = array( 'Bookmarks', 'https://www.google.com/bookmarks/mark?op=edit&bkmk=' . $pHref . '&title=' . $pName . '&annotation=' . $pSum );
		$share['blogger'] = array( 'Blogger', 'http://www.blogger.com/blog_this.pyra?t&u=' . $pHref . '&n=' . $pName . '&pli=1' );
		$share['delicious'] = array( 'Delicious', 'http://delicious.com/post?url=' . $pHref . '&title=' . $pName . '&notes=' . $pSum );
		$share['linkedin'] = array( 'LinkedIn', 'http://www.linkedin.com/shareArticle?mini=true&url=' . $pHref . '&title=' . $pName . '&source=' . $pSource . '&summary=' . $pSum );
		$share['tumblr'] = array( 'Tumblr', 'http://www.tumblr.com/share?v=3&u=' . $pHref . '&t=' . $pName . '&s=' . $pSum );
		$share['mail'] = array( 'e-mail', 'mailto:?subject=' . rawurlencode ( __( 'Check it out!', 'fastfood' ) ) . '&body=' . $pName . '%20-%20' . $pLongHref . '%0D%0A' . $pSum );

		$outer = '<div class="article-share fixfloat">';
		foreach( $share as $key => $btn ){
			if ( $args[$key] )
				$target = ( $key != 'mail' ) ? ' target="_blank"' : '';
				$outer .= '<a class="share-item" rel="nofollow"' . $target . ' id="tb-share-with-' . $key . '" href="' . $btn[1] . '"><img src="' . get_template_directory_uri() . '/images/follow/' . strtolower( $key ) . '.png" width="' . $args['size'] . '" height="' . $args['size'] . '" alt="' . $btn[0] . ' Button"  title="' . sprintf( __( 'Share with %s', 'fastfood' ), $btn[0] ) . '" /></a>';
		}
		$outer .= '</div>';
		if ( $args['echo'] ) echo $outer; else return $outer;
	}
}

// the breadcrumb
if ( !function_exists( 'fastfood_breadcrumb' ) ) {
	function fastfood_breadcrumb() {
		global $wp_query, $post, $fastfood_is_allcat_page;

		$opt 						= array();
		$opt['home'] 				= 'Home';
		$opt['sep'] 				= '';
		$opt['archive_prefix'] 		=  __( 'Archives for %s', 'fastfood' );
		$opt['search_prefix'] 		=  __( 'Search for "%s"', 'fastfood' );
		$opt['item_tag']			= 'li';
		$opt['item_class']			= '';
		$opt['wrap_tag']			= 'ul';
		$opt['wrap_class']			= '';
		$opt['class_first']			= 'first';
		$opt['class_last']			= 'last';
		$opt['nofollow']			= ' rel="nofollow" ';


		$sep = '%%FF_SEP%%';
		if ( !function_exists( 'fastfood_get_category_parents' ) ) {
			// Copied and adapted from WP source
			function fastfood_get_category_parents( $id, $link = FALSE, $separator = '%%FF_SEP%%' ){
				global $wp_query;
				$chain = '';
				$parent = &get_category( $id );
				if ( is_wp_error( $parent ) )
				   return $parent;

				$name = $parent->cat_name . ' (' . $wp_query->found_posts . ')';

				if ( $parent->parent && ( $parent->parent != $parent->term_id ) )
				   $chain .= get_category_parents( $parent->parent, true, $separator, FALSE );

				$chain .= '<span class="crumb-cat">' . $name . '</span>';
				return $chain;
			}
		}

		$on_front = get_option( 'show_on_front' );
		if ( $on_front == "page" ) {
			$homelink = '<a class="crumb-home"' . $opt['nofollow'] . 'href="' . get_permalink( get_option( 'page_on_front' ) ) . '">&nbsp;</a>';
			$bloglink = $homelink . $sep . '<a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '">' . get_the_title( get_option( 'page_for_posts' ) ) . '</a>';
		} else {
			$homelink = '<a class="crumb-home"' . $opt['nofollow'] . 'href="' . home_url() . '">&nbsp;</a>';
			$bloglink = $homelink;
		}

		if ( $fastfood_is_allcat_page ) {
			$output = $homelink . $sep . '<span>' . __( 'All Categories', 'fastfood' ) . '</span>';
		} elseif ( ( $on_front == "page" && is_front_page() ) || ( $on_front == "posts" && is_home() ) ) {
			$output = $homelink . $sep . '<span>' . $opt['home'] . '</span>';
		} elseif ( $on_front == "page" && is_home() ) {
			$output = $homelink . $sep . '<span>' . get_the_title( get_option( 'page_for_posts' ) ) . '</span>';
		} elseif ( !is_page() ) {
			$output = $bloglink . $sep;
			if ( is_single() && has_category() ) {
				$cats = get_the_category();
				$cat = $cats[0];
				if ( is_object( $cat ) ) {
					if ( $cat->parent != 0 ) {
						$output .= get_category_parents( $cat->term_id, true, $sep );
					} else {
						$output .= '<a href="' . get_category_link( $cat->term_id ) . '">' . $cat->name . '</a>' . $sep;
					}
				}
			}
			if ( is_category() ) {
				$cat = intval( get_query_var( 'cat' ) );
				$output .= fastfood_get_category_parents( $cat, false, $sep );
			} elseif ( is_tag() ) {
				$title = single_term_title( '', false );
				$output .= '<span class="crumb-tag">' . sprintf( $opt['archive_prefix'], $title ) . ' (' . $wp_query->found_posts . ')</span>';
			} elseif ( is_date() ) {
				if ( is_day() ) {
					$title = get_the_date();
				} else if ( is_month() ) {
					$title = single_month_title( ' ', false );
				} else if ( is_year() ) {
					$title = get_query_var( 'year' );
				}
				$output .= '<span class="crumb-date">' . sprintf( $opt['archive_prefix'], $title ) . ' (' . $wp_query->found_posts . ')</span>';
			} elseif ( is_author() ) {
				$author = get_queried_object();
				$title = $author->display_name;
				$output .= '<span class="crumb-auth">' . sprintf( $opt['archive_prefix'], $title ) . ' (' . $wp_query->found_posts . ')</span>';
			} elseif ( is_404() ) {
				$output .= '<span class="crumb-error">' . __( 'Page not found', 'fastfood' ) . '</span>';
			} elseif ( is_search() ) {
				$output .= '<span class="crumb-search">' . sprintf( $opt['search_prefix'], stripslashes( strip_tags(get_search_query() ) ) ) . ' (' . $wp_query->found_posts . ')</span>';
			} elseif ( is_attachment() ) {
				if ( $post->post_parent ) {
					$output .= '<a href="' . get_permalink( $post->post_parent ) . '">' . get_the_title( $post->post_parent ) . '</a>' . $sep;
				}
				$output .= '<span>' . get_the_title() . '</span>';
			} else if ( is_tax() ) {
				$taxonomy 	= get_taxonomy ( get_query_var( 'taxonomy' ) );
				$term 		= get_query_var( 'term' );
				$output .= '<span>' . $taxonomy->label . ': ' . $term . ' (' . $wp_query->found_posts . ')</span>';
			} else {
				if ( get_query_var( 'page' ) ) {
					$output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>' . $sep . '<span>' . sprintf( __( 'Page %s', 'fastfood' ), get_query_var( 'page' ) ) . '</span>';
				} else {
					$output .= get_the_title() ? '<span>' . get_the_title() . '</span>' : '<span>' . sprintf ( __( 'post #%s', 'fastfood' ), get_the_ID() ) . '</span>';
				}
			}
		} else {
			$post = $wp_query->get_queried_object();

			// If this is a top level Page, it's simple to output the breadcrumb
			if ( 0 == $post->post_parent ) {
				if ( get_query_var( 'page' ) ) {
					$output = $homelink . $sep . '<a href="' . get_permalink() . '">' . get_the_title() . '</a>' . $sep . '<span>' . sprintf( __( 'Page %s', 'fastfood' ), get_query_var( 'page' ) ) . '</span>';
				} else {
					$output = $homelink . $sep . '<span>' . get_the_title() . '</span>';
				}
			} else {
				if ( isset( $post->ancestors ) ) {
					if ( is_array( $post->ancestors ) )
						$ancestors = array_values( $post->ancestors );
					else
						$ancestors = array( $post->ancestors );
				} else {
					$ancestors = array( $post->post_parent );
				}

				// Reverse the order so it's oldest to newest
				$ancestors = array_reverse( $ancestors );

				// Add the current Page to the ancestors list (as we need it's title too)
				$ancestors[] = $post->ID;

				$links = array();
				foreach ( $ancestors as $ancestor ) {
					$tmp  = array();
					$tmp['title'] 	= strip_tags( get_the_title( $ancestor ) );
					$tmp['url'] 	= get_permalink( $ancestor );
					$tmp['cur'] = false;
					if ( $ancestor == $post->ID ) {
						$tmp['cur'] = true;
					}
					$links[] = $tmp;
				}

				$output = $homelink;
				foreach ( $links as $link ) {
					$output .= ' ' . $sep;
					if ( !$link['cur'] ) {
						$output .= '<a href="' . $link['url'] . '">' . $link['title'] . '</a>';
					} else {
						if ( get_query_var( 'page' ) ) {
							$output .= '<a href="' . $link['url'] . '">' . $link['title'] . '</a>' . $sep . '<span>' . sprintf( __( 'Page %s', 'fastfood' ), get_query_var( 'page' ) ) . '</span>';
						} else {
							$output .= '<span>' . $link['title'] . '</span>';
						}
					}
				}
			}
		}
		if ( get_query_var( 'paged' ) ) {
			$output .= $sep . '<span>' . sprintf( __( 'Page %s', 'fastfood' ), get_query_var( 'paged' ) ) . '</span>';
		}

		$output_items = explode( $sep, $output ) ;

		$class					= array();
		$class['wrap']			= ( $opt['wrap_class'] ) ? ' class="' . $opt['wrap_class'] . '"' : '';
		$class['item']			= ( $opt['item_class'] ) ? ' class="' . $opt['item_class'] . '"' : '';
		$opt['item_class']		= ( $opt['item_class'] ) ? ' ' . $opt['item_class'] : '';
		$class['item_first']	= ( $opt['item_class'] || $opt['class_first'] ) ? ' class="' . $opt['class_first'] . $opt['item_class'] . '"' : '';
		$class['item_last']		= ( $opt['item_class'] || $opt['class_last'] ) ? ' class="' . $opt['class_last'] . $opt['item_class'] . '"' : '';

		if ( count( $output_items ) == 0 ) return;
		if ( count( $output_items ) == 1 ) $output_items[0] = '<' . $opt['item_tag'] . ' class="' . $opt['last'] . '">' . $output_items[0] . '</' . $opt['item_tag'] . '>';
		if ( count( $output_items ) > 1 ) {
			foreach ( $output_items as $key => $val ) {
				if ( $key == ( count( $output_items )-1 ) ) {
					$output_items[$key] = '<' . $opt['item_tag'] . $class['item_last'] . '>' . $val . '</' . $opt['item_tag'] . '>';
				} elseif ( $key == 0 ) {
					$output_items[$key] = '<' . $opt['item_tag'] . $class['item_first'] . '>' . $val . '</' . $opt['item_tag'] . '>';
				} else {
					$output_items[$key] = '<' . $opt['item_tag'] . $class['item'] . '>' . $val . '</' . $opt['item_tag'] . '>';
				}
			}
		}

		$output = '<' . $opt['wrap_tag'] . $class['wrap'] . '>' . implode( $opt['sep'], $output_items ) . '</' . $opt['wrap_tag'] . '>';
		?>
		<div id="ff-breadcrumb-wrap">
			<div id="crumbs">
			<?php echo $output; ?>
			</div>
		</div>
		<?php
	}
}

// Get first image of a post
if ( !function_exists( 'fastfood_get_first_image' ) ) {
	function fastfood_get_first_image() {
		global $post, $posts;
		$first_info = array( 'img' => '', 'title' => '', 'src' => '' );
		//search the images in post content
		preg_match_all( '/<img[^>]+>/i',$post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_info['img'] = $result[0][0];
			$first_img = $result [0][0];
			//get the title (if any)
			preg_match_all( '/(title)=("[^"]*")/i',$first_img, $img_title );
			if ( isset( $img_title[2][0] ) ){
				$first_info['title'] = str_replace( '"','',$img_title[2][0] );
			}
			//get the path
			preg_match_all( '/(src)=("[^"]*")/i',$first_img, $img_src );
			if ( isset( $img_src[2][0] ) ){
				$first_info['src'] = str_replace( '"','',$img_src[2][0] );
			}
			return $first_info;
		} else {
			return false;
		}
	}
}

// Get first link of a post
if ( !function_exists( 'fastfood_get_first_link' ) ) {
	function fastfood_get_first_link() {
		global $post, $posts;
		$first_info = array( 'anchor' => '', 'title' => '', 'href' => '', 'text' => '' );
		//search the link in post content
		preg_match_all( "/<a\b[^>]*>(.*?)<\/a>/i",$post->post_content, $result );
		//grab the first one
		if ( isset( $result[0][0] ) ){
			$first_info['anchor'] = $result[0][0];
			$first_info['text'] = isset( $result[1][0] ) ? $result[1][0] : '';
			//get the title (if any)
			preg_match_all( '/(title)=(["\'][^"]*["\'])/i',$first_info['anchor'], $link_title );
			$first_info['title'] = isset( $link_title[2][0] ) ? str_replace( array('"','\''),'',$link_title[2][0] ) : '';
			//get the path
			preg_match_all( '/(href)=(["\'][^"]*["\'])/i',$first_info['anchor'], $link_href );
			$first_info['href'] = isset( $link_href[2][0] ) ? str_replace( array('"','\''),'',$link_href[2][0] ) : '';
			return $first_info;
		} else {
			return false;
		}
	}
}

// Get first blockquote words
if ( !function_exists( 'fastfood_get_blockquote' ) ) {
	function fastfood_get_blockquote() {
		global $post, $posts;
		$first_quote = array( 'quote' => '', 'cite' => '' );
		//search the blockquote in post content
		preg_match_all( '/<blockquote\b[^>]*>([\w\W]*?)<\/blockquote>/',$post->post_content, $blockquote );
		//grab the first one
		if ( isset( $blockquote[0][0] ) ){
			$first_quote['quote'] = strip_tags( $blockquote[0][0] );
			$words = explode( " ", $first_quote['quote'], 6 );
			if ( count( $words ) == 6 ) $words[5] = '...';
			$first_quote['quote'] = implode( ' ', $words );
			preg_match_all( '/<cite>([\w\W]*?)<\/cite>/',$blockquote[0][0], $cite );
			$first_quote['cite'] = ( isset( $cite[1][0] ) ) ? $cite[1][0] : '';
			return $first_quote;
		} else {
			return false;
		}
	}
}

if ( !function_exists( 'fastfood_theme_admin_scripts' ) ) {
	function fastfood_theme_admin_scripts() {
		global $fastfood_version;
		wp_enqueue_script( 'fastfood-options', get_template_directory_uri().'/js/admin-options.dev.js',array('jquery','farbtastic','thickbox'),$fastfood_version, true ); //thebird js
		$data = array(
			'confirm_to_defaults' => __( 'Are you really sure you want to set all the options to their default values?', 'fastfood' )
		);
		wp_localize_script( 'fastfood-options', 'fastfood_l10n', $data );
	}
}

if ( !function_exists( 'fastfood_widgets_style' ) ) {
	function fastfood_widgets_style() {
		//add custom stylesheet
		wp_enqueue_style( 'fastfood-widgets', get_template_directory_uri() . '/css/admin-widgets.css', false, '', 'screen' );
	}
}

if ( !function_exists( 'fastfood_widgets_scripts' ) ) {
	function fastfood_widgets_scripts() {
		global $fastfood_version;
		wp_enqueue_script( 'fastfood-widgets', get_template_directory_uri() . '/js/admin-widgets.dev.js', array('jquery'), $fastfood_version, true );
	}
}

// the custon header style - called only on your theme options page
if ( !function_exists( 'fastfood_theme_admin_styles' ) ) {
	function fastfood_theme_admin_styles() {
		wp_enqueue_style( 'fastfood-options', get_template_directory_uri() . '/css/options.css', array('farbtastic','thickbox'), '', 'screen' );
	}
}

// sanitize options value
if ( !function_exists( 'fastfood_sanitize_options' ) ) {
	function fastfood_sanitize_options($input) {
		global $fastfood_version;

		$the_coa = fastfood_get_coa();

		foreach ( $the_coa as $key => $val ) {
	
			if( $the_coa[$key]['type'] == 'chk' ) {								//CHK
				if( !isset( $input[$key] ) ) {
					$input[$key] = 0;
				} else {
					$input[$key] = ( $input[$key] == 1 ? 1 : 0 );
				}

			} elseif( $the_coa[$key]['type'] == 'sel' ) {						//SEL
				if ( !in_array( $input[$key], $the_coa[$key]['options'] ) )
					$input[$key] = $the_coa[$key]['default'];

			} elseif( $the_coa[$key]['type'] == 'opt' ) {						//OPT
				if ( !in_array( $input[$key], $the_coa[$key]['options'] ) )
					$input[$key] = $the_coa[$key]['default'];

			} elseif( $the_coa[$key]['type'] == 'col' ) {						//COL
				$color = str_replace( '#' , '' , $input[$key] );
				$color = preg_replace( '/[^0-9a-fA-F]/' , '' , $color );
				$input[$key] = '#' . $color;

			} elseif( $the_coa[$key]['type'] == 'url' ) {						//URL
				$input[$key] = esc_url( trim( strip_tags( $input[$key] ) ) );

			} elseif( $the_coa[$key]['type'] == 'txt' ) {						//TXT
				if( !isset( $input[$key] ) ) {
					$input[$key] = '';
				} else {
					$input[$key] = trim( strip_tags( $input[$key] ) );
				}

			} elseif( $the_coa[$key]['type'] == 'int' ) {						//INT
				if( !isset( $input[$key] ) ) {
					$input[$key] = $the_coa[$key]['default'];
				} else {
					$input[$key] = (int) $input[$key] ;
				}

			} elseif( $the_coa[$key]['type'] == 'txtarea' ) {					//TXTAREA
				if( !isset( $input[$key] ) ) {
					$input[$key] = '';
				} else {
					$input[$key] = trim( strip_tags( $input[$key] ) );
				}
			}
		}

		// check for required options
		foreach ( $the_coa as $key => $val ) {
			if ( $the_coa[$key]['req'] != '' ) { if ( $input[$the_coa[$key]['req']] == ( 0 || '') ) $input[$key] = 0; }
		}

		$input['version'] = $fastfood_version; // keep version number
		return $input;
	}
}

// the theme option page
if ( !function_exists( 'fastfood_edit_options' ) ) {
	function fastfood_edit_options() {

		if ( !current_user_can( 'edit_theme_options' ) ) wp_die( 'You do not have sufficient permissions to access this page.' );

		global $fastfood_opt, $fastfood_current_theme, $fastfood_version;

		$the_coa = fastfood_get_coa();
		$the_groups = fastfood_get_coa( 'groups' );
		$the_option_name = 'fastfood_options';

		if ( isset( $_GET['erase'] ) ) {
			$_SERVER['REQUEST_URI'] = remove_query_arg( 'erase', $_SERVER['REQUEST_URI'] );
			delete_option( $the_option_name );
			fastfood_default_options();
			$fastfood_opt = get_option( $the_option_name );
		}

		// update version value when admin visit options page
		if ( $fastfood_opt['version'] < $fastfood_version ) {
			$fastfood_opt['version'] = $fastfood_version;
			update_option( $the_option_name , $fastfood_opt );
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
			<div class="icon32 icon-settings" id="theme-icon"><br></div>
			<h2><?php echo $fastfood_current_theme . ' - ' . __( 'Theme Options','fastfood' ); ?></h2>
			<ul id="tabselector" class="hide-if-no-js">
<?php
				foreach( $the_groups as $key => $name ) {
?>
				<li id="selgroup-<?php echo $key; ?>"><a href="#" onClick="fastfoodOptions.switchTab('<?php echo $key; ?>'); return false;"><?php echo $name; ?></a></li>
<?php 
				}
?>
				<li id="selgroup-info"><a href="#" onClick="fastfoodOptions.switchTab('info'); return false;"><?php _e( 'Theme Info' , 'fastfood' ); ?></a></li>
			</ul>
			<ul id="selector" class="hide-if-js">
				<li id="theme-options-li"><a href="#theme-options"><?php _e( 'Options','fastfood' ); ?></a></li>
				<li id="theme-infos-li"><a href="#theme-infos"><?php _e( 'Theme Info','fastfood' ); ?></a></li>
			</ul>
			<div id="tabs-container">
				<div class="clear"></div>
				<div id="theme-options">
					<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Options','fastfood' ); ?></h2>
					<form method="post" action="options.php">
						<?php settings_fields( 'fastfood_settings_group' ); ?>
						<?php foreach ($the_coa as $key => $val) { ?>
							<?php if ( isset( $the_coa[$key]['sub'] ) && !$the_coa[$key]['sub'] ) continue; ?>
							<div class="tab-opt tabgroup-<?php echo $the_coa[$key]['group']; ?> type-<?php echo $the_coa[$key]['type']? $the_coa[$key]['type'] : 'container'; ?>">
								<span class="column-nam"><?php echo $the_coa[$key]['description']; ?></span>
							<?php if ( !isset ( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['default']; ?>
							<?php if ( $the_coa[$key]['type'] == 'chk' ) { ?>
									<input name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $the_opt[$key] ); ?> />
							<?php } elseif ( $the_coa[$key]['type'] == 'sel' ) { ?>
									<select name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]">
									<?php foreach($the_coa[$key]['options'] as $optionkey => $option) { ?>
										<option value="<?php echo $option; ?>" <?php selected( $the_opt[$key], $option ); ?>><?php echo $the_coa[$key]['options_readable'][$optionkey]; ?></option>
									<?php } ?>
									</select>
							<?php } elseif ( $the_coa[$key]['type'] == 'opt' ) { ?>
								<?php foreach( $the_coa[$key]['options'] as $optionkey => $option ) { ?>
									<label title="<?php echo esc_attr($option); ?>"><input type="radio" <?php checked( $the_opt[$key], $option ); ?> value="<?php echo $option; ?>" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]"> <span><?php echo $the_coa[$key]['options_readable'][$optionkey]; ?></span></label>
								<?php } ?>
							<?php } elseif ( $the_coa[$key]['type'] == 'url' ) { ?>
									<input class="option_text" id="option_field_<?php echo $key; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" />
									<?php if ( $key == 'fastfood_logo' ) {
										$fastfood_arr_params['tb_media'] = '1'; 
										$fastfood_arr_params['_wpnonce'] = wp_create_nonce( 'logo-nonce' );
										?>
										<input class="hide-if-no-js button" type="button" value="<?php echo __( 'Select', 'fastfood' ); ?>" onClick="tb_show( '<?php echo __( 'Click an image to select', 'fastfood' ); ?>', '<?php echo add_query_arg( $fastfood_arr_params, home_url() ); ?>&amp;TB_iframe=true'); return false;" />
									<?php } ?>
							<?php } elseif ( $the_coa[$key]['type'] == 'txt' ) { ?>
									<input class="option_text" id="option_field_<?php echo $key; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" />
							<?php } elseif ( $the_coa[$key]['type'] == 'int' ) { ?>
									<input class="option_text" id="option_field_<?php echo $key; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]" value="<?php echo $the_opt[$key]; ?>" />
							<?php } elseif ( $the_coa[$key]['type'] == 'txtarea' ) { ?>
									<textarea name="<?php echo $the_option_name; ?>[<?php echo $key; ?>]"><?php echo $the_opt[$key]; ?></textarea>
							<?php }	?>
							<?php if ( $the_coa[$key]['info'] != '' ) { ?><div class="column-des"><?php echo $the_coa[$key]['info']; ?></div><?php } ?>
							<?php if ( isset( $the_coa[$key]['sub'] ) ) { ?>
									<div class="sub-opt-wrap">
								<?php foreach ($the_coa[$key]['sub'] as $subkey => $subval) { ?>
									<?php if ( $subval == '' ) { echo '<br />'; continue;} ?>
										<div class="sub-opt type-<?php echo $the_coa[$subval]['type']; ?>">
										<?php if ( !isset ($the_opt[$subval]) ) $the_opt[$subval] = $the_coa[$subval]['default']; ?>
											<?php if ( $the_coa[$subval]['description'] != '' ) { ?><span><?php echo $the_coa[$subval]['description']; ?> : </span><?php } ?>
										<?php if ( $the_coa[$subval]['type'] == 'chk' ) { ?>
												<input name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="1" type="checkbox" class="ww_opt_p_checkbox" <?php checked( 1 , $the_opt[$subval] ); ?> />
										<?php } elseif ( $the_coa[$subval]['type'] == 'sel' ) { ?>
												<select name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]">
												<?php foreach($the_coa[$subval]['options'] as $optionkey => $option) { ?>
													<option value="<?php echo $option; ?>" <?php selected( $the_opt[$subval], $option ); ?>><?php echo $the_coa[$subval]['options_readable'][$optionkey]; ?></option>
												<?php } ?>
												</select>
										<?php } elseif ( $the_coa[$subval]['type'] == 'opt' ) { ?>
											<?php foreach( $the_coa[$subval]['options'] as $optionkey => $option ) { ?>
												<label title="<?php echo esc_attr($option); ?>"><input type="radio" <?php checked( $the_opt[$subval], $option ); ?> value="<?php echo $option; ?>" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]"> <span><?php echo $the_coa[$subval]['options_readable'][$optionkey]; ?></span></label>
											<?php } ?>
										<?php } elseif ( $the_coa[$subval]['type'] == 'url' ) { ?>
												<input class="option_text" id="option_field_<?php echo $subval; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" />
										<?php } elseif ( $the_coa[$subval]['type'] == 'txt' ) { ?>
												<input class="option_text" id="option_field_<?php echo $subval; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" />
										<?php } elseif ( $the_coa[$subval]['type'] == 'int' ) { ?>
												<input class="option_text" id="option_field_<?php echo $subval; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" />
										<?php } elseif ( $the_coa[$subval]['type'] == 'col' ) { ?>
												<div class="col-tools">
													<input onclick="fastfoodOptions.showColorPicker('<?php echo $subval; ?>');" style="background-color:<?php echo $the_opt[$subval]; ?>;" class="color_preview_box" type="text" id="option_color_box_<?php echo $subval; ?>" value="" readonly="readonly" />
													<div class="option_cp" id="option_colorpicker_<?php echo $subval; ?>"></div>
													<input class="option_text" id="option_color_input_<?php echo $subval; ?>" type="text" name="<?php echo $the_option_name; ?>[<?php echo $subval; ?>]" value="<?php echo $the_opt[$subval]; ?>" />
													<br />
													<a class="hide-if-no-js" href="#" onclick="fastfoodOptions.showColorPicker('<?php echo $subval; ?>'); return false;"><?php _e( 'Select a Color' , 'fastfood' ); ?></a>
													<br />
													<a class="hide-if-no-js" style="color:<?php echo $the_coa[$subval]['default']; ?>;" href="#" onclick="fastfoodOptions.updateColor('<?php echo $subval; ?>','<?php echo $the_coa[$subval]['default']; ?>'); return false;"><?php _e( 'Default' , 'fastfood' ); ?></a>
													<br class="clear" />
												</div>
										<?php }	?>
										<?php if ( $the_coa[$subval]['info'] != '' ) { ?> - <span class="sub-opt-des"><?php echo $the_coa[$subval]['info']; ?></span><?php } ?>
											</div>
									<?php }	?>
										<br class="clear" />
									</div>
							<?php }	?>
								<?php if ( $the_coa[$key]['req'] != '' ) { ?><div class="column-req"><?php echo '<u>' . __('requires','fastfood') . '</u>: ' . $the_coa[$the_coa[$key]['req']]['description']; ?></div><?php } ?>
							</div>
						<?php }	?>
						<p id="buttons">
							<input type="hidden" name="<?php echo $the_option_name; ?>[hidden_opt]" value="default" />
							<input class="button-primary" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'fastfood' ); ?>" />
							<span class="extra-actions"><a href="themes.php?page=fastfood_theme_options" target="_self"><?php _e( 'Undo Changes' , 'fastfood' ); ?></a> | <a id="to-defaults" href="themes.php?page=fastfood_theme_options&erase=1" target="_self"><?php _e( 'Back to defaults' , 'fastfood' ); ?></a></span>
						</p>
					</form>
					<p class="theme-notes">
						<small><?php _e( 'If you like/dislike this theme, or if you encounter any issues using it, please let us know it.', 'fastfood' ); ?> &raquo; <a href="<?php echo esc_url( 'http://www.twobeers.net/annunci/tema-per-wordpress-fastfood' ); ?>" title="fastfood theme" target="_blank"><?php _e( 'Leave a feedback', 'fastfood' ); ?></a></small>
						<br />-<br />
						<small>Support the theme in your language, provide a <a href="<?php echo esc_url( 'http://www.twobeers.net/wp-themes/themes-translations-wordpress' ); ?>" title="Themes translation" target="_blank">translation</a>.</small>
					</p>
				</div>
				<div id="theme-infos">
					<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Theme Info', 'fastfood' ); ?></h2>
					<?php locate_template( 'readme.html',true ); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	<?php
	}
}

//Displays the amount of time since a post or page was written in a nice friendly manner.
//Based on Plugin: Date in a nice tone (http://wordpress.org/extend/plugins/date-in-a-nice-tone/)
if ( !function_exists( 'fastfood_friendly_date' ) ) {
	function fastfood_friendly_date() {
			
		$postTime = get_the_time('U');
		$currentTime = time();
		$timeDifference = $currentTime - $postTime;
		
		$minInSecs = 60;
		$hourInSecs = 3600;
		$dayInSecs = 86400;
		$monthInSecs = $dayInSecs * 31;
		$yearInSecs = $dayInSecs * 366;

		//if over 2 years
		if ($timeDifference > ($yearInSecs * 2)) {
			$dateWithNiceTone = __( 'quite a long while ago...', 'fastfood' );

		//if over a year 
		} else if ($timeDifference > $yearInSecs) {
			$dateWithNiceTone = __( 'over a year ago', 'fastfood' );

		//if over 2 months
		} else if ($timeDifference > ($monthInSecs * 2)) {
			$num = round($timeDifference / $monthInSecs);
			$dateWithNiceTone = sprintf(__('%s months ago', 'fastfood' ),$num);
		
		//if over a month	
		} else if ($timeDifference > $monthInSecs) {
			$dateWithNiceTone = __( 'a month ago', 'fastfood' );
				   
		//if more than 2 days ago
		} else {
			$htd = human_time_diff( get_the_time('U'), current_time('timestamp') );
			$dateWithNiceTone = sprintf(__('%s ago', 'fastfood' ), $htd );
		} 
		
		echo $dateWithNiceTone;
			
	}
}

// add 'quoted on' before trackback/pingback comments link
if ( !function_exists( 'fastfood_add_quoted_on' ) ) {
	function fastfood_add_quoted_on( $return ) {
		global $comment;
		$text = '';
		if ( get_comment_type() != 'comment' ) {
			$text = '<span style="font-weight: normal;">' . __( 'quoted on', 'fastfood' ) . ' </span>';
		}
		return $text . $return;
	}
}

// strip tags and apply title format for blank titles
function fastfood_title_tags_filter( $title, $id = null ) {
	global $fastfood_opt;

	if ( is_admin() ) return $title;

	$title = strip_tags( $title, '<abbr><acronym><b><em><i><del><ins><bdo><strong><img><sub><sup>' );

	if ( $id == null ) return $title;

	if ( empty( $title ) ) {
		if ( !isset( $fastfood_opt['fastfood_blank_title'] ) || empty( $fastfood_opt['fastfood_blank_title'] ) ) return __( '(no title)', 'fastfood' );
		$postdata = array( get_post_format( $id )? get_post_format_string( get_post_format( $id ) ): __( 'Post', 'fastfood' ), get_the_time( get_option( 'date_format' ), $id ) );
		$codes = array( '%f', '%d' );
		return str_replace( $codes, $postdata, $fastfood_opt['fastfood_blank_title'] );
	} else
		return $title;
}

//set the excerpt length
if ( !function_exists( 'fastfood_excerpt_length' ) ) {
	function fastfood_excerpt_length( $length ) {
		global $fastfood_opt;
		return (int) $fastfood_opt['fastfood_excerpt_lenght'];
		//return (int) $fastfood_opt['fastfood_excerpt_length'];
	}
}

// use the "excerpt more" string as a link to the post
function fastfood_excerpt_more( $more ) {
	global $fastfood_opt, $post;
	if ( is_admin() ) return $more;
	if ( isset( $fastfood_opt['fastfood_excerpt_more_txt'] ) && isset( $fastfood_opt['fastfood_excerpt_more_link'] ) ) {
		if ( $fastfood_opt['fastfood_excerpt_more_link'] ) {
			return '<a href="' . get_permalink() . '">' . $fastfood_opt['fastfood_excerpt_more_txt'] . '</a>';
		} else {
			return $fastfood_opt['fastfood_excerpt_more_txt'];
		}
	}
	return $more;
}

// custom text for the "more" tag
function fastfood_more_link( $more_link, $more_link_text ) {
	global $fastfood_opt;
	
	if ( isset( $fastfood_opt['fastfood_more_tag'] ) && !is_admin() ) {
		$text = str_replace ( '%t', get_the_title(), $fastfood_opt['fastfood_more_tag'] );
		return str_replace( $more_link_text, $text, $more_link );
	}
	return $more_link;
}

//Add new contact methods to author panel
if ( !function_exists( 'fastfood_new_contactmethods' ) ) {
	function fastfood_new_contactmethods( $contactmethods ) {
		//add Twitter
		$contactmethods['twitter'] = 'Twitter';
		//add Facebook
		$contactmethods['facebook'] = 'Facebook';

		return $contactmethods;
	}
}

// Add Thumbnail Column in Manage Posts/Pages List
function fastfood_addthumbcolumn($cols) {
	$cols['thumbnail'] = ucwords( __('thumbnail', 'fastfood') );
	return $cols;
}

// Add Thumbnails in Manage Posts/Pages List
function fastfood_addthumbvalue($column_name, $post_id) {
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
}

// Add Thumbnail Column style in Manage Posts/Pages List
if ( !function_exists( 'fastfood_post_manage_style' ) ) {
	function fastfood_post_manage_style(){
?>
<style type="text/css">
	.fixed .column-thumbnail {
		width: 70px;
	}
</style>
<?php
	}
}

//filters wp_title()
function fastfood_filter_wp_title( $title ) {
	if ( is_single() && empty( $title ) ) {
		$_post = get_queried_object();
		$title = fastfood_title_tags_filter( '', $_post->ID ) . ' &laquo; ';
	}
    // Get the Site Name
    $site_name = get_bloginfo( 'name' );
    // Append name
    $filtered_title = $title . $site_name;
    // If site front page, append description
    if ( is_front_page() ) {
        // Get the Site Description
        $site_description = get_bloginfo( 'description' );
        // Append Site Description to title
        $filtered_title .= ' - ' . $site_description;
    }
    // Return the modified title
    return $filtered_title;
}
