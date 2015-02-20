var addComment;

( function( $ ) {

addComment = {

	init : function() {
		var t          = this,
			cancel     = t.I( 'cancel-comment-reply-link' ),
			replytitle = t.I( 'reply-title' ),
			respond    = t.I( 'respond' ),
			comments   = t.I( 'comments' );

		if ( !respond || !replytitle || !cancel )
			return;

		$( 'body' )
			.addClass( 'floating-comment-form' );

		$( '.comment_tools.has-comments', comments )
			.clone()
			.addClass( 'after-comments' )
			.insertAfter( '#commentlist-wrap' );

		$( respond )
			.before( '<div id="wp-temp-form-div"></div>' )
			.wrapInner( '<div id="respond-inner"></div>' );

		$( '#respond-inner' )
			.draggable( {
				handle: '#reply-title',
				disabled: true
			} );

		$( '<a id="hide-respond-link" href="#">Close</a>' )
			.appendTo( replytitle )
			.click( function() {
				t.hideForm();
				return false;
			} );

		$( cancel )
			.css( 'display', '' )
			.click(function() {
				t.hideForm();
				return false;
			});

		$( '.show_comment_form' )
			.click(function() {
				if ( $( respond ).is( '.hidden' ) ) {
					t.viewForm();
					if ( $( 'body' ).is( '.layout-2,.layout-1' ) )
						return false;
				} else {
					t.hideForm();
					return false;
				}
			});
	},

	viewForm : function() {
		var t       = this,
			parent  = t.I( 'comment_parent' ),
			respond = t.I( 'respond' );

		if ( !respond || !parent )
			return;

		if ( t.I( 'comment_parent' ).value !== '0' )
			$( '#reply-title .label' )
				.text( fastfood_comment_reply_l10n.replytocomment );

		if ( $( 'body' ).is( '.layout-2,.layout-1' ) ) {
			$( '#respond-inner' )
				.draggable( 'enable' );
			$( respond )
				.css( { 'opacity' : 0, 'margin-top' : 20 } )
				.addClass( 'js-res visible' )
				.removeClass( 'hidden' )
				.animate( {
					'opacity' : 1,
					'margin-top' : 0
				}, 400
				);
		} else {
			$( respond )
				.addClass( 'visible' )
				.removeClass( 'hidden' );
		}
	},

	hideForm : function() {
		var t       = this,
			temp    = t.I( 'wp-temp-form-div' ),
			respond = t.I( 'respond' );

		if ( !respond || !temp )
			return;

		t.I( 'comment_parent' ).value = '0';

		$( '#reply-title .label' )
			.text( fastfood_comment_reply_l10n.replytopost );

		$( temp )
			.after( respond );

		$( '#respond-inner' )
			.draggable( 'disable' );

		if ( $( 'body' ).is( '.layout-2,.layout-1' ) ) {
			$( respond )
				.animate( {
					'opacity' : 0,
					'margin-top' : 20
				}, 200, function() {
					$( this )
						.removeClass( 'visible js-res' )
						.addClass( 'hidden' )
						.removeAttr( 'style' );
				} );
		} else {
			$( respond )
				.removeClass( 'visible js-res' )
				.addClass( 'hidden' );
		}

		return false;
	},

	moveForm : function( commId, parentId, respondId, postId ) {
		var t       = this,
			comm    = t.I( commId ),
			respond = t.I( respondId ),
			parent  = t.I( 'comment_parent' ),
			post    = t.I( 'comment_post_ID' );

		if ( !comm || !respond || !parent )
			return;

		t.respondId = respondId;
		postId = postId || false;

		comm.parentNode.insertBefore( respond, comm.nextSibling );
		if ( post && postId )
			post.value = postId;
		parent.value = parentId;

		t.viewForm();

		try { t.I( 'comment' ).focus(); }
		catch( e ) {}

		return false;
	},

	I : function( e ) {
		return document.getElementById(e);
	}

};

$( document ).ready( function( $ ){ addComment.init(); } );

} )( jQuery );