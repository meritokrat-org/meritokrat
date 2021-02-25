<form id="meetings_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') ) { ?>
                <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="meetings">
        <table width="100%" class="fs12">
            <tr><td class="cgray fs11" colspan="2">
                    <div style="padding-left: 10px;padding-right: 50px;">
                    <?=t('В этом разделе указывайте все рабочие, информационные и другие встречи, мероприятия МПУ и ее местных ячеек (кроме учебных), загруженных или организовали. Например, участие во Второй открытом совещании Оргкомитета МПУ, организация встречи активистов города Львова и т.п.')?></div>
                        </td>

            </tr>
            <? $meetingcount=0;
            if (is_array($user_desktop_meeting)) {
            foreach ($user_desktop_meeting as $meeting) { ?>
                <tr>
                    <td class="aright"><?=t('Участие')?></td>
                    <td>
                        <input type="radio" name="status[<?=$meetingcount?>]" value="0" class="evnt"  <?=$meeting['part']==0 ? 'checked' : ''?>/><?=t('Организовал')?>
                        <input type="radio" name="status[<?=$meetingcount?>]" value="1" class="evnt"  <?=$meeting['part']==1 ? 'checked' : ''?>/><?=t('Посетил')?>
                    </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Название')?></td>
                        <td>
                                <input name="title[]" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($meeting['title']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Дата')?></td>
                        <td>
                                <? if($meeting['meeting_date']){
                                   $dparts = explode('/',$meeting['meeting_date']);
                                   $meeting['meeting_date'] = mktime(0, 0, 0, $dparts[1], $dparts[2], $dparts[0]);
                                }?>
                                <?=user_helper::datefields('date',intval($meeting['meeting_date']),true)?>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Комментарий')?></td>
                        <td><textarea style="height: 75px; width: 400px;" name="description[]"><?=stripslashes(htmlspecialchars($meeting['description']))?></textarea></td>
                </tr>
        <? $meetingcount++;  } } ?>
                <tr id="table_meetings">
                    <td colspan="2">
                        <table width="100%" class="fs12" id="table_meeting">
                            <tr>
                                <td class="aright"><?=t('Участие')?></td>
                                <td>
                                    <input type="radio" name="status[<?=$meetingcount?>]" value="0" class="evnt" /><?=t('Организовал')?>
                                    <input type="radio" name="status[<?=$meetingcount?>]" value="1" class="evnt" /><?=t('Посетил')?>
                                </td>
                            </tr>
                            <tr id="first_tr">
                                    <td class="aright"><?=t('Название')?></td>
                                    <td>
                                            <input name="title[]" rel="<?=t('')?>" class="text" type="text" value="" disabled="disabled"/>
                                    </td>
                            </tr>
                            <tr>
                                    <td class="aright"><?=t('Дата')?></td>
                                    <td>
                                            <?=user_helper::datefields('date',0,true,array('disabled'=>'disabled'))?>
                                    </td>
                            </tr>
                            <tr>
                                    <td class="aright"><?=t('Комментарий')?></td>
                                    <td><textarea style="height: 75px; width: 400px;" name="description[]" disabled="disabled"></textarea></td>
                            </tr>
                    </table>

                    </td>
               </tr>

               <tr class="mb15"><td></td>
                    <td>
                        <a href="javascript:;" class="button add_meeting" id="add_meeting">+ <?=t('Добавить событие')?></a>
                        &nbsp;<!--input type="button remove_meeting" value="-" class="button" id=""--></td>
                </tr>
               <tr class="mt15 mb15">
                        <td></td>
                        <td>
                        </td>
                </tr>
               <tr class="mt15">
                        <td></td>
                        <td>
                                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                <?=tag_helper::wait_panel('meetings_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>

        </table>
</form>