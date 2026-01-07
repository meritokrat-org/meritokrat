jQuery(document).ready(function () {

    $('#country').change(function () {
        var country_id = $(this).val();
        if (country_id == '0') {
            $('#region').html('');
            $('#region').attr('disabled', true);
            return (false);
        }
        $('#region').attr('disabled', true);
        $('#region').html('<option>завантаження...</option>');

        var url = 'https://' + context.host + '/profile/get_select';


        $.post(url, {
                "country_id": country_id
            }, function (result) {
                if (result.type == 'error') {
                    $('#region').remove().parrent().prepend("<input type='text' id='region'>");
                    $('#city').remove().parrent().prepend("<input type='text' id='city'>");
                } else {
                    var options = '<option value="">- оберіть регіон -</option>';

                    $(result.regions).each(function () {
                        options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                    });

                    $('#region').html(options);
                    $('#region').attr('disabled', false);
                }
            },
            "json"
        );
    });

    $('#region').change(function () {
        var region_id = $(this).val();
        if (region_id == '0') {
            $('#city').html('');
            $('#city').attr('disabled', true);
            return (false);
        }
        $('#city').attr('disabled', true);
        $('#city').html('<option>завантаження...</option>');

        var url = 'https://' + context.host + '/profile/get_select';
        $.post(url, {"region": region_id},
            function (result) {
                if (result.type == 'error') {
                    alert('error');
                    return (false);
                } else {
                    var options = '<option value="">- оберіть місто/район -</option>';
                    $(result.cities).each(function () {
                        options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                    });
                    $('#city').html(options);
                    $('#city').attr('disabled', false);
                }
            },
            "json"
        );
    });
});
// get regions by ajax
var region_block;

function getRegions(elem, form, item, url) {
    region_block = form + "_" + item + "_elem";
    $.get(
        url + "id/" + elem.value + "/form/" + form + "/item/" + item,
        {},
        function (data) {
            $_(region_block).innerHTML = data;
        }
    );
}


// get districts by ajax
var cities_block;

function getDistricts(elem, form, item, url) {
    cities_block = form + "_" + item + "_elem";
    $.get(
        url + "id/" + elem.value + "/form/" + form + "/item/" + item,
        {},
        function (data) {
            $_(cities_block).innerHTML = data;
        }
    );
}


