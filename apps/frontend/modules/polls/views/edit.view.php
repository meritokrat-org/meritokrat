<? $sub_menu = '/polls/create'; ?>
<? include 'partials/sub_menu.php' ?>

<div class="form_bg">
	<h1 class="column_head"><?=t('Новый вопрос')?></h1>
	<form id="edit_form" class="form mt10">
            <input type="hidden" name="id" value="<?=$poll['id']?>"/>
            <input type="hidden" name="why" value="<?=$why?>"/>
		<table width="100%" class="fs12">
			<tr>
				<td width="18%" class="aright"><?=t('Вопрос')?></td>
				<td><textarea style="width: 500px; height: 50px;" name="question" rel="<?=t('Введите вопрос')?>"><?=stripslashes(nl2br(htmlspecialchars($poll['question'])))?></textarea></td>
			</tr>
			<tr>
				<td class="aright"><?=t('Варианты')?></td>
				<td><?=t('Вы не можете редактировать варианты опроса')?>
                                <?/*
					<ol id="answers" class="mb5">
						<? foreach($answers as $id) { ?>
                                                <? $answer = polls_answers_peer::instance()->get_item($id) ?>
							<li class="mb5">
                                                                <input type="hidden" style="width: 435px;" name="answer_count[]" class="answer text" value="<?=$answer['count']?>"/>
								<input type="text" style="width: 435px;" name="answer[]" class="answer text" value="<?=$answer['answer']?>"/>
								<input type="button" value=" + " class="button" onclick="pollsController.addAnswer(this);" />
								<input type="button" value=" - " class="button_gray" onclick="pollsController.removeAnswer(this);" />
							</li>
						<? } ?>
					</ol>
                                 * 
                                 */?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="checkbox" name="is_multi" value="1" <?=($poll['is_multi'])?'checked="checked"':''?>/>
					<label for="is_multi"><?=t('Несколько вариантов ответа')?></label>
					<div class="mb5 quiet fs11"><?=t('Пользователь сможет выбрать несколько вариантов ответов')?></div>
					<input type="checkbox" name="is_custom" value="1" <?=($poll['is_custom'])?'checked="checked"':''?>/>
					<label for="is_custom"><?=t('Свой вариант')?></label>
					<div class="mb5 quiet fs11"><?=t('Пользователь сможет вписать свой вариант ответа')?></div>
				</td>
			</tr>
                        <? if(session::has_credential('admin')){ ?>
                        <tr>
				<td class="aright">*<?=t('Скрытый опрос?')?></td>
				<td>
					<input class="chhid" type="checkbox" name="hidden" value="1" <?=($poll['hidden'])?'checked="checked"':''?>>
                                        <label for="hidden"><?=t('Да')?></label>
				</td>
			</tr>
                        <tr>
                            <td class="aright">Без коментарів</td>
                                <td>
                                    <input value="1" <?=($poll['nocomments'])?'checked':''?> type="checkbox" name="nocomments"/>
                                    <label for="nocomments"><?=t('Да')?></label>
                                </td>
			</tr>
                        <? } ?>
                         <tr>
                            <td class="aright"><?=t('Показывать не зарегистрированным')?></td>
                                <td><input class="chpbl" value="1" <?=db_key::i()->exists($rkey)?'checked':''?> type="checkbox" name="public"/></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
					<?=tag_helper::wait_panel() ?>
					<div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
				</td>
			</tr>

		</table>
	</form>
</div>
        <script type="text/javascript">
$(document).ready(function(){
 $(".chhid").click(function(){ 
 $('input[name=public]').attr('checked', false);
 });
 
  $(".chpbl").click(function(){ 
 $('input[name=hidden]').attr('checked', false);
 });
});
        </script>