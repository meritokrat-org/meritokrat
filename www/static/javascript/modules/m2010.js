var m2010Controller = new function()
{
	this.indexAction = function()
	{
		$('.candidates > li').hover( function() {
			$(this).addClass('selected');
		}, function() {
			$(this).removeClass('selected');
		} );

		swfobject.embedSWF(
			'/chart.swf',
			'dynamics',
			600,
			300,
			'9.0.0',
			'expressInstall.swf',
			{'data-file': '/dynamics'},
			{wmode: 'transparent'}
		);
	};

	this.vote = function( id )
	{
		$('#choice_wait').show();

		$('.candidates span').hide();
		$('.candidates button').show();
		$('#candidate' + id + ' button').hide();
		$('#candidate' + id + ' span').fadeIn( 150 );

		$.post(
			'/vote', {id: id}, function ( r ) {
				$('#choice_wait').hide();
				$('#your_choice').html( r );
			}, 'raw'
		);
	};

	this.voteSignup = function( id )
	{
		Popup.show();
		
		$.post('/signup', {id: id}, function( response ) {
			Popup.setHtml(response);
			Popup.position();

			var form = new Form(
				'signup_form',
				{
					validators:
					{
						'email': [validatorRequired, validatorEmail],
						'password': [validatorRequired],
						'password_confirm' : function ( value ) { return ( value != form.get('password').val() ) }
					},
					success: function()
					{
						form.getForm().hide();
						$('.success').fadeIn();
					}
				}
			);

		}, 'raw');
	};

	this.forecast = function( form )
	{
		$('.error').hide(); $('.success').hide();

		$.post('/forecast', $(form).serialize(), function(r) {
			r.error ? $('.error').show() : $('.success').show();
		}, 'json');
	};
};