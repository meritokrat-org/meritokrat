var Popup = new function()
{
	this.calculateY = function()
	{
		var clientHeight =
		document.documentElement.clientHeight > window.innerHeight
		? window.innerHeight
		: document.documentElement.clientHeight;

                //alert('scrolltop:'+document.documentElement.scrollTop+' clientHeight:'+clientHeight);

		//return parseInt(document.documentElement.scrollTop + (clientHeight - $('#popup_box').height())/2);
                return parseInt($(window).scrollTop() + (clientHeight - $('#popup_box').height())/2);
	};

	this.calculateX = function()
	{
		return parseInt((document.body.clientWidth - $('#popup_box').width())/2);
	};

	this.show = function()
	{
		if ( !$('#popup_box').length )
		{
			$('body').append(
				'<div id="popup_box" class="popup_box"><div class="frame">' +
				'</div></div>'
			);
		}

		this.setHtml();
		this.position();

		$('#popup_box').fadeIn( 150 );
	};


	this.showinfo = function(alias)
	{
                
                if ( !$('#showinfobox').length )
		{
			var data;
                        $('body').append('<div id="showinfobox_opacity" style="height:100%" class="hidden" class="popup_box"></div><div id="showinfobox" class="hidden" ></div>');
		}

	        this.setHtmlinfo();   
		this.info_position(); 
                //var h = $('#showinfobox').height();
                //var w = $('#showinfobox').width();
                //var l = $('#showinfobox').css("left");
                //var t = $('#showinfobox').css("top");
                //$('#showinfobox').height(1);
                //$('#showinfobox').width(1);
                //$('#showinfobox').animate({height:'+='+h+'px'});
                //$('#showinfobox').animate({width:'+='+w+'px'});
                //$('#showinfobox').animate({width:w,height:h,left:l,top:t}, 500);
                $('#showinfobox_opacity').height($(document).height());
                $('#showinfobox_opacity').fadeIn(150);
                $('#showinfobox').fadeIn(150);
	};

        this.info_position = function()
	{
		$('#showinfobox').css({
			top: this.calculateY() - 300 + 'px',
			left: this.calculateX() -30 + 'px'
		});
	};

	this.close = function( fadeTime )
	{
		if ( !fadeTime )
		{
			fadeTime = 150;
		}

		$('#popup_box').fadeOut( fadeTime );
	};

        this.closeinfo = function( fadeTime )
	{
		if ( !fadeTime )
		{
			fadeTime = 150;
		}

		$('#showinfobox_opacity').fadeOut( fadeTime );
                $('#showinfobox').fadeOut( fadeTime );
	};

	this.setHtml = function( html )
	{
		if ( !html )
		{ 
			html = '<img src="http://s1.' + context.host + '/common/loading.gif" class="m10" />';
		}
		
		$('#popup_box > div').html( html );
	}
        
        this.setHtmlinfo = function( html )
	{
		if ( !html )
		{ 
			html = '<div style="margin-top: 70px;" class="acenter"><img src="http://s1.' + context.host + '/common/loading.gif" class="m10" /></div>';
		}
		
		$('#showinfobox').html( html );
	}

	this.position = function()
	{
		$('#popup_box').css({
			top: this.calculateY() + 'px',
			left: this.calculateX() + 'px'
		});
	};
}