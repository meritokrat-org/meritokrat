	<form class="form_bg mr10 fs12 mb10" action="/ppo/proposal_message_edit">
		<h3 class="column_head_small"><?=t('Редактировать сообщение')?></h3>

		<div class="ml10 mr10">
			<input type="hidden" name="id" value="<?=$message['id']?>">
			<div class="mb10">
				<textarea style="width: 99%; height: <?=request::get('tinymce')==1 ? '450' : '200'?>px;" name="text" rel="<?=t('Введите текст сообщения')?>"><?=stripslashes($message['text'])?></textarea>
			</div>

			<input name="submit" type="submit" value=" <?=t('Сохранить')?> " class="button">
		</div>
<? if (request::get('tinymce')==1) { ?>
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
	plugins : "insertdatetime,contextmenu,paste,directionality,visualchars,xhtmlxtras,table,media,youtube",

	theme_advanced_buttons1 : "bold,italic,underline,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,|,bullist,numlist,|,link,image,youtube",
	theme_advanced_buttons2 : "tablecontrols",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",

	content_css: '/static/css/typography.css'
});
</script>
<? } ?>
	</form>