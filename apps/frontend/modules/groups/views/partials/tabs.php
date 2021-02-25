<div class="tab_pane_gray mb10">
        <? if(!session::has_credential('designer')){ ?>
        <a rel="edit" class="tab_menu <?=(request::get('tab')!='photo')?'selected':''?>" id="tab_edit selected" href="javascript:;">
            Основні відомості
        </a>
        <? } ?>
    <? if(session::has_credential('admin')){ ?>
        <a rel="photo" class="tab_menu <?=((request::get('tab')=='photo')||(session::has_credential('designer')))?'selected':''?>" id="tab_photo" href="javascript:;">
            *Фото
        </a><? } ?>
        <div class="clear"></div>
</div>