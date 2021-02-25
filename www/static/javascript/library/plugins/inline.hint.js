(function($) {
$.fn.inlineHint = function(text, options) {
	options = $.extend({}, inlineHintHandle.defaults, options);

	if ( typeof text != 'undefined' )
	{
		options.text = text;
	}

	this.each(function() { inlineHintHandle(this,options); });
	
	return this;
}

var inlineHintHandle = function(element, options) {
	if ( typeof options.text == 'undefined' )
	{
		options.text = $(element).attr('rel');
	}

	$(element).bind('focus', function() {
		if ( $(this).val() == options.text )
		{
			$(this).val('');
		}

		$(this).removeClass('inline_hint');
	});

	$(element).bind('blur', function() {
		if ( $(this).val() == '' )
		{
			$(this).val(options.text);
			$(this).addClass('inline_hint');
		}
	});

	$(element).blur();
};

inlineHintHandle.defaults = {};

})(jQuery);