<table width="100%" style="color: black;" class="fs12">

    <tr style="background: transparent url(/static/images/common/desktop_line.png);">
        <td class="bold cbrown" width="35%" rel="info"><?=t('Членство')?></td>
        <td class="fs11 aright">
            <? if (session::has_credential('admin')) { ?>
            <a class="cbrown" href="/profile/desktop_edit?id=<?=$user_desktop['user_id']?>&tab=membership"><?=t('Редактировать')?> &rarr;</a>
            <? } ?>
        </td>
    </tr>
    <tr>
       <td class="cbrown aright"><?=t('Заявление')?></td>
       <td>
            <? if (session::get_user_id()==$user_desktop['user_id']){ ?>
            <a class="fs12" href="/zayava"><?=($zayava)?('№'.$zayava):t('Вступить в МПУ')?></a>
            <? }elseif(session::has_credential('admin') && $zayava){ ?>
            <a class="fs12" href="/zayava?id=<?=$zayava?>"><?='№'.$zayava?></a>
            <? }else{ ?>
            <?=t('Нет')?>
            <? } ?>
        </td>
    </tr>
    <? if($membership['invdate'] && $membership['invnumber']){ ?>
    <tr>
       <td class="cbrown aright"><?=t('Решение про принятие в члены МПУ')?></td>
       <td><?=date('d.m.Y',$membership['invdate'])?> № <?=$membership['invnumber']?></td>
    </tr>
    <? } ?>
    <? if($membership['kvnumber']){ ?>
    <tr>
       <td class="cbrown aright"><?=t('Номер партийного билета')?></td>
       <td>
           <span style="text-transform:lowercase">
           <?=$membership['kvnumber']?>
           <? if($membership['kvmake'] && $membership['kvgive']){ ?>
               ( <?=t('Изготовление').' / '.t('Вручение')?> )
           <? }elseif($membership['kvmake'] OR $membership['kvgive']){ ?>
               ( <?=($membership['kvmake'])?t('Изготовление'):t('Вручение')?> )
           <? } ?>
           </span>
       </td>
    </tr>
    <? } ?>
    <tr>
       <td class="cbrown aright"><?=t('Статус дан')?></td>
       <td>
       <?=($statuslog['id'])?date('d.m.Y',$statuslog['date']).' - '.user_helper::full_name($statuslog['who'],true,array(),false):t('Нет данных')?>
       </td>
    </tr>
    <? if($ppomember[0]){ ?>
    <? $curppo = ppo_peer::instance()->get_item($ppomember[0]) ?>
    <tr>
       <td class="cbrown aright"><?=t('Текущее ППО')?></td>
       <td><?=$curppo['title']?></td>
    </tr>
    <? } ?>
    <? if(count($ppohistory)){ ?>
    <tr>
       <td class="cbrown aright"><?=t('История переходов')?></td>
       <td>
           <? foreach($ppohistory as $hist){ ?>
                <? $pop = ppo_peer::instance()->get_item($hist['group_id']) ?>
                <?=date('d.m.Y',$hist['date_start'])?> - <?=t('Вступление')?> в <?=$pop['title']?> <?=($pop['reason'])?'('.$pop['reason'].')':''?>
                <br/>
           <? } ?>
       </td>
    </tr>
    <? } ?>
    <? if($membership['removedate'] && $membership['remove_type']){ 
        load::action_helper('membership', false);
        ?>
    <tr>
       <td class="cbrown aright"><?=t('Выход из МПУ')?></td>
       <td><?=($membership['removenumber']) ? $membership['removenumber'] : ' - '?></td>
    </tr>
    <tr>
       <td class="cbrown aright"><?=t('Порядок')?></td>
       <td><?=  membership_helper::get_party_off_types($membership['remove_type']);?></td>
    </tr>
    <?if($membership['removewhy'] && $membership['remove_type']) {?>
    <tr>
       <td class="cbrown aright"><?=t('Причина')?></td>
       <td>
       <?
            switch($membership['remove_type']) {
                case 2:
                    echo membership_helper::get_party_off_auto_reason($membership['removewhy']);
                    break;
                case 3:
                    echo membership_helper::get_party_off_except_reason($membership['removewhy']);
                    break;
                
            }
       ?></td>
    </tr>
    <? } ?>
    <tr>
       <td class="cbrown aright"><?=t('Дата')?></td>
       <td><?=date('d.m.Y',$membership['removedate'])?></td>
    </tr>
			<? if(session::has_credential("admin")){ ?>
				<tr>
					<td class="cbrown aright"><?=t('Статус')?></td>
					<td><?=  user_auth_peer::get_status($user_auth['status'])?></td>
				</tr>
			<? } ?>
    <? } ?>
		<? if(session::has_credential("admin") || (session::get_user_id() == $user_auth["id"] && $membership)){ ?>
			<tr>
				<td>
					&nbsp;
				</td>
				<td class="cbrown aleft">
					<a href="/zayava/termination?user_id=<?=$user_auth["id"]?>">

						<?=t('Заявление на выход из членства')?> &RightArrow;
					</a>
				</td>
			</tr> 
		<? } ?>
    <?if(session::has_credential('admin')) {?>
    <?$zayava = user_zayava_peer::instance()->get_user_zayava($user_desktop['user_id'])?>
    <tr>
        <td class="cbrown aright">
            <?=t('Оригинал заявления')?>
        </td>
        <td>
            <input type="checkbox" name="real_app" value="real_app" <?=($zayava['real_app']) ? ' checked="1"' : ''?> />
            <img id="resp1" src="/static/images/icons/true.png" style="width: 15px; height: 15px;" class="hidden">
            <img id="resp0" src="/static/images/icons/false.png" class="hidden" style="width: 15px; height: 15px;">
        </td>
    </tr>
    <? } ?>
    <tr>
       <td colspan="2">
       </td>
    </tr>
    
</table>    
<script>
    $('input[name="real_app"]').change(function(){
        if($(this).attr('checked')) real_app=2;
        else real_app=1;
        $.ajax({
            type: 'post',
            url: '/zayava/index',
            data: {
               has_real: real_app,
               id: '<?=$user_desktop['user_id']?>'
            },
            success: function(data) {
                resp = eval("("+data+")");
                if(real_app==2) $('#resp'+resp.success).fadeIn(300,function(){$(this).fadeOut(1000)});
            }
        });
    });
</script>