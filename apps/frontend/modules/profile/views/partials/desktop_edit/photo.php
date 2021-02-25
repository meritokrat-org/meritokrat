<div id="photo_form" class="hide form mt10">
    <table class="left fs12" width="100%" id="avtonumbers">
        <tr><td class="cgray fs11">
                <div style="padding-left: 10px;padding-right: 50px;">
                <?=t('В этом разделе размещайте фотографии и комментарии к ним, свидетельствующие о наглядную агитацию, которую Вы осуществляете (авторамки на Ваших авто и авто друзей, в перспективе - баннеры на балконах, фирменные футболки и т.д.).')?>
                <br/>
                <?=t('Загружайте фотографии в формате JPG, PNG или GIF размером')?> <b><?=t('не более 2 Мб.')?></b><br/>
    <?=t('При возникновении проблем попробуйте загрузить фотографию меньшего размера или')?> <a href="http://<?=context::get('host');?>/messages/compose?user_id=10599"><?=t('отправьте сообщение Администрации сайта "Меритократ"')?></a>.</div><br/>
                    </td>

        </tr>
            <tr>
                <td class="bold">
                    <span class="ml10 fs18" style="text-decoration:none;"><a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=photo">Авторамки:</a></span>
                </td>
            </tr>
            <tr>
                    <td id="all_avtonumbers" colspan="2">
                        <?
                        $avtocount=0;
                        $avtonumbers_photos = unserialize($user_desktop['information_avtonumbers_photos']);
                        if (is_array($avtonumbers_photos) && count($avtonumbers_photos)>0)
                        {
                            foreach ($avtonumbers_photos as $avtonumber)
                            {
                            if(!intval($avtonumber['type']))
                            {
                            ?>
                            <div id="photo_<?=$avtocount?>" style="width:330px;" class="ml5 mr5 left thread box_empty p10 mb10 mr10 box_content">
                                    <?=tag_helper::image('p/avtonumber/'.$user_desktop['user_id'].$avtonumber['salt'].'.jpg', array(), context::get('image_server')); ?>
                                    <a id="del_photo_<?=$avtocount?>" class="right" href="javascript:;" onclick="profileController.deleteAvtophotoItem(<?=$avtocount?>, <?=$user_desktop['user_id']?>, '<?=$avtonumber['salt']?>');" style=""><?=t('Удалить')?></a>
                                    <br/>
                                    <input type="text" maxlength="15" id="description_<?=$avtonumber['salt']?>" name="description_<?=$avtonumber['salt']?>" style="height: 15px; width: 200px;" value="<?=$avtonumber['description']?>" class="text mb5">
                                    <br/>
                                    <input type="button" value=" <?=t('Сохранить')?> " id="<?=$avtonumber['salt']?>" name="<?=$avtonumber['salt']?>" class="button avtophoto_description">
                                    <?=tag_helper::wait_panel('photo_edit_wait_'.$avtonumber['salt']) ?>
                                    <div id="photo_edit_ok_<?=$avtonumber['salt']?>" class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                            </div>
                            <?
                            $avtocount++;
                            }
                            }
                        }
                        ?>
                    </td>
            </tr>
            <tr>
                <td>
                    <form id="avtophoto_form" action="/profile/desktop_edit&type=photo&submit=1<?=session::has_credential('admin') ? '&id=' . $user_data['user_id'] : ''?>" class="mt10" enctype="multipart/form-data" method="post">
                    <? if ( session::has_credential('admin') ) { ?>
                            <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
                    <? } ?>
                    <input type="hidden" name="type" value="photo">
                    <table class="left fs12" width="100%">
                    <tr>
                            <td class="aright"><?=t('Выберите фото')?></td>
                            <td><input type="file" name="file" rel="<?=t('Картинка неверная либо слишком большая')?>" /></td>
                    </tr>
                    <tr>
                            <td class="aright"><?=t('Комментарий')?></td>
                            <td>
                                <input type="text" maxlength="15" style="height: 15px; width: 200px;" name="description" class="text mb5">
                            </td>
                    </tr>
                    <tr>
                            <td></td>
                            <td>
                                    <input type="submit" name="submit" class="button" value=" <?=t('Добавить')?> ">
                                    <input onclick="$('#photo_form').hide();" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                    <?=tag_helper::wait_panel('avtophoto_wait') ?>
                                    <!--div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div-->
                            </td>
                    </tr>
                    </table>
                    </form>
                </td>
            </tr>
            
            
            
            
            
            
            <tr>
                <td class="bold">
                    <span class="ml10 fs18" style="text-decoration:none;"><a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=photo"><?=t('Магнит на машину ')?>:</a></span>
                </td>
            </tr>
            <tr>
                    <td id="all_morenumbers" colspan="2">
                        <?
                        $avtonumbers_photos = unserialize($user_desktop['information_avtonumbers_photos']);
                        if (is_array($avtonumbers_photos) && count($avtonumbers_photos)>0)
                        {
                            foreach ($avtonumbers_photos as $avtonumber)
                            {
                            if($avtonumber['type']==1)
                            {
                            ?>
                            <div id="photo_<?=$avtocount?>" style="width:330px;" class="ml5 mr5 left thread box_empty p10 mb10 mr10 box_content">
                                    <?=tag_helper::image('p/avtonumber/'.$user_desktop['user_id'].$avtonumber['salt'].'.jpg', array(), context::get('image_server')); ?>
                                    <a id="del_photo_<?=$avtocount?>" class="right" href="javascript:;" onclick="profileController.deleteAvtophotoItem(<?=$avtocount?>, <?=$user_desktop['user_id']?>, '<?=$avtonumber['salt']?>');" style=""><?=t('Удалить')?></a>
                                    <br/>
                                    <input type="text" maxlength="15" id="description_<?=$avtonumber['salt']?>" name="description_<?=$avtonumber['salt']?>" style="height: 15px; width: 200px;" value="<?=$avtonumber['description']?>" class="text mb5">
                                    <br/>
                                    <input type="button" value=" <?=t('Сохранить')?> " id="<?=$avtonumber['salt']?>" name="<?=$avtonumber['salt']?>" class="button avtophoto_description">
                                    <?=tag_helper::wait_panel('photo_edit_wait_'.$avtonumber['salt']) ?>
                                    <div id="photo_edit_ok_<?=$avtonumber['salt']?>" class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                            </div>
                            <?
                            $avtocount++;
                            }
                            }
                        }
                        ?>
                    </td>
            </tr>
            <tr>
                <td>
                    <form id="morephoto_form" action="/profile/desktop_edit?type=photo&submit=1<?=session::has_credential('admin') ? '&id=' . $user_data['user_id'] : ''?>" class="mt10" enctype="multipart/form-data" method="post">
                    <? if ( session::has_credential('admin') ) { ?>
                            <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
                    <? } ?>
                    <input type="hidden" name="type" value="photo">
                    <table class="left fs12" width="100%">
                    <tr>
                            <td class="aright"><?=t('Выберите фото')?></td>
                            <td><input type="file" name="file" rel="<?=t('Картинка неверная либо слишком большая')?>" /></td>
                    </tr>
                    <tr>
                            <td class="aright"><?=t('Комментарий')?></td>
                            <td>
                                <input type="hidden" name="phototype" value="1" />
                                <input type="text" maxlength="15" style="height: 15px; width: 200px;" name="description" class="text mb5">
                            </td>
                    </tr>
                    <tr>
                            <td></td>
                            <td>
                                    <input type="submit" name="submit" class="button" value=" <?=t('Добавить')?> ">
                                    <input onclick="$('#photo_form').hide();" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                    <?=tag_helper::wait_panel('photo_wait') ?>
                                    <!--div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div-->
                            </td>
                    </tr>
                    </table>
                    </form>
                </td>
            </tr>

                
            
            
            
            
            <tr>
                <td class="bold" style="padding-bottom:15px">
                    <span class="ml10 mb15 fs18" style="text-decoration:none;"><?=t('Аватарки с "М" в соцсетях')?>:</span>
                </td>
            </tr>
            <tr>
                <td>
                    <form id="avatarm_form" action="/profile/desktop_edit&type=avatarm&submit=1<?=session::has_credential('admin') ? '&id=' . $user_data['user_id'] : ''?>" class="mt10" method="post">
                    <input type="hidden" name="type" value="avatarm">
                    <table class="left fs12" width="100%">
                        <? $user_contacts = unserialize($user_desktop['avatarm']); ?>
                        <? foreach ( user_data_peer::get_contact_types() as $type => $type_title ) { ?>
                        <tr>
                            <td class="aright" width="200"><span class="dib ico<?=$type?>" title="<?=$type_title?>"></span></td>
                            <td><input type="text" name="contacts[<?=$type?>]" class="text" value="<?=stripslashes(htmlspecialchars($user_contacts[$type]))?>" /></td>
                        </tr>
                        <? } ?>
                        <tr>
                            <td></td>
                            <td>
                                    <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                                    <?=tag_helper::wait_panel('avatarm_wait') ?>
                                    <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                            </td>
                        </tr>
                    </table>
                    </form>
                </td>
            </tr>

            <tr>
                <td class="bold">
                    <span class="ml10 fs18" style="text-decoration:none;"><a href="/profile/desktop?id=<?=$user_desktop['user_id']?>&tab=photo"><?=t('Другое')?>:</a></span>
                </td>
            </tr>
            <tr>
                    <td id="all_morenumbers" colspan="2">
                        <?
                        $avtonumbers_photos = unserialize($user_desktop['information_avtonumbers_photos']);
                        if (is_array($avtonumbers_photos) && count($avtonumbers_photos)>0)
                        {
                            foreach ($avtonumbers_photos as $avtonumber)
                            {
                            if($avtonumber['type']==2)
                            {
                            ?>
                            <div id="photo_<?=$avtocount?>" style="width:330px;" class="ml5 mr5 left thread box_empty p10 mb10 mr10 box_content">
                                    <?=tag_helper::image('p/avtonumber/'.$user_desktop['user_id'].$avtonumber['salt'].'.jpg', array(), context::get('image_server')); ?>
                                    <a id="del_photo_<?=$avtocount?>" class="right" href="javascript:;" onclick="profileController.deleteAvtophotoItem(<?=$avtocount?>, <?=$user_desktop['user_id']?>, '<?=$avtonumber['salt']?>');" style=""><?=t('Удалить')?></a>
                                    <br/>
                                    <input type="text" maxlength="15" id="description_<?=$avtonumber['salt']?>" name="description_<?=$avtonumber['salt']?>" style="height: 15px; width: 200px;" value="<?=$avtonumber['description']?>" class="text mb5">
                                    <br/>
                                    <input type="button" value=" <?=t('Сохранить')?> " id="<?=$avtonumber['salt']?>" name="<?=$avtonumber['salt']?>" class="button avtophoto_description">
                                    <?=tag_helper::wait_panel('photo_edit_wait_'.$avtonumber['salt']) ?>
                                    <div id="photo_edit_ok_<?=$avtonumber['salt']?>" class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                            </div>
                            <?
                            $avtocount++;
                            }
                            }
                        }
                        ?>
                    </td>
            </tr>
            <tr>
                <td>
                    <form id="morephoto_form" action="/profile/desktop_edit?type=photo&submit=1<?=session::has_credential('admin') ? '&id=' . $user_data['user_id'] : ''?>" class="mt10" enctype="multipart/form-data" method="post">
                    <? if ( session::has_credential('admin') ) { ?>
                            <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
                    <? } ?>
                    <input type="hidden" name="type" value="photo">
                    <table class="left fs12" width="100%">
                    <tr>
                            <td class="aright"><?=t('Выберите фото')?></td>
                            <td><input type="file" name="file" rel="<?=t('Картинка неверная либо слишком большая')?>" /></td>
                    </tr>
                    <tr>
                            <td class="aright"><?=t('Комментарий')?></td>
                            <td>
                                <input type="hidden" name="phototype" value="2" />
                                <input type="text" maxlength="15" style="height: 15px; width: 200px;" name="description" class="text mb5">
                            </td>
                    </tr>
                    <tr>
                            <td></td>
                            <td>
                                    <input type="submit" name="submit" class="button" value=" <?=t('Добавить')?> ">
                                    <input onclick="$('#photo_form').hide();" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                                    <?=tag_helper::wait_panel('photo_wait') ?>
                                    <!--div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div-->
                            </td>
                    </tr>
                    </table>
                    </form>
                </td>
            </tr>

    </table>
</div>