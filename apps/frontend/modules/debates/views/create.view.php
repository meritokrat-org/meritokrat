<? $sub_menu = '/debates/create'; ?>
<? include 'partials/sub_menu.php' ?>

<? if ( !$allow_create ) { ?>
	<div class="screen_message acenter"><?=t('Вы сможете начать дебаты, когда Ваш опыт достигнет')?> 35</div>
<? } else { ?>
	<div class="form_bg">
		<h1 class="column_head"><?=t('Новые дебаты')?></h1>
		<form id="add_form" class="form mt10">
			<table width="100%" class="fs12">
				<tr>
					<td class="aright" width="18%"><?=t('Предмет дебатов')?></td>
					<td><textarea rel="<?=t('Введите текст мнения')?>" name="text" style="width: 500px; height:100px;"><?=htmlspecialchars($post_data['body'])?></textarea></td>
				</tr>
				<tr>
					<td class="aright"><?=t('Метки')?></td>
					<td>
						<input name="tags" style="width:500px;" class="text" type="text" value="<?=htmlspecialchars($post_data['tags_text'])?>" />
						<div class="fs11 quiet"><?=t('Метки вводятся через запятую, например: бизнес, банки, капитализация, индексы')?></div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
						<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
						<?=tag_helper::wait_panel() ?>
						<div class="success hidden mr10 mt10"><?=t('Мнение добавлено')?></div>
					</td>
				</tr>

			</table>
		</form>
	</div>
<? } ?>