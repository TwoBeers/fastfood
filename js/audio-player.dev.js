var fastfoodAudioPlayer;

(function($) {

fastfoodAudioPlayer = function () {
	var instances = [];
	var activePlayerID;
	var playerURL = "";
	var defaultOptions = {};
	var currentVolume = -1;
	var requiredFlashVersion = "9";
	
	function getPlayer(playerID) {
		if (document.all && !window[playerID]) {
			for (var i = 0; i < document.forms.length; i++) {
				if (document.forms[i][playerID]) {
					return document.forms[i][playerID];
					break;
				}
			}
		}
		return document.all ? window[playerID] : document[playerID];
	}
	
	function addListener (playerID, type, func) {
		getPlayer(playerID).addListener(type, func);
	}
	
	return {
		setup: function (url, options) {
			playerURL = url;
			defaultOptions = options;
			if (swfobject.hasFlashPlayerVersion(requiredFlashVersion)) {
				swfobject.switchOffAutoHideShow();
				swfobject.createCSS(".swf-audio-player small", "display:none;");
			}
		},

		getPlayer: function (playerID) {
			return getPlayer(playerID);
		},
		
		addListener: function (playerID, type, func) {
			addListener(playerID, type, func);
		},
		
		embed: function (elementID, options) {
			var instanceOptions = {};
			var key;
			
			var flashParams = {};
			var flashVars = {};
			var flashAttributes = {};
	
			// Merge default options and instance options
			for (key in defaultOptions) {
				instanceOptions[key] = defaultOptions[key];
			}
			for (key in options) {
				instanceOptions[key] = options[key];
			}
			
			if (instanceOptions.transparentpagebg == "yes") {
				flashParams.bgcolor = "#FFFFFF";
				flashParams.wmode = "transparent";
			} else {
				if (instanceOptions.pagebg) {
					flashParams.bgcolor = "#" + instanceOptions.pagebg;
				}
				flashParams.wmode = "opaque";
			}
			
			flashParams.menu = "false";
			
			for (key in instanceOptions) {
				if (key == "pagebg" || key == "width" || key == "transparentpagebg") {
					continue;
				}
				flashVars[key] = instanceOptions[key];
			}
			
			flashAttributes.name = elementID;
			flashAttributes.style = "outline: none";
			
			flashVars.playerID = elementID;
			
			swfobject.embedSWF(playerURL, elementID, instanceOptions.width.toString(), "24", requiredFlashVersion, false, flashVars, flashParams, flashAttributes);
			
			instances.push(elementID);
		},
		
		syncVolumes: function (playerID, volume) {	
			currentVolume = volume;
			for (var i = 0; i < instances.length; i++) {
				if (instances[i] != playerID) {
					getPlayer(instances[i]).setVolume(currentVolume);
				}
			}
		},
		
		activate: function (playerID, info) {
			if (activePlayerID && activePlayerID != playerID) {
				getPlayer(activePlayerID).close();
			}

			activePlayerID = playerID;
		},
		
		load: function (playerID, soundFile, titles, artists) {
			getPlayer(playerID).load(soundFile, titles, artists);
		},
		
		close: function (playerID) {
			getPlayer(playerID).close();
			if (playerID == activePlayerID) {
				activePlayerID = null;
			}
		},
		
		open: function (playerID, index) {
			if (index == undefined) {
				index = 1;
			}
			getPlayer(playerID).open(index == undefined ? 0 : index-1);
		},
		
		getVolume: function (playerID) {
			return currentVolume;
		},
		
		start : function() {

			return $('audio.no-player').removeClass('no-player').each(function() {
				$this = $(this);
				var the_source = $this.children('source:first-child');
				if ( the_source.size() !== 0 ) {
					the_href = the_source.attr('src');
					var the_type = the_href.substr( the_href.length - 4, 4 )
					switch (the_type)
					{
					case '.ogg':
						if ( !document.createElement("audio").canPlayType ) {
							$this.parent().html('<span class="ff-player-notice">' + fastfoodAudioPlayer_l10n.unknown_media + ' (ogg)</span>');
						}
						break;
					case '.mp3':
						if ( !document.createElement("audio").canPlayType || (document.createElement("audio").canPlayType && !document.createElement("audio").canPlayType('audio/mpeg')) ) {
							fastfoodAudioPlayer.embed(this.id, {  
								soundFile: the_href
							});  
						}
						break;
					case '.m4a':
						if ( !document.createElement("audio").canPlayType || (document.createElement("audio").canPlayType && !document.createElement("audio").canPlayType('audio/x-m4a')) ) {
							$this.parent().html('<span class="ff-player-notice">' + fastfoodAudioPlayer_l10n.unknown_media + ' (m4a)</span>');
						}
						break;
					default:
						$this.parent().html('<span class="ff-player-notice">' + fastfoodAudioPlayer_l10n.unknown_media + '</span>');
					}				
				}
				
			});
			
		},
		
		//initialize
		init : function() {

			fastfoodAudioPlayer.setup( fastfoodAudioPlayer_l10n.player_path, {
				width: 300,
				loop: "yes",
				transparentpagebg: "yes",
				animation: "no",
				bg: "5C5959",
				leftbg: "5C5959",
				rightbg: "5C5959",
				rightbghover : "5C5959",
				righticon: "FFFFFF",
				lefticon: "FFFFFF",
				track: "5C5959",
				text: "FFFFFF",
				tracker: "828282",
				border: "828282"
			});
			$('body').on('post-load', function(event){ //Jetpack Infinite Scroll trigger
				fastfoodAudioPlayer.start();
			});
			fastfoodAudioPlayer.start();
		}
		
	}
	
}();


$(document).ready(function($){ fastfoodAudioPlayer.init(); });

})(jQuery);