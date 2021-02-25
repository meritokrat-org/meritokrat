<style>
    .paimg img{border: 1px solid #CDC9C9;}
    .paimg a.acenter{width:160px; height: 160px; vertical-align: middle; padding:9px; background-color:#F7F7F7; margin:0 5px 5px 0;line-height:0.9}
    .paimg a.acenter span{line-height:1;}
</style>
<script src="/static/javascript/jquery/jquery.prettyPhoto.js" type="text/javascript"></script>
<link type="text/css" href="/static/javascript/jquery/css/prettyPhoto.css" rel="stylesheet" />

<h1 class="mt10 ml10 column_head column_center">
	<a class="left" href="/<?=$links[$type][0]?>"><?=$links[$type][1]?></a>
        <span class="right"><?=t('Фото')?></span>
</h1>

<div class="form_bg ml10 fs12 p10 mb10">
    <div class="left">
            <a class="fs12" href="/photo?type=<?=$type?>&oid=<?=$oid?>"><?=$names[$type]?></a>
            <? if ($album_id) { ?>
                &nbsp;&rarr;&nbsp;<?=htmlspecialchars(stripslashes($album['title']))?>
            <? } ?>
    </div>
    <? if($access){ ?>
    <div class="right">
        <? /* ?>
        <a onclick="$('#add_photoalbum').show(50);" href="javascript:;"><?=t('Добавить альбом')?></a>
        &nbsp;|&nbsp;
        <? */ ?>
        <? if ( $album_id ) { ?>
        <a href="/photo/add?album_id=<?=$album_id?>" class="right"><?=t('Загрузить фото')?></a>
        <span class="right">&nbsp;|&nbsp;</span>
        <a href="/photo/edit?album_id=<?=$album_id?>" class="right"><?=t('Редактировать альбом')?></a>
        <? }else{ ?>
        <a href="/photo/add?type=<?=$type?>&oid=<?=$oid?>" class="right"><?=t('Загрузить фото')?></a>
        <? } ?>
    </div>
    <? } ?>
    <div class="clear"></div>
    <div id="add_photoalbum" class="hidden" style="display: none;">
        <form class="form_bg mr10 fs12 p10 mb10" id="photoalbum_form" method="get">
        <? if ( $album_id ) { ?>
        <input type="hidden" value="<?=$album['obj_id']?>" name="oid" id="oid">
        <input type="hidden" value="<?=$album['type']?>" name="type" id="type">
        <? }else{ ?>
        <input type="hidden" value="<?=$oid?>" name="oid" id="oid">
        <input type="hidden" value="<?=$type?>" name="type" id="type">
        <? } ?>
        <table width="100%" class="mt10"><tbody>
                <tr>
                        <td width="18%" class="aright">Назва</td>
                        <td><input type="text" rel="Введите название альбома" name="title" style="width: 500px;" class="text" id="title"></td>
                </tr>
                <!--tr>
                        <td width="18%" class="aright">Текст</td>
                        <td><textarea rel="Введіть текст" name="text" id="text" style="width: 500px; height: 50px; display: none;"></textarea></td>
                </tr-->
                <tr>
                        <td></td>
                        <td>
                                <input type="submit" class="button" value="<?=t('Добавить')?>" name="submit" id="submit">
                                <input type="button" onclick="$('#add_photoalbum').hide();" value="<?=t('Отмена')?>" class="button_gray" id="">
                                <?=tag_helper::wait_panel() ?>
                        </td>
                </tr>
        </tbody></table>
        </form>
        <div class="success hidden mr10 mt10"><?=t('Фотоальбом добавлен')?></div>
    </div>
</div>

<? if ( $album_id ) { ?>
	<? if ( $photos ) { ?>
            <div class="ml15 paimg">
                <? foreach ( $photos as $id ) { include 'partials/photo.php'; } ?>
            </div>
            <div class="clear"></div><br />
            <div class="bottom_line_d mb10 ml15"></div>
            <div class="right pager ml15"><?=pager_helper::get_full($pager)?></div>
	<? } else { ?>
            <div class="acenter fs12 p5 ml10"><?=t('Фотографий еще нет')?></div>
	<? } ?>
<? } else { ?>
        <div class="ml15 paimg">
            <? foreach ( $albums as $album_id ) { include 'partials/album.php'; } ?>
        </div>
<? } ?>

<? if ( $album_id ) { ?>
<script type="text/javascript">
        $(document).ready(function($){
            $(".paimg a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});
        });
</script>
<? } ?>