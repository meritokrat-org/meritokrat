<? $news=friends_news_peer::instance()->get_item($item_id);?>
<div id="friend_<?=$id?>" class="border1" style="padding: 10px; margin: 10px 10px 0px 0px;">
    <table class="m0">
        <tr>
            <td class="aleft p0" width="33%" style="white-space: nowrap; padding-left: 10px"><?=user_helper::photo($news['sent_by'], 'sm', array('class' => 'border1','style'=>'width:40px'))?>
            <?=user_helper::full_name($news['sent_by'],null,null,false)?></td>
            <td width="33%" style="padding: 0 0 30px 0;" class="acenter vcenter"><?=t('добавил в друзья')?></td>
            <td width="33%" class="aleft p0" style="white-space: nowrap;"><?=user_helper::photo($news['user_id'], 'sm', array('class' => 'border1','style'=>'width:40px'))?>
            <?=user_helper::full_name($news['user_id'],null,null,false)?></td>
        </tr>
    </table>
</div>