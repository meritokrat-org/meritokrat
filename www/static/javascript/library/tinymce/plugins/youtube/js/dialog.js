tinyMCEPopup.requireLangPack();

var YouTubeDialog = {
	init : function() {
	},

	insert : function() {

		var url = document.getElementById('youtubeUrl').value;
		if ( !url )
		{
			return false;
		}

		var id = url.substr( url.indexOf('?v=') + 3 );

		if ( !id )
		{
			return false;
		}

		// Insert the contents from the input into the document
		var embedCode = '<object width="'+document.forms[0].youtubeWidth.value+'" height="'+document.forms[0].youtubeHeight.value+'"><param name="movie" value="http://www.youtube.com/v/'+id+'&rel=1"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/'+id+'&rel=1" type="application/x-shockwave-flash" wmode="transparent" width="'+document.forms[0].youtubeWidth.value+'" height="'+document.forms[0].youtubeHeight.value+'"></embed></object>';
		tinyMCEPopup.editor.execCommand('mceInsertRawHTML', false, embedCode);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(YouTubeDialog.init, YouTubeDialog);
