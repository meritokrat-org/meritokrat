                            <style>
                                .popup_box .frame {
                                    float: left;
                                }
                               #send_table td{
                              border: 1px solid #ccc;
                                }
                                #send_table table{margin: 0;}
                            </style>
<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
     <? if (session::has_credential('admin')) { ?>file_browser_callback : "tinyBrowser",<? } ?>
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "body",
	theme : "advanced",
	skin : "o2k7",
        plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras, insertdatetime,contextmenu,paste,directionality,visualchars,xhtmlxtras,table,media,youtube",

	theme_advanced_buttons1 : "bold,italic,underline,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,forecolor,|,bullist,numlist,|,link,unlink,image,youtube",
    <? if (!session::has_credential('admin')) { ?>
        theme_advanced_buttons2 : "tablecontrols,|,fontselect,fontsizeselect,",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
    <? } else { ?>    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,unlink,link,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
	theme_advanced_buttons5 : "styleselect,formatselect,fontselect,fontsizeselect,link",
   <? } ?>
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",

	content_css: '/static/css/typography.css',
        document_base_url : "https://meritokrat.org/",
        remove_script_host : false,
        convert_urls : false
});
          function testSub()
          { 
						var urlt = "/admin/maillist";
						if($('#testmail').val() == ""){
							alert('Введiть email');
							return false;
						}
						$.post(urlt,
							{
								"testmail" : $('#testmail').val(),
								"subject" : $('#subject').val(),
								"body" : tinyMCE.get('body').getContent(),
								"sender_name" : $('#sender_name').val(),
								"sender_email" : $('#sender_email').val()
							},
							function(result){ 
								if(result.status == false) {
									alert('Помилка');
									return false;
								} else {
									alert('Надiслано');
								}
							},
							"json"
						);
					}
          function saveDraft()
          {
						$("#todraft").val("1");
						/*var s_data = new Object();
            $.each($("#send_form input"), function(i, input){
							if($(this).attr("name") != ""){
								switch($(this).attr("type")){
									case "radio":
										if($(this).attr("checked"))
											alert($(this).attr("name")+" = "+$(this).val());
										break;
										
									case "hidden":
									case "text":
									case "checkbox":
										alert($(this).attr("name")+" = "+$(this).val());
										break;
								}
							}
						});*/
						document.getElementById("send_form").submit();
          }
          function showPreview()
          { 
              var error = 0;
              $('div.mfilter:visible').find('select').each(function(){
                  var data = $(this).val();
                  if(data=='' || data==0 || data==null){
                      alert('<?=t('Не выбран тип рассылки')?>');
                      error = 1;
                      return false;
                  }
              });
              if(error==0){
                  Popup.show();
                  $('#popuptheme').html($('#subject').val());
                  $('#popupotpr').html($('#sender_name').val());
                  $('#popupmail').html($('#sender_email').val());
                  var body = tinyMCE.get('body').getContent();
                  $('#popupbody').html(body);
                  Popup.setHtml($('#popup').html());
                  var nwidth = $('#popup_box').css('left').split("px");
                  $('#popup_box').css('left',nwidth[0]/2);
              }
          }
          $(function($) {
              $('#ladm').hide();$('#ladmh').hide();$('#lmcol').css('width','0%');$('#rmcol').css('width','100%');
              $('#mstrl').attr('src', '/static/images/common/btn_arrow_right_gray.jpg');
          });
        </script>
<div class="left mt10" id="lmcol" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" id="rmcol" style="width: 62%;">
	<h1 class="column_head"><?=t('Рассылки')?></h1>

	<div class="box_content acenter p10 fs12">
<?if($sent){?>
Розсилка додана в чергу.
<? } else { ?>
				<div class="fs11">
					<a href="javascript:;" onclick="adminController.chageMailMode('unknown'); $('#send_form').show();" id="mode_unknown" class="mail_mode dotted">Зовнiшня</a> &nbsp;
					<a href="javascript:;" onclick="adminController.chageMailMode('known'); $('#send_form').show();" id="mode_known" class="mail_mode bold dotted">Користувацька</a>&nbsp;
                                        <a href="javascript:;" onclick="adminController.chageMailMode('act'); $('#send_form').hide();" id="mode_act" class="mail_mode dotted">Активнi</a>&nbsp;
                                        <a href="javascript:;" onclick="adminController.chageMailMode('sends'); $('#send_form').hide();" id="mode_sends" class="mail_mode dotted">Розiсланi</a>&nbsp;
                                        <a href="javascript:;" onclick="adminController.chageMailMode('user_sends'); $('#send_form').hide();" id="mode_user_sends" class="mail_mode dotted">Розiсланi користувачами</a>&nbsp;
                                        <a href="javascript:;" onclick="adminController.chageMailMode('drufts'); $('#send_form').hide();" id="mode_drufts" class="mail_mode dotted">Чернетки</a>
                                </div>
                                <form id="send_form" name="send_form" method="post">
				<input type="hidden" name="send" value="1">
				<input type="hidden" id="mail_mode" name="mail_mode" value="known">
                                <input type="hidden" id="todraft" name="todraft" value="0">
                                <input type="hidden" name="edit" value="0">
				<table class="fs11 mode_pane hidden" id="pane_unknown">
					<tr>
						<th>Ім'я</th>
						<th>Email</th>
					</tr>
					<tr class="maillist_item">
						<td><input type="text" class="text" name="name[]" /></td>
						<td>
							<input type="text" class="text" name="email[]" />
							<input type="button" class="button" value="+" onclick="adminController.maillistAdd(this);" />
							<input type="button" class="button_gray" value="&nbsp;-&nbsp;" onclick="adminController.maillistRemove(this);" />
						</td>
					</tr>
				</table>

				<div class="aleft mode_pane" id="pane_known">
					<div class="left" style="width: 195px;">
                                            <div id="filtrsmain">
                                            <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter" value="common" checked="checked" />Усім<br />
                                                <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter" value="group" />Спiльноти<br />
						<input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter" value="age" />Вiк<br />
                                                <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter" value="status" />Статус<br />
                                                <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter" value="func" />Функції<br />
						<input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter" value="region" />Регіони<br />
                                                <input onclick="adminController.showMailFilter(this.value);" class="clsa" type="radio" name="filter" value="district" />Регіон/район<br />
                                                <? load::model('lists/lists') ?>
                                                <? $list_data = lists_peer::instance()->get_list_data(session::get_user_id()) ?>
                                                <? if(count($list_data)!=0){ ?>
                                                <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter" value="lists" />Списки<br />
                                                <? } ?>
                                            </div>
                                            <input class="filter_maillists" onclick="adminController.showMailFilter(this.value);" type="radio" name="filter" value="maillists" />Списки розсилок<br />
                                        </div>
					<div style="margin-left: 160px; margin-top: 10px;">
						<div class="mfilter" id="common_filter"></div>
						<div class="mfilter hidden" id="group_filter">
							<? load::model('groups/groups') ?>
							<?=tag_helper::select('groups[]', groups_peer::instance()->get_select_list(), array('style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 48))?>
						</div>
					
                                                <div class="mfilter hidden" id="status_filter">
							<?=tag_helper::select('status[]', user_auth_peer::instance()->get_statuses(), array('style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 8))?>
						</div>

                                                <div class="mfilter hidden" id="func_filter">
							<?=tag_helper::select('func[]', user_auth_peer::get_functions(), array('style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 8))?>
						</div>

                                                <div class="mfilter hidden" id="region_filter">
							<?=tag_helper::select('region[]', geo_peer::instance()->get_regions(1), array('style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 20))?>
						</div>

                                                <? if(count($list_data)!=0){ ?>
                                                <div class="mfilter hidden" id="lists_filter">
							<?=tag_helper::select('lists[]', $list_data, array('style' => 'width: 250px;', 'multiple' => 'multiple'))?>
						</div>
                                                <? } ?>

						<div class="mfilter hidden" id="district_filter">
                                                   <input name="region_id" type="hidden" value="13" />
                                                <?$regns=geo_peer::instance()->get_regions(1);$regns[0]="Виберіть регiон";ksort($regns);?>
                                                <?=tag_helper::select('regionc',$regns, array('use_values' => false, 'value' => $user_data['region_id'], 'id'=>'region', 'rel'=>t('Выберите регион'), )); ?><br/>
                                                <? if ($user_data['region_id']>0 and $user_data['region_id']!=9999) $cities = geo_peer::instance()->get_cities($user_data['region_id']);
                                                elseif($user_data['country_id']>1) $cities['9999']='закордон';
                                                else $cities=array(""=>"Виберіть місто"); ?>
                                                <?=tag_helper::select('city[]', $cities , array('use_values' => false, 'value' => $user_data['city_id'], 'multiple' => 'multiple', 'id'=>'city', 'class'=>'city', 'rel'=>t('Выберите город/район'))); ?>

                                                </div>
						<div class="mfilter hidden" id="age_filter">
							Від <input class="text" type="text" value="16" size="4" name="age_from" /> &nbsp;
							До <input class="text" type="text" value="85" size="4" name="age_to" />
						</div>
                                                <div class="mfilter hidden" id="maillists_filter">
                                                    <? load::model('mailing'); ?>
							<?=tag_helper::select('maillists[]', mailing_peer::instance()->get_maillists(), array('style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 12))?>
						</div>
					</div>
					<div class="clear"></div>
                                        <div class="fs11" id="onactiv">Тiльки активованим:<input name="activ" type="checkbox" checked value="1"/></div><br/>
                                                        <div id="drafts_select"><?load::model('drafts'); $drafts=drafts_peer::instance()->get_drafts();$drafts[0]="Виберіть шаблон";ksort($drafts);?>
                                                                    З шаблонiв:
                                <?=tag_helper::select('drafts', $drafts, array('style' => 'width: 250px;'))?>
					</div>
                                        
                                </div>
                                 <div class="fs11">Тема</div>
                                <input type="text" style="width: 100%;" class="text" name="subject" rel="Введіть тему" id="subject">
                                <br />
                                <div class="fs11">Ім'я відправника</div>
                                <input value="Meritokrat.org info" rel="Введіть Ім'я відправника" type="text" name="sender_name" id="sender_name" class="text" style="width: 100%;" />

				<br />

				<div class="fs11">E-mail  відправника</div>
                                <input value="info@meritokrat.org.ua" rel="Введите E-mail  відправника" type="text" name="sender_email" id="sender_email" class="text" style="width: 100%;" />

				<br />
				<div class="fs11">Повідомлення</div>
                                <textarea rel="Введите сообщение" name="body" id="body" style="width: 100%; height: 700px;"></textarea>
				<div class="fs10">* <b>NAME</b> - для зазначення імені в листі</div>

				<br />
                                <div id="test" style="display: none;">E-mail:<input type="text" name="testmail" value="" id="testmail"/>
                                    <input type="button" value="Надiслати" onclick="testSub('');"/>
                                </div>
                                <div id="btns">
                                <input type="submit" name="save"  onclick="$('#send').val(0);" class="button hidden" value="Зберегти" />
                                 <div class="drafts" id="drafts" style="display: none;">Им'я:<input type="text" name="draftname" id="draftname" value="" id="draftsname"/>
                                    <input type="button" value="Зберегти" onclick="saveTemplate('');"/>
                                </div>
                                <input type="button" onclick="showDraftsForm('');" class="button" value="В шаблони" />

                                <input type="button" name="tdrfbut" onclick="saveDraft('');" class="button" value="В чернетки" />
                                <input type="button" onclick="$('#test').show();" class="button" value="Тестова вiдправка" />
                                <input type="submit" class="button" value="<?=t('Отправить')?>" />
                                </div>
                                </form>
                        <div id="popup" style="display: none;">
                            <div class="b clear">Тема:</div><span class="fs11" style="float: left;" id="popuptheme"></span>
                            <div class="b clear">Ім'я відправника:</div><span class="fs11"  style="float: left;" id="popupotpr"></span>
                            <div class="b clear">E-mail  відправника:</div><span class="fs11"  style="float: left;" id="popupmail"></span>
                            <div class="b clear">Повідомлення:</div><span class="fs11"  style="float: left;" id="popupbody"></span>
                            <div class="clear"><input type="button" onclick="Popup.close();" value="Вiдмiнити"/>
                                <input type="button" onclick="send_data()" value="<?=t('Отправить')?>"/></div>
                        </div>
		<? } ?>
                        <div id="pane_sends" class="mode_pane"></div>
                        <div id="pane_user_sends" class="mode_pane"></div>
                        <div id="pane_drufts" class="mode_pane"></div>
                        <div id="pane_act" class="mode_pane"></div>
	</div>
</div>
<script type="text/javascript">
    function send_data() {
        $('form').submit();
    }
$(".clsa").click(function(){
$('.city').attr("id","city");
});

$("#filtrsmain input").click(function(){
$('#onactiv').show();
});

$(".filter_maillists").click(function(){
$('#onactiv').hide();
});

$('#drafts_select select').change(function(){
var urlt = "/admin/drafts";
if($(this).val()>0){
$.post(urlt,{"draft_id":$(this).val()},
			function (result) {
   tinyMCE.getInstanceById('body').setContent('');
   tinyMCE.execCommand('mceFocus',false,'body');
   tinyMCE.execCommand('mceInsertContent',false,result);
                        });
}
});

function showDraftsForm()
{
  $('.drafts').show();
}

$("#mode_sends").click(function(){
  var urlt = "/admin/maillist";
		$.post(urlt,{"getsends":1},
			function (result) {
$('#pane_sends').html(result);
return false;
                        });
});

$("#mode_user_sends").click(function(){
  var urlt = "/admin/maillist";
		$.post(urlt,{"user_sends":1},
			function (result) {
$('#pane_user_sends').html(result);
return false;
                        });
});

$("#mode_act").click(function(){
  var urlt = "/admin/maillist";
		$.post(urlt,{"getact":1},
			function (result) {
$('#pane_act').html(result);
return false;
                        });
});

$("#mode_drufts").click(function(){
  var urlt = "/admin/maillist";
		$.post(urlt,{"getdrufts":1},
			function (result) {
$('#pane_drufts').html(result);
return false;
                        });
});

$('a').click(function(){
$('#btns').show();
$('#sender_name').removeAttr("disabled");
$('#sender_email').removeAttr("disabled");
$('#subject').removeAttr("disabled");
$('#save').hide();
$('#sendbut').show();
$('#tdrfbut').show();
$('#subject').val('');
$('#sender_name').val('Meritokrat.org info');
$('#sender_email').val('i@meritokrat.org');
$('#edit').val(0);
$('#send').val(1);
tinyMCE.getInstanceById('body').setContent('');
});

function viewList(id)
{
var urlt = "/admin/maillist";
$('#send_form').show();
$('#pane_act').hide();
$('#pane_sends').hide();
$('#pane_drufts').hide();
$.post(urlt,{"mailing_id":id},
function (result) {
$('#pane_known').hide();
var obj = eval( '(' + result + ')' );
tinyMCE.getInstanceById('body').setContent('');
tinyMCE.execCommand('mceFocus',false,'body');
tinyMCE.execCommand('mceInsertContent',false,obj.body);
$('#subject').val(obj.subject);
$('#sender_name').val(obj.sender_name);
$('#sender_email').val(obj.sender_email);
$('#edit').val(id);
$('#send').val(0);

if(obj.is_complite==true)
    {
$('#btns').hide();
$('#sender_name').attr("disabled","disabled");
$('#sender_email').attr("disabled","disabled");
$('#subject').attr("disabled","disabled");
//tinyMCE.removeMCEControl(tinyMCE.getEditorId('body'));
tinyMCE.execCommand('removeMCEControl',false,'body');
    }
if(obj.is_druft==true || obj.is_complite==false)
{
$('#save').show();
$('#tdrfbut').hide();
$('#send').val(1);
}
if(obj.is_druft==false && obj.is_complite==false)
    {
 $('#sendbut').hide();
 $('#tdrfbut').hide();
    }
                        });
}


function delMailing(id)
{
var urlt = "/admin/maillist";
$.post(urlt,{"delete_mailing":id},
function (result) {
$('#mailing'+id).remove();
  });
}

function saveTemplate()
          {
               var urlt = "/admin/drafts";
              if($('#draftname').val()=="undefined"){alert("Введiть им'я");return(false);}
		$.post(urlt,{"draftname":$('#draftname').val(),"draftbody":tinyMCE.get('body').getContent()},
			function (result) {
				if (result == 'error') {
					alert('Помилка');
					return(false);
				}else
                                    {
                                        $('.drafts').hide();
                                        alert('Збережено');
                                    }
                        });
          }
</script>
