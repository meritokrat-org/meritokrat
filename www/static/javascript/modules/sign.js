var signController = new function()
{
    this.indexAction = function ()
    {
		Application.initSignInForm();

                $('.entercheckbox').click(function(){
                    $('div.echeckbox').toggleClass('chon').toggleClass('choff');
                })
    };

	this.upAction = function ()
    {
		var form = new Form(
			'signup_form',
			{
				validators:
				{
					'last_name': [validatorRequired],
					'first_name': [validatorRequired],
					'country': [validatorRequired],
					//'region': [validatorRequired],
					//'city': [validatorRequired],
					'email': [validatorRequired, validatorEmail]
				},
				success: function()
				{
					form.getForm().hide();
					$('.success').fadeIn();
				}
			}
		);

		form.get('last_name').focus();
    };

	this.passwordAction = function ()
    {
                var form = new Form(
                        'change_password_form',
                        {
                                validators:
                                {
                                        'password': [validatorRequired],
                                        'password_confirm' : function ( value ) { return ( value != form.get('password').val() ) }
                                },
                                success: function()
                                {
                                        $('.success').fadeIn( 150 );
                                },
                                error_position: 'top'
                        }
                );
    };

	
	this.recoverAction = function ()
    {
                var form = new Form(
                        'recover_form',
                        {
                                validators:
                                {
                                        'email': [validatorRequired, validatorEmail]
                                },
                                success: function()
                                {
                                        $('.success').fadeIn( 150 );
                                        $('form').find('input[type="text"]').val('');
                                },
                                error_position: 'top'
                        }
                );
    };
};
