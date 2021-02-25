var pollsController = new function()
{
	this.createAction = function ()
        {
                var form = new Form(
                        'add_form',
                        {
                                validators:
                                {
                                        question: [validatorRequired, function(val) {
                                                var total = 0;
                                                $('.answer').each(function(){
                                                        if ( $.trim($(this).val()).length > 0 )
                                                        {
                                                                total++;
                                                        }
                                                });

                                                return ( total < 2 );
                                        }]
                                },
                                success: function()
                                {
                                        $('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
                                        document.location = '/polls/mine';
                                }
                        }
                );
        };

        this.editAction = function ()
        {
                var form = new Form(
                        'edit_form',
                        {
                                validators:
                                {
                                      //  question: [validatorRequired, function(val) {
                                       //         var total = 0;
                                       //        $('.answer').each(function(){
                                       //               if ( $.trim($(this).val()).length > 0 )
                                       //               {
                                       //                       total++;
                                       //               }
                                       //       });

//                                                return ( total < 2 );
  //                                      }]
                                },
                                success: function()
                                {
                                        $('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
                                        document.location = '/polls';
                                }
                        }
                );
        };

	this.vote = function( form )
	{
		$('#wait_' + $(form).attr('rel')).show();

		$.post(
			'/polls/vote',
			$(form).serialize(),
			function() {
				$('#wait_' + $(form).attr('rel')).hide();
				document.location.reload();
			}
		);
	};

	this.viewAction = function()
	{
		$('label').css({border: '1px solid #FFF'});

		$('label').hover(function(){
			$(this).parent().css({background: '#FAFAFA', border: '1px solid #EEE'});
		}, function() {
			$(this).parent().css({background: '#FFF', border: '1px solid #FFF'});
		});

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
			'/polls/comment_rate',
			{id: id, positive: positive ? 1 : 0}
		);
	};

	this.removeAnswer = function( object )
	{
		if ( $('#answers > li').length > 2 )
		{
			$(object).parent().remove();
		}
	};

	this.addAnswer = function ( object )
	{
		$('<li class="mb5">' + $(object).parent().html() + '</li>').insertAfter( $(object).parent() ).find('input').not(':button').val('');
	};
};