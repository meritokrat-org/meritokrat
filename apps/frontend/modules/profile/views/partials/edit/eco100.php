<form id="eco100_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') || $access ) { ?>
                <input type="hidden" name="id" value="<?=$user_data['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="eco100">
        <table width="100%" class="fs12">
                <tr>
                        <td class="aright"></td>
                        <td><b><?=t('Среднее')?> <?=t('образование')?></b></td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Страна')?></td>
                        <td>
                                <input name="midle_edu_country" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['midle_edu_country']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Город')?></td>
                        <td>
                                <input name="midle_edu_city" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['midle_edu_city']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright">№ <?=t('школы')?></td>
                        <td>
                                <input name="midle_edu_name" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['midle_edu_name']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Год поступления')?></td>
                        <td>
                                <input name="midle_edu_admission" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['midle_edu_admission']))?>" />
                        </td>
                </tr>

                <tr>
                        <td class="aright"><?=t('Год окончания')?></td>
                        <td>
                                <input name="midle_edu_graduation" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['midle_edu_graduation']))?>" />
                        </td>
                </tr>

                <!-- Midle special -->
                <tr>
                        <td class="aright"></td>
                        <td><b><?=t('Среднее')?> <?=t('специальное образование')?></b></td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Страна')?></td>
                        <td>
                                <input name="smidle_edu_country" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['smidle_edu_country']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Город')?></td>
                        <td>
                                <input name="smidle_edu_city" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['smidle_edu_city']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Название учебного заведения')?></td>
                        <td>
                                <input name="smidle_edu_name" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['smidle_edu_name']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Год поступления')?></td>
                        <td>
                                <input name="smidle_edu_admission" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['smidle_edu_admission']))?>" />
                        </td>
                </tr>

                <tr>
                        <td class="aright"><?=t('Год окончания')?></td>
                        <td>
                                <input name="smidle_edu_graduation" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['smidle_edu_graduation']))?>" />
                        </td>
                </tr>


                <!-- Major university education -->
                <tr>
                        <td class="aright"></td>
                        <td><b><?=t('Основное высшее образование')?></b></td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Страна')?></td>
                        <td>
                                <input name="major_edu_country" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['major_edu_country']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Город')?></td>
                        <td>
                                <input name="major_edu_city" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['major_edu_city']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('ВУЗ')?></td>
                        <td>
                                <input name="major_edu_name" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['major_edu_name']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Факультет')?></td>
                        <td>
                                <input name="major_edu_faculty" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['major_edu_faculty']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Кафедра')?></td>
                        <td>
                                <input name="major_edu_department" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['major_edu_department']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Форма обучения')?></td>
                        <? $forms = user_education_peer::instance()->get_forms();?>
                        <? array_unshift($forms,"&mdash;")?>
                        <td><?=tag_helper::select('major_edu_form', $forms, array('use_values' => false, 'value' => $user_education['major_edu_form']))?></td>
                </tr>

                <tr>
                        <td class="aright"><?=t('Статус')?></td>
                        <? $statuces = user_education_peer::instance()->get_statuces();?>
                        <? array_unshift($statuces,"&mdash;")?>
                        <td><?=tag_helper::select('major_edu_status', $statuces, array('use_values' => false, 'value' => $user_education['major_edu_status']))?></td>

                </tr>

                <tr>
                        <td class="aright"><?=t('Год поступления')?></td>
                        <td>
                                <input name="major_edu_admission" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['major_edu_admission']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Год окончания')?></td>
                        <td>
                                <input name="major_edu_graduation" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['major_edu_graduation']))?>" />
                        </td>
                </tr>


                <!-- additional university education -->
                <tr>
                        <td class="aright"></td>
                        <td><b><?=t('Дополнительное')?> <?=t('высшее образование')?></b></td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Страна')?></td>
                        <td>
                                <input name="additional_edu_country" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['additional_edu_country']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Город')?></td>
                        <td>
                                <input name="additional_edu_city" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['additional_edu_city']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('ВУЗ')?></td>
                        <td>
                                <input name="additional_edu_name" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['additional_edu_name']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Факультет')?></td>
                        <td>
                                <input name="additional_edu_faculty" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['additional_edu_faculty']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Кафедра')?></td>
                        <td>
                                <input name="additional_edu_department" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['additional_edu_department']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Форма обучения')?></td>
                        <td><?=tag_helper::select('additional_edu_form', $forms, array('use_values' => false, 'value' => $user_education['additional_edu_form']))?></td>
                </tr>

                <tr>
                        <td class="aright"><?=t('Статус')?></td>
                        <td><?=tag_helper::select('additional_edu_status', $statuces, array('use_values' => false, 'value' => $user_education['additional_edu_status']))?></td>
                </tr>

                <tr>
                        <td class="aright"><?=t('Год поступления')?></td>
                        <td>
                                <input name="additional_edu_admission" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['additional_edu_admission']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Год окончания')?></td>
                        <td>
                                <input name="additional_edu_graduation" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_education['additional_edu_graduation']))?>" />
                        </td>
                </tr>


                <tr>
                        <td class="aright"><?=t('Другое образование')?></td>
                        <td><textarea style="height: 75px; width: 400px;" name="another_edu"><?=stripslashes(htmlspecialchars($user_education['another_edu']))?></textarea></td>
                </tr>

                <tr>
                        <td></td>
                        <td>
                                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                <?=tag_helper::wait_panel('education_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>

        </table>
</form>