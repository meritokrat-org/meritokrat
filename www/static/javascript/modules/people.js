var peopleController = new function()
{
	this.indexAction = function()
	{
         
		swfobject.embedSWF(
			'/chart.swf',
			'direction_graph',
			240,
			240,
			'9.0.0',
			'expressInstall.swf',
			{'data-file': '/people/political_views'}
		);
	};

	this.chartSelectView = function( id )
	{
		alert( 'Вы выбрали: ' + id);
	}

    function showandhide(id) {
		if($_(id).style.display == 'none') {
			$('#'+id).show(300);
		}else {
			$('#'+id).hide(300);
		}
	}
};

$( "#sortable" ).sortable();
$( "#sortable" ).disableSelection();