<?php
/**
 * the comment reply script.
 *
 * @package fastfood
 * @since fastfood 0.34
 */

class FastfoodCommentReply {

	function __construct() {

			add_action( 'wp_enqueue_scripts'	, array( $this, 'init' ) );

	}


	function init() {

		if ( is_admin() || fastfood_is_mobile() || fastfood_is_printpreview() ) return;

		if ( ( !FastfoodOptions::get_opt( 'fastfood_cust_comrep' ) ) || ( !FastfoodOptions::get_opt( 'fastfood_jsani' ) ) ) {

			add_action( 'comment_form_before'	, array( $this, 'load_standard_scripts' ) );

		} else {

			add_action( 'comment_form_before'	, array( $this, 'load_custom_scripts' ) );
			add_action( 'comment_form_after'	, array( $this, 'hide_form' ) );

		}

	}


	function hide_form() {

?>

	<script type="text/javascript">
		/* <![CDATA[ */
		document.getElementById('respond').className += ' hidden';
		/* ]]> */
	</script>

<?php

	}


	function load_standard_scripts() {

		if( !get_option( 'thread_comments' ) ) return;

		wp_enqueue_script( 'comment-reply' );

	}


	function load_custom_scripts() {

		if( !get_option( 'thread_comments' ) ) return;

		wp_enqueue_script( 'fastfood-comment-reply', get_template_directory_uri() . '/js/comment-reply.js', array( 'jquery-ui-draggable', 'hoverIntent' ), fastfood_get_info( 'version' ), true ); //custom comment-reply pop-up box

		$data = array(
			'replytopost'		=> esc_attr( __( 'Leave a comment', 'fastfood' ) ),
			'replytocomment'	=> esc_attr( __( 'Reply to Comment', 'fastfood' ) ),
			'close'				=> esc_attr( __( 'Close', 'fastfood' ) ),
		);
		wp_localize_script( 'fastfood-comment-reply', 'fastfood_comment_reply_l10n', $data );

	}

}

new FastfoodCommentReply();

