<? load::model('groups/groups');  
$sub_menu=request::get('action');
load::view_helper('group');
(request::get('action')=='mine' || request::get('action')=='mine_past')?include 'partials/sub_mine_menu.php':include 'partials/sub_menu.php' ?>
<div class="left" style="width: 35%;"><? include 'partials/left.php' ?></div>
<div class="left ml10" style="width: 62%;">
	<h1 class="column_head">
            <a <?=(request::get('action')=='')?'style="text-decoration:underline;"':''?> href="<?=(request::get('action')=='mine')?'/events/mine':'/events'?>"><?=t('Будущие')?></a>
            <span class="right">
            <a <?=(request::get('action')=='arhive')?'style="text-decoration:underline;"':''?> 
                href="<?=(request::get('action')=='mine')?'/events/mine_past':'/events/arhive'?>?region=<?=request::get('region')?>&level=<?=request::get('level')?>&section=<?=request::get('section')?>&type=<?=request::get('type')?>"><?=t('Прошедшие')?></a>
            </span>
	</h1>
	
	<? if ($events) {
            $cats=events_peer::get_cats();
            $sections=events_peer::get_types();
            load::view_helper('image');
            load::view_helper('date');
        foreach ( $events as $event) {?> <div class="mb10 p10 box_content"> <?include 'partials/event.php';?></div><? }
        }
        else {
            ?>
    <div class="mb10 p10 box_content" style="text-align:center">
	<div>
            <?=t('Тут пока еще нет мероприятий')?>
	</div>
	<div class="clear"></div>
</div>
        <? } 
        ?>
	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
</div>