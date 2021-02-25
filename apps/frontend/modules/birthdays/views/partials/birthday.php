<? if( count($list)>0 ) { ?>

<? $array = array(
    t('Сегодня'),
    t('Завтра'),
    t('Послезавтра'),
    'Через 3 '.t('дня'),
    'Через 4 '.t('дня'),
    'Через 5 '.t('дней'),
    'Через 6 '.t('дней'),
    'Через 7 '.t('дней')
    ); ?>
<? foreach( $array as $k => $v ){ ?>
<div class="left ppl_holder mb10 ml5 mt10 cgray" style="border-bottom:1px solid grey;width:740px;">
    <?=$v?>
    <? if($k>2){ ?>
        <?=' ('.date("j.m.Y",(time()+(86400*$k))).') '?>
    <? } ?>
</div>
    <? foreach( $list as $id ) { ?>
    <? if(user_helper::birthday($id,true)==$k) { ?>

    <div id="friend_<?=$id?>" class="box_empty box_content left p10" style="height:70px;width: 230px;">
            <?=user_helper::photo($id, 'sm', array('class' => 'border1 left'), true)?>
            <div class="ml10" style="margin-left: 65px;">
                    <div>
                            <b><?=user_helper::full_name($id)?></b><br />
                            <? $friend = user_auth_peer::instance()->get_item($id) ?>
                            <span class="fs11 quiet"><?=user_helper::birthday($id)?></span>
                    </div>
                    <div class="fs11">
                            <a href="/messages/compose?user_id=<?=$id?>"><?=t('Написать')?></a>
                    </div>
            </div>
            <div class="clear"></div>
    </div>
<? }}}?>

<? } ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.ppl_holder').each(function(){
           if($(this).next('div.box_content').length==0){
               $(this).remove();
           }
        });
    });
</script>


<?/* foreach( $list as $id) { ?>
<? //$data = user_data_peer::instance()->get_item($user) ?>
<div id="friend_<?=$id?>" class="box_empty box_content left p10" style="height:70px;width: 230px;">
	<?=user_helper::photo($id, 'sm', array('class' => 'border1 left'), true)?>
	<div class="ml10" style="margin-left: 65px;">
	<	<div>
			<b><?=user_helper::full_name($id)?></b><br />
			<? $friend = user_auth_peer::instance()->get_item($id) ?>
                        <span class="fs11 quiet"><?=user_helper::birthday($id)?></span>
		</div>
		<div class="fs11">
			<a href="/messages/compose?user_id=<?=$id?>"><?=t('Написать')?></a>
		</div>
	</div>
	<div class="clear"></div>
</div>
<? }}else{ ?>
<div class="cgray mt15" style="text-align:center;">
<?=t('В ближайшее время нет никаких праздников')?>
</div>
<? }*/ ?>