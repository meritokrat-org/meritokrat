var feedController = new function()
{
	this.deleteItem = function( id )
	{
		$('#feed_item_' + id).fadeOut(150);
		$.post( '/feed/delete', {id: id} );
	};
};