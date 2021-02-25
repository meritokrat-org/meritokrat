<div id="Map" class="map" style="width: 550px; height: 400px"></div>
<?if(!request::get('nocontrols')){?>
<table><tr>
<td><div class="selected bold pointer bts" id="hand_b" onclick="stopEditing()"><?=t('Просмотр карты')?></div></td>
<td><div class="unselected pointer bts" id="line_b" onclick="map.clearOverlays();startLine()"><?=t('Отметить территорию деятельности')?></div></td>
</tr>
    <tr>
        <td colspan="2" class="aright" style="padding-top: 10px;">
            <input type="button" class="button" id="csave" value="<?=t('Сохранить')?>"/>
       <input type="button" class="button_gray" onclick="Popup.close()" value="<?=t('Отмена')?>"/></td>
    </tr>
</table>
<?}else{
if(session::has_credential('admin')){?>
<div class="bold bts pointer"><a href="/ppo/edit?id=<?=request::get_int('ppo_id')?>&show_teritory=1"><?=t('Редактировать')?></a></div><?}?>
<div class="bold bts pointer" onclick="$('#popup_box').hide();"><?=t('Закрыть')?></div>
<?}?>
<script type="text/javascript">
$(".bts").click(function select() {
    $(".bts").removeClass("selected bold");
    $(".bts").addClass("unselected");
    $(this).removeClass("unselected");
    $(this).addClass("selected bold");
});
 
    var polyShape;
    var polygonMode;
    var polygonDepth = "20";
    var polyPoints = new Array();
    var drawMode;
    var oldMarker;
    var geocoder = null;

    var fillColor = "#0000FF"; // blue fill
    var lineColor = "#000000"; // black line
    var opacity = .5;
    var lineWeight = 2;
    var clickedPoint;
    var kmlFillColor = "7dff0000"; // half-opaque blue fill
    var stop=false;
function mapClick(marker, clickedPoint) { 
if(clickedPoint)
    { 
        polyPoints.push(clickedPoint);
        logCoordinates();
    }
    }

function logCoordinates(){
      // loop to print coords
      if(stop===true){
      $("#coords").val('');
      for (var i = 0; i<(polyPoints.length); i++) {
        var lat = polyPoints[i].y;
        var longi = polyPoints[i].x;
        document.getElementById("coords").value += longi + ", " + lat + "; ";
      }}
     }
                $("#csave").click(function(){
                $("#zoom").val(map.getZoom());
                $("#latbox").val(map.getCenter().lat());
                $("#lonbox").val(map.getCenter().lng());
                $("#teritorytd").html('<?=t('Изменено')?>');
                $("#teritory").html('<?=t('Изменить')?>');
                Popup.close();
            });
            
function startLine() {
 stop=true;
 var color ="#ff0000";
  var line = new GPolyline([], color);
  startDrawing(line, "Line " + (++lineCounter_), function() {
    var cell = this;
    var len = line.getLength();
    cell.innerHTML = (Math.round(len / 10) / 100) + "km";
  }, color);
}
function stopEditing() {
stop=false;
poly.disableEditing({onEvent: "mouseout"});
} 

var colorIndex_ = 0;
var lineCounter_ = 0;
var options = {};

var map = new GMap2(document.getElementById("Map"));
var COLORS = [["red", "#ff0000"], ["orange", "#ff8800"], ["green","#008000"],
    ["blue", "#000080"], ["purple", "#800080"]];



function getColor(named) {
  return COLORS[(colorIndex_++) % COLORS.length][named ? 0 : 1];
}

function startDrawing(poly, name, onUpdate, color) {
  $("#coords").val('');
  map.addOverlay(poly);
  poly.enableDrawing(options);
  //poly.enableEditing({onEvent: "mouseover"});
  //poly.disableEditing({onEvent: "mouseout"});
  GEvent.addListener(poly, "endline", function() {
  });
}
</script>