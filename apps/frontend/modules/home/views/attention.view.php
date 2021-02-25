<div style="width: 760px;" class="left">
<h1 class="column_head mt10 mr10"><?=t('Важная информация')?></h1>
<div class="mt10 mr10 ml15"><?=session::get('language')=='ru' ? stripslashes($attention['text_ru']) : stripslashes($attention['text'])?></div>
    <? if (session::has_credential('admin')) { ?>
    <div>
    * <a href="/admin/edit_attention?id=<?=$attention['id']?>">Редагувати</a>
    &nbsp;&nbsp;|&nbsp;&nbsp;
    * <a href="/admin/add_attention">Додати Важливу Інформацію</a>
    </div><br>
    <? } ?>
</div>