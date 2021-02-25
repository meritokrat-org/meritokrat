var bookmarksController = new function()
{
	this.deleteItem = function( id )
	{
		$('#bookmark_item_' + id).fadeOut(150);
		$.post( '/bookmarks/delete', {id: id} );
	};
};