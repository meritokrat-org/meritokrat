var ppoController = new function()
{
	this.viewAction = function()
	{
		$('.tab_pane > a').bind('click', function() {
			$('.tab_pane > a').removeClass('selected');
			$(this).addClass('selected');
			$('.content_pane').hide();
			$('#pane_' + $(this).attr('rel')).show();
			$(this).blur();
		});
	}


	this.createAction = function ()
    {
		var form = new Form(
			'add_form',
			{
				validators:
				{
					title: [validatorRequired],
                                        adres:[validatorRequired],
                                        dzbori:[validatorRequired],
                                        'glava' : function() {
						if (form.get('category').val() == 1 ){
                                                    if(form.get('glavaid').val()>0)
                                                        return false;
						return true;
                                                }
						return false;
					},
                                        'secretar' : function() {
						if (form.get('category').val() == 1 ){
                                                    if(form.get('secretarid').val()>0)
                                                        return false;
						return true;
                                                }
						return false;
					}
				},
				success: function( response )
				{
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
					if(response.success>0)document.location = '/ppo/create?success=' + response.id;
                                        else document.location = '/ppo/edit?id='+ response.id + '&tab=photo';
				},
				wait_panel: 'wait_panel'
			}
		);
    };
    
    this.showTerritory = function(nocontrols,ppo_id)
        {
            
                Popup.show();
		$.post(
			'/ppo/show_territory',
			{nocontrols:nocontrols,ppo_id:ppo_id},
			function( response ) {Popup.setHtml(response);Popup.position();initialize2();}
            );
             
        }

	this.rateMessage = function( object, id, positive )
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
			'/ppo/message_rate',
			{id: id, positive: positive ? 1 : 0}
		);
	};

	this.ratePositionMessage = function( object, id, positive )
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
			'/ppo/position_rate',
			{id: id, positive: positive ? 1 : 0}
		);
	};

	this.rateProposalMessage = function( object, id, positive )
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
			'/ppo/proposal_rate',
			{id: id, positive: positive ? 1 : 0}
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
				success: function(response)
				{ 
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
                                        document.location = '/ppo/edit?id='+ response.id;
				},
				wait_panel: 'common_wait'
			}
		);
                    
                var moreForm = new Form(
			'more_form',
			{
				validators:
				{
				},
				success: function(response)
				{ 
                                    if(!response.error) {
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
                                    }
                                    _url = window.location+'&tab=more';
                                    window.location = _url;
				},
				wait_panel: 'more_wait'
			}
		);

                var leadershipForm = new Form(
			'leadership_form',
			{
				validators:
				{
				},
				success: function(response)
				{
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
                                        document.location = '/ppo/edit?id=' + response.id+'&tab=leadership';
				},
				wait_panel: 'leadership_wait'
			}
		);
                    
		var photoForm = new Form(
			'photo_form',
			{
				success: function( uri )
				{
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
					$('#photo').attr('src', uri);
				},
				wait_panel: 'photo_wait'
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
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
				},
				wait_panel: 'news_wait',
				format: 'raw'
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

	this.join = function( groupId )
	{
		$.post(
			'/ppo/join',
			{id: groupId},
			function () {$('#menu_join').hide();$('#menu_leave').fadeIn(150);},
			'json'
		);
	};

	this.apply = function( groupId )
	{
		$('#menu_leave').remove();
		this.join(groupId);
		$('#menu_apply').hide();
		$('#text_apply').fadeIn(150);
	}

	this.leave = function( groupId )
	{
            var answer=confirm("Ви впевнені?");
            if (answer==true) {
		$.post(
			'/ppo/leave',
			{id: groupId},
			function () {$('#menu_leave').hide();$('#menu_join').fadeIn(150);},
			'json'
		);
                    }
	};

	//this.talkAction = function ()
    //{
	//	var form = new Form(
	//		'topic_form',
	//		{
	//			validators:
	///			{
	//				topic: [validatorRequired]
	//			},
	//			success: function( response )
	//			{
	///				document.location = '/ppo/talk_topic?id=' + response.id;
	//			}
	//		}
	//	);
    //};
	this.photoAction = function ()
    {
		var form = new Form(
			'photoalbum_form',
			{
				validators:
				{
					title: [validatorRequired]
				}
			}
		);
    };

	this.positionAction = function ()
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
					document.location = '/ppo/position_topic?id=' + response.id;
				}
			}
		);
    };

	this.position_topicAction = function ()
    {
		var form = new Form(
			'message_form',
			{
				validators: {text: [validatorRequired]},
				success: function( response )
				{
					document.location = '/ppo/position_topic?id=' + response.id + '&last=1';
				}
			}
		);
    };

	this.deletePositionMessage = function( id )
	{
		if ( confirm(this.l_confirm) )
		{
			$('#talk_message' + id).fadeOut(150);
			$.post('/ppo/position_message_delete', {id: id});
		}
	};

	this.deletePositionTopic = function( id )
	{
		if ( confirm(this.l_confirm) )
		{
			document.location = '/ppo/position_topic_delete?id=' + id;
		}
	};

//
	this.proposalAction = function ()
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
					document.location = '/ppo/proposal_topic?id=' + response.id;
				}
			}
		);
    };

	this.proposal_topicAction = function ()
    {
		var form = new Form(
			'message_form',
			{
				validators: {text: [validatorRequired]},
				success: function( response )
				{
					document.location = '/ppo/proposal_topic?id=' + response.id + '&last=1';
				}
			}
		);
    };

	this.deleteProposalMessage = function( id )
	{
		if ( confirm(this.l_confirm) )
		{
			$('#talk_message' + id).fadeOut(150);
			$.post('/ppo/proposal_message_delete', {id: id});
		}
	};

	this.deleteProposalTopic = function( id )
	{
		if ( confirm(this.l_confirm) )
		{
			document.location = '/ppo/proposal_topic_delete?id=' + id;
		}
	};
//

	this.talk_topicAction = function ()
    {
		var form = new Form(
			'message_form',
			{
				validators: {text: [validatorRequired]},
				success: function( response )
				{
					document.location = '/ppo/talk_topic?id=' + response.id + '&last=1';
				}
			}
		);
    };

	this.deleteTalkMessage = function( id )
	{
		if ( confirm(this.l_confirm) )
		{
			$('#talk_message' + id).fadeOut(150);
			$.post('/ppo/talk_message_delete', {id: id});
		}
	};

	this.deleteTalkTopic = function( id )
	{
		if ( confirm(this.l_confirm) )
		{
			document.location = '/ppo/talk_topic_delete?id=' + id;
		}
	};

	this.addModerator = function( id )
	{
		if ( !$('#new_moderator').val() )
		{
			return;
		}

		$.post(
			'/ppo/add_moderator',
			{id: $('#new_moderator').val(), group_id: ppoController.groupId},
			function( response ) {
				$('#no_moderators').hide();
				$('#moderators').append(response);$('#new_moderator').val('');
			}
		);
	}

	this.deleteModerator = function( object )
	{
		$(object).parent().fadeOut();

		$.post(
			'/ppo/delete_moderator',
			{id: $(object).attr('rel'), group_id: ppoController.groupId}
		);
	}

	this.changeOwner = function( id, object )
	{
		if ( confirm($(object).attr('rel')) )
		{
			$.post(
				'/ppo/change_owner',
				{id: id, group_id: ppoController.groupId},
				function( response ) {
					document.location = '/group' + ppoController.groupId;
				}
			);
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
                    
	};
	this.add_newsAction = function()
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
        this.edit_newsAction = function()
	{
                 $('.tab_menu').click(function() {
			$('.tab_menu').removeClass('selected');
			$(this).addClass('selected');
			$(this).blur();
			$('.form').hide();
			$('#' + $(this).attr('rel') + '_form').show();
                        if($(this).attr('rel')=='edit')$('#upload_form').show();
		});
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
        
	this.deleteNews = function( id, redirect )
	{           
		$.post(
			'/ppo/delete_news',
			{id: id}
		);
                if(redirect!='')document.location = redirect;
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
		$('#title').val( $('#news_title_' + id + ' > p').text() );
		$('#text').val( $('#news_body_' + id + ' > p').text() );
		this.newsForm.getForm().appendTo( $('#news_body_' + id) );
		this.newsForm.getForm().show();
	};

	this.applicantApprove = function( id, object )
	{
		$('#new_applicants').html( parseInt($('#new_applicants').text()) - 1 );
		$(object).parent().fadeOut( 150 );

		$.post(
			'/ppo/approve_applicant',
			{id: id, group_id: ppoController.groupId}
		);
	};

	this.applicantCancel = function( id, object )
	{
		$('#new_applicants').html( parseInt($('#new_applicants').text()) - 1 );
		$(object).parent().fadeOut( 150 );

		$.post(
			'/ppo/cancel_applicant',
			{id: id, group_id: ppoController.groupId}
		);
	};

	this.photo_addAction = function()
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

	this.photo_viewAction = function()
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
                                        if($('input[name="neg_msg"]').val()==1) {
                                            var voteDisplay = $('#vote_value').children('.red' );
                                            voteDisplay.html( parseInt(voteDisplay.text()) + 1 );
                                        }
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

        this.talkAction = function ()
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

        this.talk_message_editAction = function ()
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
	this.post_editAction = function ()
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

	this.vote = function( positive )
	{
		$('#vote_pane').fadeOut(100);
                if(positive) {
                    $.post(
                            '/blogs/vote',
                            {id: ppoController.postId, positive: positive ? 1 : 0},
                            function () {
                                    var voteDisplay = $('#vote_value').children( positive ? '.green' : '.red' );
                                    voteDisplay.html( parseInt(voteDisplay.text()) + 1 );
                            }
                    );
               }
               else
               {
                   $('input[name="neg_msg"]').val(1);
                   $('#cancel_v').show('slow');
                   $('.form_bg:first').css('background-color','#ffeeee');
                   $('.column_head_small:first').css('background','url("/static/images/common/down.gif") no-repeat 600px 5px scroll');
                   $('.column_head_small:first').css('line-height','33px');
               }
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
			'/blogs/comment_rate',
			{id: id, positive: positive ? 1 : 0}
		);
	};

	this.postAction = function ()
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
};
$(function(){
    $('#cancel_v').click(function(){
        $('input[name="neg_msg"]').val(0);
        $('.form_bg:first').css('background-color','#F7F7F7');
        $('.column_head_small:first').css('background','url("/static/images/common/box_head_tiny.gif") no-repeat 0 0 scroll');
        $('.column_head_small:first').css('line-height','20px');
        $('#vote_pane').fadeIn(100);
        $(this).hide();
    });
});