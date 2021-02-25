<style type="text/css">
    .vnesky th {border: 1px solid gray;
    font-size: 12.2px;}
    .vnesky td {color:black;
    border: 1px solid gray;
    color: black;
    font-size: 12px;
    padding: 1px 5px;
    vertical-align: middle;
    }
</style>
<div style="width: 760px;" class="left vnesky">
<div class="mt10">
    <h1 class="column_head"><a href="/admin/vnesky">Членські внески</a> <a href="/admin/vnesky?status=null" class="right">Не завершені</a></h1>
   
    <form class="mr10 ml10" action="" method="get">
    <table>
        <tr>
            <td width="120"><b>Дата:</b></td>
            <td>
                з
                <input name="period_begin" id="period_begin" value="<?=request::get_string('period_begin')?>" class="text" type="text" />
                по
                <input name="period_end" id="period_end" value="<?=request::get_string('period_end')?>" class="text" type="text" />
            </td>
        </tr>
        <tr>
            <td><b>Сума:</b></td>
            <td> від
                <input name="amount_start" id="amount_start" value="<?=request::get_string('amount_start')?>" class="text" type="text" size="8"/>
                до
                <input name="amount_end" id="amount_end" value="<?=request::get_string('amount_end')?>" class="text" type="text" size="8" />грн</td>
        </tr>
        <tr>
            <td><b>Система:</b></td>
            <td>
                <input type="checkbox" name="way[]" value="bank" <?=in_array('bank',$get_ways) ? 'checked' : ''?>>bank
                <input type="checkbox" name="way[]" value="liqpay" <?=in_array('liqpay',$get_ways) ? 'checked' : ''?>>Liqpay
                <input type="checkbox" name="way[]" value="interkassa" <?=in_array('interkassa',$get_ways) ? 'checked' : ''?>>Interkassa
                <input type="checkbox" name="way[]" value="webmoney" <?=in_array('webmoney',$get_ways) ? 'checked' : ''?>>Webmoney
                <input type="checkbox" name="way[]" value="privat" <?=in_array('privat',$get_ways) ? 'checked' : ''?>>Privat
            </td>
        </tr>
        <tr>
            <td><b>Сортування:</b></td>
            <td>
                <select name="order">
                    <option value="id" <?=request::get_string('order')=='id' ? 'selected' : ''?>>ID</option>
                    <option value="date" <?=request::get_string('order')=='date' ? 'selected' : ''?>>дата</option>
                    <option value="fio" <?=request::get_string('order')=='fio' ? 'selected' : ''?>>П.І.Б.</option>
                    <option value="amount" <?=request::get_string('order')=='amount' ? 'selected' : ''?>>сума</option>
                    <option value="way" <?=request::get_string('order')=='way' ? 'selected' : ''?>>система</option>
                </select>
                <input type="radio" name="sc" value="DESC" <?=request::get_string('sc')!='ASC' ? 'checked' : ''?>>&#9660;
                <input type="radio" name="sc" value="ASC" <?=request::get_string('sc')=='ASC' ? 'checked' : ''?>>&#9650;
            </td>
        </tr>
        <tr>
            <td>
                <input class="button" type="submit" name="filter" value="Фільтрувати">
            </td>
        </tr>
    </table>
    </form>
    <table>
    <tr>
        <th rowspan="2">id</th>
        <th rowspan="2">Дата</th>
        <th rowspan="2">Користувач</th>
        <th colspan="3">Сума</th>
        <th rowspan="2">Система</th>
        <th>DEL.</th>
    </tr>
    <tr>
        <th>вступ</th>
        <th>міс</th>
        <th>благ</th>
    </tr>
    <? foreach ($list as $id)
        {
            $payment=db::get_row('SELECT * FROM acquiring_payments WHERE id=:id',array('id'=>$id));
    ?>
            <tr id="tr_<?=$payment['id']?>">
                <td><?=$payment['id']?></td>
                <td class="fs11"><?=date('d.m.Y',$payment['payment_ts'])?></td>
                <td><?=user_helper::full_name($payment['user_id'])?></td>
                <td>
                <?=$payment['opening']?>
                </td>
                <td>
                <?=$payment['monthly']?>/<?=$payment['months']?>
                </td>
                <td>
                <?=$payment['donate']?>
                </td>
                <td><?=$payment['way']?></td>                
                    <td>
                        
                        <? if (request::get_string('status')=='null') { ?>
                                <!--a rel="<?=$payment['id']?>" id="<?=$payment['id']?>" class="approve_payment" href="javascript:;"><img height="14" src="/static/images/icons/approve.png" alt="Підтвердити"/></a-->
                                &nbsp;&nbsp;
                        <? } ?>
                        <a rel="<?=$payment['id']?>" id="<?=$payment['id']?>" href="javascript:;" class="delete_payment ml5 dib icodel"></a>
                    </td>
                    
            </tr>
    <? } ?>
    </table>
</div>
	<div class="bottom_line_d mb10"></div>
	<div class="right pager"><?=pager_helper::get_full($pager)?></div>
</div>

<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript"> 
jQuery(document).ready(function($){
var settings = {
        changeMonth: true,
        changeYear: true,
         autoSize: true,
        showOptions: {direction: 'left' },
        dateFormat: 'dd-mm-yy',
        yearRange: '2010:2012',
        firstDay: true
    };

    $('#period_begin').datepicker(settings);
    $('#period_end').datepicker(settings);
    
    $('#add_payment_a').click(function(){
        $('#pay_date').datepicker(settings);
    });
    
     $("a.approve_payment").click(function(){ 
                if (confirm('Підтвердити?'))
                    {
                        var id = this.id;
                        $.post('/admin/approve_ac_payment',{'approve':id},function(data){
                        if(data=='1'){
                                        $("#tr_"+id).hide();
                        }
                        else {
                            alert('помилка');
                        } 
                            
                });
                }
        }); 
        
     $("a.delete_payment").click(function(){ 
                if (confirm('Видалити?'))
                    {
                        var id = this.id;
                        $.post('/admin/delete_ac_payment',{'del':id},function(data){
                        if(data=='1'){
                                        $("#tr_"+id).hide();
                        }
                        else {
                            alert('помилка');
                        } 
                            
                });
                }
        });

});
</script>