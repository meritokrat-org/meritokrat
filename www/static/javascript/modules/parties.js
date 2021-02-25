var partiesController = new function()
{
	this.indexAction = function()
	{
		swfobject.embedSWF(
			'/chart.swf',
			'direction_graph',
			240,
			240,
			'9.0.0',
			'expressInstall.swf',
			{'data-file': '/parties/directions'}
		);
	}

	this.newAction = this.indexAction;

	this.viewAction = function ()
	{
		$('.tab_pane > a').bind('click', function() {
			$('.tab_pane > a').removeClass('selected');
			$(this).addClass('selected');
			$('.content_pane').hide();
			$('#pane_' + $(this).attr('rel')).show();
			$(this).blur();
		});

		swfobject.embedSWF(
			'/chart.swf',
			'rate_history',
			450,
			200,
			'9.0.0',
			'expressInstall.swf',
			{'data-file': '/parties/rate_history?id=' + partiesController.paryId},
			{wmode: 'transparent'}
		);
	};

	this.browseDirection = function( v, a )
	{
		alert(a);
	}

	this.trust = function( id, trust )
	{
		$('#trust_wait').show();
		$.post(
			'/parties/trust',
			{id: id, trust: trust ? 1 : 0},
			function() {
				$('#trust_wait').hide();

				$('.custom_rate_selected').removeClass('custom_rate_selected');
				$('#' + ( trust ? 'trust' : 'not_trust' ) ).addClass('custom_rate_selected');

				if ( trust )
				{
					$('#trust_value').html( parseInt($('#trust_value').html()) + 1 );
				}
				else
				{
					$('#not_trust_value').html( parseInt($('#not_trust_value').html()) + 1 );
				}
			},
			'json'
		);
	};

	this.rateProgram = function( id, positive )
	{
		$('#program_rate_' + id + ' > a').fadeOut( 150 );

		$.post(
			'/parties/rate_program',
			{id: id, positive: positive ? 1 : 0}
		);

		var displayId = positive ? 'program_for_' : 'program_against_';
		$('#' + displayId + id).html( parseInt($('#' + displayId + id).html()) + 1 );
	};

	this.createAction = function ()
    {
		var form = new Form(
			'add_form',
			{
				validators:
				{
					title: [validatorRequired]
				},
				success: function( response )
				{
					$('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
					document.location = '/party' + response.id;
				}
			}
		);
    };

	this.editAction = function ()
    {
		var commonForm = new Form(
			'common_form',
			{
				validators:
				{
					title: [validatorRequired]
				},
				success: function()
				{
					$('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
				},
				wait_panel: 'common_wait'
			}
		);

		var programForm = new Form(
			'program_form',
			{
				validators: {},
				success: function()
				{
					$('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
				},
				wait_panel: 'program_wait'
			}
		);

		var newsForm = new Form(
			'news_form',
			{
				validators: {
					text: [validatorRequired]
				},
				success: function( response )
				{
					$('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
				},
				wait_panel: 'news_wait',
				format: 'raw'
			}
		);

		var photoForm = new Form(
			'photo_form',
			{
				success: function( uri )
				{
					$('.success').fadeIn( 250, function() { $('.success').fadeOut( 1500 ); } );
					$('#photo').attr('src', uri);
				},
				wait_panel: 'photo_wait'
			}
		);

		$('.tab_menu').click(function() {
			$('.tab_menu').removeClass('selected');
			$(this).addClass('selected');
			$(this).blur();
			$('.form').hide();
			$('#' + $(this).attr('rel') + '_form').show();
		});
    };

	this.switchDirection = function()
	{
		if ( $('#direction').val() == 5 )
		{
			$('#direction_custom').show();
		}
		else
		{
			$('#direction_custom').hide();
		}
	};

	this.newsAction = function()
	{
		this.newsForm = new Form(
			'edit_news_form',
			{
				validators: {
					text: [validatorRequired]
				},
				wait_panel: 'news_wait',
				success: function() {
					$('#news_body_' + $('#news_id').val() + ' > p').text( $('#text').val() );
					$('#news_body_' + $('#news_id').val() + ' > p').show();
					$('#edit_news_form').hide();
				}
			}
		);
	}

	this.deleteNews = function( id )
	{
		$.post(
			'/parties/delete_news',
			{id: id}
		);

		$('#news_body_' + id).fadeOut(150);
		$('#news_head_' + id).fadeOut(150);
	};

	this.editNews = function( id )
	{
		if ( $('#news_id').val() )
		{
			$('#news_body_' + $('#news_id').val() + ' > p').show();
			$('#edit_news_form').hide();
		}

		$('#news_body_' + id + ' > p').hide();
		$('#news_id').val(id);
		$('#text').val( $('#news_body_' + id + ' > p').text() );
		this.newsForm.getForm().appendTo( $('#news_body_' + id) );
		this.newsForm.getForm().show();
	}

	this.join = function( partyId, process )
	{
		if ( process )
		{
			document.location = '/parties/join?id=' + partyId + '&process=1';
		} else {
			Popup.show();
			$.get('/parties/join?id=' + partyId, function( response ) { Popup.setHtml(response); Popup.position(); }, 'plain');
		}
	};

	this.leave = function( partyId, process )
	{
		if ( process )
		{
			document.location = '/parties/leave?id=' + partyId + '&process=1';
		} else {
			Popup.show();
			$.get('/parties/leave?id=' + partyId, function( response ) { Popup.setHtml(response) }, 'text');
		}
	};

	this.talkAction = function ()
    {
		var form = new Form(
			'topic_form',
			{
				validators:
				{
					topic: [validatorRequired],
					text: [validatorRequired]
				},
				success: function( response )
				{
					document.location = '/parties/talk_topic?id=' + response.id;
				}
			}
		);
    };

	this.talk_topicAction = function ()
    {
		var form = new Form(
			'message_form',
			{
				validators: { text: [validatorRequired] },
				success: function( response )
				{
					document.location = '/parties/talk_topic?id=' + response.id + '&last=1';
				}
			}
		);
    };

	this.deleteTalkMessage = function( id )
	{
		if ( confirm(this.l_confirm) )
		{
			$('#talk_message' + id).fadeOut(150);
			$.post('/parties/talk_message_delete', {id: id});
		}
	};

	this.deleteTalkTopic = function( id )
	{
		if ( confirm(this.l_confirm) )
		{
			document.location = '/parties/talk_topic_delete?id=' + id;
		}
	};

	this.addModerator = function( id )
	{
		if ( !$('#new_moderator').val() )
		{
			return;
		}

		$.post(
			'/parties/add_moderator',
			{ id: $('#new_moderator').val(), party_id: partiesController.partyId },
			function( response ) {
				$('#no_moderators').hide();
				$('#moderators').append(response); $('#new_moderator').val('');
			}
		);
	}

	this.deleteModerator = function( object )
	{
		$(object).parent().fadeOut();

		$.post(
			'/parties/delete_moderator',
			{ id: $(object).attr('rel'), party_id: partiesController.partyId }
		);
	}

	this.addLeader = function( id )
	{
		if ( !$('#new_leader').val() )
		{
			return;
		}

		$.post(
			'/parties/add_leader',
			{ id: $('#new_leader').val(), party_id: partiesController.partyId },
			function( response ) {
				$('#no_leaders').hide();
				$('#leaders').append(response); $('#new_leader').val('');
			}
		);
	}

	this.deleteLeader = function( object )
	{
		$(object).parent().fadeOut();

		$.post(
			'/parties/delete_leader',
			{ id: $(object).attr('rel'), party_id: partiesController.partyId }
		);
	}

	this.changeOwner = function( id, object )
	{
		if ( confirm($(object).attr('rel')) )
		{
			$.post(
				'/parties/change_owner',
				{ id: id, party_id: partiesController.partyId },
				function( response ) {
					document.location = '/party' + partiesController.partyId;
				}
			);
		}
	}
};