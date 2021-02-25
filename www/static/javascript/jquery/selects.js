jQuery(document).ready(function () {

	var region = $("#region").val();
	var city = $("#city").val();
	var region_txt = $("#region_txt").val();
	var city_txt = $("#city_txt").val();
	var prefix = "";

	changeRegions($("#country"), region, region_txt);
	changeCities($("#region"), city, city_txt);

	$('#country').live("change", function () {
		changeRegions(this, 0, "");
	});

	$('select#region').live("change", function () {
		if(region != $("#region").val())
			city_txt = "";

		changeCities(this, 0, city_txt);
	});

	function changeRegions(e, region, region_txt){
		var country_id = $(e).val();
		if (country_id < 1) {
			$('#region').html('<option>&mdash;</option>');
			$('#region').attr('disabled', true);

			$('#city').html('<option>&mdash;</option>');
			$('#city').attr('disabled', true);

			$('#region_txt').val("").hide();
			$('#city_txt').val("").hide();
			return(false);
		}
		$('#region').attr('disabled', true);
		$('#region').html('<option>завантаження...</option>');

		var url = 'https://'+window.location.host+'/profile/get_select';

		$.post(	url, {
				"country_id" : country_id
			}, function (result) {
				if (result.type == 'error'){
					$('#region').html('<option>&mdash;</option>').hide();
					$('#city').html('<option>&mdash;</option>').hide();
					$('#region_txt').show();
					$('#city_txt').show();
				}
				else {
					var options = '<option value="0">- оберіть регіон -</option>';

					$('#city').html('<option>&mdash;</option>').show().attr('disabled', true);
					$('#region_txt').val("").hide();
					$('#city_txt').val("").hide();

					$(result.regions).each(function() {
						options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
					});

					$('#region').html(options);
					$('#region').attr('disabled', false);
					$('#region').show();

					if(region > 0){
						$('#region').val(region).change();
					}

					if(region_txt != ""){
						$('#region_txt').val(region).change();
					}
				}
			},
			"json"
		);
	}

	function changeCities(e, city_id, city_txt, prefix){
		var region_id = $(e).val();
		if (region_id < 1) {
			$('#city').html('<option>&mdash;</option>');
			$('#city').attr('disabled', true).show();
			$('#city_txt').val("").hide();
			return(false);
		}
		$('#city').attr('disabled', true);
		$('#city').html('<option>завантаження...</option>');

		var url = 'https://'+window.location.host+'/profile/get_select';
		$.post(	url, {
				"region" : region_id
			},
			function (result) {
				if ((result.type == 'error') || (result.cities == null) || (city_txt != "")) {
					$('#city').html('<option>&mdash;</option>').hide();

					if(city_txt.length > 0)
						$("#city_txt").val(city_txt).show();
					else
						$('#city_txt').val("").show();
					return(false);
				}
				else {
					$('#city_txt').val("").hide();
					var options = '<option value="0">- оберіть місто/район -</option>';
					$(result.cities).each(function() {
						options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
					});

					if($('#country').val() > 1)
						options += '<option value="999">- інший -</option>';

					$('#city').html(options);
					$('#city').attr('disabled', false);
					$('#city').show();

					if(city > 0){
						$("#city").val(city).change();
					}
				}
			},
			"json"
		);
	}

	$('select#city').live("change", function () {
		var city = $(this).val();
		if(city == "999"){
			$('#city_txt').val("").show();
		}else {
			$('#city_txt').val("").hide();
		}
	});
});

jQuery(document).ready(function(){

	var host = 'https://' + window.location.host + '/profile/';

	$("img.edit_work").live("click", function() {
		var id = $(this).attr("data-id");
		setTimeout(function(){
			var countries = $("[data-work-id="+id+"]").find("select.countries");

			if($(countries).attr("data-value") > 0)
				initCountries(countries);
		}, 1000);
	});

	$("select.countries").each(function (n, e) {
		initCountries(e);
	});

	function initCountries(countries) {
		var value = $(countries).attr("data-value");
		var region = $(countries).attr("data-region-select");
		var option = $('<option />');

		$(countries).html("");

		$.post(host + "get_countries", {}, function (res) {
			if (res.length == 0)
				return false;

			$.each(res, function (n, country) {
				var option = $('<option />');

				if(n == 152)
					$($("option[value='1']", countries))
						.after($(option).html(country).val(n));
				else if(n == 31)
					$($("option[value='152']", countries))
						.after($(option).html(country).val(n));
				else if(n == 71)
					$($("option[value='31']", countries))
						.after($(option).html(country).val(n));
				else if(n == 1)
					$(countries).prepend(
						$(option)
							.html(country)
							.val(n)
					);
				else
					$(countries).append(
						$(option)
							.html(country)
							.val(n)
					);

				if(n == 193)
					$(countries).prepend(
						$(option)
							.html("&mdash;")
							.val(0)
					);
			});

			$("[value =" + value + "]", countries).attr("selected", "selected");
			$(countries).attr("data-value", "0");
		}, "json");

		if(value > 0) {
			initRegions(
				$("select#" + region),
				value
			);
		}

		$(countries).change(function(){
			initRegions(
				$("select#"+region),
				$(this).val()
			);
		});
	}

	function initRegions(regions, country) {
		var regions_box = $(regions).parent();
		var city_select = $(regions).attr("data-city-select");
		var value = $(regions).attr("data-value");

		var text_input = $("<input />").attr({
			"type": "text",
			"name": "region_txt"
		});

		$(regions).html("");

		$.post(host + "get_regions", {
			country: country
		}, function (res) {
			if (res.length == 0){
				$(regions)
					.val(0)
					.hide()
					.html("");
				$(regions_box).find("[name=region_txt]").remove();
				$(regions_box).append(text_input);

				initCities(
					$("select#"+city_select),
					0
				);

				return;
			}

			$(regions_box).find("[name=region_txt]").remove();


			$(regions)
				.html(
					$('<option />')
						.html("&mdash;")
						.attr("selected", true)
				)
				.show();;

			$.each(res, function (n, region) {
				var option = $('<option />');

				$(regions).append(
					$(option)
						.html(region)
						.val(n)
				);
			});

			$("[value =" + value + "]", regions).attr("selected", "selected");
			$(regions).attr("data-value", "0");
		}, "json");

		if(value > 0)
			initCities(
				$("select#"+city_select),
				value
			);
		else {
			$("select#" + city_select)
				.html("<option val='0'>&mdash;</option>")
				.val(0)
				.attr("disabled", "disabled")
				.show();
			$("select#" + city_select)
				.parent()
				.find("[name=city_txt]").remove();
		}

		$(regions).live("change", function(){
			initCities(
				$("select#"+city_select),
				$(this).val()
			);
		});
		$(regions).show();
	}

	function initCities(cities, region) {
		var cities_box = $(cities).parent();
		var value = $(cities).attr("data-value");
		var text_input = $("<input />").attr({
			"type": "text",
			"name": "city_txt"
		});

		$(cities_box).find("[name=city_txt]").remove();
		$(cities)
			.val(0)
			.html("")
			.attr("disabled", 0)
			.show();

		$.post(host + "get_cities", {
			region: region
		}, function (res) {
			if (res.length == 0){
				$(cities)
					.val(0)
					.hide()
					.html("");

				$(cities_box).append(text_input);

				return;
			}

			$(cities)
				.html("")
				.append(
				$('<option />')
					.html("&mdash;")
					.val(0)
			);

			$.each(res, function (n, city) {
				var option = $('<option />');

				$(cities).append(
					$(option)
						.html(city)
						.val(n)
				);
			});

			$("[value =" + value + "]", cities).attr("selected", "selected");
			$(cities).attr("data-value", "0");
		}, "json");
	}
});


// get regions by ajax
	var region_block;
	function getRegions(elem,form,item,url) {
		region_block = form + "_" + item + "_elem";
		$.get(
			url+"id/"+elem.value+"/form/"+form+"/item/"+item,
			{
			},
			function(data) {
				$_(region_block).innerHTML = data;
			}
		);
	}

// get districts by ajax
	var cities_block;
	function getDistricts(elem,form,item,url) {
		cities_block = form + "_" + item + "_elem";
		$.get(
			url+"id/"+elem.value+"/form/"+form+"/item/"+item,
			{
			},
			function(data) {
				$_(cities_block).innerHTML = data;
			}
		);
	}


