
<div id="popup_content" class="hide" style="position: absolute; color: rgb(0, 0, 0); width: 500px; height: 555px; top: 280px; left: 45%; margin-left: -200px; background-color: white; border: 10px solid #660000; text-align: center; z-index: 10001; font-family: Arial,Sans-serif,Serif; -moz-border-radius: 10px 10px 10px 10px; display:block;" >
    <div id="target_loading" class="hide"><img src='/static/images/common/loaging.gif' style='margin-top:210px;margin-left:210px;' class="acenter"></div>
    <div class="m10 hide" id="popup_content_target">
        <div class="ml15 fs16 cgray bold"><?=t('Опрос Секретариата Успешной Украины')?></div>
        <div class="ml15 fs14 cbrown bold mt5 mb5"><?=t('Пожалуйста, выберите, к каким из следующих категорий Вы себя относите (можно выбрать одну или несколько категорий).')?></div>
        <div class="ml15 fs12">
            <form action="/profile/target">
            <div class="left aleft" style="width:48%;">
	<input type="checkbox"  name="target[]" value="1" /><?=t('Cтудент')?><br>
	<input type="checkbox"  name="target[]" value="2" /><?=t('Учитель')?><br>
	<input type="checkbox"  name="target[]" value="3" /><?=t('Преподаватель')?><br>
	<input type="checkbox"  name="target[]" value="4" /><?=t('Ученый')?>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
	<input type="checkbox"  name="target[]" value="5" /><?=t('Врач')?><br>
	<input type="checkbox"  name="target[]" value="6" /><?=t('Другой медицинский работник')?>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
	<input type="checkbox"  name="target[]" value="7" /><?=t('Работник органов местного<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; самоуправления')?><br>
	<input type="checkbox"  name="target[]" value="8" /><?=t('На государственной службе')?><br>
	<input type="checkbox"  name="target[]" value="9" /><?=t('На государственной выборной должности')?>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
	<input type="checkbox"  name="target[]" value="17" /><?=t('Топ-менеджер')?><br>
	<input type="checkbox"  name="target[]" value="18" /><?=t('Руководитель среднего звена')?><br>
	<input type="checkbox"  name="target[]" value="19" /><?=t('Офисный работник')?><br>
        <div class="ml5"><div class="ml15 quiet fs11"><?=t('(Менеджер по продажам, секретарь, офис-менеджер)')?></div></div>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
	<input type="checkbox"  name="target[]" value="11" /><?=t('Военный')?>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
            </div>
            <div class="mt15 left aleft mb15" style="width:48%;">
	<input type="checkbox"  name="target[]" value="10" /><?=t('Пенсионер')?><br>
	<input type="checkbox"  name="target[]" value="12" /><?=t('Военный пенсионер')?>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
	<input type="checkbox"  name="target[]" value="13" /><?=t('Крестьянин')?>
            <span class="quiet fs11"><?=t('(Работник с / х)')?></span><br>
	<input type="checkbox"  name="target[]" value="14" /><?=t('Рабочий')?>
            <span class="quiet fs11"><?=t('(Физический труд)')?></span><br>
	<input type="checkbox"  name="target[]" value="15" /><?=t('Работник сферы услуг')?><br>
        <div class="ml15"><div class="quiet fs11 ml5"><?=t('(Парикмахер, водитель, туристический гид и т.п.)')?></div></div>
	<input type="checkbox"  name="target[]" value="16" /><?=t('Профессионал')?><br>
        <div class="ml15"><div class="quiet fs11 ml5"><?=t('(Интелект.труд: архитектор, адвокат, дизайнер,')?> <?=t('бухгалтер и т.д.)')?></div></div>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
	<input type="checkbox"  name="target[]" value="20" /><?=t('Предприниматель')?><br>
        <div class="ml15"><span class="quiet fs11 ml5"><?=t('(Владелец / совладелец бизнеса)')?></span></div>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
	<input type="checkbox"  name="target[]" value="21" /><?=t('Журналист')?><br>
	<input type="checkbox"  name="target[]" value="22" /><?=t('Редактор СМИ')?><br>
	<input type="checkbox"  name="target[]" value="23" /><?=t('Ведущий на ТВ')?>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>
	<input type="checkbox"  name="target[]" value="24" /><?=t('Эксперт')?>
        <div style="margin-top:-5px;" class="mb5 ml5">______</div>       
            </div>
            <div class="clear"></div>
            <div class="acenter mt15">
                    <input type="submit" value=" <?=t('Готово')?> " class="button aleft"><br>
                    <a href="" class="right quiet fs10"><?=t('Заполнить позже')?></a>
            </div>
            </form>
       </div>
    </div>
</div>