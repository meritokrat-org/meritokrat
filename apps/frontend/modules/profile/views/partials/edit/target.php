
                <tr>
                        <td class="aright"><?=t('Цільова група')?></td>
                        <td>
                            <table class="fs12 mt10">   
                                    <? if (count($user_targets)>0 && is_array($user_targets))
                                        foreach ($user_targets as $user_target) { ?>
                                        <? $country = geo_peer::instance()->get_country($user_data['country_id']) ?>
                                        <tr><td class="aright" width="15%;"></td><td class="bold cbrown">
                                        <?=user_auth_peer::get_target($user_target)?>
                                        </td></tr>
                                    <? } ?>
                            </table>
                        </td>
                </tr>
                <tr>
                        <td colspan="2">
                            <div id="divatarget" class="<?=request::get('atab')== 'target' ? '' : 'hide'?> aleft">
                            <? $user_targets=explode(',',str_replace(array('{','}'),array('',''),$user_data['target'])); ?>
                            <? if ( $user_data['user_id']==session::get_user_id()) { ?>
                            <div class="fs12 mt10">
                                <form action="/profile/target">
                                        <div class="left aleft" style="width:48%;">
                                    <input type="checkbox"  name="target[]" value="1" <?=in_array(1,$user_targets) ? 'checked' : ''?>/><?=t('Cтудент')?><br>
                                    <input type="checkbox"  name="target[]" value="2" <?=in_array(2,$user_targets) ? 'checked' : ''?>/><?=t('Учитель')?><br>
                                    <input type="checkbox"  name="target[]" value="3" <?=in_array(3,$user_targets) ? 'checked' : ''?>/><?=t('Преподаватель')?><br>
                                    <input type="checkbox"  name="target[]" value="4" <?=in_array(4,$user_targets) ? 'checked' : ''?>/><?=t('Ученый')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="target[]" value="5" <?=in_array(5,$user_targets) ? 'checked' : ''?>/><?=t('Врач')?><br>
                                    <input type="checkbox"  name="target[]" value="6" <?=in_array(6,$user_targets) ? 'checked' : ''?>/><?=t('Другой медицинский работник')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="target[]" value="7" <?=in_array(7,$user_targets) ? 'checked' : ''?>/><?=t('Работник органов местного<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; самоуправления')?><br>
                                    <input type="checkbox"  name="target[]" value="8" <?=in_array(8,$user_targets) ? 'checked' : ''?>/><?=t('На государственной службе')?><br>
                                    <input type="checkbox"  name="target[]" value="9" <?=in_array(9,$user_targets) ? 'checked' : ''?>/><?=t('На государственной выборной должности')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="target[]" value="17" <?=in_array(17,$user_targets) ? 'checked' : ''?>/><?=t('Топ-менеджер')?><br>
                                    <input type="checkbox"  name="target[]" value="18" <?=in_array(18,$user_targets) ? 'checked' : ''?>/><?=t('Руководитель среднего звена')?><br>
                                    <input type="checkbox"  name="target[]" value="19" <?=in_array(19,$user_targets) ? 'checked' : ''?>/><?=t('Офисный работник')?><br>
                                    <div class="ml15"><div class="quiet fs11 ml5"><?=t('(Менеджер по продажам, секретарь, офис-менеджер)')?></div></div>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="target[]" value="11" <?=in_array(11,$user_targets) ? 'checked' : ''?>/><?=t('Военный')?><br>
                                        </div>
                                        <div class="mt15 left aleft mb15" style="width:48%;">
                                    <input type="checkbox"  name="target[]" value="10" <?=in_array(10,$user_targets) ? 'checked' : ''?>/><?=t('Пенсионер')?><br>
                                    <input type="checkbox"  name="target[]" value="12" <?=in_array(12,$user_targets) ? 'checked' : ''?>/><?=t('Военный пенсионер')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="target[]" value="13" <?=in_array(13,$user_targets) ? 'checked' : ''?>/><?=t('Крестьянин')?>
                                        <span class="quiet fs11"><?=t('(Работник с / х)')?></span><br>
                                    <input type="checkbox"  name="target[]" value="14" <?=in_array(14,$user_targets) ? 'checked' : ''?>/><?=t('Рабочий')?>
                                        <span class="quiet fs11"><?=t('(Физический труд)')?></span><br>
                                    <input type="checkbox"  name="target[]" value="15" <?=in_array(15,$user_targets) ? 'checked' : ''?>/><?=t('Работник сферы услуг')?><br>
                                    <div class="ml15"><span class="quiet fs11 ml5"><?=t('(Парикмахер, водитель, туристический гид и т.п.)')?></span></div>
                                    <input type="checkbox"  name="target[]" value="16" <?=in_array(16,$user_targets) ? 'checked' : ''?>/><?=t('Профессионал')?><br>
                                    <div class="ml15"><div class="quiet fs11 ml5"><?=t('(Интелект.труд: архитектор, адвокат, дизайнер,')?> <?=t('бухгалтер и т.д.)')?></div></div>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="target[]" value="20" <?=in_array(20,$user_targets) ? 'checked' : ''?>/><?=t('Предприниматель')?><br>
                                    <div class="ml15"><span class="quiet fs11 ml5"><?=t('(Владелец / совладелец бизнеса)')?></span></div>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="target[]" value="21" <?=in_array(21,$user_targets) ? 'checked' : ''?>/><?=t('Журналист')?><br>
                                    <input type="checkbox"  name="target[]" value="22" <?=in_array(22,$user_targets) ? 'checked' : ''?>/><?=t('Редактор СМИ')?><br>
                                    <input type="checkbox"  name="target[]" value="23" <?=in_array(23,$user_targets) ? 'checked' : ''?>/><?=t('Ведущий на ТВ')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="target[]" value="24" <?=in_array(24,$user_targets) ? 'checked' : ''?>/><?=t('Эксперт')?><br>        
                                        </div>
                            <div class="clear mt5 mb15"></div>
                                <div class="ml15 acenter">
                                        <input type="submit" value=" <?=t('Сохранить')?> " class="button">
                                </div>
                                </form>
                            <div class="clear mb15"></div>
                            </div>
                            <? }
                            if (session::has_credential('admin') ) { ?>
                            <? $admin_targets=explode(',',str_replace(array('{','}'),array('',''),$user_data['admin_target'])); ?>
                            <div class="fs12">
                            <b>*Адмін групи:</b>
                                <form action="/profile/target">            <div class="left aleft" style="width:48%;">
                                    <input type="checkbox"  name="admin_target[]" value="1" <?=in_array(1,$admin_targets) ? 'checked' : ''?>/><?=t('Cтудент')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="2" <?=in_array(2,$admin_targets) ? 'checked' : ''?>/><?=t('Учитель')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="3" <?=in_array(3,$admin_targets) ? 'checked' : ''?>/><?=t('Преподаватель')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="4" <?=in_array(4,$admin_targets) ? 'checked' : ''?>/><?=t('Ученый')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="admin_target[]" value="5" <?=in_array(5,$admin_targets) ? 'checked' : ''?>/><?=t('Врач')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="6" <?=in_array(6,$admin_targets) ? 'checked' : ''?>/><?=t('Другой медицинский работник')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="admin_target[]" value="7" <?=in_array(7,$admin_targets) ? 'checked' : ''?>/><?=t('Работник органов местного<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; самоуправления')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="8" <?=in_array(8,$admin_targets) ? 'checked' : ''?>/><?=t('На государственной службе')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="9" <?=in_array(9,$admin_targets) ? 'checked' : ''?>/><?=t('На государственной выборной должности')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="admin_target[]" value="17" <?=in_array(17,$admin_targets) ? 'checked' : ''?>/><?=t('Топ-менеджер')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="18" <?=in_array(18,$admin_targets) ? 'checked' : ''?>/><?=t('Руководитель среднего звена')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="19" <?=in_array(19,$admin_targets) ? 'checked' : ''?>/><?=t('Офисный работник')?><br>
                                    <div class="ml15"><div class="quiet fs11 ml5"><?=t('(Менеджер по продажам, секретарь, офис-менеджер)')?></div></div>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="admin_target[]" value="11" <?=in_array(11,$admin_targets) ? 'checked' : ''?>/><?=t('Военный')?><br>
                                        </div>
                                        <div class="mt15 left aleft mb15" style="width:48%;">
                                    <input type="checkbox"  name="admin_target[]" value="10" <?=in_array(10,$admin_targets) ? 'checked' : ''?>/><?=t('Пенсионер')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="12" <?=in_array(12,$admin_targets) ? 'checked' : ''?>/><?=t('Военный пенсионер')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="admin_target[]" value="13" <?=in_array(13,$admin_targets) ? 'checked' : ''?>/><?=t('Крестьянин')?>
                                        <span class="quiet fs11"><?=t('(Работник с / х)')?></span><br>
                                    <input type="checkbox"  name="admin_target[]" value="14" <?=in_array(14,$admin_targets) ? 'checked' : ''?>/><?=t('Рабочий')?>
                                        <span class="quiet fs11"><?=t('(Физический труд)')?></span><br>
                                    <input type="checkbox"  name="admin_target[]" value="15" <?=in_array(15,$admin_targets) ? 'checked' : ''?>/><?=t('Работник сферы услуг')?><br>
                                    <div class="ml15"><span class="quiet fs11 ml5"><?=t('(Парикмахер, водитель, туристический гид и т.п.)')?></span></div>
                                    <input type="checkbox"  name="admin_target[]" value="16" <?=in_array(16,$admin_targets) ? 'checked' : ''?>/><?=t('Профессионал')?><br>
                                    <div class="ml15"><div class="quiet fs11 ml5"><?=t('(Интелект.труд: архитектор, адвокат, дизайнер,')?> <?=t('бухгалтер и т.д.)')?></div></div>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="admin_target[]" value="20" <?=in_array(20,$admin_targets) ? 'checked' : ''?>/><?=t('Предприниматель')?><br>
                                    <div class="ml15"><span class="quiet fs11 ml5"><?=t('(Владелец / совладелец бизнеса)')?></span></div>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="admin_target[]" value="21" <?=in_array(21,$admin_targets) ? 'checked' : ''?>/><?=t('Журналист')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="22" <?=in_array(22,$admin_targets) ? 'checked' : ''?>/><?=t('Редактор СМИ')?><br>
                                    <input type="checkbox"  name="admin_target[]" value="23" <?=in_array(23,$admin_targets) ? 'checked' : ''?>/><?=t('Ведущий на ТВ')?>
                                    <div style="margin-top:-5px;" class="mb5 ml5">______</div>
                                    <input type="checkbox"  name="admin_target[]" value="24" <?=in_array(24,$admin_targets) ? 'checked' : ''?>/><?=t('Эксперт')?><br>        
                                        </div>
                            <div class="clear mt5 mb15"></div>
                                <div class="ml15 acenter">
                                        <input type="submit" value=" <?=t('Сохранить')?> " class="button">
                                </div>
                                </form>
                            <div class="clear mb15"></div>
                            </div>
                            <? } ?>
                            </div>
                        </td>
                </tr>