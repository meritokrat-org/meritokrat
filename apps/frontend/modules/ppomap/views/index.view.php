<div id="Map" class="map" style="width: 760px; height: 760px"></div>
 <?
if($_SERVER['SERVER_NAME']=='meritokrat.org')
{
$key='ABQIAAAAeJTsA7ppykO6RHwqXVTnxhRUv1QFGme1wBmmBs0G3PPf8lp1HxSLUl3FK3V4kfgdjiurxjuNdubvAg';
}
else
{
	// ABQIAAAAeJTsA7ppykO6RHwqXVTnxhS237pdi7AAC2Fq3Ha5pN09SYJt4xRkBNsN6wrom0qaIxq0Haiiaurq6A
$key='ABQIAAAAXi7AtY5jQ4YMZS3uNqaQVhSn51_jLMmjl25B6QxLNt9bnzD_KBRpTuhouSuZjyfhXbGmAM6vx3bLFw';
}

if($group['map_lat']=='' || $group['map_lon']=='')
{
$group['map_lat']='50.4599800';
$group['map_lon']='30.4810890';
$ow=0;
}
else $ow=1;
if($group['map_zoom']==0)$group['map_zoom']='8';
?>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=$key?>"
            type="text/javascript"></script>
<script type="text/javascript"> 
$(function(){ 
function mapClick(marker, clickedPoint) { 
if(clickedPoint)
    { 
        polyPoints.push(clickedPoint);
        logCoordinates();
    }
    }
function initialize2() {
        if (GBrowserIsCompatible()) {
       map = new google.maps.Map(document.getElementById("Map"));

        map.setCenter(new GLatLng('<?=$group['map_lat']?>','<?=$group['map_lon']?>'), 12);
        
        map.addControl(new GLargeMapControl());
        map.addControl(new GMapTypeControl());
        var mouseoverTimeoutId = null; 
          var latOffset = 0.01;
  var lonOffset = 0.01;

        <?
        foreach($groups as $group_id){
$group=ppo_peer::instance()->get_item($group_id);        
?>
var content<?=$group['id']?>='<div style="padding-top:20px"><a target="_blank" href="/ppo<?=$group['id']?>/<?=$group['number']?>"><?=stripslashes(htmlspecialchars($group['title']))?></a></div>';
<?
$cord_array=explode('; ', $group['coords']);
if(is_array($cord_array)){
$cord_array = array_unique(array_diff($cord_array, array('')));
if(count($cord_array)>0) {?>
    <?
    echo "var polyline = new GPolygon([";
    foreach($cord_array as $k=>$c) {
        $coordinates = explode(", ",$c);
        ?>
    <?
        echo "new GLatLng(" . $coordinates[1] . "," . $coordinates[0] . "),\n";
    }
    $firstcoordinates = explode(", ",$cord_array[0]);
    echo "new GLatLng(" . $firstcoordinates[1] . "," . $firstcoordinates[0] . ")\n";
    echo "], \"#f33f00\", 1, 1, \"#ff0000\", 0.2);\n";
    echo "map.addOverlay(polyline);\n\n";
    ?>
var pts<?=$group['id']?> = polyline.getVertex(Math.floor(polyline.getVertexCount()/2)); 
GEvent.addListener(polyline, 'mouseover', function() {
   map.openInfoWindowHtml(pts<?=$group['id']?>,content<?=$group['id']?>); 
});
GEvent.addListener(polyline, 'mouseout', function() {
mouseoverTimeoutId = setTimeout(function(){ map.closeInfoWindow()},2000);
});
                    <?
}}?>
         
<?
if($group['geolocationlng'] && $group['geolocationlat']){?>
var point = new GLatLng('<?=$group['geolocationlat']?>', '<?=$group['geolocationlng']?>');
var marker<?=$group['id']?> = new GMarker(point);

GEvent.addListener(marker<?=$group['id']?>, "click", function() {
   marker<?=$group['id']?>.openInfoWindowHtml(content<?=$group['id']?>); 
});
GEvent.addListener(marker<?=$group['id']?>, "mouseover", function() {
   marker<?=$group['id']?>.openInfoWindowHtml(content<?=$group['id']?>); 
});
GEvent.addListener(marker<?=$group['id']?>, "mouseout", function() {
mouseoverTimeoutId = setTimeout(function(){ map.closeInfoWindow()},2000);
}); 
map.addOverlay(marker<?=$group['id']?>);                

<?}?>
<?    
}
?>
       // map.clearOverlays();
        GEvent.addListener(map, 'click', mapClick);

    }
}

initialize2();
});
</script>