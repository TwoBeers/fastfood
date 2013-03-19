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

		$the_coa = fastfood_get_coa();
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

	if ( current_user_can( 'manage_options' ) && ( fastfood_get_opt( 'version' ) < fastfood_get_info( 'version' ) ) ) {
		echo '<div class="updated"><p><strong>' . sprintf( __( "%s theme says: Dont forget to set <a href=\"%s\">my options</a>!", 'fastfood' ), 'Fastfood', get_admin_url() . 'themes.php?page=fastfood_theme_options' ) . '</strong></p></div>';
	}

}


// the custon header style - called only on your theme options page
function fastfood_theme_admin_styles() {

	wp_enqueue_style( 'fastfood-options', get_template_directory_uri() . '/css/options.css', array('farbtastic','thickbox'), '', 'screen' );

}


// sanitize options value
function fastfood_sanitize_options($input) {

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

	$input['version'] = fastfood_get_info( 'version' ); // keep version number

	return $input;

}


// the theme option page
if ( !function_exists( 'fastfood_edit_options' ) ) {
	function fastfood_edit_options() {

		if ( !current_user_can( 'edit_theme_options' ) ) wp_die( 'You do not have sufficient permissions to access this page.' );

		global $fastfood_opt;

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
			<div class="icon32 icon-settings" id="theme-icon"><br></div>
			<h2><?php echo fastfood_get_info( 'current_theme' ) . ' - ' . __( 'Theme Options','fastfood' ); ?></h2>
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
									<?php if ( $subval == '' ) { echo '<br>'; continue;} ?>
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
													<br>
													<a class="hide-if-no-js" href="#" onclick="fastfoodOptions.showColorPicker('<?php echo $subval; ?>'); return false;"><?php _e( 'Select a Color' , 'fastfood' ); ?></a>
													<br>
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
						<br>-<br>
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


// add custom script for options page
function fastfood_theme_admin_scripts() {

	wp_enqueue_script( 'fastfood-options', get_template_directory_uri().'/js/admin-options.dev.js',array('jquery','farbtastic','thickbox'),fastfood_get_info( 'version' ), true ); //thebird js

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

	wp_enqueue_script( 'fastfood-widgets', get_template_directory_uri() . '/js/admin-widgets.dev.js', array('jquery'), fastfood_get_info( 'version' ), true );

}

// add custom stylesheet for widgets page
function fastfood_menus_style() {

	wp_enqueue_style( 'fastfood-menus', get_template_directory_uri() . '/css/admin-menus.css', false, '', 'screen' );

}
