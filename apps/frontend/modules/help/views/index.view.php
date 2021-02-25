<style>
    p{margin-bottom:5px;}
    li{margin-bottom:5px;}
</style>

<h1 class="column_head mt10 mr10">
    <?=(session::get('language')!='ru')?stripslashes(htmlspecialchars($data['title_ua'])):stripslashes(htmlspecialchars($data['title_ru']))?>
</h1>
<div class="mt10 mr10 ml15">
    <?=(session::get('language')!='ru')?stripslashes($data['text_ua']):stripslashes($data['text_ru'])?>
</div>
<?if($data['share']){ ?>
<div class="social-networks left mb10">
	<div class="left" style="margin-right: 30px; width: 100px;">
		<script type="text/javascript"><!--
			document.write(VK.Share.button(false,{type: "round", text: '<?=t("Поделиться")?>'}));
		--></script>
	</div>
	<div class="left" style="margin-right: 30px;">
		<a name="fb_share" type="button_count" share_url="http://<?=$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]?>" href="http://www.facebook.com/sharer.php"><?=t('Поделиться')?></a>
		<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
	</div>
	<div class="left" style="margin-right: 30px; width: 100px;">
		<a href="https://twitter.com/share" class="twitter-share-button" data-lang="ru"><?=t('Твитнуть')?></a>
	</div>
	<div class="left" style="margin-right: 30px; width: 50px;">
		<g:plusone size="medium"></g:plusone>
	</div>
	<div class="clear"></div>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>
<? } ?>
<div>
<? if (session::has_credential('admin')) { ?>
    * <a href="/admin/helpedit?id=<?=$data['id']?>"><?=t('Редактировать')?></a>
    &nbsp;&nbsp;|&nbsp;&nbsp;
    * <a href="/admin/helpedit"><?=t('Добавить новую страницу')?></a>
    <? } ?>
</div>
<style>
    .social-networks table td, th {
	padding: 0px;
    }
</style>