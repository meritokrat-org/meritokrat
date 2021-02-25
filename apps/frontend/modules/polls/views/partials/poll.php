<? $poll = polls_peer::instance()->get_item($id) ?>

<div class="left mt5"><?=user_helper::photo($poll['user_id'], 's', array('class' => 'border1'))?></div>
<div class="left ml10" style="width: 400px;">
<? if(request::get('bookmark')){ ?>
    <? $bkm = bookmarks_peer::instance()->is_bookmarked(session::get_user_id(),5,$poll['id']); ?>
    <a class="bookmark mb10 ml5 right" style="<?=($bkm)?'display:none;':'display:block;'?>" href="#add_bookmark" onclick="Application.bookmarkThisItem(this,'5','<?=$poll['id']?>');return false;"><b></b><span><?=t('В закладки')?></span></a>
    <a class="unbkmrk mb10 ml5 right" style="<?=($bkm)?'display:block;':'display:none;'?>" href="#del_bookmark" onclick="Application.unbookmarkThisItem(this,'5','<?=$poll['id']?>');return false;"><b></b><span><?=t('Удалить из закладок')?></span></a>
<? } ?>
	<a href="/poll<?=$id?>" style="font-weight:normal;" class="fs18"><?=stripslashes(htmlspecialchars($poll['question']))?></a>
	<div class="fs11 quiet mb5">
		<?=date_helper::human($poll['created_ts'], ', ')?> &nbsp;&nbsp;
		<?=user_helper::full_name($poll['user_id'])?>
                <div class="mt5">
		<?=t('Количество проголосовавших')?>: <b><?=$poll['count']?></b> &nbsp;&nbsp;
                <? if ( !polls_votes_peer::instance()->has_voted($id, session::get_user_id()) ) { ?>
                        <a class="fs11" href="/poll<?=$id?>"><?=t('Голосовать')?>...</a>
                <? } else { ?>
                        <a class="fs11" href="/poll<?=$id?>"><?=t('Смотреть результаты')?></a>
                <? } ?>
                </div>
       </div>
</div>
<div class="clear"></div><br />
