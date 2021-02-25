<div id="list_<?=$item['id']?>" class="left mt5 ml10 cbrown bold" style="width:740px; background-color: #f7f7f7; color:#640705;">
    <div id="<?=$item['id']?>" class="left ml10 title pointer" style="width:605px"><?=stripslashes(htmlspecialchars($item['title']))?>
        <? //if($item['user_id']!=session::get_user_id()){ ?>
        <?//=' ('.user_helper::full_name($item['user_id'], false).')'?>
        <?// } ?>
    </div>
    <div style="margin-top: 1px;" class="left aright">
        <input type="checkbox" name="in_team" id="in_team" <?=$item['in_team'] ? "checked" : ""?> disabled>
    </div>
    <div style="margin-top: 1px;" class="left aright ml15">
        <?=db::get_scalar('SELECT COUNT(*) FROM lists2users WHERE list_id = '.$item['id'].' AND type = 0')?>
    </div>
    <div style="margin-top: 1px;" class="right aright mr5">
        <a href="/people?list=<?=$item['id']?>" class="mr5 dib icoinf" title="<?=t('Просмотр')?>"></a>
        <? if(session::has_credential("admin")){ ?>
        <a href="#edit" alt="<?=$item['id']?>" class="mr5 dib icoedt" title="<?=t('Редактировать')?>"></a>
        <a onclick="return confirm('Ви впевнені?');" href="/lists/delete?id=<?=$item['id']?>" class="mr5 dib icodel" title="<?=t('Удалить')?>"></a>
        <? } ?>
    </div>
</div>
<div class="clear"></div>