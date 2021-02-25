<div class="left ml10" style="width: 750px;">
    <h1 class="column_head mt10"><?=t('Наша агитация')?></h1>

        <? if(count($list)>0){ ?>
            <? foreach ( $list as $id ) { ?>
                    <? if ( !$post_data = eventreport_peer::instance()->get_item($id) ) continue; ?>
                    <? include 'partials/postbig.php'; ?>
            <? } ?>
        <? }else{ ?>
            <div class="fs12 p10" style="text-align:center;color:grey;">
                <?=t('Не найдено ни одной публикации')?>
            </div>
        <? } ?>

	<div class="right pager"><?=pager_helper::get_short($pager)?></div>

</div>