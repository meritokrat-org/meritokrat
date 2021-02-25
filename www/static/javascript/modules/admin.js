var adminController = new function()
{
	this.indexAction = function()
	{
		swfobject.embedSWF(
			'/chart.swf',
			'user_stats',
			425,
			250,
			'9.0.0',
			'expressInstall.swf',
			{'data-file': '/admin/user_stats'}
		);
		swfobject.embedSWF(
			'/chart.swf',
			'user_activated_stats',
			425,
			250,
			'9.0.0',
			'expressInstall.swf',
			{'data-file': '/admin/user_activated_stats'}
		);
	};

        this.duplicateProfile = function( id, type, obj )
	{
		if(type==2){
                    if(confirm('Ви впевненi що хочете видалити цей профiль?')){
                        $.post('/admin/duplicate_user', {'id':id,'type':type}, function(){$(obj).parent().parent().remove()});
                    }
                }else{
                    if(confirm('Ви впевненi?')){
                        $.post('/admin/duplicate_user', {'id':id,'type':type}, function(){$(obj).parent().parent().remove()});
                    }
                }
	}

	this.maillistAdd = function( object )
	{
		var html = $(object).parent().parent().html();
		$(object).parent().parent().parent().append( '<tr class="maillist_item">' + html + '</tr>' );
	};

	this.maillistRemove = function( object )
	{
		if ( $('.maillist_item').length > 1 )
		{
			$(object).parent().parent().remove();
		}
	};

	this.maillistAction = function()
	{
		if ( $('#send_form').length > 0 )
		{
			var form = new Form(
				'send_form',
				{
					validators: {
						body: [validatorRequired], subject: [validatorRequired]
					},
					handle: false
				}
			);
		}
	};

	this.showMailFilter = function( filter )
	{
		$('.mfilter').hide();
		$('#' + filter + '_filter').show();
	};
        this.reSend = function ( id )
        {
            $.get('/admin/user_resend', { id: id}, function(data) {
              if(data!='ok'){
                  Popup.show();
                  Popup.setHtml(data);
                  Popup.position();
              }else{
                  $('#resend' + id).hide();
                  $('#status' + id).html(data);
              }
            });

        }

	this.chageMailMode = function( mode )
	{
		$('.mail_mode').removeClass('bold');
		$('#mode_' + mode).addClass('bold');
		$('.mode_pane').hide();
		$('#pane_' + mode).show();
		$('#mail_mode').val(mode);
	}

        this.meditAction = function ()
        {
                var uaForm = new Form(
			'ua_form',
			{
				validators: {
                                        name: [validatorRequired],
                                        title_ua: [validatorRequired],
					sender_mail: [validatorRequired],
					sender_name_ua: [validatorRequired],
					body_ua: [validatorRequired]
				},
				success: function() {
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
				},
				wait_panel: 'ua_wait'
			}
		);
                var ruForm = new Form(
			'ru_form',
			{
				validators: {
                                        name: [validatorRequired],
                                        title_ru: [validatorRequired],
					sender_mail: [validatorRequired],
					sender_name_ru: [validatorRequired],
					body_ru: [validatorRequired]
				},
				success: function() {
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
				},
				wait_panel: 'ru_wait'
			}
		);
        }

        this.infoeditAction = function ()
        {
                var uaForm = new Form(
			'ua_form',
			{
				validators: {
                                        title: [validatorRequired],
                                        name_ua: [validatorRequired]
				},
				success: function() {
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
				},
				wait_panel: 'ua_wait'
			}
		);
                var ruForm = new Form(
			'ru_form',
			{
				validators: {
                                        title: [validatorRequired],
                                        name_ru: [validatorRequired]
				},
				success: function() {
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
				},
				wait_panel: 'ru_wait'
			}
		);
        }

        this.helpeditAction = function ()
        {
                var uaForm = new Form(
			'ua_form',
			{
				validators: {
                                        alias: [validatorRequired],
                                        title_ua: [validatorRequired]
				},
				success: function( response ) {
                                        if(response.id)
                                            document.location = '/admin/help';
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
				},
				wait_panel: 'ua_wait'
			}
		);
                var ruForm = new Form(
			'ru_form',
			{
				validators: {
                                        alias: [validatorRequired],
                                        title_ru: [validatorRequired]
				},
				success: function( response ) {
                                        if(response.id)
                                            document.location = '/admin/help';
					$('.success').fadeIn( 250, function() {$('.success').fadeOut( 1500 );} );
				},
				wait_panel: 'ru_wait'
			}
		);
        }

        $('.tab_menu').click(function() {
                $('.tab_menu').removeClass('selected');
                $(this).addClass('selected');
                $(this).blur();
                $('.form').hide();
                $('#' + $(this).attr('rel') + '_form').show();
        });

        $('#tab_' + this.defaultTab).click();
};