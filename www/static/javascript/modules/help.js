var helpController = new function()
{
	this.political_viewsAction = function()
	{
		$('.answer').hover(
			function() {	$(this).addClass('box_content'); },
			function() {	$(this).removeClass('box_content'); }
		);

		$('.answer').click( function() {
			$(this).children('input').attr('checked', true);
			$(this).parent().children().removeClass('box_selected');
			$(this).addClass('box_selected');
		} );
	}

	this.bugAction = function()
	{
		var form = new Form(
			'bug_form',
			{
				validators:
				{
					'text': [validatorRequired]
				},
				success: function()
				{
					form.getForm().hide();
					$('.success').fadeIn();
				}
			}
		);
	}
};
