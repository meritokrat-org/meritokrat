<form id="tasks_form" class="form mt10 hidden">
        <? if ( session::has_credential('admin') ) { ?>
                <input type="hidden" name="id" value="<?=$user_desktop['user_id']?>">
        <? } ?>
        <input type="hidden" name="type" value="tasks">
        <table width="100%" class="fs12">
                <tr>
                        <td class="aright" width="20%"><span class="ml10 fs18" style="text-decoration:none;"><?=t('Сбор подписей')?></span></td>
                        <td class="acenter"></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table width="100%" class="fs12" id="table_signature">
            <?
            load::model('geo');
            $signaturecount=0;
            $regions = geo_peer::instance()->get_regions(1);
            if (is_array($user_desktop_signature)) {
            foreach ($user_desktop_signature as $signature) { ?>
                <tr id="region_row">
                        <td class="aright" width="30%"><?=t('Регион')?></td>
                        <td>
                                <input name="signatures[]" type="hidden" value="<?=$signaturecount?>" />
                                <input name="region_id" type="hidden" value="<?=$signature['region_id']?>" />
                                <?=tag_helper::select($signaturecount,$regions, array('use_values' => false, 'value' => $signature['region_id'], 'id'=>$signaturecount, 'rel'=>t('Выберите регион'), 'class'=>'regions')); ?>
                                    <!--input name="region" class="text" type="text" value="<?=$region['name_' . translate::get_lang()]?>" /-->
                        </td>
                </tr>

                <tr>
                        <td class="aright"><?=t('Город/Район')?></td>
                        <td>
                                <input name="city_id" type="hidden" value="<?=$signature['city_id']?>" />
                                <? if ($signature['region_id']>0 and $signature['region_id']!=9999) $cities = geo_peer::instance()->get_cities($signature['region_id']);
                                else $cities=array(); ?>
                                <?=tag_helper::select('city_'.$signaturecount, $cities , array('use_values' => false, 'value' => $signature['city_id'], 'id'=>'city_'.$signaturecount, 'rel'=>t('Выберите город/район'))); ?>
                        </td>
                </tr>

                <tr>
                        <td class="aright"><?=t('Количество')?></td>
                        <td>
                                <input name="plan_<?=$signaturecount?>" rel="<?=t('')?>" class="text" type="text" value="<?=$signature['plan']?>" />
                        </td>
                </tr>
        <? $signaturecount++;  } } ?>
                        <tr id="signature_row" width="30%">
                                <td class="aright"><?=t('Регион')?></td>
                                <td>
                                <input name="signatures[]" type="hidden" value="<?=$signaturecount?>" />
                                        <?    $regions['']='&mdash;';
                                              ksort($regions); ?>
                                        <?=tag_helper::select($signaturecount,$regions , array('use_values' => false,'style'=>'width:200px;', 'id'=>$signaturecount, 'rel'=>t('Выберите регион'), 'class'=>'regions' )); ?>
                                </td>
                        </tr>

                        <tr>
                                <td class="aright"><?=t('Город/Район')?></td>
                                <td>
                                        <input name="city_id" type="hidden" value="" />
                                        <? $cities=array(); ?>
                                        <?=tag_helper::select('city_'.$signaturecount, $cities , array('use_values' => false, 'id'=>'city_'.$signaturecount, 'rel'=>t('Выберите город/район'))); ?>
                                </td>
                        </tr>
                        <tr>
                                <td class="aright"><?=t('Количество')?></td>
                                <td>
                                        <input name="plan_<?=$signaturecount?>" rel="<?=t('')?>" class="text" type="text" value="" />
                                </td>
                        </tr>
                    </table>

                    </td>
               </tr>

               <tr class="mb15"><td></td>
                    <td>
                        <a href="javascript:;" class="button add_signature" id="add_signature">+ <?=t('Добавить')?></a>
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
                                <?=tag_helper::wait_panel('tasks_wait') ?>
                                <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                        </td>
                </tr>

        </table>
</form>