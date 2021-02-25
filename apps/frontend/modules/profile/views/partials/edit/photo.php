<?

header("Cache-Control: no-store, no-cache,  must-revalidate");
header("Expires: ".date("r"));
?>
<style>

    .imgareaselect-border1 {
        background: url(border-anim-v.gif) repeat-y left top;
    }

    .imgareaselect-border2 {
        background: url(border-anim-h.gif) repeat-x left top;
    }

    .imgareaselect-border3 {
        background: url(border-anim-v.gif) repeat-y right top;
    }

    .imgareaselect-border4 {
        background: url(border-anim-h.gif) repeat-x left bottom;
    }

    .imgareaselect-border1, .imgareaselect-border2,
    .imgareaselect-border3, .imgareaselect-border4 {
        opacity: 0.5;
        filter: alpha(opacity=50);
    }

    .imgareaselect-handle {
        background-color: #fff;
        border: solid 1px #000;
        opacity: 0.5;
        filter: alpha(opacity=50);
    }

    .imgareaselect-outer {
        background-color: #000;
        opacity: 0.5;
        filter: alpha(opacity=50);
    }

    .imgareaselect-selection {
    }
</style>
<?php
if (db_key::i()->exists('crop_coord_user_'.$user['id'])) {
    $crop_data = db_key::i()->get('crop_coord_user_'.$user['id']);
    $crop_coords = explode("-", $crop_data);
}

load::view_helper('image'); ?>
<script>
    window.crop_x = <?if (isset($crop_data)) {
        echo $crop_coords[0];
    } else {
        echo '-1';
    }?>;
    window.crop_y = <?if (isset($crop_data)) {
        echo $crop_coords[1];
    } else {
        echo '-1';
    }?>;
    window.crop_w = <?if (isset($crop_data)) {
        echo $crop_coords[2];
    } else {
        echo '-1';
    }?>;
    window.crop_h = <?if (isset($crop_data)) {
        echo $crop_coords[3];
    } else {
        echo '-1';
    }?>;
</script>

<form id="photo_form"
      action="/profile/edit?type=photo&submit=1<?= session::has_credential(
          'admin'
      ) ? '&id='.$user_data['user_id'] : '' ?>"
      class="form_bg form mt10 <?= (!$is_designer) ? 'hidden' : ''; ?>" enctype="multipart/form-data">

    <? if (session::has_credential('admin') || $user_data['id'] == session::get_user_id()) { ?>
        <input type="hidden" name="id" value="<?= $user_data['user_id'] ?>">
    <? } ?>
    <div class="row">
        <div class="col-4 acenter">
            <?= user_helper::photo(
                $user['id'],
                'p',
                array(
                    'class' => 'border1',
                    'id' => 'photo_1',
                    'style' => 'background-color: white;', /*'width'=>'150', 'height'=>'240',*/
                    'alt' => htmlspecialchars($user['first_name'].' '.$user['last_name']),
                ),
                false,
                'profile'
            ) ?>
            <br/>
            <div style="width: 200px; margin-left: 24px;"
                 class="fs12 mb5 quiet"><?= t(
                    'Вы можете удалить Ваше фото, но не забудьте установить вместо него другое'
                ) ?></div>
            <? if (session::has_credential('admin') || $user['id'] == session::get_user_id()) { ?><a
                style="text-decoration: underline;"
                href="/profile/delete_photo?id=<?= $user['id'] ?>"><?= t('Удалить') ?></a><? } ?>
        </div>
        <div class="col ml-0">
            <table class="fs12">
                <tr>
                    <td colspan="2">
                <span class="fs18"
                      style="line-height: 30px; text-decoration: none;"><?= t('Загрузка фотографии') ?></span>
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="fs11">
                        <div
                                style="color: black; text-align: justify;"><?= t(
                                'Пожалуйста, загружайте <b>только собственное актуальное</b> (сделаное в течении этого года) <b>портретное фото</b>'
                            ) ?><?= t('Вы можете загрузить фотографии в формате JPG, PNG или GIF размером') ?>
                            <b><?= t('не более 2 Мб.') ?></b> <?= t(
                                'Пожалуйста, загружайте только собственное фото, не используйте посторонних изображений.'
                            ) ?>
                        </div>
                        <!--                                <div class="mt10" style="color: black; text-align: justify;"><? //=t('<b>Внимание!</b> Фото, которое Вы загружаете в профиль, будет использоваться Секретариатом МПУ для официальной документации (удостоверений, членских билетов и т.д.), поэтому не добавляйте к фото профиля посторонних изображений, рекламных объявлений, ссылок и т.д.')?></div>-->
                    </td>
                </tr>
                <tr>
                    <!--                        <td class="aright " width="30%"><?= t('Выберите файл') ?></td>-->
                    <td colspan="2"><input type="file" id="file" name="file"
                                           rel="<?= t('Картинка неверная либо слишком большая') ?>"/></td>
                </tr>
                <tr>
                    <td colspan="2">
                <span
                        class="fs11 quiet"><?= t(
                        'При возникновении проблем попробуйте загрузить фотографию меньшего размера или'
                    ) ?>
                    <a class="quiet" style="text-decoration: underline;"
                       href="https://meritokrat.org/messages/compose?user_id=31"><? //=t('отправьте сообщение Секретариату МПУ')?></a></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input style="width: 100px; margin-right: 10px;" type="submit" name="submit" class="button"
                               value=" <?= t('Сохранить') ?> ">
                        <input style="width: 100px;" onclick="history.go(-1);" type="button" name="cancel"
                               class="button_gray"
                               value=" <?= t('Отмена') ?> ">
                        <?= tag_helper::wait_panel('photo_wait') ?>
                        <div id="photo_load_errors" class="success hidden mr10 mt10"><?= t(
                                'Изменения сохранены'
                            ) ?></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                <span class="fs18"
                      style="line-height: 30px; text-decoration: none;"><?= t('Миниатюры фотографии') ?></span>
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                <span class="fs12 quiet"
                      style="text-decoration: none;"><?= t(
                        'Выберите область на основной фотографии, появится в миниатюрах в сети.'
                    ) ?></span>
                    </td>

                </tr>
                <tr>
                    <td colspan="2">
                        <div id="preview" style="float: left; clear: none;width: 70px; height: 90px; overflow: hidden;">
                            <img
                                    src="<?= context::get('image_server').user_helper::photo_path(
                                        $user_data['user_id'],
                                        't',
                                        'user'
                                    ) ?>"
                                    style="width: 70px; height: 90px;"/>
                        </div>
                        <div id="preview_small"
                             style="float: left; clear: none;width: 50px; height: 64px; overflow: hidden;  margin-top: 24px; margin-left: 20px;">
                            <img
                                    src="<?= context::get('image_server').user_helper::photo_path(
                                        $user_data['user_id'],
                                        's',
                                        'user'
                                    ) ?>"
                                    style="width: 50px; height: 64px;"/>
                        </div>


                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="mt5">
                            <input type="button" name="crop_image_1" id="crop_image_1"
                                   style="margin-right: 10px;width: 100px; clear: none; float: left ;" class="button"
                                   value="<?= t('Сохранить'); ?>">
                            <input type="button" style="width: 100px; clear: none; float: left ;" class="button_gray"
                                   value="<?= t('Отменить'); ?>">
                            <?= tag_helper::wait_panel(
                                'photo_crop_wait',
                                array('style' => 'float:left; clear: none')
                            ); ?>
                            <div id="preview_errors" class="success hidden mt10"
                                 style="clear: both; float: left; width: 100%"><?= t('Изменения сохранены') ?></div>
                        </div>
                    </td>
                </tr>
                <!--            <tr>
                <td colspan="2">
                    <span class="fs18" style="line-height: 30px; text-decoration: none;"><?= t('Удаление фотографии') ?></span><hr style="margin-bottom: 0px;">
                </td>
            </tr>-->
                <!--            <tr>
                <td colspan="2">
                    <span class="fs11 bold" style="color: black;"><?= t(
                    'Вы можете удалить Ваше фото, но не забудьте установить вместо него другое'
                ) ?></span>
                </td>
            </tr>-->
                <!--            <tr>
                <td colspan="2">
                    <? if (session::has_credential('admin') || $user['id'] == session::get_user_id(
                    )) { ?><input type="button" style="width: 100px; clear: none; float: left ;" class="button" onClick="javascript: window.location='/profile/delete_photo?id=<?= $user['id'] ?>' " value="<?= t(
                    'Удалить'
                ) ?>"> <? } ?>
                </td>

            </tr>-->
            </table>
        </div>
    </div>
</form>