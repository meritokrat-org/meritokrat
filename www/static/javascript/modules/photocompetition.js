var photocompetitionController = new function()
{

	this.viewAction = function()
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


};