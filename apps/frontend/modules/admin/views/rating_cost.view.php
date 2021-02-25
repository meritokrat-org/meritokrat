<script type="text/javascript" src="/static/javascript/library/form/form.js"></script>
<div class="left ml10 mt10" style="width: 35%;">
<?include 'partials/left.php';?>
</div>
<div class="left ml10 mt10 acenter" style="width: 62%;">
    <h1 class="column_head">Стоимость</h1>
    <div class="box_content acenter p10 fs12">
        <div class="clear"></div>
        <select name="costs" class="left mt10 mb10">
            <option value="">&mdash;</option>
            <?
                if($costs)
                    foreach ($costs as $k => $v) 
                        echo '<option value="'.$v['id'].'">'.$v['name_ru'].'</option>';
            ?>
        </select>
        <div class="clear"></div>
        <form class="form_bg" action="/admin/rating_ajax" id="edit_cost">
            <input type="hidden" name="act" value="save_cost">
            <input type="hidden" name="id" value="">
            <table>
                <tr>
                    <td width="80px">
                        <span><?=t('Id')?></span>
                    </td>
                    <td>
                        <input type="text" disabled style="width: 195px;" name="cost_id" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Alias')?></span>
                    </td>
                    <td>
                        <input type="text" disabled style="width: 195px;" name="cost_alias" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Название')?>(ua)</span>
                    </td>
                    <td>
                        <input type="text" style="width: 195px;" name="cost_name_ua" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Название')?>(ru)</span>
                    </td>
                    <td>
                        <input type="text" style="width: 195px;" name="cost_name_ru" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Стоимость')?></span>
                    </td>
                    <td>
                        <input type="text" style="width: 195px;" name="cost" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span>&nbsp;</span>
                    </td>
                    <td>
                        <input type="submit" name="submit" class="button left" value="<?=t('Сохранить')?>">
                        <input type="button" name="cancel" class="button_gray left" value="<?=t('Отменить')?>" onClick="clearForm()">
                    </td>
                </tr>
            </table>
        </form>
        <div class="clear"></div>
        <h1 class="column_head">Стоимость региона</h1>
        <div class="clear"></div>
        <select name="region_costs" class="left mt10 mb10">
            <option value="">&mdash;</option>
            <?
                if($costs)
                    foreach ($region_costs as $k => $v) 
                        echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
            ?>
        </select>
        <div class="clear"></div>
        
        
        <form class="form_bg" action="/admin/rating_ajax" id="edit_region_cost">
            <input type="hidden" name="act" value="save_region_cost">
            <input type="hidden" name="id" value="">
            <table>
                <tr>
                    <td width="80px">
                        <span><?=t('Id')?></span>
                    </td>
                    <td>
                        <input type="text" disabled style="width: 195px;" name="region_cost_id" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Alias')?></span>
                    </td>
                    <td>
                        <input type="text" disabled style="width: 195px;" name="region_cost_alias" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Название')?></span>
                    </td>
                    <td>
                        <input type="text" style="width: 195px;" name="region_cost_name" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Стоимость')?></span>
                    </td>
                    <td>
                        <input type="text" style="width: 195px;" name="region_cost" class="text left">
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span>&nbsp;</span>
                    </td>
                    <td>
                        <input type="submit" name="submit" class="button left" value="<?=t('Сохранить')?>">
                        <input type="button" name="cancel" class="button_gray left" value="<?=t('Отменить')?>" onClick="clearForm()">
                    </td>
                </tr>
            </table>
        </form>
        
        
        <div class="clear"></div>
        <h1 class="column_head">Добавить балы</h1>
        <div class="clear"></div>
        <form autocomplete="off" onSubmit="return false;">
            <table style="margin: 10px 0 0 0;">
                <tr>
                    <td style="padding: 0px;">
                        <input type="text" id="search_users" style="float: left; clear: none" name="user_to">
                        <input type="button" class="ml10 button" id="add_points" onClick="showAddForm();" value="<?=t('Добавить')?>">
                        <input type="button" class="ml10 button" id="edit_points" value="<?=t('Показать')?>" onClick="getUserPoints(0)">
                    </td>
                </tr>
                <tr>
                    <td style="padding: 0px;">
                        <div id="user_list2" class="cb" style="display: none;"></div>
                    </td>
                </tr>
            </table>
        </form>
        <form id="add_points_form" class="hidden" action="/admin/rating_ajax">
            <input type="hidden" name="act" value="add_points">
            <input type="hidden" name="id" value="">
            <table>
                <tr>
                    <td>
                        <?=t('Количество баллов')?>
                    </td>
                    <td>
                        <input class="text left" style="width: 250px;" type="text" name="points_count">
                    </td>
                </tr>
                <tr>
                    <td>
                        <?=t('Начислено за:')?>
                    </td>
                    <td>
                        <textarea style="width: 250px;" class=" left" name="add_reason"></textarea>                    
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <input type="submit" name="submit" class="button left" value="<?=t('Сохранить')?>">
                        <input type="button" class="button_gray left" onClick="$('#add_points_form').slideToggle(300)" value="<?=t('Отменить')?>">
                    </td>
                </tr>
            </table>
        </form>
        <table id="user_points" class="hidden">
        </table>
        <div id="paginator"></div>
        <div class="success hidden"><?=t('Изменения сохранены')?></div>
        <div class="error hidden"></div>
    </div>
</div>
<style>
    
    #user_points {
        margin-top: 10px;
    }
    #user_points td,
    #user_points th {
        border: 1px solid #ccc;
        border-collapse: collapse;
    }
</style>
<script>
    $('#search_users').focusout(function(){
          $("form#add_points_form input[name='id']").val($('#search_users').attr('uid'));
    });
    $('select[name="costs"]').change(function(){
       if($(this).val()=='') {
            $('form#edit_cost input[type="text"]').val('');
       } 
       else 
           $.ajax({
              type: 'post',
              url: '/admin/rating_ajax',
              data: {
                  id: $(this).val(),
                  act: 'get_select',
                  type: 'cost'
              },
              success: function(data) {
                  resp = eval("("+data+")");
                  if(resp.success==1) {
                      $('form#edit_cost input[name="id"]').val(resp.id);
                      $('input[name="cost_id"]').val(resp.id);
                      $('input[name="cost_alias"]').val(resp.alias);
                      $('input[name="cost_name_ru"]').val(resp.name_ru);
                      $('input[name="cost_name_ua"]').val(resp.name_ua);
                      $('input[name="cost"]').val(resp.cost);
                  }
                  else 
                      alert(resp.reason);
              }
           });
    });
    $('select[name="region_costs"]').change(function(){
       if($(this).val()=='') {
            $('form#edit_region_cost input[type="text"]').val('');
       } 
       else 
           $.ajax({
              type: 'post',
              url: '/admin/rating_ajax',
              data: {
                  id: $(this).val(),
                  act: 'get_select',
                  type: 'region_cost'
              },
              success: function(data) {
                  resp = eval("("+data+")");
                  if(resp.success==1) {
                      $('form#edit_region_cost input[name="id"]').val(resp.id);
                      $('input[name="region_cost_id"]').val(resp.id);
                      $('input[name="region_cost_alias"]').val(resp.alias);
                      $('input[name="region_cost_name"]').val(resp.name);
                      $('input[name="region_cost"]').val(resp.rate);
                  }
                  else 
                      alert(resp.reason);
              }
           });
    });
    costForm = new Form(
        'edit_cost',
        {
            success: function(data) {
                if(data.success==1)
                    $('.success').fadeIn(300, function(){$(this).fadeOut(5000)});
                else 
                    $('.error').fadeIn(300, function(){$(this).html(data.reason); $(this).fadeOut(5000)});
            }
        }
    );
    regionCostForm = new Form(
        'edit_region_cost',
        {
            success: function(data) {
                if(data.success==1)
                    $('.success').fadeIn(300, function(){$(this).fadeOut(5000)});
                else 
                    $('.error').fadeIn(300, function(){$(this).html(data.reason); $(this).fadeOut(5000)});
            }
        }
    );
    adminPointsForm = new Form(
        'add_points_form',
        {
            success: function(data) {
                if(data.success==1)
                    $('.success').fadeIn(300, function(){$(this).fadeOut(5000)});
                else 
                    $('.error').fadeIn(300, function(){$(this).html(data.reason); $(this).fadeOut(5000)});
            }
        }
    );
    function showAddForm(id) {
        if(id=='undefined') alert('Введіть корректне і&aposмя або прізвище');
        if($('#user_points').css('display')=='table') $('#user_points').hide();
        $('#add_points_form').slideToggle(300); 
        $("form#add_points_form input[name='id']").val($('#search_users').attr('uid'));
    }
    function getUserPoints(p) {
        if($('#add_points_form').css('display')=='block') $('#add_points_form').slideToggle(300);
        //if(id=='undefined') alert('Введыть корректне і&aposмя або прізвище');
        else {
            if($('#search_users').val()=='')
                $('#search_users').attr('uid','0');
            $.ajax({
                type: 'post',
                url: '/admin/rating_ajax',
                data: {
                    act: 'get_user_points',
                    id: $('#search_users').attr('uid'),
                    p: p
                },
                success: function(data) {
                    resp = eval("("+data+")");
                    if(resp.success==1) {
                        html = '<tr><th>Кому нараховано</th>\n\
                                    <th>Кількість баллів</th>\n\
                                    <th>Причина нарахування</th>\n\
                                    <th>Коли нараховано\n\
                                    </th>\n\
                                    <th>Ким нараховано\n\
                                    </th>\n\
                                    <th>Дія</th>\n\
                                    </tr>';
                        for(k in resp.data) {
                            html+='<tr id="row_'+resp.data[k].id+'">\n\
                                    <td class="acenter">'+resp.data[k].to+'</td>\n\
                                    <td class="acenter">'+resp.data[k].points+"</td>\n\
                                    <td style='acenter'>\n\
                                    "+resp.data[k].reason+"</td>\
                                    <td class='acenter'>\
                                    "+resp.data[k].created_ts+"</td>\
                                    </td><td class='acenter'>\
                                    "+resp.data[k].from+"</td>\n\
                                    <td class='acenter'><a class='dib icodel pointer' title='Удалить' onclick='deletePoints("+resp.data[k].id+")'></a></td></tr>\n\
                                    ";
                        }
                    }
                    else 
                        html = '<tr><td coslpan="4">'+resp.reason+'</td></tr>';
                    $('#user_points').html(html);
                    if(resp.next || resp.prev)
                            $('#paginator').html("<table style='margin: 0px;'>\n\
                                        <tr><td colspan='6' class='aright'>\n\
                                            "+(resp.prev ? "<a href='javascript:;' onClick='getUserPoints("+(resp.page-1)+")'>&larr;Назад</a>" : "")+"&nbsp;&nbsp;&nbsp;"+(resp.next ? "<a href='javascript:;' onClick='getUserPoints("+(resp.page+1)+")'>Далі&rarr;</a>" : "")+"\n\
                                        </td></tr>\n\
                                    </table>");
                                            
                    $('#user_points').show();
                }
                
            })
        }
        
    }
    function deletePoints(id) {
        $.ajax({
            type: 'post',
            url: '/admin/rating_ajax',
            data: {
                act: 'delete_admin_points',
                id: id
            },
            success: function(data) {
                resp = eval("("+data+")");
                if(resp.success==1) 
                    $('#row_'+id).remove();
                else 
                   $('.error').fadeIn(300, function(){$(this).html(resp.reason); $(this).fadeOut(5000)}); 
            }
            
        })
    }
    
</script>