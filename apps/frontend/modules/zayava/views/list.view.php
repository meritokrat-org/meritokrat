<? 
if(!$page = request::get_int('page'))
    $num=1;
else
    $num = ($page-1)*30+1;
?>
<style>
  td {
		text-align: center;
		font-size: 12px;
		color: black;
	}
		
	.box-navigation {
		position: absolute;
		border: 1px solid #888;
		background: #FFF;
		box-shadow: 0 0 5px black;
		-moz-box-shadow: 0 0 10px rgba(0, 0, 0, 1);
		-webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 1);
		font-size: 10px;
	}
	
	.box-navigation-title {
		background: #CCC;
		color: #600;
		padding: 10px;
		font-weight: bold;
		border-bottom: 1px solid #888;
	}
	
	.box-navigation-body {
		padding: 10px;
		background: #EEE;
		border-top: 1px solid #FFF;
		border-bottom: 1px solid #888;
	}
	
	.box-navigation-body input, .box-navigation-body textarea {
		font-size: 10px;
	}
	
	.box-navigation-footer {
		background: #CCC;
		padding: 10px;
		border-top: 1px solid #FFF;
		text-align: right;
	}
	
	.box-navigation-footer input {
		width: 72px;
		font-size: 10px;
	}
</style>

<div id="box-reason" class="box-navigation hide">
	<div class="box-navigation-title"><?=t('Причина')?></div>
	<div class="box-navigation-body">
		<textarea id="text-reason" style="font-family: arial; font-size: 12px"></textarea>
	</div>
	<div class="box-navigation-footer">
		<input type="button" id="box-reason-send" value="<?=t('Отправить')?>" />
	</div>
</div>

<? include "partials/navigation.view.php" ?>

<div class="mt10">

<h1 class="column_head"><?=t('Заявления про вступление в МПУ')?> : <?=$count_of_list?><a class="right" href="/zayava?new=1"><?=t('Написать заявление')?></a></h1>
<table>
<tr>
    <!--th>№</th-->
    <th>ID</th>
    <th><?=t('П.I.Б.')?></th>
    <th><?=t('Регион')?></th>
    <th><?=t('Просмотр')?></th>
    <th><?=t('Дата')?></th>
    <th><?=t('Рекомендации')?></th>
    <th width="120"><?=t('Действия')?></th>
</tr>
<? foreach($list as $item){ ?>

<? $zayava = user_zayava_peer::instance()->get_zayava($item) ?>
<? $auth = user_auth_peer::instance()->get_item($zayava['user_id']) ?>
<? $user = user_data_peer::instance()->get_item($zayava['user_id']) ?>
<? $name = implode(' ',array($zayava['lastname'],$zayava['firstname'],$zayava['fathername'])) ?>
<? $recommend = user_recommend_peer::instance()->get_recommenders($zayava['user_id'],20) ?>
<? if($user['region_id'])$region = geo_peer::instance()->get_region($user['region_id']) ?>
<? if($user['city_id'])$city = geo_peer::instance()->get_city($user['city_id']) ?>

<tr id="ztr<?=$zayava['user_id']?>">
    <!--td width="20"><?=$num?></td-->
    <td width="20"><?=$zayava['user_id']?></td>
    <td <?=($zayava['warning'])?'style="background:#FBE3E4"':''?>><a href="/profile-<?=$zayava['user_id']?>"><?=$name?></a></td>
    <td><?=$region['name_' . translate::get_lang()]?> <?=$city['name_' . translate::get_lang()]?></td>
    <td><a href="/zayava?print=1&zayava=<?=$zayava['id']?>"><?=t('Заявление')?></a></td>
    <td><?=date("d.m.Y",$zayava['date'])?></td>
    <td>
        <? $arr = array() ?>
        <? foreach($recommend as $id){ ?>
            <? $arr[] = user_helper::full_name($id,true,array(),false) ?>
        <? } ?>
        <?=(count($arr)>0)?implode(', ',$arr):'&mdash;'?>
        <? unset($arr) ?>
    </td>
    <td class="aleft">
			<div>
				<? if(request::get('filter') == "deleted"){ ?>
					<a href="javascript:;" class="zrec dib mr5" rel="<?=$item?>" style="background: url('/static/images/icons/undo.png'); width: 16px; height: 16px"></a>
					<a href="javascript:;" class="zdel dib icodel mr5" rel="<?=$item?>"></a>
				<? } else { ?>
					<a href="/zayava?print=1&zayava=<?=$zayava['id']?>" class="dib icoprintold mr5"></a>
					<a href="/zayava?id=<?=$zayava['id']?>&list=1" class="dib icoedt mr5"></a>
					<a href="javascript:;" class="zdel dib icodel mr5" rel="<?=$item?>"></a>
					<a href="javascript:;" class="update dib icoinf mr5" rel="<?=$zayava['user_id']?>"></a>
					<? if(request::get_int('status')!=20 && count($recommend)>0 && (intval(db_key::i()->get('schanger'.session::get_user_id())) OR session::get_user_id()==2 OR session::get_user_id()==5)){ ?>
					<a href="javascript:;" class="zpri dib icoapprove" rel="<?=$zayava['user_id']?>"></a>
					<? } ?>
				<? } ?>
			</div>
			<? if(request::get('filter') != "deleted"){ ?>
				<? if($zayava["rec_user_id"] > 0){ ?>
					<? $rec_user = user_data_peer::instance()->get_item($zayava['rec_user_id']); ?>
					<div class="fs10">
						<?=t('Восстановил')?>: <a href="/profile-<?=$zayava["rec_user_id"]?>">
							<?=$rec_user["first_name"]?>&nbsp;<?=$rec_user["last_name"]?>
						</a><br />
						<?=$zayava["rec_date"]?>
					</div>
				<? } ?>
			<? } else { ?>
				<? if($zayava["del_user_id"] > 0){ ?>
					<? $del_user = user_data_peer::instance()->get_item($zayava['del_user_id']); ?>
					<div class="fs10">
						<?=t('Удалил')?>: <a href="/profile-<?=$zayava["del_user_id"]?>">
							<?=$del_user["first_name"]?>&nbsp;<?=$del_user["last_name"]?>
						</a><br />
						<?=$zayava["del_date"]?><br />
						<? if(strlen($zayava["del_reason"]) > 0){ ?>
							<?=t('Причина')?>: <?=$zayava["del_reason"]?>
						<? } ?>
					</div>
				<? } ?>
			<? } ?>
    </td>
</tr>
<? $num++;} ?>
</table>

</div>
<div class="right pager"><?=pager_helper::get_full($pager)?></div>

<script type="text/javascript">
jQuery(document).ready(function($){
		
		var tmp_link = new Object();
		var del_frame = <?=(request::get('filter')=="deleted")?'1':'0'?>;
	
		$('.zrec').click(function(){
			$.post('/zayava/rec', {'id':$(this).attr('rel')});
			$(this).parent().parent().parent().remove();
		});
    $('.zdel').click(function(){
        if(confirm('Ви впевненi що хочете видалити цю заяву?')){
					if(del_frame == 1){
						$.post('/zayava/del', 
							{
								'id':$(this).attr('rel')
							}
						);
						$(this).parent().parent().parent().remove();
					} else {
						tmp_link = $(this);
						$('#box-reason').show()
							.css('opacity', '0')
							.css('top', $(this).position().top+'px')
							.css('left', $(this).position().left+'px')
							.css('margin-left', '-'+$('#box-reason').width()+'px')
							.animate(
								{
									'opacity':'1'
								},
								256
							);
					}
					//reason-text
            /*$.post('/zayava/del',{'id':$(this).attr('rel')});
            $(this).parent().parent().parent().remove();*/
        }
    });
		$('#box-reason-send').click(function(){
			$.post('/zayava/del', 
				{
					'id':$(tmp_link).attr('rel'),
					'reason':$('#text-reason').val()
				}
			);
      $(tmp_link).parent().parent().parent().remove();
			close($('#box-reason'));
		});
		
		var close = function(box_id){
			$(box_id).animate(
				{
					'opacity':'0'
				},
				256,
				function(){
					$(this).hide();
				}
			);
		}
		
    $('.zpri').click(function(){
        var rel = $(this).attr('rel');
        if(confirm('Ви впевненi що цiй людинi можна надати статус члена МПУ?')){
            $.post('/admin/users_change_status',{'id':rel},function(){
                $('#ztr'+rel).remove();
            });
            $(this).remove();
        }
    });
    $('.update').click(function(){
            if(!confirm('<?=t('Вы уверены? Данные из заявления будут добавлены в профиль')?> '+$(this).attr('rel')))
                return false;
            $.post('/admin/user_fill_profile',{'zayava_id':$(this).attr('rel')},function(response){
                    if(response.error)
                        alert(response.error);
                    else
                        alert('Данi з заяви успiшно доданi до профiлю');
                },'json'
            );
    });
});
</script>