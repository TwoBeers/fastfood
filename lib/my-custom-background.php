<?php
/**
 * my-custom-background.php
 *
 * The custom background script.
 * "Fastfood_Custom_Background" class based on WP wp-admin/custom-background.php
 *
 * @package fastfood
 * @since fastfood 0.27
 */


add_action( 'after_setup_theme', 'fastfood_custom_background_init' );


// set up custom colors and header image
function fastfood_custom_background_init() {

	if ( fastfood_get_opt('fastfood_custom_bg' ) ) {

		add_action( 'wp_head',			'fastfood_custom_background_style' );
		add_action( 'admin_bar_menu',	'fastfood_custom_background_admin_bar', 998 );

		if ( is_admin() )
			$custom_background = new Fastfood_Custom_Background(); // the enhanced 'custom background' support

	} else {

		// the standard 'custom background' support
		$args = array(
			'default-color'				=> '',
			'default-image'				=> '',
			'wp-head-callback'			=> 'fastfood_custom_background_style',
			'admin-head-callback'		=> '',
			'admin-preview-callback'	=> ''
		);
		add_theme_support( 'custom-background', $args );

	}

}


function fastfood_custom_background_style() {

	if ( fastfood_is_printpreview() || fastfood_is_mobile() ) return;

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


// add custom background link to admin bar
function fastfood_custom_background_admin_bar() {
	global $wp_admin_bar;

	if ( !current_user_can( 'edit_theme_options' ) || !is_admin_bar_showing() )
		return;

	$add_menu_meta = array(
		'target'	=> '_blank'
	);

	$wp_admin_bar->add_menu( array(
		'id'		=> 'ff_custom_background',
		'parent'	=> 'appearance',
		'title'	 => __( 'Background', 'fastfood' ),
		'href'	  => get_admin_url() . 'themes.php?page=custom-background',
		'meta'	  => $add_menu_meta
	) );

}


/**
 * The custom background class.
 *
 */
class Fastfood_Custom_Background {

	/**
	 * Holds the page menu hook.
	 */
	var $page = '';

	
	/**
	 * Holds default background images.
	 */
	var $default_bg_images = array();

	
	/**
	 * Constructor - Register administration header callback.
	 */
	function __construct() {

		add_action( 'admin_menu', array( $this, 'init' ) );
		add_action( 'wp_ajax_set-background-image', array( $this, 'wp_set_background_image' ) );

	}


	/**
	 * Set up the hooks for the Custom Background admin page.
	 */
	function init() {

		if ( ! current_user_can( 'edit_theme_options' ) )
			return;

		$this->page = $page = add_theme_page( __( 'Background', 'fastfood' ), __( 'Background', 'fastfood' ), 'edit_theme_options', 'custom-background', array( $this, 'admin_page' ) );

		add_action( "load-$page", array( $this, 'admin_load' ) );
		add_action( "load-$page", array( $this, 'take_action' ) );
		add_action( "load-$page", array( $this, 'handle_upload' ) );

	}


	/**
	 * Set up the enqueue for the CSS & JavaScript files.
	 */
	function admin_load() {

		wp_enqueue_script( 'fastfood-custom-background', get_template_directory_uri() . '/js/admin-custom_background.js', array( 'jquery', 'farbtastic' ), '', true  );
		wp_enqueue_style( 'fastfood-admin-custom-background', get_template_directory_uri() . '/css/admin-custom_background.css', false, '', 'screen' );
		wp_enqueue_media();
		wp_enqueue_script('custom-background');
		wp_enqueue_style('wp-color-picker');
	}

	
	/**
	 * Execute custom background modification.
	 */
	function take_action() {

		if ( empty($_POST) )
			return;

		if ( isset($_POST['reset-background']) ) {
			check_admin_referer('custom-background-reset', '_wpnonce-custom-background-reset');
			remove_theme_mod('background_image');
			remove_theme_mod('background_image_thumb');
			$this->updated = true;
			return;
		}

		if ( isset($_POST['remove-background']) ) {
			check_admin_referer('custom-background-remove', '_wpnonce-custom-background-remove');
			set_theme_mod('background_image', '');
			set_theme_mod('background_image_thumb', '');
			$this->updated = true;
			wp_safe_redirect( $_POST['_wp_http_referer'] );
			return;
		}

		if ( isset($_POST['background-repeat']) ) {
			check_admin_referer('custom-background');
			if ( in_array($_POST['background-repeat'], array('repeat', 'no-repeat', 'repeat-x', 'repeat-y')) )
				$repeat = $_POST['background-repeat'];
			else
				$repeat = 'repeat';
			set_theme_mod('background_repeat', $repeat);
		}

		if ( isset($_POST['background-position-x']) ) {
			check_admin_referer('custom-background');
			if ( in_array($_POST['background-position-x'], array('center', 'right', 'left')) )
				$position_x = $_POST['background-position-x'];
			else
				$position_x = 'left';
			set_theme_mod('background_position_x', $position_x);
		}

		if ( isset( $_POST['background-position-y'] ) ) {

			check_admin_referer( 'custom-background' );
			if ( in_array( $_POST['background-position-y'], array( 'center', 'top', 'bottom' ) ) )
				$position_y = $_POST['background-position-y'];
			else
				$position_y = 'top';
			set_theme_mod( 'background_position_y', $position_y );

		}

		if ( isset($_POST['background-attachment']) ) {
			check_admin_referer('custom-background');
			if ( in_array($_POST['background-attachment'], array('fixed', 'scroll')) )
				$attachment = $_POST['background-attachment'];
			else
				$attachment = 'fixed';
			set_theme_mod('background_attachment', $attachment);
		}

		if ( isset($_POST['background-color']) ) {
			check_admin_referer('custom-background');
			$color = preg_replace('/[^0-9a-fA-F]/', '', $_POST['background-color']);
			if ( strlen($color) == 6 || strlen($color) == 3 )
				set_theme_mod('background_color', $color);
			else
				set_theme_mod('background_color', '');
		}

		if ( isset( $_POST['default-bg'] ) ) {

			check_admin_referer( 'custom-background' );
			$this->process_default_bg_images();
			if ( in_array( $_POST['default-bg'], array( 'aqua', 'pink', 'wood', 'greenwave', 'globe', 'heart', 'clouds', 'violet', 'paper', 'squarednight', 'cork', 'yellow', 'graphite' ) ) ) {
				set_theme_mod( 'background_image', esc_url( $this->default_bg_images[$_POST['default-bg']]['url'] ) );
				set_theme_mod( 'background_image_thumb', esc_url( $this->default_bg_images[$_POST['default-bg']]['thumbnail_url'] ) );
			}

		}

		$this->updated = true;
	}


	/**
	 * Process the default backgrounds.
	 */
	function process_default_bg_images() {

		$default_bg_images = array(
			'aqua' => array(
				'url'				=> '%s/images/backgrounds/aqua.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/aqua-thumbnail.jpg',
				'description'		=> __( 'Aqua', 'fastfood' ),
				'position_x'		=> 'center',
				'position_y'		=> 'top',
				'repeat'			=> 'no-repeat',
				'attach'			=> 'fixed',
				'color'				=> '#F5FDFF'
			),
			'wood' => array(
				'url'				=> '%s/images/backgrounds/wood.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/wood-thumbnail.jpg',
				'description'		=> __( 'Wood', 'fastfood' ),
				'position_x'		=> 'left',
				'position_y'		=> 'top',
				'repeat'			=> 'repeat',
				'attach'			=> 'fixed',
				'color'				=> '#000000'
			),
			'greenwave' => array(
				'url'				=> '%s/images/backgrounds/greenwave.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/greenwave-thumbnail.jpg',
				'description'		=> __( 'Green Wave', 'fastfood' ),
				'position_x'		=> 'left',
				'position_y'		=> 'top',
				'repeat'			=> 'repeat-y',
				'attach'			=> 'fixed',
				'color'				=> '#F9FFD9'
			),
			'violet' => array(
				'url'				=> '%s/images/backgrounds/violet.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/violet-thumbnail.jpg',
				'description'		=> __( 'Violet', 'fastfood' ),
				'position_x'		=> 'right',
				'position_y'		=> 'top',
				'repeat'			=> 'no-repeat',
				'attach'			=> 'fixed',
				'color'				=> '#190923'
			),
			'paper' => array(
				'url'				=> '%s/images/backgrounds/paper.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/paper-thumbnail.jpg',
				'description'		=> __( 'Paper', 'fastfood' ),
				'position_x'		=> 'left',
				'position_y'		=> 'top',
				'repeat'			=> 'repeat',
				'attach'			=> 'scroll',
				'color'				=> '#000000'
			),
			'squarednight' => array(
				'url'				=> '%s/images/backgrounds/squarednight.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/squarednight-thumbnail.jpg',
				'description'		=> __( 'Squared Night', 'fastfood' ),
				'position_x'		=> 'left',
				'position_y'		=> 'top',
				'repeat'			=> 'repeat-x',
				'attach'			=> 'scroll',
				'color'				=> '#0E111A'
			),
			'cork' => array(
				'url'				=> '%s/images/backgrounds/cork.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/cork-thumbnail.jpg',
				'description'		=> __( 'Cork', 'fastfood' ),
				'position_x'		=> 'left',
				'position_y'		=> 'top',
				'repeat'			=> 'repeat',
				'attach'			=> 'fixed',
				'color'				=> '#000000'
			),
			'yellow' => array(
				'url'				=> '%s/images/backgrounds/yellow.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/yellow-thumbnail.jpg',
				'description'		=> __( 'Yellow', 'fastfood' ),
				'position_x'		=> 'left',
				'position_y'		=> 'top',
				'repeat'			=> 'repeat',
				'attach'			=> 'scroll',
				'color'				=> '#000000'
			),
			'clouds' => array(
				'url'				=> '%s/images/backgrounds/clouds.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/clouds-thumbnail.jpg',
				'description'		=> __( 'Clouds', 'fastfood' ),
				'position_x'		=> 'left',
				'position_y'		=> 'top',
				'repeat'			=> 'repeat-x',
				'attach'			=> 'fixed',
				'color'				=> '#DBE8F9'
			),
			'graphite' => array(
				'url'				=> '%s/images/backgrounds/graphite.jpg',
				'thumbnail_url'		=> '%s/images/backgrounds/graphite-thumbnail.jpg',
				'description'		=> __( 'Graphite', 'fastfood' ),
				'position_x'		=> 'left',
				'position_y'		=> 'top',
				'repeat'			=> 'repeat',
				'attach'			=> 'scroll',
				'color'				=> '#212121'
			),
		);

		$this->default_bg_images = $default_bg_images;

		foreach ( array_keys($this->default_bg_images) as $header ) {

			$this->default_bg_images[$header]['url'] =  sprintf( $this->default_bg_images[$header]['url'], get_template_directory_uri(), get_stylesheet_directory_uri() );
			$this->default_bg_images[$header]['thumbnail_url'] =  sprintf( $this->default_bg_images[$header]['thumbnail_url'], get_template_directory_uri(), get_stylesheet_directory_uri() );

		}

	}


	/**
	 * Display UI for selecting one of several default backgrounds.
	 */
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


	/**
	 * Display the custom background page.
	 */
	function admin_page() {

		$this->process_default_bg_images();

?>
	<div class="wrap" id="custom-background">

		<?php screen_icon(); ?>

		<h2><?php _e('Custom Background', 'fastfood'); ?></h2>

		<?php if ( !empty($this->updated) ) { ?>
			<div id="message" class="updated">
				<p><?php printf( __( 'Background updated. <a href="%s">Visit your site</a> to see how it looks.', 'fastfood' ), home_url( '/' ) ); ?></p>
			</div>
		<?php } ?>

		<h3><?php _e('Background Image', 'fastfood'); ?></h3>

		<table class="form-table">

			<tbody>

				<tr valign="top">

					<th scope="row"><?php _e('Preview', 'fastfood'); ?></th>

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
						<img class="custom-background-image" src="<?php echo set_url_scheme( get_theme_mod( 'background_image_thumb', get_background_image() ) ); ?>" style="visibility:hidden;" alt="" /><br />
						<img class="custom-background-image" src="<?php echo set_url_scheme( get_theme_mod( 'background_image_thumb', get_background_image() ) ); ?>" style="visibility:hidden;" alt="" />
						<?php } ?>
						</div>
					</td>

				</tr>

				<?php if ( get_background_image() ) : ?>
				<tr valign="top">

					<th scope="row"><?php _e('Remove Image', 'fastfood'); ?></th>

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

					<th scope="row"><?php _e('Restore Original Image', 'fastfood'); ?></th>

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

					<th scope="row"><?php _e('Select Image', 'fastfood'); ?></th>

					<td>
						<form enctype="multipart/form-data" id="upload-form" class="wp-upload-form" method="post" action="">
							<p>
								<label for="upload"><?php _e( 'Choose an image from your computer:', 'fastfood' ); ?></label><br />
								<input type="file" id="upload" name="import" />
								<input type="hidden" name="action" value="save" />
								<?php wp_nonce_field( 'custom-background-upload', '_wpnonce-custom-background-upload' ); ?>
								<?php submit_button( __( 'Upload', 'fastfood' ), 'button', 'submit', false ); ?>
							</p>
							<p>
								<label for="choose-from-library-link"><?php _e( 'Or choose an image from your media library:', 'fastfood' ); ?></label><br />
								<a id="choose-from-library-link" class="button"
									data-choose="<?php esc_attr_e( 'Choose a Background Image', 'fastfood' ); ?>"
									data-update="<?php esc_attr_e( 'Set as background', 'fastfood' ); ?>"><?php _e( 'Choose Image', 'fastfood' ); ?></a>
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
								<?php $this->show_default_bg_selector(); ?>
								<div class="clear"></div>
							</div>
						</td>

					</tr>

				</tbody>

			</table>

			<h3><?php _e('Display Options', 'fastfood') ?></h3>

			<table class="form-table">

				<tbody>

					<?php if ( get_background_image() ) : ?>
					<tr valign="top">

						<th scope="row"><?php _e( 'Position', 'fastfood' ); ?></th>

						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Background Position', 'fastfood' ); ?></span></legend>
								<label><input name="background-position-x" type="radio" value="left"<?php checked('left', get_theme_mod('background_position_x', 'left')); ?> /><?php _e('Left', 'fastfood') ?></label>
								<label><input name="background-position-x" type="radio" value="center"<?php checked('center', get_theme_mod('background_position_x', 'left')); ?> /><?php _e('Center', 'fastfood') ?></label>
								<label><input name="background-position-x" type="radio" value="right"<?php checked('right', get_theme_mod('background_position_x', 'left')); ?> /><?php _e('Right', 'fastfood') ?></label>
								<br><br>
								<label><input name="background-position-y" type="radio" value="top"<?php checked( 'top', get_theme_mod( 'background_position_y', 'top' ) ); ?> /><?php _e( 'Top', 'fastfood' ) ?></label>
								<label><input name="background-position-y" type="radio" value="center"<?php checked( 'center', get_theme_mod( 'background_position_y', 'top' ) ); ?> /><?php _e( 'Center', 'fastfood' ) ?></label>
								<label><input name="background-position-y" type="radio" value="bottom"<?php checked( 'bottom', get_theme_mod( 'background_position_y', 'top' ) ); ?> /><?php _e( 'Bottom', 'fastfood' ) ?></label>
							</fieldset>
						</td>

					</tr>

					<tr valign="top">

						<th scope="row"><?php _e( 'Repeat', 'fastfood' ); ?></th>

						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Background Repeat', 'fastfood' ); ?></span></legend>
								<label><input type="radio" name="background-repeat" value="no-repeat"<?php checked( 'no-repeat', get_theme_mod('background_repeat', 'repeat' ) ); ?>> <?php _e( 'No Repeat', 'fastfood' ); ?></option></label>
								<label><input type="radio" name="background-repeat" value="repeat"<?php checked( 'repeat', get_theme_mod('background_repeat', 'repeat' ) ); ?>> <?php _e( 'Tile', 'fastfood' ); ?></option></label>
								<label><input type="radio" name="background-repeat" value="repeat-x"<?php checked( 'repeat-x', get_theme_mod('background_repeat', 'repeat' ) ); ?>> <?php _e( 'Tile Horizontally', 'fastfood' ); ?></option></label>
								<label><input type="radio" name="background-repeat" value="repeat-y"<?php checked( 'repeat-y', get_theme_mod('background_repeat', 'repeat' ) ); ?>> <?php _e( 'Tile Vertically', 'fastfood' ); ?></option></label>
							</fieldset>
						</td>

					</tr>

					<tr valign="top">

						<th scope="row"><?php _e( 'Attachment', 'fastfood' ); ?></th>

						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Background Attachment', 'fastfood' ); ?></span></legend>
								<label><input name="background-attachment" type="radio" value="scroll" <?php checked( 'scroll', get_theme_mod( 'background_attachment', 'scroll' ) ); ?> /><?php _e( 'Scroll', 'fastfood' ) ?></label>
								<label><input name="background-attachment" type="radio" value="fixed" <?php checked( 'fixed', get_theme_mod( 'background_attachment', 'scroll' ) ); ?> /><?php _e( 'Fixed', 'fastfood' ) ?></label>
							</fieldset>
						</td>

					</tr>
					<?php endif; // get_background_image() ?>

					<tr valign="top">

						<th scope="row"><?php _e( 'Background Color', 'fastfood' ); ?></th>

						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Background Color', 'fastfood' ); ?></span></legend>
								<input type="text" name="background-color" id="background-color" value="#<?php echo esc_attr( get_background_color() ); ?>" />
							</fieldset>
						</td>
					</tr>

				</tbody>

			</table>

			<?php wp_nonce_field('custom-background'); ?>
			<?php submit_button( null, 'primary', 'save-background-options' ); ?>
		</form>

	</div>
<?php
	}


	/**
	 * Handle an Image upload for the background image.
	 */
	function handle_upload() {

		if ( empty($_FILES) )
			return;

		check_admin_referer('custom-background-upload', '_wpnonce-custom-background-upload');
		$overrides = array('test_form' => false);

		$uploaded_file = $_FILES['import'];
		$wp_filetype = wp_check_filetype_and_ext( $uploaded_file['tmp_name'], $uploaded_file['name'], false );
		if ( ! wp_match_mime_types( 'image', $wp_filetype['type'] ) )
			wp_die( __( 'The uploaded file is not a valid image. Please try again.', 'fastfood' ) );

		$file = wp_handle_upload($uploaded_file, $overrides);

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
		update_post_meta( $id, '_wp_attachment_is_custom_background', get_option('stylesheet' ) );

		set_theme_mod('background_image', esc_url_raw($url));

		$thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );
		set_theme_mod('background_image_thumb', esc_url_raw( $thumbnail[0] ) );

		do_action('wp_create_file_in_uploads', $file, $id); // For replication
		$this->updated = true;
	}


	public function wp_set_background_image() {
		if ( ! current_user_can('edit_theme_options') || ! isset( $_POST['attachment_id'] ) ) exit;
		$attachment_id = absint($_POST['attachment_id']);
		$sizes = array_keys(apply_filters( 'image_size_names_choose', array('thumbnail' => __('Thumbnail', 'fastfood'), 'medium' => __('Medium', 'fastfood'), 'large' => __('Large', 'fastfood'), 'full' => __('Full Size', 'fastfood')) ));
		$size = 'thumbnail';
		if ( in_array( $_POST['size'], $sizes ) )
			$size = esc_attr( $_POST['size'] );

		update_post_meta( $attachment_id, '_wp_attachment_is_custom_background', get_option('stylesheet' ) );
		$url = wp_get_attachment_image_src( $attachment_id, $size );
		$thumbnail = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
		set_theme_mod( 'background_image', esc_url_raw( $url[0] ) );
		set_theme_mod( 'background_image_thumb', esc_url_raw( $thumbnail[0] ) );
		exit;
	}
}
