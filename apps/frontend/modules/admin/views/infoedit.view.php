<style>
input.ifull{width:500px;}
textarea{width:720px;}
</style>
<script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
<script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php" type="text/javascript"></script>
<script type="text/javascript">
// O2k7 skin
tinyMCE.init({
	mode : "textareas",
     <? if (session::has_credential('admin')) { ?>file_browser_callback : "tinyBrowser",<? } ?>
	language : '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
	elements : "text_ua,text_ru,admin_text_ua,admin_text_ru",
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
        width: 720,
        height:450
});
</script>
<? $num=1 ?>
<? if(session::get('language')!='ru') $lang='ua'; else $lang='ru'; ?>
<div class="form_bg mt10">

    <h1 class="column_head"><a href="/admin/info"><?=t('Подсказки')?></a> &rarr; <?=t('Редактирование')?></h1>

    <div class="tab_pane_gray mb10">
        <a href="javascript:;" id="tab_ua" class="tab_menu selected" rel="ua"><?=t('Украинский')?></a>
        <a href="javascript:;" id="tab_ru" class="tab_menu" rel="ru"><?=t('Русский')?></a>
        <div class="clear"></div>
    </div>

    <form id="ua_form" class="form mt10">
        <input type="hidden" name="id" value="<?=$info['id']?>"/>
        <input type="hidden" name="lang" value="ua"/>
        <table width="100%" class="fs12 ml10">
            <tr>
                <td>
                    <b><?=t('Название')?></b><br/>
                    <input class="ifull" name="title" rel="<?=t('Введите название')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($info['title']))?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <b><?=t('Заголовок')?></b><br/>
                    <input class="ifull" name="name_ua" rel="<?=t('Введите заголовок')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($info['name_ua']))?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <b><?=t('Текст')?></b><br/>
                    <textarea name="text_ua" rel="<?=t('Введите текст')?>" class="text"><?=stripslashes(htmlspecialchars($info['text_ua']))?></textarea>
                </td>
            </tr>
            <? if(session::has_credential('programmer')){ ?>
            <tr>
                <td>
                    <b><?=t('Дополнительный текст')?></b><br/>
                    <textarea name="admin_text_ua" rel="<?=t('Введите текст')?>" class="text"><?=stripslashes(htmlspecialchars($info['admin_text_ua']))?></textarea>
                </td>
            </tr>
            <? } ?>
            <tr>
                <td>
                        <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                        <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                        <?=tag_helper::wait_panel('ua_wait') ?>
                        <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                </td>
            </tr>
        </table>
    </form>

     <form id="ru_form" class="form mt10 hidden">
        <input type="hidden" name="id" value="<?=$info['id']?>"/>
        <input type="hidden" name="lang" value="ru"/>
        <table width="100%" class="fs12 ml10">
            <tr>
                <td>
                    <b><?=t('Название')?></b><br/>
                    <input class="ifull" name="title" rel="<?=t('Введите название')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($info['title']))?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <b><?=t('Заголовок')?></b><br/>
                    <input class="ifull" name="name_ru" rel="<?=t('Введите заголовок')?>" class="text" type="text" value="<?=stripslashes(htmlspecialchars($info['name_ru']))?>" />
                </td>
            </tr>
            <tr>
                <td>
                    <b><?=t('Текст')?></b><br/>
                    <textarea name="text_ru" rel="<?=t('Введите текст')?>" class="text"><?=stripslashes(htmlspecialchars($info['text_ru']))?></textarea>
                </td>
            </tr>
            <? if(session::has_credential('programmer')){ ?>
            <tr>
                <td>
                    <b><?=t('Дополнительный текст')?></b><br/>
                    <textarea name="admin_text_ru" rel="<?=t('Введите текст')?>" class="text"><?=stripslashes(htmlspecialchars($info['admin_text_ru']))?></textarea>
                </td>
            </tr>
            <? } ?>
            <tr>
                <td>
                        <input type="submit" name="submit" class="button" value=" <?=t('Сохранить')?> ">
                        <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray" value=" <?=t('Отмена')?> ">
                        <?=tag_helper::wait_panel('ua_wait') ?>
                        <div class="success hidden mr10 mt10"><?=t('Изменения сохранены')?></div>
                </td>
            </tr>
        </table>
    </form>

</div>
<script type="text/javascript">
    $('input[type="submit"]').click(function(){
        $('textarea[name="text_ua"]').val(tinyMCE.get('text_ua').getContent());
        $('textarea[name="text_ru"]').val(tinyMCE.get('text_ru').getContent());
        $('textarea[name="admin_text_ua"]').val(tinyMCE.get('admin_text_ua').getContent());
        $('textarea[name="admin_text_ru"]').val(tinyMCE.get('admin_text_ru').getContent());
    });
</script>