$(document).ajaxError( function( event, request, ajaxOptions )
{
    var text = '<span style="color: red;">Ajax Error:</span>|||' + 'Response:|||' + request.responseText;

    writeAjaxDebug( text );
}
);

$(document).ajaxSuccess( function( event, request, ajaxOptions )
{
    writeAjaxDebug( request.responseText );
}
);

function writeAjaxDebug( text )
{
    $('#ajax_debug').show();

	text = text.replace(/<\/td><td>/g, ' ');
	text = text.replace(/<\/td>/g, '|||');
	text = text.replace(/<\/h3>/g, '||||||');
	text = $('<div>' + text + '</div>').text();
    text = text.replace(/\|\|\|/g, '<br/>');
    
    $('#ajax_debug').html('<b>AJAX response:</b><br />' + text);
};

function varDump( variable )
{
	var s = '';

	for ( var i in variable )
	{
			s += i + ': ' + variable[i] + '\n';
	}

	alert(s);
};

$(document).ready( function() {
	$('#web_debug').appendTo( $('body') );
	$('#web_debug').css({position: 'relative', top: '-40px', left: '0px', width: '1000px', margin: 'auto'});
} );