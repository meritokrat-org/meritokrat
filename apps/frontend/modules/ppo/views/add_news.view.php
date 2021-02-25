<form method="post" class="form_bg mt10">
	<h1 class="column_head"><?=t('Добавление новости')?></h1>
	<table width="100%" class="fs12">
		<tr>
			<td width="18%" class="aright"><?=t('Заголовок')?></td>
			<td><input rel="<?=t('Введите заголовок новости')?>" style="width: 350px;" name="title" type="text" class="text" /></td>
		</tr>
		
                <tr>
                        <td class="aright"><?=t('Текст')?></td>
                        <td><textarea name="text" style="width: 350px;height: 250px;"></textarea></td>
                </tr>
                        <tr>
				<td class="aright"><?=t('Изображение')?></td>
				<td id="imageformholder"></td>
			</tr>
		<tr>
			<td></td>
			<td>
                                <input type="hidden" name="id" value="<?=request::get_int('id',null)?>">
                                <input type="submit" name="submit" class="button" value=" <?=t('Отправить')?> ">
				<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
				<?=tag_helper::wait_panel() ?>
				<div class="success hidden mr10 mt10"><?=t('Новость добавлена')?></div>
			</td>
		</tr>
	</table>

<script type="text/javascript" src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
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

	content_css: '/static/css/typography.css'
});
</script>
</form>
        <form id="upload_form" class="hide" action="/profile/upload" enctype="multipart/form-data" style="position:absolute;">
            <input type="file" id="file" class="text ml5" name="file" alt="" />
            <input type="button" id="add_img" name="add_img" class="button" value=" <?=t('Добавить')?> ">
            <?=tag_helper::wait_panel() ?>
            <input type="submit" name="submit" style="opacity:0;" />
        </form>
        <script>
        var adds = 0;
        <? if (session::has_credential('admin')) { ?>adds = 10;<? } ?>
        $(document).ready(function(){
                var pos = $('#imageformholder').position();
                $('#upload_form').css({'top':pos.top+adds,'left':pos.left}).show();
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