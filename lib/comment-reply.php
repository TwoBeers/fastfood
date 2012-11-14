<?php
/**
 * the comment reply script.
 *
 * @package fastfood
 * @since fastfood 0.34
 */

if ( !class_exists( 'FastfoodCommentReply' ) ) {

	class FastfoodCommentReply {

		/**
		* PHP 4 Compatible Constructor
		*/
		function FastfoodCommentReply(){

			$this->__construct();

		}

		/**
		* PHP 5 Constructor
		*/
		function __construct() {
			global $fastfood_opt, $fastfood_is_printpreview, $fastfood_is_mobile, $fastfood_is_ie6;

			if ( is_admin() || $fastfood_is_mobile || $fastfood_is_printpreview ) return;

			if ( $fastfood_is_ie6 || !$fastfood_opt['fastfood_cust_comrep'] ) {

				add_action( 'comment_form_before', array( $this, 'load_standard_scripts' ) );

			} else {

				add_action( 'comment_form_before', array( $this, 'load_custom_scripts' ) );
				add_action( 'comment_form_after', array( $this, 'hide_form' ) );
				add_filter( 'comment_reply_link', array( $this, 'change_function' ), 10, 4 );

			}

		}

		function change_function($link, $args, $comment, $post) {

			return str_replace("addComment", "fastfoodCustomReply", $link);

		}

		function hide_form() {

	?>

	<script type="text/javascript">
		/* <![CDATA[ */
		document.getElementById('respond').style.display = 'none';
		/* ]]> */
	</script>

	<?php

		}

		function load_standard_scripts() {

			if( get_option( 'thread_comments' ) ) {

				wp_enqueue_script( 'comment-reply' ); //standard comment-reply box

			}

		}

		function load_custom_scripts() {
			global $fastfood_version;

				wp_enqueue_script( 'fastfood-comment-reply', get_template_directory_uri() . '/js/comment-reply.dev.js', array( 'jquery-ui-draggable' ), $fastfood_version, true ); //custom comment-reply pop-up box

		}
	  
	}

}

new FastfoodCommentReply();

