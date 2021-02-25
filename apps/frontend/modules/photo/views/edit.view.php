<h1 class="mt10 ml10 column_head column_center">
	<a class="left" href="/photo?album_id=<?=$albums['id']?>"><?=$albums['title']?></a>
        <span class="right"><?=t('Редактирование')?></span>
</h1>

<div class="form_bg ml10 fs12 mb10">

    <div id="add_photoalbum">
        <form class="form_bg fs12 p10 mb10" method="post">
        <input type="hidden" value="<?=$album['id']?>" name="album_id">
        <input type="hidden" value="<?=$album['obj_id']?>" name="oid">
        <input type="hidden" value="<?=$album['type']?>" name="type">
        <table width="100%" class="mt10"><tbody>
                <tr>
                        <td width="18%" class="aright"><?=t('Название альбома')?></td>
                        <td>
                            <input type="text" rel="Введите название альбома" name="album_name" style="width: 450px;" class="text" value="<?=$album['title']?>">
                            &nbsp;
                            <a class="button" style="padding:2px 5px;font-size:12px" onclick="return confirm('<?=t('Удалить альбом?')?>');" href="/photo/album_delete?id=<?=$album_id?>"><?=t('Удалить')?></a>
                        </td>
                </tr>
                <tr>
                        <td></td>
                        <td>
                                <input type="submit" class="button" value="<?=t('Сохранить')?>" name="submit" id="submit">
                                <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                        </td>
                </tr>
        </tbody></table>
        </form>
    </div>
</div>

<? if ( $photos ) { ?>
    <div class="ml15 paimg">
        <? foreach ( $photos as $id ) { include 'partials/edit.php'; } ?>
    </div>
    <div class="clear"></div><br />
    <div class="bottom_line_d mb10 ml15"></div>
    <div class="right pager ml15"><?=pager_helper::get_full($pager)?></div>
<? } ?>