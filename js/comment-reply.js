var fastfoodCustomReply;

(function($) {

fastfoodCustomReply = {

	//initialize
	init : function() {

		this.resetForm();
		this.addCloseButton();
		$respond = $('#respond');
		$respond.appendTo('#content');
		$respond.draggable({ handle: '#reply-title' });
		$(".show_comment_form").click(function() {
			fastfoodCustomReply.viewForm();
			return false;
		});
		$respond.hoverIntent(
			function(){ //when mouse enters, slide down the sub list
				$respond.animate(
					{ 'opacity' : 1 },
					200,
					'swing'
				);
			},
			function(){ //when mouse leaves, hide the sub list
				$respond.stop().animate(
					{ 'opacity' : 0.3 },
					1000,
					'swing'
				);
			}
		);


	},

	moveForm : function(commId, parentId, respondId, postId) {
		var t = this, div, comm = t.I(commId), respond = t.I(respondId), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('comment_post_ID'), replytitle = t.I('reply-title');

		if ( ! comm || ! respond || ! cancel || ! parent || ! replytitle)
			return;

		t.respondId = respondId;
		postId = postId || false;

		if ( post && postId )
			post.value = postId;
		parent.value = parentId;
		
		replytitle.innerHTML = fastfood_comment_reply_l10n.replytocomment + t.addCloseButton();
		replytitle.appendChild(cancel);

		$('#respond').css({'display' : 'block' , 'opacity' : 0 }).animate(
			{ 'opacity' : 1 , 'margin-top' : 0 },
			500
		);

		cancel.onclick = function() {
			var t = fastfoodCustomReply, respond = t.I(t.respondId);

			if ( ! respond )
				return;

			t.I('comment_parent').value = '0';
			this.onclick = null;
			respond.style.display = "none";
			return false;
		}

		try { t.I('comment').focus(); }
		catch(e) {}

		return false;
	},
	
	resetForm : function() {
		var t = this, respond = t.I('respond'), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent');

		if ( ! cancel || ! respond )
			return;

		if ( parent ) parent.value = '0';
		cancel.onclick = null;
		$('#respond').css({ 'display' : 'none' , 'opacity' : 0, 'margin-top' : 20 });
		respond.className = 'js-res';
		return false;
	},

	viewForm : function() {
		var t = this, respond = t.I('respond'), cancel = t.I('cancel-comment-reply-link'), replytitle = t.I('reply-title');
		
		if ( ! respond || ! cancel || ! replytitle)
			return false;
		this.resetForm();
		replytitle.innerHTML = fastfood_comment_reply_l10n.replytopost + t.addCloseButton();
		replytitle.appendChild(cancel);
		$('#respond').css({'display' : 'block' , 'opacity' : 0 }).animate(
			{ 'opacity' : 1 , 'margin-top' : 0 },
			500
		);
		
		try { t.I('comment').focus(); }
		catch(e) {}

		return false;
	},

	addCloseButton : function() {
		var t = this, respond = t.I('respond'), replytitle = t.I('reply-title');

		if ( ! respond || ! replytitle )
			return '';

		return '<i id="ff_reply_close" class="el-icon-remove" title="' + fastfood_comment_reply_l10n.close + '" onclick="return fastfoodCustomReply.resetForm()" ></i>';

	},
	
	I : function(e) {
		return document.getElementById(e);
	}

};

$(document).ready(function($){ fastfoodCustomReply.init(); });

})(jQuery);