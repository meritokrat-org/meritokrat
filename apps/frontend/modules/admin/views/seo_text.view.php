<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="/static/javascript/library/form/form.js"></script>
<script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "textareas",
     <? if (session::has_credential('admin')) { ?>file_browser_callback : "tinyBrowser",<? } ?>
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "st",
	theme : "advanced",
	skin : "o2k7",
        plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras, insertdatetime,contextmenu,paste,directionality,visualchars,xhtmlxtras,table,media,youtube",

	theme_advanced_buttons1 : "bold,italic,underline,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,forecolor,|,bullist,numlist,|,link,unlink,image,youtube",
    <? if (!session::has_credential('admin')) { ?>
        theme_advanced_buttons2 : "tablecontrols,|,fontselect,fontsizeselect,",
	theme_advanced_buttons3 : "",
	theme_advanced_buttons4 : "",
    <? } else { ?>    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,unlink,link,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
	theme_advanced_buttons5 : "styleselect,formatselect,fontselect,fontsizeselect,link",
   <? } ?>
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",

	content_css: '/static/css/typography.css',
        document_base_url : "https://meritokrat.org/",
        remove_script_host : false,
        convert_urls : false,
        width: 400,
        height:350
});
</script>
<style>
    label {
        width: 200px;
    }
    input.text  { width : 410px;}
    select  { width : 410px;}

</style>
<div class="left ml10 mt10" style="width: 100%;">
        <h1 class="column_head">Вже створені</h1>
        <div class="box_content acenter p10 fs12">
            <?
            if($texts) { ?>
            <table>
                <tr>
                    <th>№</th>
                    <th>Alias</th>
                    <th>Назва</th>
                    <th>Модуль</th>
                    <th>Екшн</th>
                    <th>Прихований</th>
                    <th>Дія</th>
                </tr>
                <?foreach ($texts as $k => $v) {?>
                <tr id="text_<?=$v['id']?>">
                    <td><?=($k+1)?></td>
                    <td><?=$v['alias']?></td>
                    <td><?=$v['name']?></td>
                    <td><?=$v['module']?></td>
                    <td><?=$v['action']?></td>
                    <td><?=($v['hidden']) ? 'Так': 'Ні';?></td>
                    <td>
                        <a href="/admin/seo_text_edit?id=<?=$v['id']?>" title="<?=t('Редактировать')?>" class="dib icoedt"></a>
                        <a href="javascript:;" onClick="if(confirm('Удалить?')) del_item('<?=$v['id']?>')" title="<?=t('Удалить')?>" class="dib icodel"></a>
                    </td>
                </tr>
                <? } ?>
            </table>    
            <? } ?>
        </div>
        <div class="clear"></div>
        <h1 class="column_head">Додати SEO-текст</h1>
        <div class="box_content acenter p10 fs12">
                <a href="javascript:;" onClick="$('#add_text_form').slideToggle(300);">Додати текст</a>
                <div class="clear"></div>
		<form class="aleft hidden" id="add_text_form"  action="/admin/seo_ajax">
                        <input type="hidden" value="add_text" name="type">
                        <table style="margin:0 0 10px 0; width: 25%;">
                            <tr>
                                <td style="padding: 0;">
                                    <div class="label left"><label class="fs10 quiet">Ua</label></div>
                                    <input type="radio" value="ua" style="width: 20px;" checked name="lang[]" class="text left">
                                </td>
                                <td  style="padding: 0;">
                                    <div class="label left"><label class="fs10 quiet">Ru</label></div>
                                    <input type="radio" value="ru" style="width: 20px;" name="lang[]" class="text left">
                                </td >
                                <td style="padding: 0;">
                                    <div class="label left"><label class="fs10 quiet">En</label></div>
                                    <input type="radio" value="en" style="width: 20px;" name="lang[]" class="text left"><br/>
                                </td>
                            </tr>
                        </table>
                        <div class="clear"></div>
                        <div class="label"><label class="fs10 quiet">Alias</label></div>
			<input type="text" value="" name="alias" class="text"><br/>
                        <div class="label"><label class="fs10 quiet">Имя</label></div>
			<input type="text" value="" name="name" class="text"><br/>
                        <div class="label"><label class="fs10 quiet">Текст</label></div>
                        <textarea name="seo_text" id="st"></textarea>
                        <div class="label"><label class="fs10 quiet">Модуль</label></div>
                        <select name="module" class="">
                            <option value="">&mdash;</option>
                            <?
                                if($modules)
                                    foreach ($modules as $k => $v) 
                                        echo '<option value="'.$v.'">'.$v.'</option>';
                            ?>
                        </select><br/>
                        <div class="label"><label class="fs10 quiet">Екшн</label></div>
                        <select name ="action" class="">
                            <option value="">&mdash;</option>
                        </select><br/>
                        <div class="label left"><label class="fs10 quiet">Dynamic</label></div>
                        <input type="checkbox" name="dynamic" value="1" class="text left"><br/>
                        <div class="clear"></div>
                        <input type="submit" name="submit" onClick="$('textarea').val(tinyMCE.get('st').getContent())" class="button left" style="width: auto !important" value="Сохранить" class="text left">
                        <input type="button" name="cancel" class="button_gray left ml10" style="width: auto !important" value="dynamic" class="text left"><br/>
                        
		</form>
        </div>
<script>
    function del_item(id) {
        $.ajax({
           type: 'post',
           url: '/admin/seo_ajax',
           data: {
               type: 'delete_text',
               id: id
           },
           success: function(data) {
               resp = eval("("+data+")");
               if(resp.success==1) 
                   $('#text_'+id).remove();
               else 
                   alert(resp.reason);
           }
           
           
        });
    }
    $('select[name="module"]').change(function(){
        if($(this).val()=='') 
             $('select[name="action"]').html('<option value="">&mdash;</option>');
        
        $.ajax({
            type:'post',
            url: '/admin/seo_ajax',
            data: {
                type: 'get_actions',
                value: $(this).val()
            },
            success: function(data) {
                 resp = eval("("+data+")");
                 if(resp.success==1) {
                     delete resp.success;
                     html = $('select[name="action"]').html();
                     for(k in resp.actions) 
                        html += '<option value="'+resp.actions[k]+'">'+resp.actions[k]+'</option>';
                     $('select[name="action"]').html(html);
                 }
            }
        })
    });
    var addTextForm = new Form(
                        'add_text_form',
                        {
                            success: function(data) {
                                resp = eval("("+data+")");
                                if (resp.success==1) 
                                    window.location = '/admin/seo_text_edit?id='+resp.id;
                                else 
                                    alert(resp.reason);
                            }
                        }
    );
</script>
</div>