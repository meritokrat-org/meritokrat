<form id="educations_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') ) { ?>
                <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="educations">
        <table width="100%" class="fs12">
            <tr><td class="cgray fs11" colspan="2">
                    <div style="padding-left: 10px;padding-right: 50px;">
                    <?=t('В этом разделе указывайте все учебные занятия МПУ, которые Вы организовали или посетили. Например, Тренинг для активистов МПУ и т.п.')?></div>
                        </td>

            </tr>
            <?
            $educationcount=0;
            if (is_array($user_desktop_education)) {
            foreach ($user_desktop_education as $event) { ?>
                <tr>
                        <td class="aright"><?=t('Участие')?></td>
                        <td>
                            <input type="radio" name="education_status[<?=$educationcount?>]" value="0" class="evnt" <?=$event['part']==0 ? 'checked' : ''?> /><?=t('Провел')?>
                            <input type="radio" name="education_status[<?=$educationcount?>]" value="1" class="evnt" <?=$event['part']==1 ? 'checked' : ''?> /><?=t('Принял участие')?>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Название')?></td>
                        <td>
                                <input name="education_title[]" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($event['title']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Дата')?></td>
                        <td>
                                <? if($event['education_date']){
                                   $dparts = explode('/',$event['education_date']);
                                   $event['education_date'] = mktime(0, 0, 0, $dparts[1], $dparts[2], $dparts[0]);
                                }?>
                                <?=user_helper::datefields('education_date',intval($event['education_date']),true)?>
                        </td>
                </tr>
                <tr><td></td></tr>
                <tr>
                        <td class="aright"><?=t('Комментарий')?></td>
                        <td><textarea style="height: 75px; width: 400px;" name="education_description[]"><?=stripslashes(htmlspecialchars($event['description']))?></textarea></td>
                </tr>
        <?  $educationcount++;  } } ?>
                <tr id="table_educations">
                    <td colspan="2">
                        <table width="100%" class="fs12" id="table_education">
                            <tr>
                                    <td class="aright"><?=t('Участие')?></td>
                                    <td>
                                        <input type="radio" name="education_status[<?=$educationcount?>]" value="0" class="evnt" /><?=t('Провел')?>
                                        <input type="radio" name="education_status[<?=$educationcount?>]" value="1" class="evnt" /><?=t('Принял участие')?>
                                    </td>
                            </tr>
                            <tr id="first_tr">
                                    <td class="aright"><?=t('Название')?></td>
                                    <td>
                                            <input name="education_title[]" rel="<?=t('')?>" class="text" type="text" value="" disabled="disabled"/>
                                    </td>
                            </tr>
                            <tr>
                                    <td class="aright"><?=t('Дата')?></td>
                                    <td>
                                            <?=user_helper::datefields('education_date',0,true,array('disabled'=>'disabled'))?>
                                            <!--input id="education_date_<?=$educationcount?>" name="education_date_<?=$educationcount?>" rel="<?=t('Заполните дату корректно')?>" type="text" type="text" style="width:153px;" value="" disabled="disabled" /-->
                                    </td>
                            </tr>
                            <tr><td></td></tr>
                            <tr>
                                    <td class="aright"><?=t('Комментарий')?></td>
                                    <td><textarea style="height: 75px; width: 400px;" name="education_description[]" disabled="disabled"></textarea></td>
                            </tr>
                    </table>
                    </td>
               </tr>
               <tr class="mb15"><td></td>
                    <td>
                        <a href="javascript:;" class="button add_education" id="add_education">+ <?=t('Добавить')?></a>
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
                                <?=tag_helper::wait_panel('educations_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>

        </table>
</form>