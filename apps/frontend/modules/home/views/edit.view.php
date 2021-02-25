<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
        <br/>&nbsp;&nbsp;<b><?=t('Редактирование страницы')?></b><br/>
	<form method="post" class="fs11">
           Текст: <br/>
			<textarea name="body" style="width: 555px; height: 750px;"><?=$body?></textarea>
			<br /><br />

		<input type="submit" name="submit" class="button" value="<?=t('Сохранить')?>">

	</form>

<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "body",
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
</div>