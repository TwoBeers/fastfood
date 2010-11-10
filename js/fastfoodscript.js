window.addEvent("domready",function(){
	$moo("mainmenu").getChildren("li").each(function(c){ //main menu dropdown animation
		var e=50;
		var b=e+"px";
		var d=c.getElement("ul");
		if(d){
			c.getElement("a").appendText(" »");
			var a=new Fx.Morph(d,{duration:200,wait:false,transition:Fx.Transitions.Back.easeOut});
			d.setStyle("opacity","0");
			d.setStyle("margin-top",b);
			c.addEvents({
				mouseenter:function(){
					if(d.getStyle("margin-top").toInt()>19){
						d.setStyle("margin-top",b);
						a.cancel();
						a.start({
							"margin-top":0,opacity:1
						})
					}},
				mouseleave:function(){
					a.cancel();
					a.start({
						"margin-top":20
					});
					d.setStyle("opacity","0")
				}
			});
			d.setStyle("display","block")
		}
	});
	$moo("quickbar").getElements("div.menuitem").each(function(d){ //quickbar animation
		var e=d.getElement("div.menuback");
		var c=d.getElement("div.itemimg");
		if(e){
			var a=new Fx.Morph(e,{duration:500,wait:false,transition:Fx.Transitions.Back.easeOut});
			var b=new Fx.Morph(c,{duration:300,wait:false});
			c.className="itemimg_js";
			e.className="menuback_js";
			d.addEvents({
				mouseenter:function(){
					a.cancel();
					e.setStyle("display","block");
					a.start({height:[0,250],opacity:1});
					b.cancel();
					b.start({width:65})
				},
				mouseleave:function(){
					a.cancel();
					e.setStyle("display","none");
					e.setStyle("height",0);
					e.setStyle("opacity",0);
					b.cancel();
					b.start({width:50})
				}
			})
		}
	});
	$moo("content").getElements("div.metafield").each(function(c){ //meta animation
		var d=c.getElement("div.metafield_content");
		var b=c.getParent();
		if(d){
			d.className="metafield_content_js";
			d.slide("hide");
			var a=new Fx.Slide(d,{duration:300,wait:false,transition:Fx.Transitions.Back.easeOut});
			c.addEvents({
				mouseenter:function(){
					a.cancel();
					a.slideIn()
				},
				mouseleave:function(){
					a.cancel();
					d.slide("hide")
				}
			})
		}
	});
	$moo('navbuttons').getChildren('div.minibutton').each( function( elem ){ //navbuttons tooltip animation
		var gap = 60;
		var gap_string = gap + 'px';
		var list = elem.getElement('span.nb_tooltip');	
		if (list) {
			var myFx = new Fx.Morph(list, {duration:200, wait:false, transition: Fx.Transitions.Back.easeOut});
			list.setStyle('opacity', '0');
			list.setStyle('right', gap_string);
			elem.addEvents({
				'mouseenter' : function(){ 					
					list.setStyle('right', gap_string);
					myFx.cancel();
					myFx.start({
						'right': 23,
						'opacity': 1
					});
				},
				'mouseleave' : function(){ 
					myFx.cancel();
					list.setStyle('right', gap_string);
					list.setStyle('opacity', '0');
				}
			});	
		};
	});

});