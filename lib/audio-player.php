<?php
/**
 * audio player script.
 *
 * @package fastfood
 * @since fastfood 0.31
 */


add_action( 'template_redirect', 'fastfood_init_audio_player' );

// setup for audio player
if ( !function_exists( 'fastfood_init_audio_player' ) ) {
	function fastfood_init_audio_player(){
		global $fastfood_is_printpreview, $fastfood_is_mobile;

		if ( is_admin() || $fastfood_is_mobile || $fastfood_is_printpreview || !is_singular() ) return;

		add_action( 'wp_head', 'fastfood_localize_audio_player' );
		add_action( 'fastfood_hook_after_post_content', 'fastfood_add_audio_player' );

	}
}

// add scripts
if ( !function_exists( 'fastfood_audioplayer_scripts' ) ) {
	function fastfood_audioplayer_scripts(){
		global $fastfood_version;

		wp_enqueue_script( 'fastfood-audioplayer', get_template_directory_uri() . '/js/audio-player.min.js', array( 'swfobject' ), $fastfood_version, true  );

	}
}

// initialize scripts
if ( !function_exists( 'fastfood_localize_audio_player' ) ) {
	function fastfood_localize_audio_player(){

?>

<script type="text/javascript">
	/* <![CDATA[ */
		ff_unknown_media_format = "<?php _e( 'unknown media format', 'fastfood' ); ?>";
		ff_SWFPlayer = "<?php echo get_template_directory_uri().'/resources/audio-player/player.swf'; ?>";
	/* ]]> */
</script>

<?php

	}
}

// search for linked mp3's and add an audio player
if ( !function_exists( 'fastfood_add_audio_player' ) ) {
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
			// Add js
			fastfood_audioplayer_scripts();
			

		foreach ($result[0] as $key => $value) {
?>

<div class="ff-player-container">
	<small><?php echo $result[0][$key];?></small>
	<div class="ff-player-content">
		<audio controls="">
			<source src="<?php echo $result[3][$key];?>" />
			<span class="ff-player-notice"><?php _e( 'this audio type is not supported by your browser','fastfood' ); ?></span>
		</audio>
	</div>
</div>

<?php
		}
	}
}

?>