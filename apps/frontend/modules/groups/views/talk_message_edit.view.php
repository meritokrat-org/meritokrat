	<form class="form_bg mr10 fs12 mb10" method="post" action="/groups/talk_message_edit">
		<h3 class="column_head_small"><?=t('Редактировать сообщение')?></h3>
                <input type="hidden" name="id" value="<?=$message['id']?>">
		<div class="ml10 mr10">
                    <table width="100%" class="fs12">
                        <? if (request::get('tinymce')==1) { ?>
			<tr>
                            <td class="aright" width="18%">Тема:</td>
                            <td>
                                <input type="text" name="topic" style="width: 99%;" value="<?=stripslashes(htmlspecialchars($topic['topic']))?>">
                            </td>
			</tr>
                        <? } ?>
			<tr>
                            <td class="aright" width="18%">Текст:</td>
                            <td>
                                <textarea style="width: 99%; height: <?=request::get('tinymce')==1 ? '450' : '200'?>px;" name="text" rel="<?=t('Введите текст сообщения')?>"><?=stripslashes(htmlspecialchars($message['text']))?></textarea>
                            </td>

			</tr>

                        <tr>
                            <td class="aright"><?=t('Изображение')?></td>
                            <td id="imageformholder"></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                            <input name="submit" type="submit" value=" <?=t('Сохранить')?> " class="button">
                            <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">

                            </td>
                        </tr>
                    </table>
		</div>
<? if (request::get('tinymce')==1) { ?>

<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
     <? if (session::has_credential('admin')) { ?>file_browser_callback : "tinyBrowser",<? } ?>
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "text",
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
        convert_urls : false

});
</script>
<? } ?>
</form>

<? if (request::get('tinymce')==1) { ?>
<form id="upload_form" class="hide" action="/profile/upload" class="" enctype="multipart/form-data" style="position:absolute;">
    <input type="file" id="file" class="text ml5" name="file" alt="" />
    <input type="button" id="add_img" name="add_img" class="button" value=" <?=t('Добавить')?> ">
    <?=tag_helper::wait_panel() ?>
    <input type="submit" name="submit" style="opacity:0;" />
</form>
<script>
$(document).ready(function(){
        var pos = $('#imageformholder').position();
        $('#upload_form').css({'top':pos.top+10,'left':pos.left}).show();
        _clear();
});
function _clear(){
    $('#add_img').click(function(){
        if($('#file').val()){
            $('#upload_form').trigger('submit');
            $('#file').val('');
            document.getElementById('file').innerHTML = document.getElementById('file').innerHTML;
        }
    });
}
</script>
<? } ?>