<form id="bio_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') ) { ?>
                <input type="hidden" name="id" value="<?=$user_data['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="bio">
        <table width="100%" class="fs12">

                <tr>
                        <td class="aright"><?=t('Дата и место рождения, семья')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="birth_family"><?=stripslashes(htmlspecialchars($user_bio['birth_family']))?></textarea>
                                <!--p class="quiet fs10"><?=t('Вводите через запятую: спорт, политика, власть')?></p-->
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Основное образование')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="major_education"><?=stripslashes(htmlspecialchars($user_bio['major_education']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Трудовая деятельность')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="work"><?=stripslashes(htmlspecialchars($user_bio['work']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Общественная деятельность')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="society"><?=stripslashes(htmlspecialchars($user_bio['society']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Политическая деятельность')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="politika"><?=stripslashes(htmlspecialchars($user_bio['politika']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Научная деятельность')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="science"><?=stripslashes(htmlspecialchars($user_bio['science']))?></textarea>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Семинары, тренинги, другое доп. образование')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="additional_education"><?=stripslashes(htmlspecialchars($user_bio['additional_education']))?></textarea>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Важные успехи и достижения')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="progress"><?=stripslashes(htmlspecialchars($user_bio['progress']))?></textarea>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Другая информация')?>:</td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="other"><?=stripslashes(htmlspecialchars($user_bio['other']))?></textarea>
                        </td>
                </tr>
                <tr>
                        <td></td>
                        <td>
                                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                <?=tag_helper::wait_panel('bio_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>
        </table>
</form>