<?php
/**
 * audio-player.php
 *
 * audio player script.
 *
 * @package fastfood
 * @since fastfood 0.31
 */


add_action( 'template_redirect', 'fastfood_init_audio_player' );


// setup for audio player
if ( !function_exists( 'fastfood_init_audio_player' ) ) {
	function fastfood_init_audio_player(){

		if ( is_admin() || fastfood_is_mobile() || fastfood_is_printpreview() || !is_singular() || ! fastfood_get_opt( 'fastfood_audio_player' ) ) return;

		add_action( 'fastfood_hook_post_content_after', 'fastfood_add_audio_player' );

	}
}


// add scripts
function fastfood_audioplayer_scripts(){

	wp_enqueue_script( 'fastfood-audioplayer', get_template_directory_uri() . '/js/audio-player.min.js', array( 'swfobject' ), fastfood_get_info( 'version' ), true  );

	$data = array(
		'unknown_media' => esc_js( __( 'unknown media format', 'fastfood' ) ), //ff_unknown_media_format
		'player_path' => get_template_directory_uri().'/resources/audio-player/player.swf', //ff_SWFPlayer
	);
	wp_localize_script( 'fastfood-audioplayer', 'fastfoodAudioPlayer_l10n', $data );

}


// search for linked mp3's and add an audio player
function fastfood_add_audio_player( $text = '' ) {
	global $post;

	$pattern = "/<a ([^=]+=['\"][^\"']+['\"] )*href=['\"](([^\"']+\.(mp3|ogg|m4a)))['\"]( [^=]+=['\"][^\"']+['\"])*>([^<]+)<\/a>/i";

	if ( $text != '')
		preg_match_all( $pattern, $text, $result );
	elseif ( is_attachment() )
		preg_match_all( $pattern, wp_get_attachment_link( $post->ID ), $result );
	else
		preg_match_all( $pattern, $post->post_content, $result );

	if ( $result[0] )
		fastfood_audioplayer_scripts(); // Add js

	$instance = 0;

	foreach ($result[0] as $key => $value) {
		$instance++;

?>
<div class="ff-player-container">
	<small><?php echo $result[0][$key];?></small>
	<div class="ff-player-content">
		<audio controls="" id="ff-player-<?php echo $instance . '-' . $post->ID; ?>" class="no-player">
			<source src="<?php echo $result[3][$key];?>" />
			<span class="ff-player-notice"><?php _e( 'this audio type is not supported by your browser','fastfood' ); ?></span>
		</audio>
	</div>
</div>
<?php

	}

}
