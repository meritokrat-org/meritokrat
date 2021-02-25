<form id="interests_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') ) { ?>
                <input type="hidden" name="id" value="<?=$user_data['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="interests">
        <table width="100%" class="fs12">

                <tr>
                        <td class="aright"><?=t('Интересы, увлечения')?></td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="interests"><?=stripslashes(htmlspecialchars($user_data['interests']))?></textarea>
                                <p class="quiet fs10"><?=t('Вводите через запятую: спорт, политика, власть')?></p>
                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Любимые книги')?></td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="books"><?=stripslashes(htmlspecialchars($user_data['books']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Любимые фильмы')?></td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="films"><?=stripslashes(htmlspecialchars($user_data['films']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Любимые интернет-порталы')?></td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="sites"><?=stripslashes(htmlspecialchars($user_data['sites']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Любимая музыка')?></td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="music"><?=stripslashes(htmlspecialchars($user_data['music']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td class="aright"><?=t('Досуг')?></td>
                        <td>
                                <textarea style="height: 75px; width: 400px;" name="leisure"><?=stripslashes(htmlspecialchars($user_data['leisure']))?></textarea>

                        </td>
                </tr>
                <tr>
                        <td></td>
                        <td>
                                <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                <?=tag_helper::wait_panel('interests_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>
        </table>
</form>