var searchController = new function()
{
    this.indexAction = function ()
    {
		$('#city').autocomplete({cache: false, minchars: 2, noresults: 'Неизвестный город', ajax_get: function( key, cont ) { $.post(
			'/profile/get_city',
			{key: key},
			function( r ) {
				var res = [];
				for( var i = 0; i < r.length; i ++ ) res.push({ id: r[i].id , value: r[i]['name_' + context.language] , info: r[i]['region_name_' + context.language]});
				cont(res);
			},
			'json');
		}, callback: function( data ) { $('#city_id').val(data.id); }});
    };
};
