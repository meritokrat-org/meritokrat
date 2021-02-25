<div class="form_bg">
	<h1 class="column_head mt10"><a href="/messages"><?=t('Cообщения')?></a> &rarr; <?=t('Новое сообщение')?></h1>

	<form id="region_form" class="form mt10" onsubmit="return false;">
                <? if($region_id){ ?>
                    <input type="hidden" name="region_id" value="<?=$region_id?>" />
                <? }else{ ?>
                    <input type="hidden" name="city_id" value="<?=$city_id?>" />
                <? } ?>
		<table width="100%" class="fs12">
			<? if($region_id){ ?>
                        <tr>
				<td class="aright"><?=t('Регион')?></td>
				<td><?=$region_name['name_' . translate::get_lang()]?></td>
			</tr>
                        <? }else{ ?>
                        <tr>
				<td class="aright"><?=t('Район')?></td>
				<td><?=$city_name['name_' . translate::get_lang()]?></td>
			</tr>
                        <? } ?>
			<tr>
				<td class="aright"><?=t('Сообщение')?></td>
                                <td>
                                    <textarea rel="<?=t('Введите текст сообщения')?>" id="body" name="body" style="width: 500px; height:150px;"><?=stripslashes(htmlspecialchars(request::get('body')))?></textarea>
                                </td>
			</tr>
                        <tr>
				<td class="aright"><?=t('Кому')?></td>
                                <td>
                                    <input type="radio" value="0" name="type" checked="checked" /><?=t('Активированным')?> (<?=t('Внутренняя рассылка')?>)
                                    &nbsp;&nbsp;
                                    <input type="radio" value="1" name="type" /><?=t('Всем')?> (<?=t('Email рассылка')?>)
                                </td>
			</tr>
			<tr>
				<td></td>
				<td>
                                    <input type="submit" name="submit" class="button" value=" <?=t('Отправить')?> ">
                                    <input type="button" name="nosubmit" class="button_gray hide" value=" <?=t('Отправить')?> ">
                                    <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                    <?=tag_helper::wait_panel() ?>
                                    <div class="success hidden mr10 mt10"><?=t('Сообщение отправлено')?></div>
				</td>
			</tr>

		</table>
	</form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#region_form').submit(function(){
            if(tinyMCE.get('body').getContent()==''){
                alert('Введiть текст');
                return false;
            }
            $('input[name="submit"]').hide().next('input').show();
            $('#wait_panel').show();
            $.post('/messages/compose',{
                <? if($region_id){ ?>
                'region_id':$('input[name="region_id"]').val(),
                <? }else{ ?>
                'city_id':$('input[name="city_id"]').val(),    
                <? } ?>
                'body':tinyMCE.get('body').getContent(),
                'type':$('input[name="type"]:checked').val(),
                'submit':1
                },function(data){
                    document.location = '/messages';
                }
            );
        });
    });
</script>

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
</script>