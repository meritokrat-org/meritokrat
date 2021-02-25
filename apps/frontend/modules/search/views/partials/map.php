<?
$user_data=user_data_peer::instance()->get_item(session::get_user_id());
?>
<!--<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>-->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCvfdOuQ3tJI1SNGZ1Nzg7fjeXECzTdOes&callback=initMap"
        type="text/javascript"></script>
<script type="text/javascript" src="/static/javascript/markerclusterer_packed.js"></script>
<script type="text/javascript" src="/static/javascript/map.js"></script>
<script type="text/javascript">
    <?
        if(!request::get('lat'))$_REQUEST['lat']=$user_data['locationlat'];
        if(!request::get('lng'))$_REQUEST['lng']=$user_data['locationlng'];
    ?>
    // jQuery(document).ready(function($) {
    function initMap() {
        mapWrapper = new MapWrapper('<?=(!request::get('lat'))?'50.4599800':request::get('lat')?>','<?=(!request::get('lng'))?'30.4810890':request::get('lng')?>',<?=  request::get_int('su') ? '6' : (request::get('lat')?'15':'12')?>);
        mapWrapper.init();
        mapWrapper.getMarkers('<?=$user_data['locationlng']?>','<?=$user_data['locationlat']?>','<?=request::get_int('user_id')?request::get_int('user_id'):session::get_user_id()?>');
    }
    // });
</script>    
<div class="map" id="Map" style="height: 750px; width: 750px;"></div>
