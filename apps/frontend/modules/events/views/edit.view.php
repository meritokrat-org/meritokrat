<? include 'partials/sub_menu.php' ?>
<? include 'partials/datepicker.php' ?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    jQuery('input#start','div#copyFromHistory form');
    jQuery('input#end','div#copyFromHistory form');
    $('input#start').datetimepicker({
        onClose:function(){
            $.post('/events/datarange',{'start':$('input#start').val(),'id':<?=$event['id']?>},function(data){$('input#end').val(data);});
        },
        dateFormat: 'dd.mm.yy'
    });
    $('input#end').datetimepicker({ dateFormat: 'dd.mm.yy' });
    $('a#map').click(function(){
        var adress = $('select[name="regionc"] option:selected').html()+', '+$('select[name="city"] option:selected').html() +', '+$('input[name="adress"]').val();
        adress = adress.split(' ').join('+');
        window.open('http://maps.google.com/maps?q='+adress+'&hl=ru&ie=UTF8&ct=clnk&split=0');
    });
    $('input[type="submit"]').click(function(){
        $('textarea[name="description"]').val(tinyMCE.get('description').getContent());
    });
});
</script>
                <?
                        load::view_helper('image');
                        if(!$event['photo'] && $event['type']==1){
                            load::view_helper('group');
                            load::model('groups/groups');
                           }
                ?>
<? $sub_menu = '/events/create'; ?>
	<h1 class="column_head"><a href="/event<?=$event['id']?>"><?= $event['name'] ?></a> → <?=t('Редактирование')?></h1>
        <? include 'partials/tabs.php' ?>
<form id="edit_form" method="post" class="form_bg mt10 form <?=(session::has_credential('designer'))?'hidden':''?>">
    <input type="hidden" name="id" value="<?=request::get_int('id')?>"/>
	<table width="100%" class="fs12">
		<tr>
			<td width="23%" class="aright"><?=t('Название')?> <b>*</b></td>
                        <td><input rel="<?=t('Введите название события')?>" style="width: 350px;" value="<?=stripslashes(htmlspecialchars($event['name']))?>" name="name" type="text" class="text" /></td>
		</tr>
		<tr>
			<td class="aright"><?=t('Вид')?> <b>*</b></td>
                        <td><?=tag_helper::select('section',events_peer::get_types(),array('value'=>$event['section']))?></td>
		</tr>
		<tr id="section-format">
			<td class="aright"><?=t('Формат')?>:</td>
			<td>
				<div id="section-format-2">
					<? $f = events_peer::get_formats(); ?>
					
					<div>
						<input type="checkbox" id="campaign" name="campaign" <?= $event["format"]["campaign"] ? "checked" : "" ?> />
						<label for="campaign"><?=$f["campaign"]?></label>
					</div>
					<div>
						<input type="checkbox" id="propaganda" name="propaganda" <?= $event["format"]["propaganda"] ? "checked" : "" ?> />
						<label for="propaganda"><?=$f["propaganda"]?></label>
					</div>
					<div>
						<input type="checkbox" id="other" name="other" <?= $event["format"]["other"] ? "checked" : "" ?> />
						<label for="other"><?=$f["other"]?></label>
						<input type="text" id="other_text" name="other_text" value="<?=$event["format"]["other_text"]?>" />
					</div>
				</div>
			</td>
		</tr>
               <tr>
			<td class="aright"><?=t('Уровень')?> <b>*</b></td>
                        <td><?=tag_helper::select('level',events_peer::get_levels(),array('value'=>$event['level']))?></td>
		</tr>
                <tr>
			<td class="aright"><?=t('Дата начала')?> <b>*</b></td>
                        <td><input rel="<?=t('Введите дату начала')?>" style="width: 150px;" value="<?=date("d.m.Y H:i",$event['start'])?>" name="start" id="start" type="text" class="start text" /></td>
		</tr>
                <tr>
			<td class="aright"><?=t('Дата окончания')?> <b>*</b></td>
                        <td><input rel="<?=t('Введите дату окончания')?>" style="width: 150px;" value="<?=date("d.m.Y H:i",$event['end'])?>" name="end" id="end" type="text" class="end text" /></td>
		</tr>
                <tr>
			<td class="aright"><?=t('Где')?> <b>*</b></td>
                        <td>
                            <input name="region_id" type="hidden" value="13" />
                            <?$regns=geo_peer::instance()->get_regions(1);$regns[0]="Виберіть регiон";ksort($regns);?>
                            <?=tag_helper::select('regionc',$regns, array('use_values' => false, 'value' => $event['region_id'], 'id'=>'region', 'rel'=>t('Выберите регион'), )); ?><br/>
                        </td>
                </tr>
                <tr>
			<td class="aright"></td>
                        <td>
                            <? if ($event['region_id']>0 and $event['region_id']!=9999) $cities = geo_peer::instance()->get_cities($event['region_id']);
                            elseif($event['country_id']>1) $cities['9999']='закордон';
                            else $cities=array(""=>t('Выберите регион')) ?>
                            <?=tag_helper::select('city', $cities , array('use_values' => false, 'value' => $event['city_id'],'id'=>'city', 'class'=>'city', 'rel'=>t('Выберите город/район'))); ?>
                        </td>
		</tr>
                <tr>
			<td class="aright"><?=t('Адрес')?> <b>*</b></td>
                        <td>
                            <input rel="<?=t('Введите адрес')?>" style="width: 240px;" value="<?=$event['adress']?>" name="adress" type="text" class="text" />
                            &nbsp;<a id="map" href="javascript:;"><?=t('Проверить на карте')?></a>
                        </td>
		</tr>
                <tr>
			<td class="aright"><?=t('Стоимость')?> <b>*</b></td>
                        <td><?=tag_helper::select('price',events_peer::get_price_types(),array('value'=>$event['price']))?></td>
		</tr>
                <tr>
			<td width="21%" class="aright"><?=t('Веб-сайт')?></td>
                        <td><input style="width: 240px;" value="<?=$event['site']?>" name="site" type="text" class="text" /></td>
		</tr>
                <tr>
                        <td class="aright"><?=t('Описание события')?> <b>*</b></td>
                        <td><textarea name="description"  rel="<?=t('Введите описание')?>" style="width: 350px;"><?=stripslashes(htmlspecialchars($event['description']))?></textarea></td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Примечания')?></td>
                        <td><textarea name="notes"  style="width: 400px;"><?=stripslashes(htmlspecialchars($event['notes']))?></textarea></td>
                </tr>
                <tr>
			<td class="aright"><?=t('Подтверждение участия')?> <b>*</b></td>
                        <td>
                            <?=tag_helper::select('confirm',events_peer::get_confirm(),array('value'=>$event['confirm']))?><br/>
                        </td>
		</tr>
                <? if(session::has_credential('admin')) { ?>
                <tr>
                    <td class="aright">*<?=t('Скрытое событие')?></td>
			<td><input type="checkbox" name="hidden" value="1" <?=($event['hidden'])?'checked="checked"':''?> /></td>
		</tr>
                <? } ?>
                <tr>
                    <td class="aright"><b>* <?=t('Внимание')?></b></td>
			<td><?=t('Все поля обязательны для заполнения')?></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="submit" class="button" value="<?=t('Сохранить')?> ">
				<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
			</td>
		</tr>
	</table>
</form>
<form method="post" id="photo_form" class="form_bg mt10 <?=(!session::has_credential('designer'))?'hidden':''?> form" enctype="multipart/form-data">
    		<table width="100%" class="fs12"><tr>
      				<td><div class="left acenter" style="width: 250px;">
                                        <?
                if($event['type']==1 && !$event['photo'])
               echo group_helper::photo($event['content_id'], 'p', array('class' => 'border1', 'id' => 'photo'));
        else
               echo image_helper::photo($event['photo'], 'p', 'events', array('class' => 'border1', 'id' => 'photo'));
        ?>
		</div></td><td class="aright" width="30%"><?=t('Выберите файл')?></td>
				<td><input type="file" name="file" rel="<?=t('Картинка неверная либо слишком большая')?>" /><br/><br/>
                                <input type="submit" name="submit" class="button" value="<?=t('Сохранить')?> ">
				<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> "></td>
            </tr>
</table></form>

<?if(request::get('tab')){?>
<script type="text/javascript">
    jQuery('.form').hide();
    jQuery('#<?=request::get('tab')?>_form').show();
</script><?}?>

<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
     <? if (session::has_credential('admin')) { ?>file_browser_callback : "tinyBrowser",<? } ?>
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "description",
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
</script>