<div class="form_bg mt10">
		<h1 class="column_head">Редагувати важливу інформацію</h1>
                <div class="tab_pane_gray mb10">
                        <a rel="ua" class="tab_menu selected" id="ua_version" href="javascript:;">Україньска</a>
                        <a rel="ru" class="tab_menu" id="ru_version" href="javascript:;">Русский</a>
                        <div class="clear"></div>
                </div>

		<form method="post" class="mt10" action="">
			<table width="100%" class="fs12">
                           
				<tr>
					<td class="aright" width="10%">Показувати</td>
					<td>
                                            <input type="radio" value="false" name="hidden" <?=$attention['hidden']=='true' ? '': 'checked'?>> так
                                            <input type="radio" value="true" name="hidden" <?=$attention['hidden']=='true' ? 'checked' : ''?>> ні
                                        </td>
				</tr>
				<tr>
					<td class="aright" width="10%"><?=t('Название')?></td>
					<td><input type="text" name="title" value="<?=stripslashes($attention['title'])?>"></td>
				</tr>
				<tr id="tr_anounces_ua">
					<td class="aright" width="10%"><?=t('Анонс')?></td>
					<td><textarea rel="<?=t('Введите анонс Вашей')?>" name="anounces" style="width: 600px; height:200px;"><?=stripslashes($attention['anounces'])?></textarea></td>
				</tr>
				<tr id="tr_text_ua">
					<td class="aright" width="10%">Зміст</td>
					<td><textarea rel="<?=t('Введите текст Вашей')?>" name="text" id="text" style="width: 600px; height:627px;"><?=stripslashes($attention['text'])?></textarea></td>
				</tr>
                                <!-- RUSSIAN VERSION -->
				<tr id="tr_anounces_ru" class="hide">
					<td class="aright" width="10%"><?=t('Анонс')?> rus</td>
					<td><textarea rel="<?=t('Введите анонс Вашей')?>" name="anounces_ru" id="anounces_ru" style="width: 600px; height:200px;"><?=stripslashes($attention['anounces_ru'])?></textarea></td>
				</tr>
				<tr id="tr_text_ru" class="hide">
					<td class="aright" width="10%">Содержание rus</td>
					<td><textarea rel="<?=t('Введите текст Вашей')?>" name="text_ru" id="text_ru" style="width: 600px; height:627px;"><?=stripslashes($attention['text_ru'])?></textarea></td>
				</tr>

                                <tr class="mt10 mb15">
                                        <td class="aright mb15">&nbsp;</td>
                                        <td></td>
                                </tr>

				<tr>
					<td></td>
					<td>
						<input type="hidden" name="id" class="button" value="<?=$attention['id']?>">
                                                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
						<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">


						<div class="success hidden mr10 mt10"><?=t('Мнение добавлено')?></div>
					</td>
				</tr>


<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
	language : 'uk',
	elements : "anounces,anounces_ru",
	theme : "advanced",
	skin : "o2k7",
	plugins : "insertdatetime,contextmenu,paste,directionality,visualchars",

	theme_advanced_buttons1 : "bold,italic,underline,blockquote,|,forecolor,|,bullist,numlist,|,link,image",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",

	content_css: '/static/css/typography.css'
});
// O2k7 skin
tinyMCE.init({
	mode : "exact",
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "text,text_ru",
	theme : "advanced",
	skin : "o2k7",
        plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras, insertdatetime,contextmenu,paste,directionality,visualchars,xhtmlxtras,table,media,youtube",

	theme_advanced_buttons1 : "bold,italic,underline,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,forecolor,|,bullist,numlist,|,link,unlink,image,youtube",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,unlink,link,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
	theme_advanced_buttons5 : "styleselect,formatselect,fontselect,fontsizeselect,link",

	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",

	content_css: '/static/css/typography.css',
        document_base_url : "https://meritokrat.org/",
        remove_script_host : false,
        convert_urls : false
});
</script>
			</table>
		</form>
    </div>

<script type="text/javascript">
jQuery(document).ready(function(){
        $('#ua_version').click(function(){
                $('#tr_anounces_ru').hide();
                $('#tr_text_ru').hide();
                $('#tr_anounces_ua').show();
                $('#tr_text_ua').show();
                $('#ua_version').addClass('selected');
                $('#ru_version').removeClass('selected');
            });
        $('#ru_version').click(function(){
                $('#tr_anounces_ua').hide();
                $('#tr_text_ua').hide();
                $('#tr_anounces_ru').show();
                $('#tr_text_ru').show();
                $('#ru_version').addClass('selected');
                $('#ua_version').removeClass('selected');
            });
    });
</script>