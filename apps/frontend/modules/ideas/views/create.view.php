<? $sub_menu = '/ideas/create'; ?>
<? include 'partials/sub_menu.php' ?>

<?/* if ( !$allow_create ) { ?>
	<div class="screen_message acenter"><?=t('Вы сможете высказать идеи, когда Ваш опыт достигнет')?> 5.</div>
<? } else { */?>
	<div class="form_bg">
		<h1 class="column_head"><?=t('Новая идея')?></h1>
                <div class="tab_pane_gray mb10">
                        <a rel="ua" class="tab_menu selected" id="ua_version" href="javascript:;">Україньска</a>
                        <a rel="ru" class="tab_menu" id="ru_version" href="javascript:;">Русский</a>
                        <div class="clear"></div>
                </div>

		<form id="add_form" method="post" class="form mt10">
			<table width="100%" class="fs12">
				<? /*<tr>
					<td class="aright" width="18%"><?=t('Категория')?></td>
					<td>
						<?=tag_helper::select('segment', ideas_peer::get_segments())?>
					</td>
				</tr>  */ ?>
                                <tr id="tr_title_ua">
					<td class="aright" width="18%"><?=t('Название идеи')?></td>
					<td><input type="text" class="text" rel="<?=t('Введите заголовок Вашей идеи')?>" name="title" style="width: 500px;" value="" /></td>
				</tr>
				<tr id="tr_anounces_ua">
					<td class="aright" width="18%"><?=t('Анонс идеи')?><div class="fs11 quiet"><?=t('Изложите краткое содержание идеи')?></div></td>
					<td><textarea rel="<?=t('Введите анонс Вашей идеи')?>" name="anounces" style="width: 500px; height:100px;"></textarea></td>
				</tr>
				<tr id="tr_text_ua">
					<td class="aright" width="18%"><?=t('Содержание идеи')?></td>
					<td><textarea rel="<?=t('Введите текст Вашей идеи')?>" name="text" id="text" style="width: 500px; height:327px;"></textarea></td>
				</tr>
                                <!-- RUSSIAN VERSION -->
				<tr id="tr_title_ru" class="hide">
					<td class="aright" width="18%"><?=t('Название идеи')?> rus</td>
					<td><input type="text" class="text" rel="<?=t('Введите заголовок Вашей идеи')?>" id="title_ru" name="title_ru" style="width: 500px;" value="" /></td>
				</tr>
				<tr id="tr_anounces_ru" class="hide">
					<td class="aright" width="18%"><?=t('Анонс идеи')?> rus<div class="fs11 quiet"><?=t('Изложите краткое содержание идеи')?></div></td>
					<td><textarea rel="<?=t('Введите анонс Вашей идеи')?>" name="anounces_ru" id="anounces_ru" style="width: 500px; height:100px;"></textarea></td>
				</tr>
				<tr id="tr_text_ru" class="hide">
					<td class="aright" width="18%"><?=t('Содержание идеи')?> rus</td>
					<td><textarea rel="<?=t('Введите текст Вашей идеи')?>" name="text_ru" id="text_ru" style="width: 500px; height:327px;"></textarea></td>
				</tr>
                                
        			<tr>
        				<td class="aright"><?=t('Метки')?></td>
        				<td>
                				<input name="tags" style="width:500px;" class="text" type="text" value="" />
                				<div class="fs11 quiet"><?=t('Метки вводятся через запятую, например: бизнес, банки, капитализация, индексы')?></div>
                			</td>
        			</tr>

                                <tr>
                                        <td class="aright"><?=t('Изображение')?></td>
                                        <td id="imageformholder"></td>
                                </tr>

                                <tr class="mt10 mb15">
                                        <td class="aright mb15">&nbsp;</td>
                                        <td></td>
                                </tr>

				<tr>
					<td></td>
					<td>
						<input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
						<input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">


						<div class="success hidden mr10 mt10"><?=t('Мнение добавлено')?></div>
					</td>
				</tr>


<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "exact",
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "text,text_ru",
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
			</table>
		</form>
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

    </div>
<//? } ?>

<script type="text/javascript">
jQuery(document).ready(function(){
        $('#ua_version').click(function(){
                $('#tr_title_ru').hide();
                $('#tr_anounces_ru').hide();
                $('#tr_text_ru').hide();
                $('#tr_title_ua').show();
                $('#tr_anounces_ua').show();
                $('#tr_text_ua').show();
                $('#ua_version').addClass('selected');
                $('#ru_version').removeClass('selected');
            });
        $('#ru_version').click(function(){
                $('#tr_title_ua').hide();
                $('#tr_anounces_ua').hide();
                $('#tr_text_ua').hide();
                $('#tr_title_ru').show();
                $('#tr_anounces_ru').show();
                $('#tr_text_ru').show();
                $('#ru_version').addClass('selected');
                $('#ua_version').removeClass('selected');
            });
    });
</script>