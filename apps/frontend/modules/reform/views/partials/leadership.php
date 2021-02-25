<?
$function1 = (int) reform_members_peer::instance()->get_user_by_function(1,$group['id']);
$function2 = (int) reform_members_peer::instance()->get_user_by_function(2,$group['id']);
$rev = reform_members_peer::instance()->get_user_by_function(3,$group['id']);
$members = reform_members_peer::instance()->get_users_by_function(4,$group['id']);
?>
 <form id="leadership_form" action="/projects/edit?type=leadership&submit=1&id=<?=$group['id']?>" class="form mt10 hidden">
    <table width="100%" class="fs12">
        <tr><td style="width: 40%" class="aright mr15 bold"><?= t('ТЕКУЩЕЕ РУКОВОДСТВО') ?></td>
            <td></td>                        
        </tr>
        <?if($function1!=session::get_user_id() && $function2!=session::get_user_id()){?>
        <tr><td class="aright mr15 bold">Координатор 1</td>
            <td><span id="function1div"><?= user_helper::full_name($function1, true, array(), false) ?>
                </span><input type="hidden" id="function1id" name="function1id"/>  
                - <a class="one_add_function" rel="function1" href="javascript:;"><?= t('Изменить') ?></a></td>                        
        </tr><?}?>
        <?if($function1!=session::get_user_id() && $function2!=session::get_user_id()){?>
        <tr><td class="aright mr15 bold">Координатор 2</td>
        <input type="hidden" id="function2id" name="function2id"/>    
        <td><span id="function2div"><?= user_helper::full_name($function2, true, array(), false) ?>
            </span> - <a class="one_add_function" rel="function2" href="javascript:;"><?= t('Изменить') ?></a></td>  
        </tr>
        <?}?>
        <tr><td class="aright mr15 bold"><?=t('Члены Совета')?></td>
            <td><a class="more_add_function" rel="function4" href="javascript:;"><?=t('Добавить')?></a>
            </td>                        
        </tr>
        <?foreach ($members as $m):?>
        <tr id="member<?=$m?>"><td></td>
            <td>
                <input type="hidden" value="<?=$m?>" name="function4[]"/>        
                    <?=user_helper::full_name($m, true, array(), false) . ' - '?>
                <a class="one_delete_function" rel="<?=$m?>" href="javascript:;"><?= t('Удалить') ?></a>  
            </td>                        
        </tr>
        <?endforeach;?>
        <tr class="function4"><td class="aright mr15 bold"><?=t('Ревизор')?></td>
            <td>
                <input type="hidden" id="function3id" name="function3id"/>        
                <span id="function3div">
                    <?=$rev ? user_helper::full_name($rev, true, array(), false) . ' - ' : '' ?>
                </span>
                <a class="one_add_function" rel="function3" href="javascript:;"><?= (!$rev) ? t('Добавить') : t('Изменить') ?></a>  
            </td>                        
        </tr>
          <tr>
			<td></td>
			<td>
				<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
				<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
				<?=tag_helper::wait_panel() ?>
				<div class="success hidden mr10 mt10"><?=t('Изменения сохранены...')?></div>
			</td>
		</tr>
        <tr><td style="width: 40%" class="aright mr15 bold"><?= t('ИСТОРИЯ ИЗМЕНЕНИЙ РУКОВОДСТВА') ?></td>
            <td></td>                        
        </tr>
        <?load::model('reform/members_history');
        $members=reform_members_history_peer::instance()->get_history($group['id'],1);
        if($members){?>
         <tr><td class="aright mr15 bold">Координатор 1</td>
            <td></td>                        
        </tr>
        <?
        foreach ($members as $m):?>
        <tr><td></td>
            <td>   
                    <?=user_helper::full_name($m['user_id'], true, array(), false)?> <?=date("d.m.Y",$m['date_start'])?> - <?=$m['date_end']?date("d.m.Y",$m['date_end']):t('текущий момент')?>
            </td>                        
        </tr>
        <?endforeach;}
        $members=reform_members_history_peer::instance()->get_history($group['id'],2);
        if($members){?>
        <tr><td class="aright mr15 bold">Координатор 2</td>
            <td></td>                        
        </tr>
        <?
        foreach ($members as $m):?>
        <tr><td></td>
            <td>   
                    <?=user_helper::full_name($m['user_id'], true, array(), false)?> <?=date("d.m.Y",$m['date_start'])?> - <?=$m['date_end']?date("d.m.Y",$m['date_end']):t('текущий момент')?>
            </td>                        
        </tr>
        <?endforeach;}?>
        <?$members=reform_members_history_peer::instance()->get_history($group['id'],3);
        if($members){?>
        <tr><td class="aright mr15 bold"><?=t('Ревизор')?></td>
            <td></td>                        
        </tr>
        <?
        foreach ($members as $m):?>
        <tr><td></td>
            <td>   
                    <?=user_helper::full_name($m['user_id'], true, array(), false)?> <?=date("d.m.Y",$m['date_start'])?> - <?=$m['date_end']?date("d.m.Y",$m['date_end']):t('текущий момент')?>
            </td>                        
        </tr>
        <?endforeach;}?>
        <?$members=reform_members_history_peer::instance()->get_history($group['id'],4);
        if($members){?>
        <tr><td class="aright mr15 bold"><?=t('Члены Совета')?></td>
            <td></td>                        
        </tr>
        <?
        foreach ($members as $m):?>
        <tr><td></td>
            <td>   
                    <?=user_helper::full_name($m['user_id'], true, array(), false)?> <?=date("d.m.Y",$m['date_start'])?> - <?=$m['date_end']?date("d.m.Y",$m['date_end']):t('текущий момент')?>
            </td>                        
        </tr>
        <?endforeach;}?>
    </table>
 </form>
<script type="text/javascript">
    $(".one_add_function").click(function() {
        Application.showProjectsUsers($(this).attr('rel'),<?= $group['id'] ?>);
        $(this).html('<?= t('Изменить') ?>');
        return false; 
    });
    $(".more_add_function").click(function() {
        Application.showProjectsUsers($(this).attr('rel'),<?= $group['id'] ?>,1);
        return false; 
    });      
    $(".one_delete_function").click(function() {  
        $("#member"+$(this).attr('rel')).remove();
        return false; 
    });   
</script>