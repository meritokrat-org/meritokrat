<div  class="fs11 border1 p10" style="color: #333333; font-weight: normal;margin-top: 25px;"><?= t('Загальна статистика') ?></div>

<div  style="margin-bottom: 26px;margin-top: 5px;" class="tab_pane">
    <div  style="margin-top: 0px;">
        <div class="desktop_panel">
            <a  href="/results" 
               class="tab_menu<?= (request::get_string('tab') and request::get_string('tab') != 'information') ? '' : ' selected' ?>" 
               rel="information"><?= t('Основное') ?></a>
        </div>

                <!--<div class="desktop_panel"><a href="javascript:;" id="tab_info" class="tab_menu<?= request::get_string('tab') == 'info' ? ' selected' : '' ?>" rel="info"><?= t('Агитация') ?></a></div>-->

        <div class="desktop_panel">
            <a class="tab_menu<?=(request::get_string('tab')=='info')?' selected':''?>" 
               href="/results/photos?tab=info" id="tab_naglyadka"  rel="naglyadka"><?= t('Наглядная агитация') ?></a>
        </div>

                <!--<div class="desktop_panel"><a href="javascript:;" id="tab_people" class="tab_menu<?= request::get_string('tab') == 'people' ? ' selected' : '' ?>" rel="people"><?= t('Люди') ?></a></div>-->

               <!-- <div class="desktop_panel"><a href="/signatures" class="<?= request::get_string('tab') == 'tasks' ? ' selected' : '' ?>" rel=""><?= t('Подписи') ?></a></div>-->

    </div>
    <div class="clear"></div>
</div>