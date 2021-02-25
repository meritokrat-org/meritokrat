<style type="text/css">
.ui-datepicker {
    margin-left:160px;
}
.addval {
    width: 55px;
}

    .cgray {
        color: #333333;
}
.descedit table{
    float:left;
}
.tab_pane_gray a {
    margin-left: 5px;
}
#ui-datepicker-div{
    display:none;
}
</style>
<div class="form_bg mt10">
    <h1 class="column_head"><?=user_helper::full_name($user_desktop['user_id'], true, array(), false)?> &rarr;  <a style="color:#FFCC66;font-weight: bold;text-decoration: none;" href="/profile/desktop?id=<?=$user['id']?>"><?=t('Рабочий стол')?></a> &rarr; <?=t('Редактирование')?></h1>
	<div class="tab_pane_gray mb10" style="padding-top:5px">
                <? if ( session::has_credential('admin') ) { ?><a href="javascript:;" id="tab_function" class="tab_menu <?=session::has_credential('admin') ? 'selected' : ''?>" rel="function">* <?=t('Функции')?></a><? } ?>
                <a href="javascript:;" id="tab_information" class="tab_menu <?=session::has_credential('admin') ? '' : 'selected'?>" rel="information"><?=t('Агитация')?></a>
                <a href="javascript:;" id="tab_photo" class="tab_menu" rel="photo"><?=t('Наглядная агитация')?></a>
		<? /*if (session::has_credential('admin') and session::get_user_id()!=5) { ?><a href="javascript:;" id="tab_peoples" class="tab_menu" rel="peoples">* <?=t('Люди')?></a><? }*/ ?>
		<a href="javascript:;" id="tab_tasks" class="tab_menu" rel="tasks"><?=t('Подписи')?></a>
<!--                <a href="javascript:;" id="tab_help" class="tab_menu" rel="help"><?=t('Помощь')?></a>-->
                <a href="javascript:;" id="tab_meetings" class="tab_menu" rel="meetings"><?=t('Мероприятия МПУ')?></a>
		<a href="javascript:;" id="tab_educations" class="tab_menu" rel="educations"><?=t('Обучение МПУ')?></a>
                <a href="javascript:;" id="tab_events" class="tab_menu" rel="events"><?=t('Другие мероприятия')?></a>
                <a href="javascript:;" id="tab_other" class="tab_menu" rel="other"><?=t('Другое')?></a>
                <a href="javascript:;" id="tab_payments" class="tab_menu" rel="payments"><?=t('Взносы')?></a>
                <? if(session::has_credential('admin')){ ?>
                <a href="javascript:;" id="tab_membership" class="tab_menu" rel="membership">*<?=t('Членство')?></a>
                <? } ?>
		<div class="clear"></div>
	</div>
    <div class="descedit">
<!-- FUNCTION -->
    <? if ( session::has_credential('admin') ) { ?>
        <? include 'partials/desktop_edit/function.php'; ?>
    <? } ?>

<!-- INFORMATION -->
    <? include 'partials/desktop_edit/information.php'; ?>

<!-- PHOTO -->
    <? include 'partials/desktop_edit/photo.php'; ?>

<!-- PEOPLE -->
    <? include 'partials/desktop_edit/people.php'; ?>

<!--MEETING-->
    <? include 'partials/desktop_edit/meeting.php'; ?>

<!--SIGNATURES-->
    <? include 'partials/desktop_edit/signatures.php'; ?>

<!--EVENTS-->
    <? include 'partials/desktop_edit/events.php'; ?>

<!--OTHER-->
    <? include 'partials/desktop_edit/other.php'; ?>

<!--EDUCATION    -->
    <? include 'partials/desktop_edit/education.php'; ?>

<!--HELP-->
    <? include 'partials/desktop_edit/help.php'; ?>

<!--PAYMENTS-->
    <? include 'partials/desktop_edit/payments.php'; ?>

<? if(session::has_credential('admin')){ ?>
<!--MEMBERSHIP-->
    <? include 'partials/desktop_edit/membership.php'; ?>
<? } ?>

</div>

</div>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
      /*$.datepicker.setDefaults($.extend(
      $.datepicker.regional["uk"])
    );*/

    _enable();
    _signature();
    _select();
    
  
$('.tab_menu').click(function() {
			$('.tab_menu').removeClass('selected');
			$(this).addClass('selected');
			$(this).blur();
			$('.form').hide();
			$('#' + $(this).attr('rel') + '_form').show();
		});

		$('#tab_' + this.defaultTab).click();
var num = <?=$meetingcount?>;
var evnum = <?=$eventcount?>;
var ednum = <?=$educationcount?>;
var publication = <?=$publicationcount?>;
var banner = <?=$bannercount?>;
var signature = <?=$signaturecount?>;


   $(".p_types").change(function() {
       if ($(this).val()==16) {
           $('#another_'+$(this).attr('id')).show();
       }
       else {
           $('#another_'+$(this).attr('id')).hide();
       }
   });


   $(".avtophoto_description").click(function() {
                var salt = $(this).attr('id');
                var text = $('#description_'+salt).val();
		$('#photo_edit_wait_'+salt).show();
		$.post( '/profile/edit_desktop_photo', {salt: salt, description: text, user_id: <?=$user_desktop['user_id']?>} );
		$('#photo_edit_wait_'+salt).fadeOut(250, function() {$('#photo_edit_ok_'+salt).fadeIn( 250, function() {$('#photo_edit_ok_'+salt).fadeOut(2500);} );} );
   });

var settings = {
        changeMonth: true,
        changeYear: true,
         autoSize: true,
        showOptions: {direction: 'left' },
        dateFormat: 'yy/mm/dd',
        yearRange: '2002:2025',
        firstDay: true
    };
$('#recive_help_date').datepicker(settings);

    <?
    for ($i=$eventcount;$i>=0;$i--) $datepickers[]="#event_date_".$i;
    for ($i=$educationcount;$i>=0;$i--) $datepickers[]="#education_date_".$i;
    for ($i=$meetingcount;$i>=0;$i--) $datepickers[]="#date_".$i;
    for ($i=$publicationcount;$i>=0;$i--) $datepickers[]="#p_date_".$i;
    for ($i=$bannercount;$i>=0;$i--) $datepickers[]="#b_date_".$i;
    ?>
    $("<?=implode(',',$datepickers)?>").datepicker(settings);
    
   $("#add_meeting").click(function() {
    num += 1;
   /* $(this).attr("value","Видалити");
    $(this).addClass("button_gray");
    $(this).removeClass("add_meeting");
    $(this).attr("id","");*/
    myappend(num);
    $("#date_"+num).datepicker({
                changeMonth: true,
                changeYear: true,
                 autoSize: true,
                showOptions: {direction: 'left' },
                dateFormat: 'dd/mm/yy',
                shortYearCutoff: 90,
                yearRange: '2002:2025',
                firstDay: true
            });
    _enable();
    });
    

    $("#add_signature").click(function() {
    signature += 1;
    signatureappend(signature);
    _signature();
    });
    
   $("#add_event").click(function() {
    evnum += 1;
    eventappend(evnum);
    $("#event_date_"+evnum).datepicker({
                changeMonth: true,
                changeYear: true,
                 autoSize: true,
                showOptions: {direction: 'left' },
                dateFormat: 'dd/mm/yy',
                shortYearCutoff: 90,
                yearRange: '2002:2025',
                firstDay: true
            });
    _enable();
    });

   $("#add_education").click(function() {
    ednum += 1;
    educationappend(ednum);
    $("#education_date_"+ednum).datepicker({
                changeMonth: true,
                changeYear: true,
                 autoSize: true,
                showOptions: {direction: 'left' },
                dateFormat: 'dd/mm/yy',
                shortYearCutoff: 90,
                yearRange: '2002:2025',
                firstDay: true
            });
    _enable();
    });

$("#add_banner").click(function(){
    banner += 1 ;
$("#banners").append("<tr>\n\
    <td class=\"aright\"><?=t('Название')?></td><td>\n\
    <input name=\"b_tite[]\" class=\"text\" type=\"text\" value=\"\" />\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Ссылка')?></td>\n\
    <td><input name=\"b_url_"+banner+"\" class=\"text\" type=\"text\" value=\"\" />\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Комментарий')?></td>\n\
    <td>\n\
    <textarea style=\"height: 35px; width: 400px;\" name=\"b_description_"+banner+"\"></textarea>\n\
    </td>\n\
    </tr>");
    });

$("#add_publication").click(function(){
    publication += 1;
    var selects = '<?=str_replace(array("\n","\r"),"",user_helper::datefields('p_date',0,true))?>';
$("#publications").append("<tr>\n\
    <td class=\"aright\"><?=t('Вид')?></td>\n\
    <td>\n\
    <input type=\"checkbox\" name=\"publ["+publication+"]\" value=\"1\" class=\"publ\" /> <?=t('Печатная')?><input type=\"checkbox\" name=\"purl["+publication+"]\" value=\"1\" class=\"purl\" /> <?=t('Интернет')?>\n\
    </td>\n\
    </tr>\n\<tr>\n\
    <td class=\"aright\"><?=t('Тип')?></td>\n\
    <td>\n\
    <select name=\"p_type[]\" id=\"p_type[]\" class=\"p_types\">\n\
    <option selected=\"\" value=\"\">&mdash;</option>\n\
    <option value=\"1\" onclick=\"$('#another_p_type_"+publication+"').hide();\"><?=t('Интервью')?></option>\n\
    <option value=\"2\" onclick=\"$('#another_p_type_"+publication+"').hide();\"><?=t('Статья')?></option>\n\
    <option value=\"3\" onclick=\"$('#another_p_type_"+publication+"').hide();\"><?=t('Коментар')?></option>\n\
    <option value=\"4\" onclick=\"$('#another_p_type_"+publication+"').hide();\"><?=t('Новость')?></option>\n\
    <option value=\"5\" onclick=\"$('#another_p_type_"+publication+"').hide();\"><?=t('Обьявление')?></option>\n\
    <option value=\"16\" onclick=\"$('#another_p_type_"+publication+"').show();\"><?=t('Другое')?></option>\n\
    </select>\n\
    <div class=\"mt5 hide\" id=\"another_p_type_"+publication+"\">\n\
    <input type=\"text\" name=\"another_p_type[]\" id=\"another_p_type_"+publication+"\" >\n\
    </div>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Название публикации')?></td>\n\
    <td>\n\
    <input name=\"p_title[]\" class=\"text\" type=\"text\" value=\"\" />\n\
    </td>\n\
    </tr>\n\
    <tr><td class=\"aright\"><?=t('Название СМИ')?></td>\n\
    <td>\n\
    <input name=\"p_media_name[]\" class=\"text\" type=\"text\" value=\"\" />\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Дата')?></td><td>\n\
    "+selects+"\n\
    </td>\n\
    </tr>\n\
    <tr class=\"hide\">\n\
    <td class=\"aright\"><?=t('Ссылка')?></td>\n\
    <td>\n\
    <input name=\"p_url[]\" class=\"text\" type=\"text\" value=\"\" /></td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Комментарий')?></td>\n\
    <td><textarea style=\"height: 35px; width: 400px;\" name=\"p_description[]\"></textarea>\n\
    </td>\n\
    </tr>");

    $("#p_date_"+publication).datepicker({
                changeMonth: true,
                changeYear: true,
                 autoSize: true,
                showOptions: {direction: 'left' },
                dateFormat: 'dd/mm/yy',
                shortYearCutoff: 90,
                yearRange: '2002:2025',
                firstDay: true
            });
        _select();
        });



function myappend(num){
    var selects = '<?=str_replace(array("\n","\r"),"",user_helper::datefields('date',0,true,array('disabled'=>'disabled')))?>';
     $("#table_meeting").append("<?//<table width=\"100%\" class=\"fs12\" id=\"table_meeting_"+$(this).id+"\">\n\?><tr>\n\
    <td class=\"aright\"><?=t('Участие')?></td>\n\
    <td><input type=\"radio\" name=\"status[]\" value=\"0\" class=\"evnt\" /><?=t('Организовал')?>\
    <input type=\"radio\" name=\"status[]\" value=\"1\" class=\"evnt\" /><?=t('Посетил')?></td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Название')?></td><td><input name=\"title[]\" rel=\"<?=t('')?>\" class=\"text\" type=\"text\" disabled=\"disabled\" />\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Дата')?></td><td>\n\
    "+selects+"\n\
    <td></tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Комментарий')?></td><td><textarea style=\"height: 75px; width: 400px;\" name=\"description[]\" disabled=\"disabled\"></textarea>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td>\n\
    </td>\n\</tr><?//</table>?>");
 }
function eventappend(num){
    var selects = '<?=str_replace(array("\n","\r"),"",user_helper::datefields('event_date',0,true,array('disabled'=>'disabled')))?>';
     $("#table_event").append("<tr>\n\
    <td class=\"aright\"><?=t('Участие')?></td>\n\
    <td><input type=\"radio\" name=\"event_status[]\" value=\"0\" class=\"evnt\" /><?=t('Организовал')?>\n\
    <input type=\"radio\" name=\"event_status[]\" value=\"2\" class=\"evnt\" /><?=t('Выступил')?>\n\
    <input type=\"radio\" name=\"event_status[]\" value=\"1\" class=\"evnt\" /><?=t('Посетил')?></td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Название')?></td><td><input name=\"event_title[]\" rel=\"<?=t('')?>\" class=\"text\" type=\"text\" disabled=\"disabled\"/>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Дата')?></td><td>\n\
    "+selects+"\n\
    <td></tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Комментарий')?></td><td><textarea style=\"height: 75px; width: 400px;\" name=\"event_description[]\" disabled=\"disabled\"></textarea>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td>\n\
    </td>\n\</tr>");
 }


function educationappend(num){
    var selects = '<?=str_replace(array("\n","\r"),"",user_helper::datefields('education_date',0,true,array('disabled'=>'disabled')))?>';
     $("#table_education").append("<?//<table width=\"100%\" class=\"fs12\" id=\"table_meeting_"+$(this).id+"\">\n\?><tr>\n\
    <td class=\"aright\"><?=t('Участие')?></td>\n\
    <td><input type=\"radio\" name=\"education_status[]\" value=\"0\" class=\"evnt\" /><?=t('Провел')?>\
    <input type=\"radio\" name=\"education_status[]\" value=\"1\" class=\"evnt\" /><?=t('Принял участие')?></td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Название')?></td><td><input name=\"education_title[]\" rel=\"<?=t('')?>\" class=\"text\" type=\"text\" disabled=\"disabled\"/>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Дата')?></td><td>\n\
    "+selects+"\n\
    <td></tr>\n\
    <tr><td></td></tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Комментарий')?></td><td><textarea style=\"height: 75px; width: 400px;\" name=\"education_description[]\" disabled=\"disabled\"></textarea>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td>\n\
    </td>\n\</tr><?//</table>?>");
 }

function signatureappend(num){
    $("#table_signature").append("<tr id=\"table_signature\">\n\
    <td colspan=\"2\"><input name=\"signatures[]\" type=\"hidden\" value=\""+num+"\" />\n\
    <table width=\"100%\" class=\"fs12\" id=\"table_signatures\">\n\
    <tr id=\"signature_row\">\n\
    <td class=\"aright\"><?=t('Регион')?></td>\n\
    <td><select style=\"width:200px;\" id=\""+num+"\" rel=\"Оберіть регіон\" class=\"regions\" name=\""+num+"\">\n\
    <option value=\"\" selected>&mdash;</option>\n\
    <option value=\"1\" >АР Крим</option>\n\
    <option value=\"2\" >Севастополь</option>\n\
    <option value=\"3\" >Волинська область</option>\n\
    <option value=\"4\" >Вінницька область</option>\n\
    <option value=\"5\" >Дніпропетровська область</option>\n\
    <option value=\"6\" >Донецька область</option>\n\
    <option value=\"7\" >Житомирська область</option>\n\
    <option value=\"8\" >Закарпатська область</option>\n\
    <option value=\"9\" >Запорізька область</option>\n\
    <option value=\"10\" >Івано-Франківська область</option>\n\
    <option value=\"11\" >Кіровоградська область</option>\n\
    <option value=\"12\" >Київська область</option>\n\
    <option value=\"13\" >Київ</option>\n\
    <option value=\"14\" >Луганська область</option>\n\
    <option value=\"15\" >Львівська область</option>\n\
    <option value=\"16\" >Миколаївська область</option>\n\
    <option value=\"17\" >Одеська область</option>\n\
    <option value=\"18\" >Полтавська область</option>\n\
    <option value=\"19\" >Рівненська область</option>\n\
    <option value=\"21\" >Сумська область</option>\n\
    <option value=\"22\" >Тернопільська область</option>\n\
    <option value=\"23\" >Харківська область</option>\n\
    <option value=\"24\" >Херсонська область</option>\n\
    <option value=\"25\" >Хмельницька область</option>\n\
    <option value=\"26\" >Черкаська область</option>\n\
    <option value=\"27\" >Чернівецька область</option>\n\
    <option value=\"28\" >Чернігівська область</option>\n\
    </select></td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Город/Район')?></td>\n\
    <td>\n\
    <input name=\"city_id\" type=\"hidden\" />\n\ <select id=\"city_"+num+"\" rel=\"Оберіть місто/район\" name=\"city_"+num+"\"></select>\n\
    </td>\n\
    </tr>\n\
    <tr>\n\
    <td class=\"aright\"><?=t('Количество')?></td>\n\
    <td>\n\
    <input name=\"plan_"+num+"\" class=\"text\" type=\"text\" />\n\
    </td>\n\
    </tr>\n\
    </table>\n\
    </td>\n\
    </tr>");
}
  
    	
function _enable(){
    $('input.evnt[type="radio"]').click(function(){
        var element = $(this).parent('td').parent('tr').next('tr');
        element.find('input').removeAttr('disabled');
        element.next('tr').find('input,select').removeAttr('disabled');
        element.next('tr').next('tr').next('tr').find('textarea').removeAttr('disabled');
    });
    }
        

function _signature(){
    $('.regions').change(function () {
            	var region_id = $(this).val();
		var region_attr_id = $(this).attr('id');
		if (region_id == '0') {
			$('#city_'+region_attr_id).html('');
			$('#city_'+region_attr_id).attr('disabled', true);
			return(false);
		}
		$('#city_'+region_attr_id).attr('disabled', true);
		$('#city_'+region_attr_id).html('<option>завантаження...</option>');

		var url = '/profile/get_select';
		$.post(	url,{"region":region_id},
			function (result) {
				if (result.type == 'error') {
					alert('error');
					return(false);
				}
				else {
					var options = '<option value="">- оберіть місто/район -</option>';
					$(result.cities).each(function() {
						options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
					});
					$('#city_'+region_attr_id).html(options);
					$('#city_'+region_attr_id).attr('disabled', false);
				}
			},
			"json"
		);
	});
}
function _select(){
    $('input.publ[type="checkbox"]').click(function(){
        var name = $(this).parent('td').parent('tr').next('tr').next('tr').next('tr');
        var data = name.next('tr');
        if($(this).is(':checked')){
            name.show();
            data.show();
        }
    });
    $('input.purl[type="checkbox"]').click(function(){
        var url = $(this).parent('td').parent('tr').next('tr').next('tr').next('tr').next('tr').next('tr');
        if($(this).is(':checked')){
            url.show();
        }else{
            url.hide();
        }
    });
    }
function _int(val){
    var val = parseInt(val);
    if(isNaN(val))
        return 0;
    else
        return val;
}
});
</script>
