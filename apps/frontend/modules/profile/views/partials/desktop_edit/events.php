<form id="events_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') ) { ?>
                <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="events">
        <table width="100%" class="fs12">
            <tr><td class="cgray fs11" colspan="2">
                    <div style="padding-left: 10px;padding-right: 50px;">
                    <?=t('В этом разделе указывайте все общественно-политические и учебные мероприятия, которые Вы организовали или посетили, даже если они не касаются непосредственно деятельности МПУ. Например, общественный мероприятие - Конференция "Регулирование политических партий в Украине: современное состояние и направления реформ", акция общественного активизма - Уборка Парке Партизанской Славы, учебные мероприятия - Вебинар по лоббированию, Мастер-класс для общественных активистов и т.п.')?></div>
                        </td>

            </tr>
            <?
            $eventcount=0;
            if (is_array($user_desktop_event)) {
            foreach ($user_desktop_event as $event) { ?>
                <tr>
                        <td class="aright"><?=t('Участие')?></td>
                        <td>
                            <input type="radio" name="event_status[<?=$eventcount?>]" value="0" class="evnt" <?=$event['part']==0 ? 'checked' : ''?> /><?=t('Организовал')?>
                            <input type="radio" name="event_status[<?=$eventcount?>]" value="2" class="evnt" <?=$event['part']==2 ? 'checked' : ''?> /><?=t('Выступил')?>
                            <input type="radio" name="event_status[<?=$eventcount?>]" value="1" class="evnt" <?=$event['part']==1 ? 'checked' : ''?> /><?=t('Посетил')?>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Название')?></td>
                        <td>
                                <input name="event_title[]" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($event['title']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Дата')?></td>
                        <td>
                                <? if($event['event_date']){
                                   $dparts = explode('/',$event['event_date']);
                                   $event['event_date'] = mktime(0, 0, 0, $dparts[1], $dparts[2], $dparts[0]);
                                }?>
                                <?=user_helper::datefields('event_date',intval($event['event_date']),true)?>
                        </td>
                </tr>
                <tr <?if($event['part']==1) { echo ' class="hidden member_cnt'.$eventcount.'"'; } else echo ' class="member_cnt'.$eventcount.'"'?>>
                        <td class="aright"><?=t('Количество участников')?></td>
                        <td>
                            <input type="text" class="text" name="member_count[]" value="<?=$event['member_count']?>"></input>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Комментарий')?></td>
                        <td><textarea style="height: 75px; width: 400px;" name="event_description[]"><?=stripslashes(htmlspecialchars($event['description']))?></textarea></td>
                </tr>
        <?  $eventcount++;  } } ?>
                <tr id="table_events">
                    <td colspan="2">
                        <table width="100%" class="fs12" id="table_event">
                            <tr>
                                    <td style="width: 120px;" class="aright"><?=t('Участие')?></td>
                                    <td>
                                        <input type="radio" name="event_status[<?=$eventcount?>]" value="0" class="evnt" /><?=t('Организовал')?>
                                        <input type="radio" name="event_status[<?=$eventcount?>]" value="2" class="evnt" /><?=t('Выступил')?>
                                        <input type="radio" name="event_status[<?=$eventcount?>]" value="1" class="evnt" /><?=t('Посетил')?>
                                    </td>
                            </tr>
                            <tr id="first_tr">
                                    <td class="aright"><?=t('Название')?></td>
                                    <td>
                                            <input name="event_title[]" rel="<?=t('')?>" class="text" type="text" value="" disabled="disabled"/>
                                    </td>
                            </tr>
                            <tr>
                                    <td class="aright"><?=t('Дата')?></td>
                                    <td>
                                        <?=user_helper::datefields('event_date',0,true,array('disabled'=>'disabled'))?>
                                    </td>
                            </tr>
                            <tr class="hidden member_cnt<?=$eventcount?>">
                                    <td class="aright" style="width: 120px;"><?=t('Количество участников')?></td>
                                    <td>
                                        <input type="text" class="text" name="member_count[]">
                                    </td>
                            </tr>
                            <tr>
                                    <td class="aright"><?=t('Комментарий')?></td>
                                    <td><textarea style="height: 75px; width: 400px;" name="event_description[]" disabled="disabled"></textarea></td>
                            </tr>
                    </table>
                    </td>
               </tr>
               <tr class="mb15"><td></td>
                    <td>
                        <a href="javascript:;" class="button add_event" id="add_event">+ <?=t('Добавить мероприятие')?></a>
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
                                <?=tag_helper::wait_panel('events_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>

        </table>
</form>
<script>
       $('.evnt').change(function(){
          evCnt = $(this).attr('name');
          evCnt = evCnt.replace('event_status[','').replace(']','');
          if($(this).val()==2 || $(this).val()==0)
              $('.member_cnt'+evCnt).show();
          else
              $('.member_cnt'+evCnt).hide();
       }); 

</script>