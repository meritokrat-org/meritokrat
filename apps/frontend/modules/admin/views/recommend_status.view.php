<?// var_dump($recomended_users);?>
<div class="left mt10 hide" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 97%;">
    <? if (request::get('all')==1) { ?>
        <h1 class="column_head">Рекомендації</h1>
        <br><a class="fs18" href="/admin/recommend_status">в Члени МПУ</a> <?=db::get_scalar("SELECT count(*) FROM user_recommend WHERE checked=0 and status=20")?> &nbsp; &nbsp; &nbsp;| &nbsp; &nbsp; &nbsp;
        <a class="fs18" href="/admin/recommend_status?status=10">в Мерітократи</a> <?=db::get_scalar("SELECT count(*) FROM user_recommend WHERE checked=0 and status=10")?> &nbsp; &nbsp; &nbsp;| &nbsp; &nbsp; &nbsp;
        <a class="bold fs18" href="/admin/all_recomendations">в мережу</a> <?=db::get_scalar("SELECT count(*) FROM user_recomendations WHERE accepted_ts<1")?>
</div>
    <?
    } else {?>
<h1 class="column_head"><a href="/admin/recommend_status?all=1">Список рекомендацій</a> &rArr; статус "<?=user_auth_peer::get_status(request::get_int('status') ? request::get_int('status') : 20)?>"</h1>
        <a href="/admin/recommend_status?status=<?=request::get_int('status') ? request::get_int('status') : 20?>" class="fs12 <?=request::get('checked') ? '' : 'bold'?>">Нові</a> <?=db::get_scalar("SELECT count(*) FROM user_recommend WHERE checked=0 and status=".(request::get_int('status') ? request::get_int('status') : 20))?> |
        <a href="?status=<?=request::get_int('status') ? request::get_int('status') : 20?>&checked=1" class="fs12 <?=request::get('checked') ? 'bold' : ''?>">Перевірені</a> <?=db::get_scalar("SELECT count(*) FROM user_recommend WHERE checked>0 and status=".(request::get_int('status') ? request::get_int('status') : 20))?><br>
	<div class="box_content p10 mb10 fs12">
	<table>
			<tr>
                            <td class="bold cgray" style="width:32%">рекомендує</td>
                            <td class="bold cgray" style="width:32%">кого</td>
                            <td class="bold cgray" style="width:16%">перевірка</td>
                            <td style="width: 6%;" class="bold cgray">DEL</td>
			</tr>
        <? foreach ( $recomended_users as $item_id ) { ?>
        <? $item = user_recommend_peer::instance()->get_item($item_id) ?>
                  
			<tr id="tr_<?=$item_id?>">
                            <td>
                                <?=user_helper::full_name($item['recommending_user_id'])?><br>
                                <span class="fs11 cgray"><?=date_helper::get_format_date($item['ts'])?></span>
                            </td>
                            <td>
                                
                                <?=user_helper::photo($item['user_id'],'sm',array('style'=>'width:30px;'))?>
                                <?=user_helper::full_name($item['user_id'])?>
                            </td>
                            <td>
                            <? if ($item['accept_ts']>0) { ?>
                            <b><?=user_helper::full_name($item['accept_user_id'])?></b><br>
                            <span class="fs11 cgray"><?=date_helper::get_format_date($item['accept_ts'])?></span>
                            <? } else { ?>
                            <a href="/admin/approve_recommend_status?id=<?=$item_id?>">перевірено</a>
                            <? } ?>
                            </td>
                            <td>
                            <a href="javascript:;" id="<?=$item_id?>" class="del_recommend">X</a>
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
        $("a.del_recommend").click(function(){ 
                if (confirm('Видалити?'))
                    {
                var id = this.id;
                $.post('/admin/del_recommend_status',{'del':id},function(data){
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
<? } ?>