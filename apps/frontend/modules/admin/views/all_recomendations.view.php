<?// var_dump($recomended_users);?>
<div class="left mt10 hide" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 97%;">
	<h1 class="column_head">Список рекомендацій</h1>
        <a href="/admin/all_recomendations" class="fs12 <?=request::get('all') ? '' : 'bold'?>">Непідтверджені</a> | <a href="?all=1" class="fs12 <?=request::get('all') ? 'bold' : ''?>">Усі</a><br>
		<div class="box_content p10 mb10 fs12">
	  <table>
			<tr>
                            <td class="bold cgray" style="width:22%">рекомендує</td>
                            <td class="bold cgray" style="width:65%">кого</td>
                            <td class="bold cgray" style="width:16%">підтверд</td>
                            <td style="width: 6%;" class="bold cgray">DEL</td>
			</tr>
        <? foreach ( $recomended_users as $item_id ) { ?>
        <? $item = user_recomendations_peer::instance()->get_item($item_id) ?>
                  
			<tr id="tr_<?=$item_id?>">
                            <td>
                                <?=user_helper::full_name($item['user_id'])?><br>
                                <span class="fs11 cgray"><?=date_helper::get_format_date($item['created_ts'])?></span>
                            </td>
                            <td>
                                <b><?=$item['name']?> <?=$item['last_name']?></b>, <?=$item['email']?><br>
                                <span class="fs11 cgray"><?=$item['recomendation']?></span>
                            </td>
                            <td>
                            <? if ($item['accepted_ts']>0) { ?>
                            <b><?=user_helper::full_name($item['accepted_user_id'])?></b><br>
                            <span class="fs11 cgray"><?=date_helper::get_format_date($item['accepted_ts'])?></span>
                            <? } else { ?>
                            <a href="/admin/approve_recomendation?id=<?=$item_id?>">прийняти</a>
                            <? } ?>
                            </td>
                            <td>
                            <a href="javascript:;" id="<?=$item_id?>" class="del_recomendation">X</a>
                            </td>
			</tr>
	<? } ?>
                    </table>
                  
	</div>
	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
	

</div>
<script type="text/javascript">
jQuery(document).ready(function(){                    
        $("a.del_recomendation").click(function(){ 
                if (confirm('Видалити?'))
                    {
                var id = this.id;
                $.post('/admin/del_recomendation',{'del':id},function(data){
                        if(data=='1'){
                                        $("#tr_"+id).hide();
                        }
                        else {
                            alert();
                        } 
                            
                });
                }
        });
});   
</script>