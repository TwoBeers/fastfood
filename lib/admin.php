<?php
/**
 * admin.php
 *
 * admin stuff (options,settings,etc).
 *
 * @package fastfood
 * @since fastfood 0.30
 */


/* custom actions */
add_action( 'admin_init',					'fastfood_default_options' );
add_action( 'admin_head',					'fastfood_post_manage_style' );
add_action( 'manage_posts_custom_column',	'fastfood_add_extra_value', 10, 2 );
add_action( 'manage_pages_custom_column',	'fastfood_add_extra_value', 10, 2 );
add_filter( 'manage_posts_columns',			'fastfood_add_extra_column' );
add_filter( 'manage_pages_columns',			'fastfood_add_extra_column' );
add_action( 'admin_notices',				'fastfood_setopt_admin_notice' );
add_action( 'admin_menu',					'fastfood_create_menu' );


/* custom filters */
add_filter( 'user_contactmethods',			'fastfood_new_contactmethods',10,1 );


// check and set default options 
function fastfood_default_options() {

		$the_coa = FastfoodOptions::get_coa();
		$the_opt = get_option( 'fastfood_options' );

		// if options are empty, sets the default values
		if ( empty( $the_opt ) || !isset( $the_opt ) ) {

			foreach ( $the_coa as $key => $val ) {
				$the_opt[$key] = $the_coa[$key]['default'];
			}
			$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'fastfood_options' , $the_opt );

		} else if ( !isset( $the_opt['version'] ) || $the_opt['version'] < fastfood_get_info( 'version' ) ) {

			// check for unset values and set them to default value -> when updated to new version
			foreach ( $the_coa as $key => $val ) {
				if ( !isset( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['default'];
			}
			$the_opt['version'] = ''; //null value to keep admin notice alive and invite user to discover theme options
			update_option( 'fastfood_options' , $the_opt );

		}

}


// print a reminder message for set the options after the theme is installed or updated
function fastfood_setopt_admin_notice() {

	if ( current_user_can( 'manage_options' ) && ( FastfoodOptions::get_opt( 'version' ) < fastfood_get_info( 'version' ) ) ) {
		echo '<div class="updated"><p><strong>' . sprintf( __( "%s theme says: Dont forget to set <a href=\"%s\">my options</a>!", 'fastfood' ), 'Fastfood', get_admin_url() . 'themes.php?page=fastfood_theme_options' ) . '</strong></p></div>';
	}

}


// the custon header style - called only on your theme options page
function fastfood_theme_admin_styles() {

	wp_enqueue_style( 'fastfood-options', get_template_directory_uri() . '/css/options.css', array(), '', 'screen' );

}


// sanitize options value
function fastfood_sanitize_options($input) {

	$the_coa = FastfoodOptions::get_coa();

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
			if( isset( $the_coa[$key]['range'] ) ) {
				$input[$key] = min( $input[$key], $the_coa[$key]['range'][1] );
				$input[$key] = max( $input[$key], $the_coa[$key]['range'][0] );
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

	$input['version'] = fastfood_get_info( 'version' ); // keep version number

	return $input;

}


// print the options fields
function fastfood_print_option( $option, $value, $is_sub, $option_name, $key, $before = '', $after = '' ) {

	echo $before;

	switch ( $option['type'] ) {
		case 'chk':
			?>
				<input name="<?php echo $option_name; ?>[<?php echo $key; ?>]" class="theme_option_checkbox" value="1" type="checkbox" <?php checked( 1 , $value ); ?> />
			<?php
			break;
		case 'sel':
			?>
				<select name="<?php echo $option_name; ?>[<?php echo $key; ?>]">
				<?php foreach($option['options'] as $optionkey => $optionval) { ?>
					<option value="<?php echo $optionval; ?>" <?php selected( $value, $optionval ); ?>><?php echo $option['options_readable'][$optionkey]; ?></option>
				<?php } ?>
				</select>
			<?php
			break;
		case 'opt':
			foreach( $option['options'] as $optionkey => $optionval ) {
			?>
				<label name="<?php echo $option_name; ?>[<?php echo $key; ?>]" title="<?php echo esc_attr($optionval); ?>"><input type="radio" <?php checked( $value, $optionval ); ?> value="<?php echo $optionval; ?>"> <span><?php echo $option['options_readable'][$optionkey]; ?></span></label>
			<?php
			}
			break;
		case 'col':
			?>
				<input name="<?php echo $option_name; ?>[<?php echo $key; ?>]" class="theme_option_input fastfood_cp" type="text" id="<?php echo $option_name; ?>[<?php echo $key; ?>]" value="<?php echo $value; ?>" data-default-color="<?php echo $option['default']; ?>" />
				<span class="description hide-if-js"><?php _e( 'Default' , 'fastfood' ); ?>: <?php echo $option['default']; ?></span>
			<?php
			break;
		case 'url':
			?>
				<input name="<?php echo $option_name; ?>[<?php echo $key; ?>]" class="theme_option_text" id="option_field_<?php echo $key; ?>" type="text" value="<?php echo $value; ?>" />
			<?php
			break;
		case 'txt':
			?>
				<input name="<?php echo $option_name; ?>[<?php echo $key; ?>]" class="theme_option_text" id="option_field_<?php echo $key; ?>" type="text" value="<?php echo $value; ?>" />
			<?php
			break;
		case 'int':
			?>
				<input name="<?php echo $option_name; ?>[<?php echo $key; ?>]" class="theme_option_text" id="option_field_<?php echo $key; ?>" type="text" value="<?php echo $value; ?>" />
			<?php
			break;
		case 'txtarea':
			?>
				<textarea name="<?php echo $option_name; ?>[<?php echo $key; ?>]"><?php echo $value; ?></textarea>
			<?php
			break;
	}

	echo $after;

}


// the theme option page
if ( !function_exists( 'fastfood_edit_options' ) ) {
	function fastfood_edit_options() {

		if ( !current_user_can( 'edit_theme_options' ) ) wp_die( 'You do not have sufficient permissions to access this page.' );

		global $fastfood_opt;

		$the_coa = FastfoodOptions::get_coa();
		$the_hierarchy = FastfoodOptions::get_hierarchy();
		$the_option_name = 'fastfood_options';

		if ( isset( $_GET['erase'] ) ) {
			$_SERVER['REQUEST_URI'] = remove_query_arg( 'erase', $_SERVER['REQUEST_URI'] );
			delete_option( $the_option_name );
			fastfood_default_options();
			$fastfood_opt = get_option( $the_option_name );
		}

		// update version value when admin visit options page
		if ( $fastfood_opt['version'] < fastfood_get_info( 'version' ) ) {
			$fastfood_opt['version'] = fastfood_get_info( 'version' );
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
		<h2><?php echo fastfood_get_info( 'current_theme' ) . ' - ' . __( 'Theme Options','fastfood' ); ?></h2>

		<div id="theme_donation">
			<small><?php _e( 'Our developers need coffee (and beer). How about a small donation?', 'fastfood' ); ?></small>
			<br />
			<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5FWKWFH62RRC8"><img src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" alt="PayPal - The safer, easier way to pay online."/></a>
		</div>

		<h2 id="tabselector" class="nav-tab-wrapper">
			<img src="<?php echo get_template_directory_uri() . '/images/ff34_logo.png' ?>" alt="fastfood"/>
			<?php foreach( $the_hierarchy as $key => $genus ) { ?>
				<a id="selgroup-<?php echo $key; ?>" class="nav-tab" href="#" onClick="fastfoodOptions.switchTab('<?php echo $key; ?>'); return false;"><?php echo $genus['label']; ?></a>
			<?php } ?>
			<a id="selgroup-info" class="nav-tab" href="#" onClick="fastfoodOptions.switchTab('info'); return false;"><?php _e( 'Theme Info' , 'fastfood' ); ?></a>
		</h2>

		<div id="theme-options">
			<form method="post" action="options.php">
				<?php settings_fields( 'fastfood_settings_group' ); ?>
				<?php foreach( $the_hierarchy as $genus_key => $genus ) { ?>
					<div class="tabgroup tabgroup-<?php echo $genus_key ?>">
						<?php foreach( $genus['sub'] as $species_key => $species ) { ?>
							<h2><?php echo $species['label']; ?></h2>
							<?php if ( $species['description'] != '' ) echo '<small>' . $species['description'] . '</small>'; ?>
							<div class="">
								<?php foreach( $species['sub'] as $item_key => $item ) { ?>
									<?php $key = $item; ?>
									<div class="tab-opt opt-<?php echo $the_coa[$key]['type']; ?>">
										<?php if ( !isset ( $the_opt[$key] ) ) $the_opt[$key] = $the_coa[$key]['default']; ?>
										<?php 
											switch ( $the_coa[$key]['type'] ) {
												case 'lbl':
													echo '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>';
													break;

												case 'chk':
													fastfood_print_option( $the_coa[$key], $the_opt[$key], false, $the_option_name, $key , '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>', '' );
													break;

												case 'sel':
													fastfood_print_option( $the_coa[$key], $the_opt[$key], false, $the_option_name, $key , '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>', '' );
													break;

												case 'opt':
													fastfood_print_option( $the_coa[$key], $the_opt[$key], false, $the_option_name, $key , '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>', '' );
													break;

												case 'col':
													fastfood_print_option( $the_coa[$key], $the_opt[$key], false, $the_option_name, $key , '<div class="col-tools"><span class="column-nam">' . $the_coa[$key]['description'] . '</span><br />', '</div>' );
													break;

												case 'url':
													fastfood_print_option( $the_coa[$key], $the_opt[$key], false, $the_option_name, $key , '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>', $after );
													break;

												case 'txt':
													fastfood_print_option( $the_coa[$key], $the_opt[$key], false, $the_option_name, $key , '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>', '' );
													break;

												case 'int':
													fastfood_print_option( $the_coa[$key], $the_opt[$key], false, $the_option_name, $key , '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>', '' );
													break;

												case 'txtarea':
													fastfood_print_option( $the_coa[$key], $the_opt[$key], false, $the_option_name, $key , '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>', '' );
													break;

												case 'catcol':
													echo '<span class="column-nam">' . $the_coa[$key]['description'] . '</span>';
													$args = array(
														'orderby'		=> 'name',
														'order'			=> 'ASC',
														'hide_empty'	=> 0,
													);
													$categories = get_categories( $args );
													foreach( $categories as $category ) {
														$hexnumber = '#';
														for ( $i2=1; $i2<=3; $i2++ ) {
															$hexnumber .= dechex( rand( 64, 255 ) );
														}
														$catcolor = isset( $the_opt[$key][$category->term_id] ) ? $the_opt[$key][$category->term_id] : $hexnumber;
														
														fastfood_print_option( array( 'type' => 'col', 'default' => $the_coa[$key]['defaultcolor'] ), $catcolor, false, $the_option_name, $key . '][' . $category->term_id, '<div class="col-tools"><span>' . $category->name . ' (' . $category->count . ')</span><br />', '<span class="description hide-if-js">' .  __( 'Default' , 'fastfood' ) . ': ' . $the_coa[$key]['defaultcolor'] . '</span></div>' );

													}
													break;

											}
										?>
										<?php if ( $the_coa[$key]['info'] != '' ) { ?><div class="column-des"><?php echo $the_coa[$key]['info']; ?></div><?php } ?>

										<?php if ( isset( $the_coa[$key]['sub'] ) ) { ?>
											<div class="sub-opt-wrap">
												<?php foreach ($the_coa[$key]['sub'] as $subkey) { ?>
													<?php if ( $subkey == '' ) { echo '<br />'; continue;} ?>
													<?php $after =( $the_coa[$subkey]['info'] != '' ) ? '<span>' . $the_coa[$subkey]['info'] . '</span>' : ''; ?>
													<div class="sub-opt">
														<?php if ( !isset ($the_opt[$subkey]) ) $the_opt[$subkey] = $the_coa[$subkey]['default']; ?>
														<?php 
															switch ( $the_coa[$subkey]['type'] ) {
																case 'chk':
																	fastfood_print_option( $the_coa[$subkey], $the_opt[$subkey], false, $the_option_name, $subkey , '<span>' . $the_coa[$subkey]['description'] . ' : </span>', $after );
																	break;

																case 'sel':
																	fastfood_print_option( $the_coa[$subkey], $the_opt[$subkey], false, $the_option_name, $subkey , '<span>' . $the_coa[$subkey]['description'] . ' : </span>', $after );
																	break;

																case 'opt':
																	fastfood_print_option( $the_coa[$subkey], $the_opt[$subkey], false, $the_option_name, $subkey , '<span>' . $the_coa[$subkey]['description'] . ' : </span>', $after );
																	break;

																case 'col':
																	fastfood_print_option( $the_coa[$subkey], $the_opt[$subkey], false, $the_option_name, $subkey , '<div class="col-tools"><span>' . $the_coa[$subkey]['description'] . '</span><br />', '</div>' . $after );
																	break;

																case 'url':
																	fastfood_print_option( $the_coa[$subkey], $the_opt[$subkey], false, $the_option_name, $subkey , '<span>' . $the_coa[$subkey]['description'] . ' : </span>', $after );
																	break;

																case 'txt':
																	fastfood_print_option( $the_coa[$subkey], $the_opt[$subkey], false, $the_option_name, $subkey , '<span>' . $the_coa[$subkey]['description'] . ' : </span>', $after );
																	break;

																case 'int':
																	fastfood_print_option( $the_coa[$subkey], $the_opt[$subkey], false, $the_option_name, $subkey , '<span>' . $the_coa[$subkey]['description'] . ' : </span>', $after );
																	break;

																case 'txtarea':
																	fastfood_print_option( $the_coa[$subkey], $the_opt[$subkey], false, $the_option_name, $subkey , '<span>' . $the_coa[$subkey]['description'] . ' : </span>', $after );
																	break;

																case 'catcol':
																	echo '<span class="column-nam">' . $the_coa[$subkey]['description'] . '</span>';
																	$args = array(
																		'orderby'		=> 'name',
																		'order'			=> 'ASC',
																		'hide_empty'	=> 0,
																	);
																	$categories = get_categories( $args );
																	foreach( $categories as $category ) {
																		$hexnumber = '#';
																		for ( $i2=1; $i2<=3; $i2++ ) {
																			$hexnumber .= dechex( rand( 64, 255 ) );
																		}
																		$catcolor = isset( $the_opt[$subkey][$category->term_id] ) ? $the_opt[$subkey][$category->term_id] : $hexnumber;

																		fastfood_print_option( array( 'type' => 'col', 'default' => $the_coa[$subkey]['defaultcolor'] ), $catcolor, false, $the_option_name, $subkey . '][' . $category->term_id, '<div class="col-tools"><span>' . $category->name . ' (' . $category->count . ')</span><br />', '<span class="description hide-if-js">' .  __( 'Default' , 'fastfood' ) . ': ' . $the_coa[$subkey]['defaultcolor'] . '</span></div>' );

																	}
																	break;

															}
														?>
													</div>
												<?php } ?>
												<br class="clear" />
											</div>
										<?php } ?>
										<?php if ( $the_coa[$key]['req'] != '' ) { ?><div class="column-req"><?php echo '<u>' . __('requires','fastfood') . '</u>: ' . $the_coa[$the_coa[$key]['req']]['description']; ?></div><?php } ?>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				<?php } ?>
				<div id="buttons">
					<input type="hidden" name="<?php echo $the_option_name; ?>[hidden_opt]" value="default" />
					<input class="button button-primary" type="submit" name="Submit" value="<?php _e( 'Update Options' , 'fastfood' ); ?>" />
					<br />
					-
					<br />
					<a class="button" href="themes.php?page=fastfood_theme_options" target="_self"><?php _e( 'Undo Changes' , 'fastfood' ); ?></a>
					<br />
					-
					<br />
					<a class="button" id="to-defaults" href="themes.php?page=fastfood_theme_options&erase=1" target="_self"><?php _e( 'Back to defaults' , 'fastfood' ); ?></a>
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
			<h2 class="hide-if-js" style="text-align: center;"><?php _e( 'Theme Info', 'fastfood' ); ?></h2>
			<?php locate_template( 'readme.html',true ); ?>
		</div>
	</div>
<?php
	}
}


// Add new contact methods to author panel
function fastfood_new_contactmethods( $contactmethods ) {

	//add Twitter
	$contactmethods['twitter'] = 'Twitter';

	//add Facebook
	$contactmethods['facebook'] = 'Facebook';

	//add Google+
	$contactmethods['googleplus'] = 'Google+';

	return $contactmethods;

}


// Add Thumbnail Column in Manage Posts/Pages List
function fastfood_add_extra_column( $cols ) {

	$cols['id'] = ucwords( 'ID' );
	$cols['thumbnail'] = ucwords( __( 'thumbnail', 'fastfood' ) );
	return $cols;

}


// Add Thumbnails in Manage Posts/Pages List
function fastfood_add_extra_value( $column_name, $post_id ) {

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


// Add Thumbnail Column style in Manage Posts/Pages List
function fastfood_post_manage_style(){

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


// create theme option page
function fastfood_create_menu() {

	$pageopt = add_theme_page( __( 'Theme Options','fastfood' ), __( 'Theme Options','fastfood' ), 'edit_theme_options', 'fastfood_theme_options', 'fastfood_edit_options' );

	add_action( 'admin_init',						'fastfood_register_tb_settings' );
	add_action( 'admin_print_styles-' . $pageopt,	'fastfood_theme_admin_custom_styles' );
	add_action( 'admin_print_scripts-' . $pageopt,	'fastfood_theme_admin_scripts' );
	add_action( 'admin_print_styles-widgets.php',	'fastfood_widgets_style' );
	add_action( 'admin_print_scripts-widgets.php',	'fastfood_widgets_scripts' );
	add_action( 'admin_print_styles-nav-menus.php',	'fastfood_menus_style' );

}


// register fastfood settings
function fastfood_register_tb_settings() {

	register_setting( 'fastfood_settings_group', 'fastfood_options', 'fastfood_sanitize_options' );

}


// add custom stylesheet for options page
function fastfood_theme_admin_custom_styles() {

	wp_enqueue_style( 'wp-color-picker' );
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


// add custom script for options page
function fastfood_theme_admin_scripts() {

	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script( 'fastfood-options', get_template_directory_uri().'/js/admin-options.js',array('jquery','thickbox'),fastfood_get_info( 'version' ), true ); //thebird js

	$data = array(
		'confirm_to_defaults' => esc_js( __( 'Are you really sure you want to set all the options to their default values?', 'fastfood' ) )
	);
	wp_localize_script( 'fastfood-options', 'fastfood_l10n', $data );

}


// add custom stylesheet for widgets page
function fastfood_widgets_style() {

	wp_enqueue_style( 'fastfood-widgets', get_template_directory_uri() . '/css/admin-widgets.css', false, '', 'screen' );

}


// add custom script for widgets page
function fastfood_widgets_scripts() {

	wp_enqueue_script( 'fastfood-widgets', get_template_directory_uri() . '/js/admin-widgets.js', array('jquery'), fastfood_get_info( 'version' ), true );

}

// add custom stylesheet for widgets page
function fastfood_menus_style() {

	wp_enqueue_style( 'fastfood-menus', get_template_directory_uri() . '/css/admin-menus.css', false, '', 'screen' );

}
