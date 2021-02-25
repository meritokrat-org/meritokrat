<? $array = array(
    'mine' => t('К моим мыслям'),
    'ppl' => t('К комментированным мною мыслям'),
    'fav' => t('К мыслям любимых авторов'),
    'favppl' => t('Комментарии любимых авторов'),
    'bkm' => t('К мыслям в закладках'),
    'all' => t('Все последние'),
    'groups_posts_comments' => t('К мыслям в моих сообществах'),
    'my_child_comments' => t('К моим комментариям')
) ?>
<?// include 'partials/sub_menu.php' ?>
<div class="left" style="width: 35%;" id="ladmh"><? include 'partials/left.php' ?></div>
<div id="mstr_div" style="cursor: pointer; padding-top:90px;width:4%" class="left hide">         
            <?=tag_helper::image('/common/btn_arrow_right_gray.jpg',array('id'=>"mstr",'width'=>"30"))?>
</div>
<div class="left ml10 mt10" id="ladm" style="width: 62%;">

    <h1 class="column_head mb10">
        <a href="/comments"><?=t('Комментарии')?></a> &rarr; <?=($type)?$array[$type]:t('Все')?>
        <? if($type!='all'){ ?>
            <a class="right" href="/comments?type=all"><?=t('Все последние')?></a>
        <? } ?>
    </h1>

    <? if ( !$list ) { ?>
            <div class="screen_message quiet acenter"><?=t('Тут еще нет записей')?></div>
    <? }else{ ?>
        <? foreach ( $list as $id ) { ?>
		<? include 'partials/comment.php'; ?>
	<? } ?>
        <div class="right pager"><?=pager_helper::get_full($pager)?></div>
    <? } ?>
            

</div>

<script type="text/javascript">
$('#mstrl').click(function() {
     $('#ladmh').hide();
     $('#ladm').css('width','93%');
     $('#mstr_div').show();
});
$('#mstr').click(function() {
    $('#ladmh').show();
    $('#ladmh').css('width','35%');
     $('#ladm').css('width','62%');
     $('#mstr_div').hide();
});
</script>