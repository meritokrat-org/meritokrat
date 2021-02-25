<style type="text/css">
    .mpu_payments th {border: 1px solid gray;
    font-size: 12.2px;}
    .mpu_payments td {color:black;
    border: 1px solid gray;
    color: black;
    font-size: 12px;
    padding: 1px 5px;
    vertical-align: middle;
    }
</style>
<div style="width: 760px;" class="left mpu_payments">
<div class="mt10">
    <h1 class="column_head"><a href="/admin/mpu_payments">Благодійні внески</a> <a href="/admin/mpu_payments?status=null" class="right">Не завершені</a></h1>
    
    <div class="form_bg fs12 p5 mb10">
            <? if ($added_payment) { ?>
            <div class="success acenter">Внесок <?=$added_payment?> додано успішно</div>
            <? } ?>
            <div style="color: rgb(51, 51, 51); text-align: justify;" class="left">
                    <a class="right" href="javascript:;" onclick="Application.ShowHide('add_payment');" id="add_payment_a">&nbsp;Додати внесок</a>
            </div>
            <div class="clear"></div>
            <div style="display: none;" class="hidden" id="add_payment">
                <form method="post" class="form_bg mr10 fs12 p10 mb10">
                    <div class="mb5"><b>Дата:</b> <input id="pay_date" name="pay_date" class="text ml15" type="text" /></div>
                    <div class="mb5"><b>Сума:</b> <input name="pay_sum" class="text ml15" type="text" size="10" />UAH</div>
                    <div class="mb5">
                        <b>Система:</b>
                            <input type="radio" value="nal" name="pay_system">нал
                            <input type="radio" value="beznal" name="pay_system">безнал
                            <input type="radio" value="liqpay" name="pay_system">Liqpay
                            <input type="radio" value="interkassa" name="pay_system">Interkassa
                            <input type="radio" value="privat" name="pay_system">Privat
                            <input type="radio" value="webmoney" name="pay_system">Webmoney
                    </div>
                    <div class="mb5"><b>П.I.Б.</b> <input name="pay_fio" class="text ml15" type="text" size="20" /></div>
                    <div class="mb5"><b>email:</b> <input name="pay_email" class="text ml10" type="text" size="10" /></div>
                    
                    <input class="button" type="submit" name="submit" value="<?=t('Добавить')?>">
                </form>
            </div>
    </div>
    
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
            <td><b>Система</b></td>
            <td>
                <input type="checkbox" name="way[]" value="nal" <?=in_array('nal',$get_ways) ? 'checked' : ''?>>нал
                <input type="checkbox" name="way[]" value="beznal" <?=in_array('beznal',$get_ways) ? 'checked' : ''?>>безнал
                <input type="checkbox" name="way[]" value="liqpay" <?=in_array('liqpay',$get_ways) ? 'checked' : ''?>>Liqpay
                <input type="checkbox" name="way[]" value="interkassa" <?=in_array('interkassa',$get_ways) ? 'checked' : ''?>>Interkassa
                <input type="checkbox" name="way[]" value="webmoney" <?=in_array('webmoney',$get_ways) ? 'checked' : ''?>>Webmoney
                <input type="checkbox" name="way[]" value="privat" <?=in_array('privat',$get_ways) ? 'checked' : ''?>>Privat
            </td>
        </tr>
        <tr>
            <td><b>Сайт</b></td>
            <td>
                <input type="checkbox" name="site[]" value="m-p-u.org" <?=in_array('m-p-u.org',$get_sites) ? 'checked' : ''?>>m-p-u.org
                <input type="checkbox" name="site[]" value="meritokrat" <?=in_array('meritokrat',$get_sites) ? 'checked' : ''?>>meritokrat.org
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
        <th>id</th>
        <th>Дата</th>
        <th>П.I.Б.</th>
        <th>email<br>телефон</th>
        <th>Примітки</th>
        <th>Сума</th>
        <th>Система</th>
        <th>Сайт</th>
        <?=request::get_string('status')=='null' ? '<th>Підтв.</th>' : '<th>DEL.</th>' ?>
    </tr>
    <? foreach ($list as $id)
        {
            $payment=db::get_row('SELECT * FROM payment WHERE id=:id',array('id'=>$id),'mpu');
    ?>
            <tr id="tr_<?=$payment['id']?>">
                <td><?=$payment['id']?></td>
                <td class="fs11"><?=date('d.m.Y',strtotime($payment['date'],true))//date('j',strtotime($payment['date'],true)).' '.date_helper::get_month_name(date('n',strtotime($payment['date'],true))).' '.date('y',strtotime($payment['date'],true))?></td>
                <td><?=$payment['fio']?></td>
                <td><?=$payment['email']?>
                    <?=($payment['phone'] && $payment['email']) ? '<br>'.$payment['phone'] : $payment['phone']?></td>
                <td><?=$payment['desc']?></td>
                <td><?=$payment['amount']?><?=$payment['currency'] ? mpu_payments_peer::getcurrency($payment['currency']) : 'UAH'?></td>
                <td><?=$payment['way']?></td>
                <td><?=$payment['site']?></td>
                
                    <td>
                        
                        <? if (request::get_string('status')=='null') { ?>
                                <a rel="<?=$payment['id']?>" id="<?=$payment['id']?>" class="approve_payment dib icoapprove" href="javascript:;" title="Подтвердить"></a>
                                &nbsp;&nbsp;
                        <? } ?>
                        <a rel="<?=$payment['id']?>" id="<?=$payment['id']?>" href="javascript:;" class="delete_payment dib icodel" title="Удалить"></a>
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
                        $.post('/admin/approve_payment',{'approve':id},function(data){
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
                        $.post('/admin/delete_payment',{'del':id},function(data){
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