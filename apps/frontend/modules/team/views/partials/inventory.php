<style>
    .inner_table {
    border-collapse: collapse;
    }
    .inner_table td,th {
        text-align: center;
        border:1px solid #aaaaaa;
        
    }
    .inner_table input {
        text-align: center;
    }
</style>
<div id="inventory_form" class="hidden">
<?if($inventory_type && $inv_owners) {
    $type_data=user_party_inventory_peer::instance()->get_inventory_type();?>
<form id="edit_inventory_form">
    <input type="hidden" name="type" value="inventory">
    <input type="hidden" name="edit" value="1">
    <table>
        <tr>
            <td>
                
                
                <?
                $c=0;
                $total=0;
                foreach($inventory_type as $inv_id) {
                    $ids = db::get_cols("SELECT id FROM party_inventory WHERE inventory_type=:itype AND ppo_id IN (".implode(",", $inv_owners).")",array('itype'=>$inv_id));
                    $sum = db::get_scalar("SELECT SUM(inventory_count) FROM party_inventory WHERE inventory_type=:itype AND ppo_id IN (".implode(",", $inv_owners).")",array('itype'=>$inv_id));
                    $total_cost = db::get_scalar("SELECT SUM(inventory_count*cost) FROM party_inventory WHERE inventory_type=:itype AND ppo_id IN (".implode(",", $inv_owners).")",array('itype'=>$inv_id));
                    $total+=$sum;
                ?>
                <div class="bold fs16 cblack">
                    <div style='width: 200px;' class='left'>
                        <?=($type_data[$inv_id].':');?>
                    </div>
                    <div class='left'>
                        <a href='javascript: show_info("<?=$inv_id?>")'>
                            <?=$sum.' од.';?>
                        </a>
                    </div>
                </div>
                <div class="clear"></div>
                <table class="inner_table type<?=$inv_id?> hidden">
                    <tr style="background: #f0f0f0;">
                        <th style="width: 33%;">
                           <?=t('Пользователь')?>
                        </th>
                        <th>
                           <?=t('Количество')?>
                        </th>
                        <th>
                           <?=t('Цена')?>
                        </th>
                        <th>
                           <?=t('Сумма')?>
                        </th>
                        <th>
                           <?=t('Дата')?>
                        </th>
                        <th>
                           <?=t('Действие')?>
                        </th>
                    </tr>
                    <?if($ids)
                        foreach ($ids as $id) {
                        $c++;
                        $data = user_party_inventory_peer::instance()->get_item($id);
//                        var_dump($data);
//                        exit;
                    ?>
                    <input type="hidden" name="id[]" value="<?=$data['id']?>">
                    <tr style="background: #ffffff;">
                        <td>
                            <?=user_helper::full_name($data['user_id']);?>
                        </td>
                        <td>
                           <input type="text" name="inventory_count[]" class="text" value="<?=$data['inventory_count']?>"> 
                        </td>
                        <td>
                           <input type="text" name="inventory_cost[]" class="text" style="width: 50px;" value="<?=$data['cost']?>"> 
                        </td>
                        <td>
                           <input type="text" name="cost_sum[]" disabled class="text" style="width: 50px;;" value="<?=$data['cost']*$data['inventory_count']?>"> 
                        </td>
                        <td>
                           <input type="text" name="inventory_date[]" id="date_<?=$c;?>" class="text date_pic" value="<?=date('d.m.Y',$data['date_ts'])?>  ">
                        </td>
                        <td>
                            <a href="javascript: ;" onClick="delete_item('<?=$data['id']?>')">
                                <img src="/static/images/icons/3.3.png">
                            </a>
                        </td>
                    </tr>
                
                <? } ?>
                    <tr>
                        <td>
                            <b><?=t('Общая сумма')?></b>
                        </td>
                        <td colspan="2">
                            &nbsp;
                        </td>
                        <td>
                            <?=$total_cost?>
                        </td>
                        <td colspan="2">
                            
                        </td>
                    </tr>
            </table>
            <?} ?>
                
                
                
    </td>
        </tr>
        <tr>
                <td>
                        <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                        <input onclick="$('.inner_table').slideToggle(1)" type="button" name="cancel" class="button" value="<?=t('Детальная информация')?> ">
                        <?=tag_helper::wait_panel() ?>
                </td>
                
        </tr>
    </table>
</form>
<? } ?>
    <input style="margin-left: 5px;" onclick="$('#add_inventory_form').slideToggle(300)" type="button" name="cancel" class="button" value="+ <?=t('Добавить')?> ">
    <div class='clear'></div>
<form id="add_inventory_form" class="hidden">
    <input type="hidden" name="type" value="inventory">
    <table>
        <tr>
            <td class="aright">Найменування</td>                
            <td><?
                    $inventory_types = user_party_inventory_peer::instance()->get_inventory_type();
                    $inventory_types[''] = '&mdash;';
                    ksort($inventory_types);?>
                <?=  tag_helper::select('inventory_type', $inventory_types,array('style'=>'width: 200px;'));?>
            </td>
        </tr>
        <tr>
            <td class="aright">Отримав</td>                
            <td>
                <?
                foreach($inv_owners as $app_id)
                    $owners[$app_id] = user_helper::full_name($app_id,false,array(),false); 

                $owners[0] = '&mdash;';
                ksort($owners);
                ?>
                <?=tag_helper::select('inventory_owner', $owners, array('style'=>'width: 200px;'))?>
            </td>
        </tr>
        <tr>
            <td class="aright">Кількість</td>                
            <td>
                <input type="text" name="inventory_count" class="text" style='width: 25px;'>
            </td>                        
        </tr>
        <tr>
            <td class="aright">Ціна</td>                
            <td>
                <input type="text" name="inventory_cost" class="text"  style='width: 25px;'>
            </td>                        
        </tr>
        <tr>
            <td class="aright">Дата</td>                
            <td>
                <input type="text" name="inventory_date" id="date_0" class="date_pic text">
            </td>                        
        </tr>
        <tr>
                <td></td>
                <td>
                        <input type="submit" name="submit" class="button" value=" <?=t('Добавить')?> ">
                        <input onclick="" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                        <?=tag_helper::wait_panel() ?>
                        <div class="success hidden mr10 mt10"><?=t('Изменения сохранены...')?></div>
                </td>
        </tr>
    </table>
</form>
</div>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script>
    function show_info(id) { $('.type'+id).slideToggle(1);}
    function delete_item(id) {
        $.ajax({
            type: 'post',
            url: '/ppo/edit',
            data: {
                type: 'inventory',
                id: id,
                act: 'delete',
                submit: 1
            },
            success: function(resp) {
                data = eval("("+resp+")");
                if(data.success==1) $('.success').fadeIn(300, function(){$(this).fadeOut(2000,function(){url = window.location;window.location=url+'&tab=inventory'});});
                else alert(data.reason);
            }
        });
    }
    var addInventory = new Form(
        'add_inventory_form',
        {
            validators: {
                
            },
            success: function(resp) {
                data = resp;
                if(data.success==1) $('.success').fadeIn(300, function(){$(this).fadeOut(2000,function(){url = window.location;window.location=url+'&tab=inventory'});});
                else alert(data.reason);
            }
        }
    );
    var editInventory = new Form(
        'edit_inventory_form',
        {
            validators: {
                
            },
            success: function(resp) {
                data = resp;
                if(data.success==1) $('.success').fadeIn(300, function(){$(this).fadeOut(2000,function(){url = window.location;window.location=url+'&tab=inventory'});});
                else alert(data.reason);
            }
        }
    );
//    $(function(){
        var settings = {
            changeMonth: true,
            changeYear: true,
             autoSize: true,
            showOptions: {direction: 'left' },
            dateFormat: 'dd-mm-yy',
            yearRange: '2010:2025',
            firstDay: true
        };
        _c=-1;
        $('input').each(function() {
            if($(this).hasClass('date_pic')) {
                _c=_c+1;
                $('#date_'+_c).datepicker(settings);
            }
            
        });
//    });
</script>