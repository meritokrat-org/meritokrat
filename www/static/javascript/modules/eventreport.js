var eventreportController = new function()
{
	$('.popup').click(function(){
                $('input').removeClass('clicked');
                $(this).next('input').addClass('clicked');
                Popup.show();
                Popup.setHtml($('#messageholder').html());
                Popup.position();
            });
};