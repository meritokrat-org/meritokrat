<h1 class="mt10 mr10 column_head">
	<a href="/group<?=$group['id']?>"><?=htmlspecialchars($group['title'])?></a> &rarr; <?=t('Позиция МПУ')?>
</h1>

<? if ( session::has_credential('admin')) { ?>
<form class="form_bg mr10 fs12 p10 mb10" action="/groups/position_create?id=<?=$group['id']?>" method="post" id="topic_form">
    <? if ( !request::get_int('add')) { ?>
		<div class="left">
			<a class="filter <?=!$filter ? ' filter_selected' : ''?>" href="position?id=<?=$group['id']?>"><?=t('Новые Темы')?></a>
			<a class="filter <?=$filter == 'hot' ? ' filter_selected' : ''?>" href="position?id=<?=$group['id']?>&filter=hot"><?=t('Самые обсуждаемые')?></a>
		</div>
		<a href="javascript:;" onclick="$('#create_topic').show(50);" class="mt5 right dotted"><?=t('Добавить')?></a>
		<div class="clear"></div> <? } ?>
                <div class="<?=request::get_int('add') ? '' : 'hidden'?>" id="create_topic">
			<input type="hidden" name="id" value="<?=$group['id']?>">
			<table width="100%" class="mt10">
				<tr>
					<td width="18%" class="aright"><?=t('Название')?></td>
					<td><input type="text" class="text" style="width: 500px;" name="topic" rel="<?=t('Введите название темы')?>" /></td>
				</tr>
				<tr>
					<td width="18%" class="aright"><?=t('Текст')?></td>
					<td><textarea style="width: 500px; height: 50px;" id="text" name="text" rel="<?=t('Введите текст')?>"></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input name="submit" type="submit" value=" <?=t('Создать')?> " class="button">
						<input type="button" class="button_gray" value=" <?=t('Отмена')?> " onclick="$('#create_topic').hide();" />
						<?=tag_helper::wait_panel()?>
					</td>
				</tr>
			</table>
		</div>
<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
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
	</form>
<? } else { ?>
	<div class="mr10"><?=user_helper::login_require( t('Недостаточно прав') )?></div><br />
<? } ?>

<? if ( $list and !request::get_int('add')) { ?>
	<? foreach ( $list as $id ) { include 'partials/position.php'; } ?>
	<div class="bottom_line_d mb10 mr10"></div>
	<div class="right pager mr10"><?=pager_helper::get_full($pager)?></div>
<? } elseif(!request::get_int('add')) { ?>
	<div id="no_questions" class="acenter fs12 p5 ml10">
		<?=t('')?>
	</div>
<? } ?>
