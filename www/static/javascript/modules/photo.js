var photoController = new function()
{
	this.indexAction = function ()
        {
                    var form = new Form(
                            'photoalbum_form',
                            {
                                    validators:
                                    {
                                            title: [validatorRequired]
                                    },
                                    success: function( response )
                                    {
                                            $('#photoalbum_form').hide();
                                            $('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
                                    },
                                    format: 'raw'
                            }
                    );
        };

	this.addAction = function()
	{
		$('#album_id').bind('change', function() {
			if ( $(this).val() == '-1' )
			{
				$('#album_name_pane').show();
			}
			else
			{
				$('#album_name_pane').hide();
				$('#album_name').val('');
			}
		});
	};

	this.viewAction = function()
	{
		var form = new Form(
			'comment_form',
			{
				validators:
				{
					text: [validatorRequired]
				},
				success: function( response )
				{
					$('#no_comments').hide();
					$('#comments').append( response );
					form.getForm().hide( 150 );
				},
				format: 'raw'
			}
		);

		var replyForm = new Form(
			'comment_reply_form',
			{
				validators:
				{
					text: [validatorRequired]
				},
				success: function( response )
				{
					$('#no_comments').hide();
					$('#child_comments_' + replyForm.get('parent_id').val()).append( response );
					replyForm.getForm().hide();
				},
				format: 'raw'
			}
		);

		$('.comment_reply').bind('click', function(){
			replyForm.get('text').val('');
			$('#comment_reply_form').appendTo($(this).parent());
			$('#comment_reply_form').show();
			replyForm.get('parent_id').val( $(this).attr('rel') );
			replyForm.get('text').focus();
		});
        };

        this.save = function(id,album_id)
        {
            $wait = $('#photo_'+id).find('#wait_panel');
            $success = $('#photo_'+id).find('.success');
            $wait.show();
            $.post('/photo/edit_photo',{'photo_id':id,'album_id':album_id,'photo_title':$('#photo_'+id).find('.album_title').val()},function(){
                $wait.hide();
                $success.fadeIn( 250, function() {$success.fadeOut( 1500 );} );
            });
        }

        this.del = function(id,album_id)
        {
            if(confirm('Ви впевненi?')){
                $('#photo_'+id).remove();
                $.post('/photo/delete',{'photo_id':id,'album_id':album_id});
            }else{
                return false;
            }
        }

};