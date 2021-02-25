<h1 class="mt10 mr10 column_head">
	<?=user_helper::full_name($user['id'])?> &rarr; <?=t('Стена')?>
</h1>

<? if ( session::is_authenticated() ) { ?>
	<? if ( session::get_user_id() != $user['id'] ) { ?>
	<form style="background: #F7F7F7;" class="mr10 fs12 p10 mb10" action="/profile/ask" id="ask_form">
		<b><?=t('Оставить сообщение')?></b>
		<!--div class="mt5 mb10 fs11 quiet"><?=t('Принимаются только конструктивные вопросы, все другие будут удаляться')?></div-->

		<input type="hidden" name="profile_id" value="<?=$user['id']?>">
		<div class="mt5">
			<textarea rel="<?=t('Введите текст')?>" name="text" style="width: 98%; height: 50px;"></textarea>
			<div class="mt5">
				<input name="submit" type="submit" value=" <?=t('Отправить')?> " class="button">
				<?=tag_helper::wait_panel('ask_wait')?>
			</div>
		</div>
	</form>
	<div class="mr10 mb10 acenter hidden success" id="question_success"><?=t('Спасибо, Ваше сообщение добавлено')?></div>
	<? } ?>
<? } else { ?>
	<?=user_helper::login_require( t('Войдите в систему, что-бы задавать вопросы') )?><br />
<? } ?>

<a class="filter <?=!$filter ? ' filter_selected' : ''?>" href="questions?id=<?=$user['id']?>"><?=t('Популярные')?></a>
<a class="filter <?=$filter == 'new' ? ' filter_selected' : ''?>" href="questions?id=<?=$user['id']?>&filter=new"><?=t('Новые')?></a>
<!--a class="filter <?=$filter == 'no_reply' ? ' filter_selected' : ''?>" href="questions?id=<?=$user['id']?>&filter=no_reply"><?=t('Без ответа')?></a-->
<br /><br />

<div id="questions">
	<? if ( $questions ) { ?>
		<? foreach ( $questions as $id ) { include 'partials/question_advanced.php'; } ?>
		<div class="bottom_line_d mb10 mr10" style="margin-left: 60px;"></div>
		<div class="right pager mr10"><?=pager_helper::get_full($pager)?></div>
	<? } else { ?>
		<div id="no_questions" class="acenter fs12 p5 ml10"><?=t('Сообщений нет')?></div>
	<? } ?>
</div>

<form id="question_reply_form" class="hidden" action="/profile/question_reply">
	<input type="hidden" name="id"/>
	<textarea rel="Напишите текст" style="width: 99%; height: 50px;" name="reply"></textarea>
	<input type="submit" name="submit" class="mt5 mb5 button" value=" <?=t('Отправить')?> " />
	<?=tag_helper::wait_panel('reply_wait')?>
	<input type="button" class="button_gray" value=" <?=t('Отмена')?> " onclick="$('#question_reply_form').hide();">
</form>