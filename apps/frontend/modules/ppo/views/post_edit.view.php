<div class="mt10 form_bg">

	<h1 class="column_head">
				<a href="/group<?=request::get_int('group_id') ? request::get_int('group_id') : $post_data['ppo_id']?>"><?=$group['title']?></a> &rarr;
                                <?= $post_data ? t('Редактирование записи') : t('Создание записи') ?></h1>

	<form id="edit_form" name="edit_form" class="form" method="post">
			<input type="hidden" name="group_id" value="<?=$post_data['ppo_id'] ? $post_data['ppo_id'] : request::get_int('group_id')?>" />
		<? if ( $post_data ) { ?>
			<input type="hidden" name="id" value="<?=$post_data['id']?>" />
                        <input type="hidden" name="why" value="<?=$why?>" />
		<? } ?>
		<table width="100%" class="fs12">
                    <? if ($warning==1) { ?>
                        <tr>
				<td class="acenter" colspan=2 style="color:red;"> <b>Помилка:</b> Не заповнені всі обов’язкові поля!</td>
			</tr>
                    <? } ?>
			<tr>
				<td class="aright" width="18%"><?=t('Заголовок')?><b> *</b></td>
				<td><input name="title" rel="<?=t('Введите заголовок')?>" style="width:513px;" class="text" type="text" value="<?=$_POST['title'] ?  stripslashes(htmlspecialchars($_POST['title'])) : stripslashes(htmlspecialchars($post_data['title']))?>" /></td>
			</tr>

			<tr>
				<td class="aright"><?=t('Текст')?><b> *</b></td>
                                <td height="350"><textarea rel="<?=t('Введите текст')?>" name="body" style="height:350px;width:513px;"><?=$_POST['body'] ?  stripslashes($_POST['body']) : stripslashes($post_data['body'])?></textarea></td>
			</tr>

                        <tr>
				<td class="aright"><?=t('Изображение')?></td>
				<td id="imageformholder"></td>
			</tr>
                       <tr>
                            <td class="aright"><?=t('Показывать не зарегистрированным')?></td>
                                <td><input value="1"<?=($post_data['id'] && db_key::i()->exists($rkey))?'checked':''?> type="checkbox" name="public"/></td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
					<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">


					<div class="success hidden mr10 mt10"><?=t('Запись сохранена')?></div>
				</td>
			</tr>

		</table>

<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>
<script type="text/javascript">

// O2k7 skin
tinyMCE.init({
	mode : "exact",
     <? if (session::has_credential('admin')) { ?>file_browser_callback : "tinyBrowser",<? } ?>
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "body",
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
        height: 350

});
</script>

	</form>
        <form id="upload_form" class="hide" action="/profile/upload" class="" enctype="multipart/form-data" style="position:absolute;">
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
</div>
