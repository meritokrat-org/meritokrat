<? /*	<h1 class="column_head ml10 mt10"><?=t('Стена')?></h1>
	<div class="aright fs11 pt5">
		<?// if ( session::is_authenticated() && ( $user['id'] != session::get_user_id() ) ) { ?>
			<a class="dotted" href="javascript:;" onclick="$('#ask_form').show(50);"><?=t('Оставить сообщение')?></a>
		<?// } ?>
		<!--a class="ml10" href="/profile/questions?id=<?=$user['id']?>"><?=t('Все вопросы')?> &rarr;</a-->
	</div>

<? if ( session::is_authenticated() ) { ?>
	<form style="background: #F7F7F7;" class="fs12 p10 mb10 ml10 hidden" action="/profile/ask" id="ask_form">
		<input type="hidden" name="profile_id" value="<?=$user['id']?>">
		<div class="mt5">
			<textarea rel="<?=t('Введите текст сообщения')?>" name="text" style="width: 98%; height: 50px;"></textarea>
			<div class="mt5">
				<input name="submit" type="submit" value=" <?=t('Отправить')?> " class="button">
				<?=tag_helper::wait_panel('ask_wait')?>
				<a href="javascript:;" class="dotted fs11" onclick="$('#ask_form').hide();"><?=t('Отмена')?></a>
			</div>
		</div>
	</form>

	<div class="mt10 ml10 acenter hidden success" id="quesiton_success"><?=t('Спасибо, Ваше сообщение добавлено')?></div>

<? } ?>

<div id="questions">
	<? if ( $questions ) { ?>
		<? foreach ( $questions as $id ) { include 'question.php'; } ?>
	<? } else { ?>
		<div id="no_questions" class="acenter fs12 p5 ml10"><?=t('Сообщений нет')?></div>
	<? } ?>
</div>
 */
?>