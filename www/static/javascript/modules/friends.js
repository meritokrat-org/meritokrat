var friendsController = new function()
{
	this.decline = function( userId )
	{
		$.post(
			'/friends/decline',
			{user_id: userId},
			function( response ) {
                if(response==1){
                    window.location.href='/friends';
                }
                $('#friend_' + userId).fadeOut( 150 );
            });
	};

	this.accept = function( userId )
	{
		$.post(
			'/friends/accept',
			{user_id: userId},
			function( response ) {
                if(response==1){
                    window.location.href='/friends';
                }
                $('#friend_' + userId).fadeOut( 150 );
            });
	};

	this.deleteFriend = function( id )
	{
		if ( confirm(this.l_are_you_sure) )
		{
			$('#friend_' + id).fadeOut( 150 );
			$.post( '/friends/delete', {id: id} );
		}
	}
};