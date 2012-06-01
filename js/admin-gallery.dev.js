var galedAddImage;

jQuery(document).ready(function($) {
    galedAddImage = function ( the_id, the_src, the_link ) {
		$('#galed-sortable-list').append('<div id="item-' + the_id + '" class="item"><img alt="' + the_id + '" src="' + the_src + '" /><a class="item-remove" href="javascript:galedRemoveItem(\'#item-' + the_id + '\')"></a><a class="item-link" href="' + the_link + '" target="_blank" title=""></a></div>');
	}
	
    galedGenerateCode = function () {
	
		gal_code = '';
	
		var tmparr=new Array();
		$('#galed-sortable-list .item > img').each( function(index) {
			tmparr[index] = $(this).attr("alt");
		});
		ids_code = 'include="' + tmparr.join() + '" ';
		
		col_code = 'columns="' + $('#galed-columns').val() + '" ';
		siz_code = 'size="' + $('#galed-size').val() + '" ';
		lnk_code = 'link="' + $('#galed-link').val() + '" ';
		oby_code = 'orderby="' + $('#galed-orderby').val() + '" ';
		ord_code = 'order="' + $('#galed-order').val() + '" ';
		
		gal_code = '[gallery ' + ids_code + col_code + siz_code + lnk_code + oby_code + ord_code + ']';
		
		$('#galed-code').val( gal_code );
		$('#galed-code-note').css('display', 'block');

	}

    galedRemoveItem = function ( the_item ) {
		$(the_item).animate({
			opacity: 0
			}, 300, function() {
				$(this).remove();
			});
	}
	
	$( "#galed-sortable-list" ).sortable({
			placeholder: "galed_sortable_placeholder"
		});
	$( "#galed-sortable-list" ).disableSelection();
	
	$('#galed-code').click( function() {
		document.getElementById('galed-code').focus();
		document.getElementById('galed-code').select();
	});
	
	$( "#menu-appearance,#menu-appearance > a,#menu-media,#menu-media > a," ).toggleClass('wp-has-current-submenu wp-not-current-submenu wp-menu-open');

});