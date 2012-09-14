<?php
/**
 * The custom background script.
 *
 * "Fastfood_Custom_Background" class based on WP wp-admin/custom-background.php
 *
 */

add_action( 'after_setup_theme', 'fastfood_custom_background_init' );

// set up custom colors and header image
if ( !function_exists( 'fastfood_custom_background_init' ) ) {
	function fastfood_custom_background_init() {
		global $fastfood_opt;

		if ( isset( $fastfood_opt['fastfood_custom_bg'] ) && $fastfood_opt['fastfood_custom_bg'] == 1 ) {
			// the enhanced 'custom background' support
			fastfood_add_custom_background( 'fastfood_custom_bg' , 'fastfood_admin_custom_bg_style' , '' );
		} else {
			// the standard 'custom background' support
			$args = array(
				'default-color'          => '',
				'default-image'          => '',
				'wp-head-callback'       => '',
				'admin-head-callback'    => '',
				'admin-preview-callback' => ''
			);
			if ( function_exists( 'get_custom_header' ) ) {
				add_theme_support( 'custom-background', $args );
			} else {
				// Compat: Versions of WordPress prior to 3.4.
				define( 'BACKGROUND_COLOR', $args['default-color'] );
				add_custom_background( 'fastfood_custom_bg' , '' , '' );
			}
		}

	}
}

// Styles the header image displayed on the Appearance > Header admin panel.
if ( !function_exists( 'fastfood_admin_custom_bg_style' ) ) {
	function fastfood_admin_custom_bg_style() {
		wp_enqueue_style( 'fastfood-custom-background', get_template_directory_uri() . '/css/admin-custom_background.css', false, '', 'screen' );
	}
}

//Add callbacks for background image display. based on WP theme.php -> add_custom_background()
if ( !function_exists( 'fastfood_add_custom_background' ) ) {
	function fastfood_add_custom_background( $header_callback = '', $admin_header_callback = '', $admin_image_div_callback = '' ) {
		if ( isset( $GLOBALS['custom_background'] ) )
			return;

		add_action( 'wp_head', $header_callback );

		if ( ! is_admin() )
			return;
		$GLOBALS['custom_background'] =& new Fastfood_Custom_Background( $admin_header_callback, $admin_image_div_callback );
		add_action( 'admin_menu', array( &$GLOBALS['custom_background'], 'init' ) );
	}
}

// custom background style - gets included in the site header
if ( !function_exists( 'fastfood_custom_bg' ) ) {
	function fastfood_custom_bg() {
		global $fastfood_is_printpreview, $fastfood_is_mobile;
		if ( $fastfood_is_printpreview || $fastfood_is_mobile ) return;

		$background = get_background_image();
		$color = get_background_color();
		if ( ! $background && ! $color ) return;

		$style = $color ? "background-color: #$color;" : '';

		if ( $background ) {
			$image = " background-image: url('$background');";

			$repeat = get_theme_mod( 'background_repeat', 'repeat' );
			if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) $repeat = 'repeat';
			$repeat = " background-repeat: $repeat;";

			$position_x = get_theme_mod( 'background_position_x', 'left' );
			$position_y = get_theme_mod( 'background_position_y', 'top' );
			if ( ! in_array( $position_x, array( 'center', 'right', 'left' ) ) ) $position = 'left';
			if ( ! in_array( $position_y, array( 'center', 'top', 'bottom' ) ) ) $position = 'top';
			$position = " background-position: $position_x $position_y;";

			$attachment = get_theme_mod( 'background_attachment', 'scroll' );
			if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) ) $attachment = 'scroll';
			$attachment = " background-attachment: $attachment;";

			$style .= $image . $repeat . $position . $attachment;
		} else {
			$style .= ' background-image: url("");';
		}
		?>
		<style type="text/css">
			body { <?php echo trim( $style ); ?> }
		</style>
		<?php
	}
}

class Fastfood_Custom_Background {


	/* Holds default background images. */
	var $default_bg_images = array();

	/* Callback for administration header. */
	var $admin_header_callback;

	/* Callback for header div. */
	var $admin_image_div_callback;

	/* Holds the page menu hook. */
	var $page = '';

	/* PHP4 Constructor - Register administration header callback. */
	function Fastfood_Custom_Background( $admin_header_callback = '', $admin_image_div_callback = '' ) {
		$this->admin_header_callback = $admin_header_callback;
		$this->admin_image_div_callback = $admin_image_div_callback;
	}

	/* Set up the hooks for the Custom Background admin page. */
	function init() {
		if ( !current_user_can( 'edit_theme_options' ) )
			return;

		$this->page = $page = add_theme_page( __( 'Background', 'fastfood' ), __( 'Background', 'fastfood' ), 'edit_theme_options', 'custom-background', array( &$this, 'admin_page' ) );

		add_action( "load-$page", array( &$this, 'admin_load' ) );
		add_action( "load-$page", array( &$this, 'take_action' ), 49 );
		add_action( "load-$page", array( &$this, 'handle_upload' ), 49 );

		if ( $this->admin_header_callback )
			add_action( "admin_head-$page", $this->admin_header_callback, 51 );
	}

	/* Set up the enqueue for the CSS & JavaScript files. */
	function admin_load() {
		wp_enqueue_script( 'fastfood-custom-background', get_template_directory_uri() . '/js/admin-custom_background.dev.js', array( 'jquery', 'farbtastic' ), '', true  );
		wp_enqueue_style( 'farbtastic' );
	}

	/* Execute custom background modification. */
	function take_action() {

		if ( empty($_POST) )
			return;

		if ( isset( $_POST['reset-background'] ) ) {
			check_admin_referer( 'custom-background-reset', '_wpnonce-custom-background-reset' );
			remove_theme_mod( 'background_image' );
			remove_theme_mod( 'background_image_thumb' );
			$this->updated = true;
			return;
		}

		if ( isset( $_POST['remove-background'] ) ) {
			check_admin_referer( 'custom-background-remove', '_wpnonce-custom-background-remove' );
			set_theme_mod( 'background_image', '' );
			set_theme_mod( 'background_image_thumb', '' );
			$this->updated = true;
			return;
		}

		if ( isset( $_POST['background-repeat'] ) ) {
			check_admin_referer( 'custom-background' );
			if ( in_array( $_POST['background-repeat'], array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y' ) ) )
				$repeat = $_POST['background-repeat'];
			else
				$repeat = 'repeat';
			set_theme_mod( 'background_repeat', $repeat );
		}

		if ( isset( $_POST['background-position-x'] ) ) {
			check_admin_referer( 'custom-background' );
			if ( in_array( $_POST['background-position-x'], array( 'center', 'right', 'left' ) ) )
				$position_x = $_POST['background-position-x'];
			else
				$position_x = 'left';
			set_theme_mod( 'background_position_x', $position_x );
		}

		// CUSTOM ADD 'background-position-y'
		if ( isset( $_POST['background-position-y'] ) ) {
			check_admin_referer( 'custom-background' );
			if ( in_array( $_POST['background-position-y'], array( 'center', 'top', 'bottom' ) ) )
				$position_y = $_POST['background-position-y'];
			else
				$position_y = 'top';
			set_theme_mod( 'background_position_y', $position_y );
		}

		if ( isset( $_POST['background-attachment'] ) ) {
			check_admin_referer( 'custom-background' );
			if ( in_array( $_POST['background-attachment'], array( 'fixed', 'scroll' ) ) )
				$attachment = $_POST['background-attachment'];
			else
				$attachment = 'fixed';
			set_theme_mod( 'background_attachment', $attachment );
		}

		if ( isset( $_POST['background-color'] ) ) {
			check_admin_referer( 'custom-background' );
			$color = preg_replace( '/[^0-9a-fA-F]/', '', $_POST['background-color'] );
			if ( strlen( $color ) == 6 || strlen( $color ) == 3 )
				set_theme_mod( 'background_color', $color );
			else
				set_theme_mod( 'background_color', '' );
		}

		if ( isset( $_POST['default-bg'] ) ) {
			check_admin_referer( 'custom-background' );
			$this->process_default_bg_images();
			if ( in_array( $_POST['default-bg'], array( 'aqua', 'pink', 'wood', 'greenwave', 'globe', 'heart', 'clouds', 'violet', 'paper', 'squarednight', 'cork', 'yellow' ) ) ) {
				set_theme_mod( 'background_image', esc_url( $this->default_bg_images[$_POST['default-bg']]['url'] ) );
				set_theme_mod( 'background_image_thumb', esc_url( $this->default_bg_images[$_POST['default-bg']]['thumbnail_url'] ) );
			}
		}

		$this->updated = true;
	}


	/* Process the default backgrounds */
	function process_default_bg_images() {
		$default_bg_images = array(
			'aqua' => array(
				'url' => '%s/images/backgrounds/aqua.jpg',
				'thumbnail_url' => '%s/images/backgrounds/aqua-thumbnail.jpg',
				'description' => __( 'Aqua', 'fastfood' ),
				'position_x' => 'center',
				'position_y' => 'top',
				'repeat' => 'no-repeat',
				'attach' => 'fixed',
				'color' => '#F5FDFF'
			),
			'pink' => array(
				'url' => '%s/images/backgrounds/pink.jpg',
				'thumbnail_url' => '%s/images/backgrounds/pink-thumbnail.jpg',
				'description' => __( 'Pink', 'fastfood' ),
				'position_x' => 'right',
				'position_y' => 'bottom',
				'repeat' => 'no-repeat',
				'attach' => 'fixed',
				'color' => '#ffffff'
			),
			'wood' => array(
				'url' => '%s/images/backgrounds/wood.jpg',
				'thumbnail_url' => '%s/images/backgrounds/wood-thumbnail.jpg',
				'description' => __( 'Wood', 'fastfood' ),
				'position_x' => 'left',
				'position_y' => 'top',
				'repeat' => 'repeat',
				'attach' => 'fixed',
				'color' => '#000000'
			),
			'greenwave' => array(
				'url' => '%s/images/backgrounds/greenwave.jpg',
				'thumbnail_url' => '%s/images/backgrounds/greenwave-thumbnail.jpg',
				'description' => __( 'Green Wave', 'fastfood' ),
				'position_x' => 'left',
				'position_y' => 'top',
				'repeat' => 'repeat-y',
				'attach' => 'fixed',
				'color' => '#F9FFD9'
			),
			'heart' => array(
				'url' => '%s/images/backgrounds/heart.jpg',
				'thumbnail_url' => '%s/images/backgrounds/heart-thumbnail.jpg',
				'description' => __( 'Heart', 'fastfood' ),
				'position_x' => 'left',
				'position_y' => 'top',
				'repeat' => 'no-repeat',
				'attach' => 'fixed',
				'color' => '#000000'
			),
			'violet' => array(
				'url' => '%s/images/backgrounds/violet.jpg',
				'thumbnail_url' => '%s/images/backgrounds/violet-thumbnail.jpg',
				'description' => __( 'Violet', 'fastfood' ),
				'position_x' => 'right',
				'position_y' => 'top',
				'repeat' => 'no-repeat',
				'attach' => 'fixed',
				'color' => '#190923'
			),
			'paper' => array(
				'url' => '%s/images/backgrounds/paper.jpg',
				'thumbnail_url' => '%s/images/backgrounds/paper-thumbnail.jpg',
				'description' => __( 'Paper', 'fastfood' ),
				'position_x' => 'left',
				'position_y' => 'top',
				'repeat' => 'repeat',
				'attach' => 'scroll',
				'color' => '#000000'
			),
			'squarednight' => array(
				'url' => '%s/images/backgrounds/squarednight.jpg',
				'thumbnail_url' => '%s/images/backgrounds/squarednight-thumbnail.jpg',
				'description' => __( 'Squared Night', 'fastfood' ),
				'position_x' => 'left',
				'position_y' => 'top',
				'repeat' => 'repeat-x',
				'attach' => 'scroll',
				'color' => '#0E111A'
			),
			'cork' => array(
				'url' => '%s/images/backgrounds/cork.jpg',
				'thumbnail_url' => '%s/images/backgrounds/cork-thumbnail.jpg',
				'description' => __( 'Cork', 'fastfood' ),
				'position_x' => 'left',
				'position_y' => 'top',
				'repeat' => 'repeat',
				'attach' => 'fixed',
				'color' => '#000000'
			),
			'yellow' => array(
				'url' => '%s/images/backgrounds/yellow.jpg',
				'thumbnail_url' => '%s/images/backgrounds/yellow-thumbnail.jpg',
				'description' => __( 'Yellow', 'fastfood' ),
				'position_x' => 'left',
				'position_y' => 'top',
				'repeat' => 'repeat',
				'attach' => 'scroll',
				'color' => '#000000'
			),
			'clouds' => array(
				'url' => '%s/images/backgrounds/clouds.jpg',
				'thumbnail_url' => '%s/images/backgrounds/clouds-thumbnail.jpg',
				'description' => __( 'Clouds', 'fastfood' ),
				'position_x' => 'left',
				'position_y' => 'top',
				'repeat' => 'repeat-x',
				'attach' => 'fixed',
				'color' => '#DBE8F9'
			)
		);

		$this->default_bg_images = $default_bg_images;
		foreach ( array_keys($this->default_bg_images) as $header ) {
			$this->default_bg_images[$header]['url'] =  sprintf( $this->default_bg_images[$header]['url'], get_template_directory_uri(), get_stylesheet_directory_uri() );
			$this->default_bg_images[$header]['thumbnail_url'] =  sprintf( $this->default_bg_images[$header]['thumbnail_url'], get_template_directory_uri(), get_stylesheet_directory_uri() );
		}
	}

	/* Display UI for selecting one of several default backgrounds. */
	function show_default_bg_selector() {
		foreach ( $this->default_bg_images as $header_key => $header ) {
			$header_thumbnail = $header['thumbnail_url'];
			$header_url = $header['url'];
			$header_desc = $header['description'];
			?>
			<div class="default-bg">
				<label><input name="default-bg" type="radio" value="<?php echo esc_attr( $header_key ); ?>" <?php checked( $header_url, get_theme_mod( 'background_image' ) ); ?>/>
				<img src="<?php echo $header_thumbnail; ?>" alt="<?php echo esc_attr( $header_desc ); ?>" title="<?php echo esc_attr( $header_desc ); ?>" /></label>
				<h3><?php echo esc_attr( $header_desc ); ?></h3>
				<div class="default-bg-info">
					<?php echo __( 'Position', 'fastfood' ) . ': <span class="default-bg-info-posx">' . $header['position_x'] . '</span> <span class="default-bg-info-posy">' . $header['position_y']; ?></span></br>
					<?php echo __( 'Repeat', 'fastfood' ) . ': <span class="default-bg-info-rep">' . $header['repeat']; ?></span></br>
					<?php echo __( 'Attachment', 'fastfood' ) . ': <span class="default-bg-info-att">' . $header['attach']; ?></span></br>
					<?php echo __( 'Color', 'fastfood' ) . ': <span class="default-bg-info-col">' . $header['color']; ?></span></br>
				</div>
			</div>
			<?php
		}
	}

	/* Display the custom background page. */
	function admin_page() {
		$this->process_default_bg_images();
?>
<div class="wrap" id="custom-background">
<?php screen_icon(); ?>
<h2><?php _e( 'Custom Background', 'fastfood' ); ?></h2>
<?php if ( !empty( $this->updated ) ) { ?>
<div id="message" class="updated">
<p><?php printf( __( 'Background updated. <a href="%s">Visit your site</a> to see how it looks.', 'fastfood' ), home_url( '/' ) ); ?></p>
</div>
<?php }

	if ( $this->admin_image_div_callback ) {
		call_user_func($this->admin_image_div_callback);
	} else {
?>
<h3><?php _e( 'Background Image', 'fastfood' ); ?></h3>
<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row"><?php _e( 'Preview', 'fastfood' ); ?></th>
<td>
<?php
$background_styles = '';
if ( $bgcolor = get_background_color() )
	$background_styles .= 'background-color: #' . $bgcolor . ';';

if ( get_background_image() ) {
	// background-image URL must be single quote, see below
	$background_styles .= ' background-image: url(\'' . get_background_image() . '\');'
		. ' background-repeat: ' . get_theme_mod( 'background_repeat', 'repeat' ) . ';'
		. ' background-position: ' . get_theme_mod( 'background_position_y', 'top' ) . ' ' . get_theme_mod( 'background_position_x', 'left' );
}
?>
<div id="custom-background-image" style="<?php echo $background_styles; ?>"><?php // must be double quote, see above ?>
<?php if ( get_background_image() ) { ?>
<img class="custom-background-image" src="<?php echo get_background_image(); ?>" style="visibility:hidden;" alt="" /><br />
<img class="custom-background-image" src="<?php echo get_background_image(); ?>" style="visibility:hidden;" alt="" />
<?php } ?>
</div>
<?php } ?>
</td>
</tr>
<?php if ( get_background_image() ) : ?>
<tr valign="top">
<th scope="row"><?php _e( 'Remove Image', 'fastfood' ); ?></th>
<td>
<form method="post" action="">
<?php wp_nonce_field( 'custom-background-remove', '_wpnonce-custom-background-remove' ); ?>
<?php submit_button( __( 'Remove Background Image', 'fastfood' ), 'button', 'remove-background', false ); ?><br/>
<?php _e( 'This will remove the background image. You will not be able to restore any customizations.', 'fastfood' ) ?>
</form>
</td>
</tr>
<?php endif; ?>

<?php if ( defined( 'BACKGROUND_IMAGE' ) ) : // Show only if a default background image exists ?>
<tr valign="top">
<th scope="row"><?php _e( 'Restore Original Image', 'fastfood' ); ?></th>
<td>
<form method="post" action="">
<?php wp_nonce_field( 'custom-background-reset', '_wpnonce-custom-background-reset' ); ?>
<?php submit_button( __( 'Restore Original Image', 'fastfood' ), 'button', 'reset-background', false ); ?><br/>
<?php _e( 'This will restore the original background image. You will not be able to restore any customizations.', 'fastfood' ) ?>
</form>
</td>
</tr>

<?php endif; ?>
<tr valign="top">
<th scope="row"><?php _e( 'Upload Image', 'fastfood' ); ?></th>
<td><form enctype="multipart/form-data" id="upload-form" method="post" action="">
<label for="upload"><?php _e( 'Choose an image from your computer:', 'fastfood' ); ?></label><br /><input type="file" id="upload" name="import" />
<input type="hidden" name="action" value="save" />
<?php wp_nonce_field( 'custom-background-upload', '_wpnonce-custom-background-upload' ) ?>
<?php submit_button( __( 'Upload', 'fastfood' ), 'button', 'submit', false ); ?>
</p>
</form>
</td>
</tr>
</tbody>
</table>

<form method="post" action="">
<table class="form-table">
<tbody>
<tr>
	<th scope="row"><?php _e( 'Predefined themes', 'fastfood' ); ?></th>
	<td>
		<div id="available-bg">
			<?php
				$this->show_default_bg_selector();
			?>
			<div class="clear"></div>
		</div>
	</td>
</tr>
</tbody>
</table>
<h3><?php _e( 'Display Options', 'fastfood' ) ?></h3>
<table class="form-table">
<tbody>
<tr valign="top" class="background-details" <?php if ( !get_background_image() ) echo 'style="display: none;"'; ?>>
<th scope="row"><?php _e( 'Position', 'fastfood' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Position', 'fastfood' ); ?></span></legend>
<label>
<input name="background-position-x" type="radio" value="left"<?php checked( 'left', get_theme_mod( 'background_position_x', 'left' ) ); ?> />
<?php _e( 'Left', 'fastfood' ) ?>
</label>
<label>
<input name="background-position-x" type="radio" value="center"<?php checked( 'center', get_theme_mod( 'background_position_x', 'left' ) ); ?> />
<?php _e( 'Center', 'fastfood' ) ?>
</label>
<label>
<input name="background-position-x" type="radio" value="right"<?php checked( 'right', get_theme_mod( 'background_position_x', 'left' ) ); ?> />
<?php _e( 'Right', 'fastfood' ) ?>
</label>
<?php // CUSTOM ADD 'background-position-y' ?>
</br></br>
<label>
<input name="background-position-y" type="radio" value="top"<?php checked( 'top', get_theme_mod( 'background_position_y', 'top' ) ); ?> />
<?php _e( 'Top', 'fastfood' ) ?>
</label>
<label>
<input name="background-position-y" type="radio" value="center"<?php checked( 'center', get_theme_mod( 'background_position_y', 'top' ) ); ?> />
<?php _e( 'Center', 'fastfood' ) ?>
</label>
<label>
<input name="background-position-y" type="radio" value="bottom"<?php checked( 'bottom', get_theme_mod( 'background_position_y', 'top' ) ); ?> />
<?php _e( 'Bottom', 'fastfood' ) ?>
</label>
</fieldset></td>
</tr>

<tr valign="top" class="background-details" <?php if ( !get_background_image() ) echo 'style="display: none;"'; ?>>
<th scope="row"><?php _e( 'Repeat', 'fastfood' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Repeat', 'fastfood' ); ?></span></legend>
<label><input type="radio" name="background-repeat" value="no-repeat"<?php checked( 'no-repeat', get_theme_mod('background_repeat', 'repeat' ) ); ?>> <?php _e( 'No Repeat', 'fastfood' ); ?></option></label>
	<label><input type="radio" name="background-repeat" value="repeat"<?php checked( 'repeat', get_theme_mod('background_repeat', 'repeat' ) ); ?>> <?php _e( 'Tile', 'fastfood' ); ?></option></label>
	<label><input type="radio" name="background-repeat" value="repeat-x"<?php checked( 'repeat-x', get_theme_mod('background_repeat', 'repeat' ) ); ?>> <?php _e( 'Tile Horizontally', 'fastfood' ); ?></option></label>
	<label><input type="radio" name="background-repeat" value="repeat-y"<?php checked( 'repeat-y', get_theme_mod('background_repeat', 'repeat' ) ); ?>> <?php _e( 'Tile Vertically', 'fastfood' ); ?></option></label>
</fieldset></td>
</tr>

<tr valign="top" class="background-details" <?php if ( !get_background_image() ) echo 'style="display: none;"'; ?>>
<th scope="row"><?php _e( 'Attachment', 'fastfood' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Attachment', 'fastfood' ); ?></span></legend>
<label>
<input name="background-attachment" type="radio" value="scroll" <?php checked( 'scroll', get_theme_mod( 'background_attachment', 'scroll' ) ); ?> />
<?php _e( 'Scroll', 'fastfood' ) ?>
</label>
<label>
<input name="background-attachment" type="radio" value="fixed" <?php checked( 'fixed', get_theme_mod( 'background_attachment', 'scroll' ) ); ?> />
<?php _e( 'Fixed', 'fastfood' ) ?>
</label>
</fieldset></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e( 'Color', 'fastfood' ); ?></th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e( 'Background Color', 'fastfood' ); ?></span></legend>
<?php $show_clear = get_background_color() ? '' : ' style="display:none"'; ?>
<input type="text" name="background-color" id="background-color" value="#<?php echo esc_attr(get_background_color()) ?>" />
<a class="hide-if-no-js" href="#" id="pickcolor"><?php _e( 'Select a Color', 'fastfood' ); ?></a> <span <?php echo $show_clear; ?>class="hide-if-no-js" id="clearcolor"> (<a href="#"><?php _e( 'Clear', 'fastfood' ); ?></a>)</span>
<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
</fieldset></td>
</tr>
</tbody>
</table>

<?php wp_nonce_field( 'custom-background' ); ?>
<?php submit_button( null, 'primary', 'save-background-options' ); ?>
</form>

</div>
<?php
	}

	/* Handle an Image upload for the background image. */
	function handle_upload() {

		if ( empty($_FILES) )
			return;

		check_admin_referer('custom-background-upload', '_wpnonce-custom-background-upload');
		$overrides = array('test_form' => false);
		$file = wp_handle_upload($_FILES['import'], $overrides);

		if ( isset($file['error']) )
			wp_die( $file['error'] );

		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$filename = basename($file);

		// Construct the object array
		$object = array(
			'post_title' => $filename,
			'post_content' => $url,
			'post_mime_type' => $type,
			'guid' => $url,
			'context' => 'custom-background'
		);

		// Save the data
		$id = wp_insert_attachment($object, $file);

		// Add the meta-data
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );

		set_theme_mod('background_image', esc_url($url));

		$thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );
		set_theme_mod('background_image_thumb', esc_url( $thumbnail[0] ) );

		do_action('wp_create_file_in_uploads', $file, $id); // For replication
		$this->updated = true;
	}

}
?>