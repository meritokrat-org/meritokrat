var debatesController = new function()
{
	this.createAction = function ()
    {
		var form = new Form(
			'add_form',
			{
				validators:
				{
					text: [validatorRequired]
				},
				success: function()
				{
					$('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
					document.location = '/debates/mine';
				}
			}
		);
    };

	this.rateArgument = function( object, id, positive )
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
			'/debates/argument_rate',
			{id: id, positive: positive ? 1 : 0}
		);
	};

	var replyForm = null;

	this.viewAction = function ()
    {
		var form = new Form(
			'vote_form',
			{
				validators: { text: [validatorRequired] },
				success: function( response )
				{
					form.getForm().hide(150);

					if ( $('#agree_y').attr('checked') )
					{
						$('#votes_for').html( parseInt($('#votes_for').html()) + 1 );
					}
					else
					{
						$('#votes_against').html( parseInt($('#votes_against').html()) + 1 );
					}

					if ( form.get('text').val() ) $('#arguments').append( response );
				},
				format: 'raw'
			}
		);

		replyForm = new Form(
			'reply_form',
			{
				validators:
				{
					text: [validatorRequired]
				},
				success: function( response )
				{
					$('#child_arguments_' + replyForm.get('parent_id').val()).append( response );
					$('#reply_form').hide();
				},
				format: 'raw',
				wait_panel: 'reply_wait'
			}
		);
    };

	this.reply = function( object, id, agree )
	{
		replyForm.get('parent_id').val(id);
		replyForm.get('agree').val( agree ? 'y' : 'n' );
		replyForm.get('text').focus();
		replyForm.get('text').val('');

		$('.reply > a').removeClass('bold');
		$(object).addClass('bold');
		$('#reply_form').appendTo($(object).parent());
		$('#reply_form').show();
	};
};
