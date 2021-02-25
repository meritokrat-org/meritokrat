<div class="form_bg">

	<h1 class="column_head mt10"><?= $doc ? t('Редактирование') : t('Новый документ') ?></h1>

	<form id="edit_form" class="form">
		<? if ( $doc ) { ?>
			<input type="hidden" name="id" value="<?=$doc['id']?>" />
		<? } ?>
		<table width="100%" class="fs12 mt15 ml10">

			<tr>
				<td>
                                    <b><?=t('Alias')?></b><br/> 
                                    <input name="alias" rel="<?=t('Введите alias')?>" style="width:700px;" class="text" type="text" value="<?=$doc['alias']?>" />
                                </td>
			</tr>
                        <tr>
				<td>
                                    <b><?=t('Заголовок')?></b><br/>
                                    <input name="title" rel="<?=t('Введите заголовок')?>" style="width:700px;" class="text" type="text" value="<?=$doc['title']?>" />
                                </td>
			</tr>

			<tr>
                                <td height="350">
                                <b><?=t('Текст')?></b><br/>
                                <textarea rel="<?=t('Введите текст')?>" name="text" style="height:450px;width:705px;"><?=$doc['text']?></textarea>
                                </td>
			</tr>

			<tr>
				<td>
					<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                        <?=tag_helper::wait_panel('wait_panel') ?>
					<div class="success hidden mr10 mt10"><?=t('Запись сохранена')?></div>
				</td>
			</tr>

		</table>

	</form>


</div>

<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
     <? if (session::has_credential('admin')) { ?>file_browser_callback : "tinyBrowser",<? } ?>
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "text",
	theme : "advanced",
	skin : "o2k7",
        plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras, insertdatetime,contextmenu,paste,directionality,visualchars,xhtmlxtras,table,media,youtube",

	theme_advanced_buttons1 : "bold,italic,underline,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,forecolor,|,bullist,numlist,|,link,unlink,image,insertimage,|,code",
        theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
	theme_advanced_buttons5 : "",

	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",

	content_css: '/static/css/typography.css',
        document_base_url : "https://meritokrat.org/",
        remove_script_host : false,
        convert_urls : false,
        height: 350

});
$('input[type="submit"]').click(function(){
        $('textarea[name="text"]').val(tinyMCE.get('text').getContent());
    });
</script>