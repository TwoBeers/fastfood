
addComment = {
	moveForm : function(commId, parentId, respondId, postId) {
		var t = this, div, comm = t.I(commId), respond = t.I(respondId), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('comment_post_ID'), replytitle = t.I('reply-title'), replytocomment = t.I('replytocomment');

		if ( ! comm || ! respond || ! cancel || ! parent || ! replytocomment || ! replytitle)
			return;

		t.respondId = respondId;
		postId = postId || false;

		if ( ! t.I('wp-temp-form-div') ) {
			div = document.createElement('div');
			div.id = 'wp-temp-form-div';
			div.style.display = 'none';
			respond.parentNode.insertBefore(div, respond);
		}

		comm.parentNode.insertBefore(respond, comm.nextSibling);
		if ( post && postId )
			post.value = postId;
		parent.value = parentId;
		
		replytitle.innerHTML = replytocomment.value;
		replytitle.appendChild(cancel);
		
		respond.className = 'floating-respond js-res';
		respond.style.display = "block";

		cancel.onclick = function() {
			var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I(t.respondId);

			if ( ! temp || ! respond )
				return;

			t.I('comment_parent').value = '0';
			temp.parentNode.insertBefore(respond, temp);
			temp.parentNode.removeChild(temp);
			this.onclick = null;
			respond.style.display = "none";
			respond.className = 'fixed-respond js-res';
			return false;
		}

		try { t.I('comment').focus(); }
		catch(e) {}

		return false;
	},
	
	resetForm : function() {
		var t = addComment, temp = t.I('wp-temp-form-div'), respond = t.I('respond'), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent');

		if ( ! cancel || ! respond )
			return;

		if ( parent ) parent.value = '0';
		if ( temp ) {
			temp.parentNode.insertBefore(respond, temp);
			temp.parentNode.removeChild(temp);
		}
		cancel.onclick = null;
		respond.style.display = "none";
		respond.className = 'fixed-respond js-res';
		return false;
	},

	viewForm : function() {
		var t = addComment, respond = t.I('respond'), cancel = t.I('cancel-comment-reply-link'), replytitle = t.I('reply-title'), replytopost = t.I('replytopost');
		
		if ( ! respond || ! cancel || ! replytitle)
			return false;
		addComment.resetForm();
		respond.className = 'fixed-respond js-res';
		if ( replytopost ) replytitle.innerHTML = replytopost.value;
		replytitle.appendChild(cancel);
		respond.style.display = "block";
		
		try { t.I('comment').focus(); }
		catch(e) {}

		return false;
	},
	
	addCloseButton : function() {
		var t = addComment, respond = t.I('respond'), replytitle = t.I('reply-title');

		if ( ! respond || ! replytitle )
			return;
		if ( ! t.I('ff_reply_close') ) {
			div = document.createElement('div');
			div.id = 'ff_reply_close';
			div.innerHTML = '<input type="button" title="Close" value="x" style="padding: 0 0 3px; position: absolute; top: 10px; right: 10px; width:19px;" onclick="return addComment.resetForm()" />';
			respond.insertBefore(div, replytitle);
		}
	},
	
	I : function(e) {
		return document.getElementById(e);
	}
}
