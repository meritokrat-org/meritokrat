<form id="peoples_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') ) { ?>
                <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="people">
        <table width="100%" class="fs12">
        <? /*
        <tr>
                <td class="aright"><?=t('Проинформировал')?></td>
                <td>
                    <a href="#input" class="bold"><?=intval($user_desktop['information_people_count'])?></a>
                    <input type="text" name="information_people_count" value="<?=intval($user_desktop['information_people_count'])?>" class="hide linkval" style="width:50px;" />
                    <input type="button" value="OK" class="fs11 button hide linkval" />
                    <span>+</span>
                    <input type="text" value="0" class="addval" />
                    <input type="button" value="<?=t('Додати')?>" class="fs11 button addval" />
                </td>
        </tr>
         */ ?>
        <? if ( session::get_user_id()==29) { ?>
                <tr>
                        <td class="aright"><?=t('Привлек в M.org')?></td>
                        <td>
                                <input name="people_attracted" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_desktop['people_attracted']))?>" />
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Рекомендовал в M.org')?></td>
                        <td>
                                <input name="people_recommended" rel="<?=t('')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($user_desktop['people_recommended']))?>" />
                        </td>
                </tr>
       <? } ?>
                <tr>
                        <td></td>
                        <td>
                                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?>" id="submit">
                                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                <?=tag_helper::wait_panel('peoples_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>

        </table>
</form>