<?php
if ( !current_user_can( 'edit_posts' ) ) {
	wp_die( 'You do not have sufficient permissions to access this page.' );
}

function fastfood_media_navi( $paged, $ppp, $count, $attached_to ) {

	$arr_params['galed_media'] = '1';
	$arr_params_option['galed_media'] = '1';

	$navi = '<div class="galed-media-navi">';


	$lastposts = get_posts( 'numberposts=-1' );
	$navi .= '<select class="galed-media-navi-filter" onChange="galedGoTo(this.options[this.selectedIndex].value)" value="ciao">';
	$navi .= '<option class="first-option" value="' . (add_query_arg('galed_media','1', home_url() )) . '">' . __('Images Library','fastfood') . '</option>';
	foreach( $lastposts as $post ) {
		$arr_params_option['galed_id'] = $post->ID;
		$post_title = ( $post->post_title != '') ? $post->post_title : 'Post #' . $post->ID ;
		$navi .= '<option value="' . (add_query_arg( $arr_params_option, home_url() )) . '" ' . selected( $attached_to, $post->ID, false ) . '>' . $post_title . '</option>';
	}
	$navi .= '</select>';


	if ( $attached_to )
		$arr_params_option['galed_id'] = $attached_to;
	else
		unset($arr_params_option['galed_id']);
	
	$navi .= '<span class="galed-img-count">' . sprintf( __( '%s images', 'fastfood' ), $count ) . '</span>';
	if ( $paged == 2 ) { $navi .= '<a href="' . add_query_arg( $arr_params, home_url() ) . '">&laquo;</a>'; }
	if ( $paged > 2 ) { $arr_params['galed_paged'] = ( $paged - 1 ); $navi .= '<a href="' . add_query_arg( $arr_params, home_url() ) . '">&laquo;</a>'; }
	if ( ( $count / $paged ) > 1 ) {
		$navi .= '<select id="sample" onChange="galedGoTo(this.options[this.selectedIndex].value)">';
		for ($i=1; $i<=ceil( $count / $ppp ); $i++) {
			if ( $i == 1 ) { $navi .= '<option value="' . (add_query_arg( $arr_params_option, home_url() )) . '" ' . selected( $paged, 1 , false) . '>' . $i . '</option>'; }
			if ( $i > 1 ) { $arr_params_option['galed_paged'] = ( $i ); $navi .= '<option value="' . (add_query_arg( $arr_params_option, home_url() )) . '" ' . selected( $paged, $i , false) . '>' . $i . '</option>'; }
		}	
		$navi .= '</select>';
		$navi .= '<span>' . sprintf( __( 'of %s', 'fastfood' ), ceil( $count / $ppp ) ) . '</span>';
	}
	if ( $count > ( $paged * $ppp ) ) { $arr_params['galed_paged'] = ( $paged + 1 ); $navi .= '<a href="' . add_query_arg( $arr_params, home_url() ) . '">&raquo;</a>'; }
	$navi .= '</div>';
	return $navi;
	
}

function fastfood_media_library() {
	$paged = isset( $_GET['galed_paged'] ) ? (int)$_GET['galed_paged'] : 1;
	$attached_to = isset( $_GET['galed_id'] ) ? (int)$_GET['galed_id'] : null;
	$ppp = 21;
	$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_mime_type' => 'image', 'post_status' => null, 'post_parent' => $attached_to ); 
	$attachments = get_posts( $args );
	
	$navi = fastfood_media_navi( $paged, $ppp, count($attachments), $attached_to );
	
	$attachments = array_slice( $attachments, ( ( $paged - 1 ) * $ppp ), $ppp );
	echo $navi;
	if ($attachments) { ?>
		<?php  ?>
		<div id="galed-media-library">
			<?php foreach ( $attachments as $attachment ) {
				setup_postdata($attachment);
				$details = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' ); 
			?>
				<div class="thumb"><a href="javascript:void(0)" onClick="galedSendToInput('<?php echo $attachment->ID; ?>','<?php echo $details[0]; ?>','<?php echo wp_get_attachment_url( $attachment->ID ); ?>')"><?php echo wp_get_attachment_image( $attachment->ID ); ?></a></div>
			<?php } ?>
		</div>
		<?php echo $navi; ?>
	<?php }

}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<title><?php echo bloginfo( 'name' ); ?> - <?php _e('Images Library','fastfood'); ?></title>
	</head>
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/css/admin-media.css" />
	<body>
		<?php fastfood_media_library(); ?>
		<script type="text/javascript">
			/* <![CDATA[ */
			function galedSendToInput( the_id, the_src, the_link ) {
				var win = window.dialogArguments || parent || opener || top;
				win.galedAddImage( the_id, the_src, the_link );
			}
			function galedGoTo(the_url) {
				window.open(the_url,'_self');
			}
			/* ]]> */
		</script>
	</body>
</html>