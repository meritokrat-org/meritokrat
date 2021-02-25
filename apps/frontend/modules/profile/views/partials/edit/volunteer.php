<form id="volonteer_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin')  || $access) { ?>
                <input type="hidden" name="id" value="<?=$user_data['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="volunteer">
        <table width="100%" class="fs12">
            <tr>
                    <td></td>
                    <td>
                            <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                            <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                            <?=tag_helper::wait_panel('work_wait') ?>
                            <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                    </td>
            </tr>
        </table>
</form>