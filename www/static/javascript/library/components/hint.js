var hint = new function()
{
	this.close = function( id )
	{
		$('#' + id).fadeOut( 150 );

		$.post( '/profile/save_hint', { hint: id } );
	}
}