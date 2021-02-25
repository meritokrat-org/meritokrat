<div class="mt10 mr10 form_bg">
	<h1 class="column_head"><a href="/group<?=$group['id']?>"><?=t('Сообщество')?></a> &rarr; <?=t('Редактирование')?></h1>

	<div class="tab_pane_gray mb10">
                <? if ( !session::has_credential('designer') ) { ?>
		<a href="javascript:;" class="tab_menu selected" rel="common"><?=t('Основные сведения')?></a>
                <? } ?>
		<a href="javascript:;" class="tab_menu <?=(session::has_credential('designer'))?'selected':''?>" rel="photo"><?=t('Лого')?></a>
		<!--a href="javascript:;" class="tab_menu" rel="news"><?=t('Новости')?></a-->
		<? if ( ( $group['user_id'] == session::get_user_id() ) || groups_peer::instance()->is_moderator($group['id'],session::get_user_id()) ) { ?>
		<? if ( ( $group['user_id'] == session::get_user_id() ) || session::has_credential('admin') ) { ?><a href="javascript:;" class="tab_menu" rel="moderators"><?=t('Модераторы')?></a><? } ?>
			<? if ( $group['privacy'] == groups_peer::PRIVACY_PRIVATE ) { ?>
				<a href="javascript:;" class="tab_menu" rel="applicants">
					<?=t('Заявки')?>
					<span id="new_applicants" class="green fs10"><?=$applicants ? '+' . count($applicants) : ''?></span>
				</a>
			<? } ?>
		<? } ?>
		<div class="clear"></div>
	</div>

	<form id="common_form" class="form mt10 <?=(session::has_credential('designer'))?'hidden':''?>">
		<input type="hidden" name="type" value="common">
		<input type="hidden" name="id" value="<?=$group['id']?>">
		<table width="100%" class="fs12">

                        <tr>
				<td class="aright"><?=t('Категория')?> <b>*</b></td>
                                <td><?=tag_helper::select('category', groups_peer::get_categories(), array('value' => $group['category'],'id'=>'category'))?></td>
			</tr>
			<tr>
				<td class="aright"><?=t('Название сообщества')?> <b>*</b></td>
				<td><input name="title" style="width: 350px;" class="text" type="text" value="<?=stripslashes(htmlspecialchars($group['title']))?>" /></td>
			</tr>
			
			<tr id="sfera-tr" class="hidden">
				<td class="aright"><?=t('Сфера')?></td>
                                <? $group_types=groups_peer::get_types();
                                   $group_types['']='&mdash;';
                                   ksort($group_types); ?>
				<td><?=tag_helper::select('gtype', groups_peer::get_types($group_types), array('value' => $group['type']))?></td>
			</tr>
                        <tr id="level-tr" class="<?=($group['level']>0 and $group['category']==3) ? '' : 'hidden'?>">
                                <td class="aright"><?=t('Уровень')?></td>
                                <td><?=tag_helper::select('level', groups_peer::get_levels(), array('value' => $group['level']))?></td>
                        </tr>
			<!--tr>
				<td class="aright"><?=t('Территория')?></td>
				<td><?=tag_helper::select('teritory', groups_peer::get_teritories(), array('value' => $group['teritory']))?></td>
			</tr-->
			<tr>
				<td class="aright"><?=t('Приватность')?></td>
				<td>
					<? if(session::has_credential('admin')) { ?>
                                        
                                        <input type="checkbox" id="private_1" name="private" value="1" <?=$group['private'] ? 'checked' : ''?>/><label for="private_1">* Персональна спільнота</label> <span class="quiet fs11">(матеріали додають лише власник та модератори)</span>
                                        <br/>
                                        
                                        <input <?=$group['hidden']==1 ? 'checked' : ''?> type="checkbox" id="hidden_1" name="hidden" value="1"/>* Скрита спільнота <span class="quiet fs11">(не бачить ніхто крім запрошених)</span>
                                        <br/>

						<input <?=$group['show_in_main_page']==1 ? 'checked' : ''?> type="checkbox" id="show_in_main_page_1" name="show_in_main_page" value="1"/>* Вивести на головну сторінку
						<br/>
                                        <? } ?>
					<input <?=$group['privacy'] == groups_peer::PRIVACY_PUBLIC ? 'checked' : ''?> type="radio" id="privacy_<?=groups_peer::PRIVACY_PUBLIC?>" name="privacy" value="<?=groups_peer::PRIVACY_PUBLIC?>"/>
					<label for="privacy_<?=groups_peer::PRIVACY_PUBLIC?>"><?=t('Открытая')?></label>

					<input <?=$group['privacy'] == groups_peer::PRIVACY_PRIVATE ? 'checked' : ''?> type="radio" id="privacy_<?=groups_peer::PRIVACY_PRIVATE?>" name="privacy" value="<?=groups_peer::PRIVACY_PRIVATE?>"/>
					<label for="privacy_<?=groups_peer::PRIVACY_PRIVATE?>"><?=t('Закрытая')?></label>
					<div class="mt5 quiet fs11"><?=t('Закрытые сообщества будут доступны только вступившим в них учасникам.')?></div>
				</td>
			</tr>
			<!--tr>
				<td class="aright"><?=t('Web сайт')?></td>
				<td><input name="url" style="width: 350px;" class="text" type="text" value="<?=htmlspecialchars($group['url'])?>" /></td>
			</tr-->
			<tr>
				<td class="aright"><?=t('Краткое описание')?></td>
				<td><textarea name="aims" style="width: 350px;"><?=stripslashes(htmlspecialchars($group['aims']))?></textarea></td>
			</tr>
			<tr>
				<td class="aright"><?=t('Детальное описание')?></td>
				<td><textarea name="description" style="width: 350px;"><?=stripslashes(htmlspecialchars($group['description']))?></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
					<?=tag_helper::wait_panel('common_wait') ?>
					<div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
				</td>
			</tr>

		</table>
	</form>

	<form id="photo_form" action="/groups/edit?type=photo&submit=1&id=<?=$group['id']?>" class="form mt10 <?=(session::has_credential('designer'))?'':'hidden'?>" enctype="multipart/form-data">
		<div class="left acenter" style="width: 250px;">
			<?=group_helper::photo($group['id'], 'p', false, array('class' => 'border1', 'id' => 'photo'))?>
		</div>
		<table class="left fs12" style="width: 400px;">
			<tr>
				<td class="cgray fs11" colspan="2">
                                      <?=t('Вы можете загрузить фотографии в формате JPG, PNG или GIF размером')?> <b><?=t('не более 2 Мб.')?></b> <?=t('Не используйте посторонних изображений.')?><br/><br/>
                                      <?=t('При возникновении проблем попробуйте загрузить фотографию меньшего размера или')?> <a href="/messages/compose?user_id=10599"><?=t('отправьте сообщение Администрации сайта "Меритократ"')?></a><br/><br/>
                                     
                                    <br /><br /><br />
				</td>
			</tr>
			<tr>
				<td class="aright" width="150"><?=t('Выберите файл')?></td>
				<td><input type="file" name="file" rel="<?=t('Картинка неверная либо слишком большая')?>" /></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
					<?=tag_helper::wait_panel('photo_wait') ?>
					<div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
				</td>
			</tr>
		</table>
		<div class="clear"></div>
	</form>

	<!--form id="news_form" class="hidden form mt10 m10">
		<input type="hidden" name="type" value="news">
		<input type="hidden" name="id" value="<?=$group['id']?>">

		<div class="m10 fs11 quiet"><?=t('Для редактирования новостей перейдите')?> <a href="/groups/news?id=<?=$group['id']?>"><?=t('на эту страницу')?></a></div>

		<table width="100%" class="fs12" id="add_news">
			<tr>
				<td class="aright"><?=t('Текст новости')?></td>
				<td><textarea rel="<?=t('Введите текст новости')?>" name="text" style="width: 500px; height: 200px;"></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?=t('Добавить')?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
					<?=tag_helper::wait_panel('news_wait') ?>
					<div class="success hidden mr10 mt10"><?=t('Новость добавлена')?></div>
				</td>
			</tr>
		</table>
	</form-->

	<? if ( ( $group['user_id'] == session::get_user_id() ) || session::has_credential('admin') ) { ?>
		<div id="moderators_form" class="hidden form p10">

			<div class="fs12">
				<div class="bold mb5"><?=t('Список модераторов')?></div>
				<? if ( !$moderators ) { ?>
					<div id="no_moderators" class="quiet fs11 mb10"><?=t('Список пуст')?></div>
				<? } ?>
				<div id="moderators">
					<? foreach ( $moderators as $moderator_id ) { include 'partials/moderator.php'; } ?>
				</div>
			</div>


			<div class="fs12">
				<div class="mb5">
					<b><?=t('Добавить модератора')?></b>
					<span class="quiet fs11">(<?=t('Укажите ссылку на анкету человека, либо введите его')?> ID)</span><br />
				</div>
				<input type="text" id="new_moderator" class="text">
				<input type="button" class="button" onclick="groupsController.addModerator();" value=" <?=t('Добавить')?> " />
			</div>

		</div>

	<? } ?>
	<? if ( ( $group['user_id'] == session::get_user_id() ) || groups_peer::instance()->is_moderator($group['id'], session::get_user_id()) ) { ?>
		<div id="applicants_form" class="hidden form p10">
			<div class="fs12">
				<div class="bold mb5"><?=t('Список заявок на вступление')?></div>
				<? if ( !$applicants ) { ?>
					<div id="no_applicants" class="quiet fs11 mb10"><?=t('Список пуст')?></div>
				<? } else { ?>
				<div id="applicants">
					<? foreach ( $applicants as $applicant_id ) { include 'partials/applicant.php'; } ?>
				</div>
				<? } ?>
			</div>
			<br />
		</div>
        <? } ?>

</div>
<script type="text/javascript">
jQuery(document).ready(function($){
      /*$.datepicker.setDefaults($.extend(
      $.datepicker.regional["uk"])
    );*/
 
$('#category').change(function() {
			if ($(this).val()==2) {
                            $("#sfera-tr").show();
                            $("#level-tr").hide();
                            $("#hidden_1").removeAttr('checked');
                        }
			else if ($(this).val()==3) {
                            $("#sfera-tr").hide();
                            $("#level-tr").show();
                            $("#hidden_1").removeAttr('checked');
                        }
                        else {
                            $("#sfera-tr").hide();
                            $("#level-tr").hide();
                            $("#hidden_1").removeAttr('checked');
                                if ($(this).val()==4) {
                                $("#hidden_1").attr('checked','checked');
                                $("#privacy_2").attr('checked','checked');
                                $("#privacy_1").removeAttr('checked');
                            }
                        }
		});
 });
 </script>