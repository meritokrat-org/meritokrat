<script type="text/javascript" src="/static/javascript/library/form/form.js"></script>
<div class="left ml10 mt10" style="width: 100%;">
    <h1 class="column_head">SEO теги</h1>
    <div class="box_content acenter p10 fs12">
        <form class="form_bg mt10" action="/admin/seo_ajax" id="edit_tags">
            <input type="hidden" name="type" value="add_tags">
            <input type="hidden" name="mod_tag_id" value="">
            <input type="hidden" name="act_tag_id" value="">
            <table>
                <tr>
                    <td width="80px">
                        <span><?=t('Язык')?></span>
                    </td>
                    <td>
                        <div class="label left"><label class="fs10 quiet" >Ua</label></div>
			<input type="radio" value="ua" checked name="lang[]" style="width: 20px;" class="text left">
                        <div class="label left"><label class="fs10 quiet" style="width: 20px;">Ru</label></div>
			<input type="radio" value="ru" name="lang[]" class="text left">
                        <div class="label left"><label class="fs10 quiet" style="width: 20px;">En</label></div>
			<input type="radio" value="en" name="lang[]" class="text left">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 70%;">
                        <h1 class="column_head">Modules</h1>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Модуль')?></span>
                    </td>
                    <td>
                        <select name="modname" class="left" style="width: 200px;" onChange="changeModule()">
                            <option value="">&mdash;</option>
                            <?
                                if($modules)
                                    foreach ($modules as $k => $v) 
                                        echo '<option value="'.$v.'">'.$v.'</option>';
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="80px">
                        <span><?=t('Заголовок')?></span>
                    </td>
                    <td>
                        <input type="text" style="width: 195px;" name="module_title" class="text left" value="<?=$tags['title_ua']?>"
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 70%;">
                        <h1 class="column_head">Actions</h1>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td width="150px">
                        <span><?=t('Екшн')?></span>
                    </td>
                    <td>
                        <select name ="actname"  class="left" style="width: 200px;" onChange="changeAction()">
                            <option value="">&mdash;</option>
                        </select>
                        
                        
                    </td>
                </tr>
                <tr class="hidden" id="title_type">
                    <td>
                        <span><?=t('Заголовок')?></span>
                    </td>
                    <td >
                        <div class="label left"><label class="fs10 quiet"><?=t('Динамический')?></label></div>
                        <input type="radio" value="3" name="ttype[]" class="text left"><br/>
                        <div class="label left"><label class="fs10 quiet"><?=t('Статический')?></label></div>
                        <input type="radio" value="2" name="ttype[]" class="text left">
                        <div class="clear"></div>
                        <div class="hidden mt5 mb5" id="stat_type">
                            <input style="width: 200px;" value="" type="text" name="action_title" class="text left">
                        </div>
                        <div class="clear"></div>
                        <div class="label left"><label class="fs10 quiet"><?=t('Отсутсвует')?></label></div>
                        <input type="radio" value="1" name="ttype[]" class="text left">
                    </td>
                </tr>
                    <tr id="attl" class="hidden">
                        <td width="100px">
                            <span><?=t('Ключевые слова')?></span>
                        </td>
                        <td>
                            <input type="text" style="width: 195px;" name="action_kw" class="text left">
                        </td>
                    </tr>
                    <tr id="akw" class="hidden">
                        <td width="100px">
                            <span><?=t('Описание')?></span>
                        </td>
                        <td>
                            <input type="text" style="width: 195px;" name="action_desc" class="text left">
                        </td>
                    </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="submit" class="button left" value="<?=t("Сохранить")?>">
                        <input type="button" name="cancel" class="button_gray left" value="<?=t("Отменить")?>" onClick="history.go(-1)">
                    </td>
                </tr>
            </table>
        </form>
        <div class="success hidden"><?=t('Изменения сохранены')?></div>
    </div>
</div>

<script>
function changeAction() {    
         $this = $('select[name="actname"]');
         currAct =  $('select[name="actname"] option:selected').val();

         $('input[name="ttype[]"]').removeAttr('checked');
         $('#stat_type').hide();
         $('input[name="action_kw"]').val('');
         $('input[name="action_desc"]').val('');
         $('input[name="action_title"]').val('');
         $('input[name="act_tag_id"]').val('');
         
        if($this.val()=='') $('#title_type','#attl','#akw').hide();
        else {
            $('#attl').show();
            $('#title_type').show();
            $('#akw').show();
            
            $.ajax({
                type:'post',
                url: '/admin/seo_ajax',
                data: {
                    type: 'get_action_data',
                    value: $this.val(),
                    modname: $('select[name="modname"] option:selected').val(),
                    language: $('input[name="lang[]"]:checked').val()
                },
                success: function(data) {
                     resp = eval("("+data+")");
                     if(resp.success==1) {
                         if(resp.id) {
                             $('input[name="ttype[]"]').each(function() {
                                 if($(this).val()==resp.ttype) $(this).attr('checked',1);
                                 if(resp.ttype=='2') {
                                     $('input[name="action_title"]').val(resp.title);
                                     $('#stat_type').show();
                                 }
                                 
                             });
                             $('input[name="action_kw"]').val(resp.kwds);
                             $('input[name="action_desc"]').val(resp.desc);
                             $('input[name="act_tag_id"]').val(resp.id);
                         }
                     }
                }
            });
        }
        
//    });
}
    $('input[name="ttype[]"]').change(function(){
            if($(this).val()!='2') $('#stat_type').hide();
            else $('#stat_type').show();
    });
function changeModule() {
        currAct =  $('select[name="actname"] option:selected').val();
        $this = $('select[name="modname"]');
        
//        $('select[name="actname"] option').removeAttr('selected');
        $('input[name="module_title"]').val('');
        $('input[name="mod_tag_id"]').val('');
        if($this.val()=='') 
             $('select[name="actname"]').html('<option value="">&mdash;</option>');
        
        $.ajax({
            type:'post',
            url: '/admin/seo_ajax',
            data: {
                type: 'get_actions',
                value: $this.val(),
                language: $('input[name="lang[]"]:checked').val()
            },
            success: function(data) {
                 resp = eval("("+data+")");
                 if(resp.success==1) {
                     if(resp.module_tags) {
                         $('input[name="module_title"]').val(resp.module_tags.title);
                         $('input[name="mod_tag_id"]').val(resp.module_tags.id);
                     }
                     
                     delete resp.success;
                     delete resp.module_tags;
                     
                     html = $('select[name="actname"]').html();
                     for(k in resp.actions) {
                        html += '<option value="'+resp.actions[k]+'"';
                        if(currAct==resp.actions[k]) html += ' selected ';
                        html += '>'+resp.actions[k]+'</option>';
                     }
                     $('select[name="actname"]').html(html);
                 }
            }
        });
        changeAction();
}
    tagForm = new Form(
        'edit_tags',
        {
            success: function(data) {
                if(data.success==1) {
                    $('.success').fadeIn(300, function() {$(this).fadeOut(5000);});
                }
            }
        }
    );
    $('input[name="lang[]"]').change(function() {
       currAct =  $('select[name="actname"] option:selected').val();
       changeModule();
       
       changeAction();
    });
</script>