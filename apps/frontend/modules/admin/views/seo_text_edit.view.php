<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>
<div class="left ml10 mt10" style="width: 62%;">
    <h1 class="column_head">SEO-тексти</h1>
<?if($edit_text) { ?>
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
	<div class="box_content acenter p10 fs12">
            
		<form class="aleft" id="edit_text_form" action="/admin/seo_ajax">
                        <input type="hidden" value="add_text" name="type">
                        <input type="hidden" value="<?=request::get_int('id')?>" name="edit_id">
                        <input type="hidden" value="<?=$edit_text['alias']?>" name="alias"></input>
                        
                        <div class="label left"><label class="fs10 quiet">Ua</label></div>
			<input type="radio" value="ua" checked name="lang[]" class="text left">
                        <div class="label left"><label class="fs10 quiet">Ru</label></div>
			<input type="radio" value="ru" name="lang[]" class="text left">
                        <div class="label left"><label class="fs10 quiet">En</label></div>
			<input type="radio" value="en" name="lang[]" class="text left"><br/>
                        
                        <div class="label"><label class="fs10 quiet">Alias</label></div>
			<input type="text" value="<?=$edit_text['alias']?>" disabled name="alias" class="text"><br/>
                        <div class="label"><label class="fs10 quiet">Alias</label></div>
			<input type="text" value="<?=$edit_text['name']?>" name="name" class="text"><br/>
                        <div class="label"><label class="fs10 quiet">Текст</label></div>
                        <textarea name="seo_text" id="st"><?=  stripslashes($edit_text['text_ua'])?></textarea>
                        <div class="label"><label class="fs10 quiet">Модуль</label></div>
                        <select name="module" class="">
                            <option value="">&mdash;</option>
                            <?
                                
                                if($modules)
                                    foreach ($modules as $k => $v) {
                                        print_r('<option value="'.$v.'"');
                                        echo ($v==$edit_text['module']) ? ' selected ': '';
                                        print_r('>'.$v.'</option>');
                                    }
                            ?>
                            
                        </select><br/>
                        <div class="label"><label class="fs10 quiet">Екшн</label></div>
                        <select name ="action" class="">
                            <option value="">&mdash;</option>
                        </select><br/>
                        <div class="label left"><label class="fs10 quiet">Dynamic</label></div>
                        <input type="checkbox" name="dynamic" value="1" <?=($edit_text['hidden']) ? ' checked ' : ''?> class="text left"><br/>
                        <div class="clear"></div>
                        <input type="submit" name="submit" onClick="$('textarea').val(tinyMCE.get('st').getContent());" class="button left" style="width: auto !important" value="Сохранить" class="text left">
                        <input type="button" name="cancel" class="button_gray left ml10" style="width: auto !important" value="dynamic" class="text left"><br/>
                        
		</form>
            <div class='clear'></div>
            <div class="success hidden"><?=t('Изменения сохранены')?></div>
        </div>

<script>
    currAct = '<?= $edit_text['action']?>';
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
                     for(k in resp.actions) {
                        html += '<option value="'+resp.actions[k]+'"';
//                        action = resp[k].action;
                        if(currAct==resp.actions[k].replace('.action.php','')) html += ' selected ';
                        html += '>'+resp.actions[k]+'</option>';
                     }
                     $('select[name="action"]').html(html);
                 }
            }
        })
    }).change();
    var editTextForm = new Form(
                        'edit_text_form',
                        {
                            success: function() {
                                $('.success').fadeIn(300, function(){$(this).fadeOut(3000);});
                            }
                        }
    );
    $('form').find('input[type="radio"]').change(function(){
       $.ajax({
          type: 'post',
          url: '/admin/seo_ajax' ,
          data: {
                 type: 'get_text_by_lang',
                 id: $('input[name="edit_id"]').val(),
                 lang: $(this).val()
                },
          success: function(resp) {
                $data = eval("("+resp+")");
                tinyMCE.activeEditor.setContent($data.content);
          }
       });
    });
</script>
<? } ?>
</div>