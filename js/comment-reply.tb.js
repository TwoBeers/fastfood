
addComment = {
	moveForm : function(commId, parentId, respondId, postId) {
		var t = this, div, comm = t.I(commId), respond = t.I(respondId), cancel = t.I('cancel-comment-reply-link'), parent = t.I('comment_parent'), post = t.I('comment_post_ID');

		if ( ! comm || ! respond || ! cancel || ! parent )
			return;

		t.respondId = respondId;
		postId = postId || false;

		if ( post && postId )
			post.value = postId;
		parent.value = parentId;
		respond.className = 'floating-respond js-res';
		cancel.style.display = '';

		cancel.onclick = function() {
			var t = addComment, respond = t.I(t.respondId);

			if ( ! respond )
				return;

			t.I('comment_parent').value = '0';
			this.style.display = 'none';
			this.onclick = null;
			respond.style.display = "none";
			return false;
		}

		try { t.I('comment').focus(); }
		catch(e) {}
		respond.style.display = "block";
		return false;
	},
	resetForm : function() {
		var t = addComment, respond = t.I('respond'), cancel = t.I('cancel-comment-reply-link');

		if ( ! cancel || ! respond )
			return;

		t.I('comment_parent').value = '0';
		cancel.style.display = 'none';
		cancel.onclick = null;
		respond.style.display = "none";
		return false;
	},
	I : function(e) {
		return document.getElementById(e);
	}
}
