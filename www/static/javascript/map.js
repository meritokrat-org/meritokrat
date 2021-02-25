function MapWrapper(centerX, centerY, gzoom) {
    var _this = this;
    this.map = null;
    this.setMarker = null;
    this.geocoder = null;
    this.markerClusterer = null;
    this.mzoom = gzoom;

    this.centerX = centerX;
    this.centerY = centerY;
    //this.centerX =  55.7557; //начальные координаты - город Москва
    //this.centerY = 37.6176;

    var retAddress = "";
    this.init = function () {
        //инициализируем Geocoder
        if (_this.geocoder == null) {
            _this.geocoder = new google.maps.Geocoder();
        }
        //инициализируем карту
        if (_this.map == null) {
            var latlng = new google.maps.LatLng(_this.centerX, _this.centerY);
            var myOptions = {
                zoom: _this.mzoom,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            _this.map = new google.maps.Map(document.getElementById("Map"),
                myOptions);
        }

        //по кнопке Ok
        $("#submit").unbind("click");
        $("#submit").click(function () {
            _this.setMapToCity();
        });
        //или по enter в поле адреса устанавливаем карту на город + маркер которым всё отмечаем
        $("#address").unbind("keypress");
        $("#address").keypress(function (e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                _this.setMapToCity();
            }
        });

        $(".input_city").show();
        $(".info-put-marker").hide();
        $(".info-upload-picture").hide();
        //по кнопке Сохранить - сохраняем в БД но пока этого не видно
        $("#mapbutton").click(function () {
            _this.SaveToDB();
        });
    }

    //Установить карту на город 
    this.setMapToCity = function () {
        var address = $("#address").attr("value");

        _this.geocoder.geocode({'address': address}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                _this.map.setCenter(results[0].geometry.location);
                //установить Zoom таким образом, чтобы город был показан весь
                _this.map.setZoom(_this.getZoom(results[0].geometry.viewport));
                //и поставить маркет для отметки адреса
                _this.addMarker();
                $(".info-put-marker").show();
            }
        });
    }
//добавить маркер
    this.addMarker = function () {
        if (_this.setMarker != null) {
            _this.setMarker.setMap(_this.map);
            _this.setMarker.setPosition(_this.map.getCenter());
        } else {
            _this.setMarker = new google.maps.Marker({
                map: _this.map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: _this.map.getCenter()
            });
            google.maps.event.addListener(_this.setMarker, 'click', _this.toggleBounceMarker);
            //при окончании перемещения маркера установить функцию 
            google.maps.event.addListener(_this.setMarker, 'dragend', _this.markerPositionChanged);
        }
    }
    //вычисление значения Zoom по границам 
    this.getZoom = function (bounds) {

        var width = $(".map").width();
        var height = $(".map").height();

        var dlat = Math.abs(bounds.getNorthEast().lat() - bounds.getSouthWest().lat());
        var dlon = Math.abs(bounds.getNorthEast().lng() - bounds.getSouthWest().lng());

        var max = 0;
        if (dlat > dlon) {
            max = dlat;
        } else {
            max = dlon;
        }

        // Center latitude in radians
        var clat = Math.PI * Math.abs(bounds.getSouthWest().lat() + bounds.getNorthEast().lat()) / 360.;

        var C = 0.0000107288;
        var z0 = Math.ceil(Math.log(dlat / (C * height)) / Math.LN2);
        var z1 = Math.ceil(Math.log(dlon / (C * width * Math.cos(clat))) / Math.LN2);

        return 18 - ((z1 > z0) ? z1 : z0);
    }


    //при клике на маркет он будет подыматься и показывать куда упадет когда его отпустим
    this.toggleBounceMarker = function () {
        if (_this.setMarker.getAnimation() != null) {
            _this.setMarker.setAnimation(null);
        } else {
            _this.setMarker.setAnimation(google.maps.Animation.BOUNCE);
        }
    }

    //получить координаты и информацию о местоположении
    this.markerPositionChanged = function () {
        var latlng = _this.setMarker.getPosition();
        _this.GetInfo(latlng);
    }

    //получение данных по 
    this.GetInfo = function (latlng) {
        _this.geocoder.geocode({'latLng': latlng, 'language': 'ru'}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                _this.map.setCenter(results[0].geometry.location);
            } else {
                alert("Пошло что-то не так, потому что: " + status);
            }
        });
    }

    //Составить строку адреса по первому результату
    this.ComposeAddress = function (item) {
        retAddress = "";
        $.each(item.address_components, function (i, address_item) {
            var isOk = false;
            $.each(address_item.types, function (j, typeName) {
                //не будем брать значения адреса улицы и локали (города) - город потом будет в administrative_level_2
                if (typeName != "street_address" && typeName != "locality") {
                    isOk = true;
                }
            });
            if (isOk) {
                if (retAddress == "") {
                    retAddress = address_item.long_name;
                } else {
                    retAddress = retAddress + ", " + address_item.long_name;
                }
            }
        });
        return retAddress;
    }

    //сохраняем в БД
    this.SaveToDB = function () {
        $("#LocationLat").val(_this.setMarker.getPosition().lat());
        $("#LocationLng").val(_this.setMarker.getPosition().lng());
        $("#MapZoom").val(_this.map.getZoom());
    }

    //получить все маркеры из БД
    this.getMarkers = function (lng, lnlt, uid) {
        /*var myLatlng = new google.maps.LatLng(50.433238078518606,30.369803392684958);
         var myOptions = {
         zoom: 4,
         center: myLatlng,
         mapTypeId: google.maps.MapTypeId.ROADMAP
         }
         var marker = new google.maps.Marker({
         position: myLatlng,
         title:"Hello World!"
         });

         // To add the marker to the map, call setMap();
         marker.setMap(_this.map);  */
        $.ajax({
            type: "POST",
            url: "/search/get_markers",
            dataType: "json",
            data: ({locationlng: lng, locationlat: lnlt, user_id: uid}),
            success: function (data) {
                // var data = eval('('+data+')');
                var mouseoverTimeoutId = null;
                if (data.result == "ok") {
                    var markers = [];
                    $.each(data.data, function (i, item) {
                        var contentString = '<div class="content">' + item.Info + '</div>';
                        var latlng = new google.maps.LatLng(item.locationlat, item.locationlng);
                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });
                        var marker = new google.maps.Marker({
                            position: latlng
                        });
                        markers.push(marker);
                        if (item.active)infowindow.open(_this.map, marker);
                        google.maps.event.addListener(marker, 'mouseover', function () {
                            //  if(mouseoverTimeoutId){clearTimeout(mouseoverTimeoutId);
                            //   mouseoverTimeoutId = null;
                            //                 } 
                            infowindow.open(_this.map, marker);
                        });
                        google.maps.event.addListener(marker, 'click', function () {
                            infowindow.open(_this.map, marker);
                        });
                        google.maps.event.addListener(marker, 'mouseout', function () {
                            mouseoverTimeoutId = setTimeout(function () {
                                infowindow.close(_this.map, marker)
                            }, 2000);
                        });
                    });

                    if (_this.markerClusterer != null) {
                        _this.markerClusterer.clearMarkers();
                    }
                    _this.markerClusterer = new MarkerClusterer(_this.map, markers, {
                        maxZoom: 13,
                        gridSize: 50,
                        styles: null
                    });

                } else {
                    $.post("/search/get_markers2", {user_id: uid});
                }
            },
            error: function () {
                $.post("/search/get_markers2", {user_id: uid});
            }
        });

    }
}