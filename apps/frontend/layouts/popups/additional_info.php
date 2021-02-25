<div id="popup_content" class="hide" style="position: absolute; color: rgb(0, 0, 0); width: 500px; height: 455px; top: 280px; left: 45%; margin-left: -200px; background-color: white; border: 10px solid #660000; text-align: center; z-index: 10001; font-family: Arial,Sans-serif,Serif; -moz-border-radius: 10px 10px 10px 10px; display:block;" >
    <div id="target_loading" class="hide"><img src="/static/images/common/loaging.gif" style="margin-top:210px;margin-left:210px;" class="acenter"/></div>
    <div class="m10 hide" id="popup_content_target">
        <div class="ml15 fs14 cbrown bold mt5 mb15"><?=t('Пожалуйста, заполните дополнительные данные о себе')?></div>
        <div class="ml15 fs12 mt15">
            <form action="/profile/additional_info" method="post">
                <table>
                    <? if (!$additional_data['birthday']) {
                        $range_years=range(1930,2000);
                        foreach ($range_years as $year) $years[$year]=$year;
                    ?>                    
                    <tr>
                        <td style="width:120px" class="bold aright"><?=t('Дата рождения')?></td>
												<? $days = array(); ?>
												<? for($i = 0; $i < 31; $i++){ ?>
													<? $days[$i+1] = $i+1; ?>
												<? } ?>
                        <td class="left"><?=tag_helper::select('day', $days, array("style"=>"width:55px"))?> - <?=tag_helper::select('month', date_helper::get_month_name(), array("style"=>"width:115px"))?> - <?=tag_helper::select('year', $years, array("style"=>"width:65px"))?></td>
                    </tr>
                    <? } ?>
                    <? if (!$additional_data['phone']){// && !$additional_data['work_phone'] && !$additional_data['mobile']) { ?>
                    <tr>
                        <td style="width:120px" class="bold aright"><?=t('Телефон')?></td>
                        <td class="left"><input type="text" class="text" name="phone" style="width:250px"></td>
                    </tr>
                    <? } ?>
                    <? if (!$additional_data['about']) { ?>
                    <tr>
                        <td style="width:120px" class="bold aright"><?=t('Коротко о себе')?></td>
                        <td class="left"><textarea  name="about" style="height: 60px; width: 250px;"></textarea></td>
                    </tr>
                    <? } ?>
                    <? if (!$additional_data['why_join']) { ?>
                    <tr>
                        <td style="width:120px" class="bold aright"><?=t('Почему присоединились к сети "Меритократ"')?></td>
                        <td class="left"><textarea  name="why_join" style="height: 60px; width: 250px;"></textarea></td>
                    </tr>
                    <? } ?>
                    <? if (!$additional_data['can_do']) { ?>
                    <tr>
                        <td style="width:120px" class="bold aright"><?=t('Чем бы хотели заниматься в Меритократическом движении')?></td>
                        <td class="aleft fs14" style="color:black;">
                            <? /* ?>
                            <input type="radio" name="can_do" value="1">
                            <?=t('партийное строительство')?><br>
                            <input type="radio" name="can_do" value="2">
                            <?=t('публичная политика')?><br>
                            <input type="radio" name="can_do" value="3">
                            <?=t('агитационная работа')?><br>
                            <!--input type="radio" name="can_do" value="4">
                            <?=t('идеологическая деятельность')?><br-->
                            <? */ ?>
                            <input type="checkbox" name="can_do[]" class="can_do" value="1" />
                            <?=t('готов заниматься интернет агитацией')?><br>
                            <input type="checkbox" name="can_do[]" class="can_do" value="2" />
                            <?=t('готов заниматься уличной агитацией')?><br>
                            <input type="checkbox" name="can_do[]" class="can_do" value="3" />
                            <?=t('могу помогать финансово (каждая гривня имеет значение)')?><br>
                        </td>
                    </tr>
                    <? } ?>
                </table>
            <div class="clear"></div>
            <div class="acenter mt15">
                    <input type="submit" value=" <?=t('Готово')?> " class="button aleft"><br>
                    <a href="javascript:;" class="right quiet fs10" id="close_popup"><?=t('Заполнить позже')?></a>
            </div>
            </form>
       </div>
    </div>
</div>