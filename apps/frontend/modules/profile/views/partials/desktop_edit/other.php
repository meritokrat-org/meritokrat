<form id="other_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') ) { ?>
                <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="other">
        <table width="100%" class="fs12">
           <tr>
                <td class="aright"><?=t('Другое')?></td>
                <td><textarea style="height: 150px; width: 400px;" name="other"><?=$user_desktop['other']?></textarea></td>
           </tr>
           <tr class="mt15">
                    <td></td>
                    <td>
                            <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                            <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                            <?=tag_helper::wait_panel('other_wait') ?>
                            <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                    </td>
            </tr>

        </table>
</form>