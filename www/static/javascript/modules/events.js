var eventsController = new function()
{
	this.createAction = function ()
    {
                var form = new Form(
			'add_form',
			{
				validators:
				{
					name: [validatorRequired],
					description: [validatorRequired],
                                        start: [validatorRequired],
                                        end: [validatorRequired],
                                        adress: [validatorRequired]
				},
				success: function( response )
				{
					document.location = '/events/edit?id='+response.id+'&tab=photo';
				},
				wait_panel: 'wait_panel'
			});
                $('#cat').bind('change', function() { 
                if($(this).val()==2){
                        $('#catname').show();
                        form = new Form(
			'add_form',
			{
				validators:
				{
					name: [validatorRequired],
                                        catname: [validatorRequired],
					description: [validatorRequired],
                                        start: [validatorRequired],
                                        end: [validatorRequired],
                                        adress: [validatorRequired]
 
				},
				success: function( response )
				{
					document.location = '/events/edit?id='+response.id+'&tab=photo';
				},
				wait_panel: 'wait_panel'
			}
		);}else
                    {
                  $('#catname').hide();
                  form = new Form(
			'add_form',
			{
				validators:
				{
					name: [validatorRequired],
					description: [validatorRequired],
                                        start: [validatorRequired],
                                        end: [validatorRequired],
                                        adress: [validatorRequired]
				},
				success: function( response )
				{
					document.location = '/events/edit?id='+response.id+'&tab=photo';
				},
				wait_panel: 'wait_panel'
			}
		);
                    }
		});
		
		var sf2height = 0; 
		
		$('#section').change(function(){
			if($(this).val() == 9){
				if(sf2height == 0)
					sf2height = $('#section-format-2').height();
				$('#section-format-2')
					.height('0px')
					.css('opacity', '0')
					.animate(
						{
							'height':sf2height+'px',
							'opacity':'1'
						},
						256
					);
				$('#section-format').css('opacity', '0')
					.show()
					.animate(
						{
							'opacity':'1'
						},
						256
					);
			} else {
				if(sf2height == 0)
					sf2height = $('#section-format-2').height();
				$('#section-format-2').animate(
					{
						'height':'0px'
					},
					256
				);
				$('#section-format').css('opacity', '1')
					.animate(
						{
							'opacity':'0'
						},
						256,
						function(){
							$(this).hide();
						}
					);
			}
		});
		
		$('#section').change();

    };

    this.editAction = function ()
    {

        		$('.tab_menu').click(function() {
			$('.tab_menu').removeClass('selected');
			$(this).addClass('selected');
			$(this).blur();
			$('.form').hide();
			$('#' + $(this).attr('rel') + '_form').show();
		});

		
                
                var form = new Form(
			'edit_form',
			{
				validators:
				{
					name: [validatorRequired],
					description: [validatorRequired],
                                        start: [validatorRequired],
                                        end: [validatorRequired],
                                        adress: [validatorRequired]
				},
				success: function( response )
				{
					document.location = '/event' + response.id;
				},
				wait_panel: 'wait_panel'
			});
                $('#cat').bind('change', function() {
                if($(this).val()==2){
                        $('#catname').show();
                        form = new Form(
			'edit_form',
			{
				validators:
				{
					name: [validatorRequired],
                                        catname: [validatorRequired],
					description: [validatorRequired],
                                        start: [validatorRequired],
                                        end: [validatorRequired],
                                        adress: [validatorRequired]

				},
				success: function( response )
				{
					document.location = '/event' + response.id;
				},
				wait_panel: 'wait_panel'
			}
		);}else
                    {
                  $('#catname').hide();
                  form = new Form(
			'edit_form',
			{
				validators:
				{
					name: [validatorRequired],
					description: [validatorRequired],
                                        start: [validatorRequired],
                                        end: [validatorRequired],
                                        adress: [validatorRequired]
				},
				success: function( response )
				{
					 document.location = '/event' + response.id;
				},
				wait_panel: 'wait_panel'
			}
		);
                    }
		});
		
		var sf2height = 0; 
		
		$('#section').change(function(){
			if($(this).val() == 9){
				if(sf2height == 0)
					sf2height = $('#section-format-2').height();
				$('#section-format-2')
					.height('0px')
					.css('opacity', '0')
					.animate(
						{
							'height':sf2height+'px',
							'opacity':'1'
						},
						256
					);
				$('#section-format').css('opacity', '0')
					.show()
					.animate(
						{
							'opacity':'1'
						},
						256
					);
			} else {
				if(sf2height == 0)
					sf2height = $('#section-format-2').height();
				$('#section-format-2').animate(
					{
						'height':'0px'
					},
					256
				);
				$('#section-format').css('opacity', '1')
					.animate(
						{
							'opacity':'0'
						},
						256,
						function(){
							$(this).hide();
						}
					);
			}
		});
		
		$('#section').change();

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

		$('.comment_reply').bind('click', function(){
			replyForm.get('text').val('');
			$('#comment_reply_form').appendTo($(this).parent());
			$('#comment_reply_form').show();
			replyForm.get('parent_id').val( $(this).attr('rel') );
			replyForm.get('text').focus();
		});
    };



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
};