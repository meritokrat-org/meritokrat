var Calendar = new function()
{
	this.monthdays = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

        this.checkdate = function(obj)
	{
		var $elements = $(obj).parent().children();
                var $dayfield = $elements.eq(0);
                var $monthfield = $elements.eq(1);
                var $yearfield = $elements.eq(2);

                if($monthfield.val()!=0){
                    var current = $dayfield.val();
                    var curlength = $dayfield.children().length;
                    var newlength = this.getdays($yearfield.val(),($monthfield.val()-1));

                    if(newlength!=curlength){
                        if(newlength<current){
                            current = newlength;
                        }
                        $dayfield.html('');
                        for(var i=1;i<=newlength;i++){
                            $dayfield.append('<option value="'+i+'">'+i+'</option>');
                        }
                        if(current!=0){
                            $dayfield.children().eq(current-1).attr('selected',true);
                        }
                    }
                }else{
                    if($dayfield.children().eq(0).attr('value')!=0){
                        $dayfield.prepend('<option value="0">&mdash;</option>');
                    }
                    $dayfield.children().eq(0).attr('selected',true);
                }
	};

        this.getdays = function(SomeYear, SomeMonth) {
                return ((SomeMonth == 1) && ((SomeYear % 400 == 0) || ((SomeYear % 4 == 0) && (SomeYear % 100 != 0)))) ? 29 : this.monthdays[SomeMonth];
        }
}