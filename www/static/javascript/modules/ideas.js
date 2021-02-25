var ideasController = new function()
{
    	this.testAction = function ()
    {
		var form = new Form(
			'edit_form',
			{
				validators:
				{
					title: [validatorRequired],
					text: [validatorRequired]
				},
				success: function()
				{
					$('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
					document.location = '/idea'+form.get('id').val();
				}
			}
		);
    };
	this.createAction = function ()
    {
		var form = new Form(
			'add_form',
			{
				validators:
				{
					title: [validatorRequired],
					text: [validatorRequired]
				},
				success: function()
				{
					$('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
					document.location = '/ideas/mine';
				}
			}
		);

                var uploadForm = new Form(
			'upload_form',
			{
				validators:
				{
				},
				success: function( response )
				{
                                        if(response==0)alert('Файл занадто великий');
                                        else if(response==1)alert('Можна завантажувати лише малюнки');
                                        tinyMCE.execCommand('mceInsertContent',false,response);
				},
				format: 'raw'
			}
		);
    };

	this.rateIdea = function( id )
	{
		$.post(
			'/ideas/rate',
			{id: id},
			function() { $('#rate').html( parseInt($('#rate').html()) + 1 ) },
			'json'
		);

		$('#rate_pane > a').fadeOut(150);
	};

	this.viewAction = function ()
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

                var updateForm = new Form(
			'comment_update_form',
			{
				validators:
				{
					text: [validatorRequired]
				},
				success: function( response )
				{
                                        Application.doComUpd(updateForm.getForm(),response);
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

	this.rateComment = function( object, id, positive )
	{
		$(object).parent().fadeOut(150);

		var rateEl = $(object).parent().parent().children('span.bold');
		var newRate = parseInt(rateEl.html()) + (positive ? 1 : -1);

		if ( newRate > 0 )
		{
			newRate = '+' + newRate;
			rateEl.css({color: 'green'});
		}
		else
		{
			rateEl.css({color: 'red'});
		}

		rateEl.html( newRate );

		$.post(
			'/ideas/comment_rate',
			{id: id, positive: positive ? 1 : 0}
		);
	};

        this.editAction = function()
	{
		var uploadForm = new Form(
			'upload_form',
			{
				validators:
				{
				},
				success: function( response )
				{
                                        if(response==0)alert('Файл занадто великий');
                                        else if(response==1)alert('Можна завантажувати лише малюнки');
                                        tinyMCE.execCommand('mceInsertContent',false,response);
				},
				format: 'raw'
			}
		);
	};
};
