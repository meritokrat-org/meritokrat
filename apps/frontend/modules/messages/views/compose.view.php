<? if (request::get_int('group')) { ?>
    <script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
    <script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php"
            type="text/javascript"></script>
    <script type="text/javascript">
        // O2k7 skin
        tinyMCE.init({
            mode: 'exact',
            <? if (session::has_credential('admin')) { ?>file_browser_callback: 'tinyBrowser',<? } ?>
            language: '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
            elements: 'body',
            theme: 'advanced',
            skin: 'o2k7',
            plugins: 'safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras, insertdatetime,contextmenu,paste,directionality,visualchars,xhtmlxtras,table,media,youtube',

            theme_advanced_buttons1: 'bold,italic,underline,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,forecolor,|,bullist,numlist,|,link,unlink,image,youtube',
            <? if (!session::has_credential('admin')) { ?>
            theme_advanced_buttons2: 'tablecontrols,|,fontselect,fontsizeselect,',
            theme_advanced_buttons3: '',
            theme_advanced_buttons4: '',
            <? } else { ?>
            theme_advanced_buttons2: 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,unlink,link,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor',
            theme_advanced_buttons3: 'tablecontrols,|,hr,removeformat,visualaid',
            theme_advanced_buttons4: 'insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage',
            theme_advanced_buttons5: 'styleselect,formatselect,fontselect,fontsizeselect,link',
            <? } ?>
            theme_advanced_toolbar_location: 'top',
            theme_advanced_toolbar_align: 'left',

            content_css: '/static/css/typography.css',
        });
    </script>
<? } ?>
<script type="text/javascript">
    function setSelectionRange(input, selectionStart, selectionEnd) {
        if (input.setSelectionRange) {
            input.focus();
            input.setSelectionRange(selectionStart, selectionEnd);
        } else if (input.createTextRange) {
            var range = input.createTextRange();
            range.collapse(true);
            range.moveEnd('character', selectionEnd);
            range.moveStart('character', selectionStart);
            range.select();
        }
    }

    function setCaretToPos(input, pos) {
        setSelectionRange(input, pos, pos);
    }

    $(document).ready(function () {
        setCaretToPos(document.getElementById('body'), 0);
        if ($('#receiver').length == 1) {
            $('#receiver').val('<?=t("Администрация Меритократ.орг")?>');
        }
    });
</script>

<div class="form_bg">
    <h1 class="column_head mt10"><a href="/messages"><?= t('Cообщения') ?></a> &rarr; <?= t('Новое сообщение') ?></h1>

    <form id="compose_form" name="compose_form" class="form mt10" rel="<?= t('Начинайте вводить имя друга') ?>..."
          onsubmit="return false;">
        <input type="hidden" name="receiver_id"
               value="<?= request::get_int('group') ? request::get_int('group') : $user['user_id'] ?><?= $uauth["ban"] > 0 ? "10599" : "" ?>"/>
        <table width="100%" class="fs12">
            <tr>
                <td class="aright"><?= t('Имя получателя') ?></td>
                <td>
                    <? if (session::has_credential('admin')) { ?><input type="hidden" name="sender_id"
                                                                        value="<?= request::get_int('sender_id') ?>"> <? } ?>
                    <? if (request::get_int('group')) { ?>Группа <?= $group_title ?>
                        <input type="hidden" name="group" value="<?= request::get_int('group') ?>">
                        <input type="hidden" value="0" rel="<?= t('Выберите получателя') ?>" style="width: 500px;"
                               name="receiver"/>
                    <? } else if ($uauth["ban"] > 0) { ?>
                        <input type="hidden" class="text" rel="<?= t('Выберите получателя') ?>" style="width: 500px;"
                               name="receiver" id="receiver"/>
                        <?= t('В связи с ограничением прав в сети, Вы можете отправлять сообщения только <a href="/profile-10599">Администрации Меритократ.орг</a>.') ?>
                    <? } else { ?>
                        <input type="text" class="form-control" rel="<?= t('Выберите получателя') ?>"
                               style="width: 500px;"
                               name="receiver"/>
                    <? } ?>
                </td>
            </tr>
            <? if (!request::get_int('group')) { ?>
                <tr>
                    <td class="aright"></td>
                    <td>
                                    <span class="quiet fs11">
																		<? if ($uauth["ban"] == 0) { ?>
                                                                            * <?= t('Введите имя друга или нескольких друзей через запятую') ?>
                                                                            <br/>
                                                                        <? } ?>
                                    </span>
                    </td>
                </tr>
            <? } ?>
            <tr>
                <td class="aright"><?= t('Сообщение') ?></td>
                <td><textarea id="body" class="form-control" rel="<?= t('Введите текст сообщения') ?>" name="body"
                              style="width: 500px; height:150px;"><?= (request::get('body')) ? stripslashes(htmlspecialchars(request::get('body'))) : (($message_data['body']) ? "\n\n\n" . t('Переадресованное сообщение') . ':' . $message_data['body'] : '') ?></textarea>
                    <? if (request::get_int('group')) { ?>
                        <div class="fs10">* <b>NAME</b> - для зазначення імені в листі</div>
                    <? } ?>
                </td>
            </tr>
            <? if (!request::get_int('group')) { ?>
                <tr>
                    <td></td>
                    <td style="cursor: pointer; width: 600px;">
                        <? $smiles = messages_peer::instance()->get_smiles();
                        foreach ($smiles as $code => $value) { ?>
                            <a class="ml5 mr5" onclick='InsertSmile("<?= $code ?>")'><?= $value ?></a>
                        <? } ?>
                </tr>
            <? } ?>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="submit" class="button" value=" <?= t('Отправить') ?> ">
                    <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
                           value=" <?= t('Отмена') ?> ">
                    <?= tag_helper::wait_panel() ?>
                    <div class="success hidden mr10 mt10"><?= t('Сообщение отправлено') ?></div>
                </td>
            </tr>

        </table>
    </form>
</div>
<script language="javascript" type="text/javascript">
    <!--
    var ie = document.all ? 1 : 0;
    var ns = document.getElementById && !document.all ? 1 : 0;

    function InsertSmile(SmileId) {
        if (ie) {
            document.all.body.focus();
            document.all.body.value += ' ' + SmileId + ' ';
        } else if (ns) {
            document.forms['compose_form'].elements['body'].focus();
            document.forms['compose_form'].elements['body'].value += ' ' + SmileId + ' ';
        }
    }

    // -->
</script>
