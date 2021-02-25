<h1 class="column_head" style="padding-bottom: 0px;"><?=t('Общее количество')?></h1>
<div class="left">
    <div class="right">
	<?=t('Все')?>&nbsp;<a href="/results/people?act=avatarm&type=all"><?=(int)$avatarmcntall?></a>
    </div>
    <div class="clear"></div>
    <table class="left fs12" width="100%" style="border-spacing: 2px; margin-bottom: 0px;">
	<tr>
	    <? $counter=0; ?>
<? foreach ( user_data_peer::get_contact_types() as $type => $type_title ) {
    if($type==6) continue;
    $counter++;
    ?>
                <td class="aleft" style="background-color: #f7f7f7; border: 1px solid #ccc;">                    
			<?=$type_title?> <?=tag_helper::image('/logos/' . $type . '.png', array('class' => 'vcenter', 'title' => $type_title))?>
		</td>
                <td class="bold acenter" style="width: 43px;  background-color: #eeeeee; border: 1px solid #ccc;"><a href="/results/people?act=avatarm&type=<?=$type?>"><?=(int)$avatarmcnt[$type]?></a></td>
           <?if($counter%4==0) {?> 
		</tr><tr>
	    <? } ?>
<? } ?>
	</tr>
    </table>
    <div class="clear"></div>
<h1 class="column_head pb5 mt5"><?=t('Детализация по участникам')?></h1>
    <table style="border: 0px solid #ccc;">
	<tr>
	    <?
	    $c = 0;
	    foreach($users as $uid=>$udata) {
		if($udata['count']) {
		    $c++;
		?>
		<td style="
		    background-color: #f7f7f7; 
		   
		    width: 150px;
		    ">
		    <a href="/profile/desktop?id=<?=$uid?>&tab=naglyadka" style="margin-left: 0px;"><?=$udata['name']?></a>
		</td>
		<td class="acenter" style="
		    padding: 3px 5px;
		     
		    background-color: #eee; 
		    "
		>
		    <a href="/profile/desktop?id=<?=$uid?>&tab=naglyadka" class="bold"><?=$udata['count']?></a>
		</td>
		<td style="background-color: #f7f7f7; width: 45px;"></td>
		<?if($c%3==0) {?></tr><tr><? } ?>
	    <? } } ?>
	</tr>
    </table>
</div>