<?
//новости или обьявления
$mpu_types = array(blogs_posts_peer::TYPE_NEWS_POST, blogs_posts_peer::TYPE_DECLARATION_POST);
$post_data ? $post_type = $post_data['type'] : $post_type = request::get_int('type');
$sub_menu = '/blogs/edit'; ?>
<? include 'partials/sub_menu.php' ?>
<script type="text/javascript">
    function textchange() {
        var count = 280
        a = document.edit_form.anounces.value.length;
        if (a > count) {
            document.edit_form.anounces.value = document.edit_form.anounces.value.substring(0, count);
        }
        a = document.edit_form.anounces.value.length;
        document.edit_form.count_sym.value = count - a;
    }
</script>
<? if (request::get('tab')) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.form').hide();
            $('#tab_<?=request::get('tab')?>').show();
            $('#<?=request::get('tab')?>_form').show();
        });
    </script><? } ?>


<div class="form_bg">

    <h1 class="column_head"><?= $post_data ? t('Редактирование записи') : t('Создание записи') ?></h1>
    <? if ($post_data['mission_type'] == 3 || $post_data['mission_type'] == 2)include 'partials/tabs.php' ?>
    <div id="resizeable">
        <form id="edit_form" name="edit_form" class="form" method="post">
            <? if ($post_data) { ?>
                <input type="hidden" name="id" value="<?= $post_data['id'] ?>"/>
                <input type="hidden" name="why" value="<?= $why ?>"/>
                <input type="hidden" name="type" value="<?= $post_data['type'] ?>"/>
                <input type="hidden" name="mission_type" value="<?= $post_data['mission_type'] ?>"/>
            <? } else { ?>
                <input type="hidden" name="type" value="<?= request::get_int('type') ?>"/>
            <? } ?>
            <table width="100%" class="fs12">
                <? if ($warning == 1) { ?>
                    <tr>
                        <td class="acenter" colspan=2 style="color:red;"><b>Помилка:</b> Не заповнені всі обов’язкові
                            поля!
                        </td>
                    </tr>
                <? } ?>
                <tr>
                    <td class="aright" width="18%"><?= t('Заголовок') ?><b> *</b></td>
                    <td><input name="title" rel="<?= t('Введите заголовок') ?>" style="width:513px;" class="text"
                               type="text"
                               value="<?= $_POST['title'] ? stripslashes(htmlspecialchars($_POST['title'])) : stripslashes(htmlspecialchars($post_data['title'])) ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="aright">Cфера</td>
                    <td>
                        <?= tag_helper::select('sfera', blogs_posts_peer::get_sferas(), array('value' => ($post_data['sfera'] ? $post_data['sfera'] : 30))) ?>
                    </td>
                </tr>
                <? if (session::has_credential('admin')) { ?>
                    <tr>
                        <td class="aright">*<?= t('Дата') ?></td>
                        <td>
                            <input name="created_ts" id="created_ts" style="width:513px;" class="text" type="text"
                                   value="<?= $post_data['created_ts'] ? date("d.m.Y H:i", ($post_data['created_ts'])) : '' ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="aright">*<?= t('Просмотров') ?></td>
                        <td>
                            <input name="views" style="width:513px;" class="text" type="text"
                                   value="<?= $_POST['views'] ? intval($_POST['views']) : intval($post_data['views']) ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="aright">*<?= t('Оценка') ?></td>
                        <td>
                            <input name="for" style="width:513px;" class="text" type="text"
                                   value="<?= $_POST['for'] ? intval($_POST['for']) : intval($post_data['for']) ?>"/>
                        </td>
                    </tr>
                <? } ?>
                <tr>
                    <td class="aright">*<?= t('Тип') ?></td>
                    <td>
                        <? $blogs_types = array(
                            blogs_posts_peer::TYPE_BLOG_POST => 'Блог',
                            blogs_posts_peer::TYPE_NOTATE_POST => t('Заметка'),
                            blogs_posts_peer::TYPE_ARCHIVE_POST => t('Архив'));
                        if (session::has_credential('admin') || (session::has_credential('editor') || (!$post_data && session::get_user_id() == $post_data['user_id']))) $blogs_types[blogs_posts_peer::TYPE_MIND_POST] = t('Публикация');
                        if (session::has_credential('admin')) {
                            $blogs_types[blogs_posts_peer::TYPE_NEWS_POST] = t('Новость');
                        }
                        if (session::has_credential('admin')) {
                            $blogs_types[blogs_posts_peer::TYPE_PROGRAMA_POST] = '*' . t('Програма');
                        }
                        ?>
                        <?= tag_helper::select('newtype', $blogs_types, array('value' => $post_data['type'], 'id' => 'typechanger', 'style' => 'float:left')) ?>
                    </td>
                </tr>
                <? if (session::has_credential('admin')) { ?>
                    <tr>
                        <td class="aright">&nbsp;</td>
                        <td>
                            <input type="checkbox" id="notchangeauthor" name="notchangeauthor" value="1"/>
                            <label for="notchangeauthor"><?= t('Не менять автора') ?></label>
                        </td>
                    </tr>
                <? } ?>
                <? if (session::has_credential('admin')) { ?>
                    <tr class="programholder" style="<?= ($post_data['type'] != 9) ? 'display:none' : '' ?>">
                        <td class="aright"></td>
                        <td>
                            <div style="display: flex; flex-flow: row nowrap">
                                <div>
                                    <div style="margin-bottom: 5px">*<?= t('Тема') ?></div>
                                    <select name="programs[]" multiple="true" style="min-height:35em">
                                        <? if (!is_array($programs))$programs = array() ?>
                                        <? $programtypes = user_helper::get_program_types() ?>
                                        <? foreach ($programtypes as $k => $v) { ?>
                                            <option
                                                value="<?= $k ?>" <?= (in_array($k, $programs)) ? 'selected' : '' ?>><?= $v ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                                <div>
                                    <div style="margin-bottom: 5px">*<?= t('Целевая группа') ?></div>
                                    <select name="target_groups[]" multiple="true" style="min-height:35em">
                                        <? if (!is_array($targets))$targets = array() ?>
                                        <? $grouptypes = user_helper::get_target_groups() ?>
                                        <? foreach ($grouptypes as $k => $v) { ?>
                                            <option
                                                value="<?= $k ?>" <?= (in_array($k, $targets)) ? 'selected' : '' ?>><?= $v ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <tr class="programholder" style="<?= ($post_data['type'] != 9) ? 'display:none' : '' ?>">
                        <td class="aright"></td>
                        <td>
                            <label><input type="checkbox" class="mpubox" name="mpu"
                                          value="1" <?= ($post_data['mpu'] == 1) ? 'checked' : '' ?> /> <?= t('Идеи') ?>
                            </label>
                            &nbsp;
                            <label><input type="checkbox" class="mpubox" name="mpu"
                                          value="2" <?= ($post_data['mpu'] == 2) ? 'checked' : '' ?> /> <?= t('Позиция') ?>
                            </label>
                        </td>
                    </tr>
                <? } ?>
                <? if (!in_array($post_data['type'], $mpu_types) && !in_array(request::get_int('type'), $mpu_types)) { ?>
                    <tr>
                        <td class="aright"><?= t('Метки') ?></td>
                        <td>
                            <input name="tags" style="width:513px;" class="text" type="text"
                                   value="<?= $_POST['tags'] ? stripslashes(htmlspecialchars($_POST['tags'])) : stripslashes(htmlspecialchars($post_data['tags_text'])) ?>"/>
                            <div
                                class="fs11 quiet"><?= t('Метки вводятся через запятую, например: бизнес, банки, капитализация, индексы') ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="aright"><?= t('Анонс') ?><b> *</b></td>
                        <td><textarea rel="<?= t('Введите анонс') ?>" onkeyup="textchange()" name="anounces"
                                      id="anounces"
                                      style="height:100px;width:513px;"><?= $_POST['anounces'] ? stripslashes(htmlspecialchars($_POST['anounces'])) : stripslashes(htmlspecialchars($post_data['anounces'])) ?></textarea><br>
                            <?= t('Осталось символов') ?>: <input type='text' size="3" name='count_sym' disabled
                                                                  value='<?= (280 - mb_strlen(stripslashes(htmlspecialchars($post_data['anounces'])))) ?>'>
                        </td>
                    </tr>
                <? } elseif (session::has_credential('admin')) { ?>
                    <tr>
                        <td class="aright"><?= t('Удерживать на главной') ?><b> *</b></td>
                        <td>
                            <input type="hidden" name="anounces" value="&nbsp;"/>
                            <? if (db::get_scalar("SELECT count(*) FROM blogs_posts WHERE type=" . $post_type . " AND favorite=true") < 3) { ?>
                                <input type="checkbox" name="favorite"
                                       <? if ($_POST['favorite'] || $post_data['favorite']) { ?>checked<? } ?> >
                            <? } else { ?>
                                Вже відмічено три публікації
                            <? } ?>
                        </td>
                    </tr>
                <? } ?>
                <tr>
                    <td class="aright"><?= t('Текст') ?><b> *</b></td>
                    <td height="350">
                        <textarea rel="<?= t('Введите текст') ?>" name="body"
                                  style="height:350px;width:513px;"><?= $_POST['body'] ? stripslashes($_POST['body']) : stripslashes($post_data['body']) ?></textarea>
                    </td>
                </tr><? /*
			<tr>
				<td class="aright"><?=t('Упоминания')?></td>
				<td>
					<input id="mention" style="width:513px;" class="text" type="text" value="" />
					<div class="fs11 quiet"><?=t('Начинайте вводить имя упоминаемого в этой статье человека')?></div>
					<div style="width:513px;" class="mt5 fs11" id="mentions"></div>
				</td>
			</tr>*/ ?>
                <? if (!session::has_credential('admin')) { ?>
                    <tr>
                        <td class="aright"><?= t('Изображение') ?></td>
                        <td id="imageformholder"></td>
                    </tr>
                <? } ?>
                <tr>
                    <td class="aright">* <?= t('Показывать только зарегистрированным') ?></td>
                    <td>
                        <input
                            value="1" <?= (($post_data['public'] != true && gettype($post_data['public']) || !isset($post_data['public'])) == "boolean") ? 'checked' : '' ?>
                            type="checkbox" name="private"/>
                    </td>
                </tr>
                <? if (session::has_credential('admin')) { ?>
                    <tr>
                        <td class="aright">*Без коментарів</td>
                        <td><input value="1" <?= ($post_data['nocomments']) ? 'checked' : '' ?> type="checkbox"
                                   name="nocomments"/></td>
                    </tr>
                <? } ?>
                <? if (session::has_credential('admin')) { ?>
                    <tr>
                        <td class="aright">*Без оцiнок</td>
                        <td><input value="1" <?= ($post_data['novotes']) ? 'checked' : '' ?> type="checkbox"
                                   name="novotes"/></td>
                    </tr>
                <? } ?>
                <? if (session::has_credential('admin')) { ?>
                    <tr>
                        <td class="aright">&nbsp;</td>
                        <td>
                            <input value="1" <?= ($post_data['show_in_mpu'] != false) ? 'checked' : '' ?>
                                   type="checkbox" id="show_in_mpu" name="show_in_mpu"/>
                            <label for="show_in_mpu"><?= t('Показать на m-p-u.org') ?></label>
                        </td>
                    </tr>
                <? } ?>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
                        <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
                               value=" <?= t('Отмена') ?> ">

                        <? /* foreach ( $blog_types as $type => $type_title ) { ?>
						<input type="radio" class="ml10" name="type" value="<?=$type?>" id="post_type_<?=$type?>"
							<?= ($post_data['type'] == $type) || ( !$post_data['type'] && $type == blogs_posts_peer::TYPE_BLOG_POST ) ? 'checked' : ''?> />
						<label class="fs11" for="post_type_<?=$type?>"><?=$type_title?></label>
					<? } */ ?>

                        <div class="success hidden mr10 mt10"><?= t('Запись сохранена') ?></div>
                    </td>
                </tr>

            </table>

            <script src="/static/javascript/library/tinymce/tiny_mce.js"></script>
            <script src="/static/javascript/library/tinymce/plugins/tinybrowser/tb_tinymce.js.php"
                    type="text/javascript"></script>
            <script type="text/javascript">

                // O2k7 skin
                tinyMCE.init({
                    mode: "exact",
                    <? if (session::has_credential('admin')) { ?>file_browser_callback: "tinyBrowser",<? } ?>
                    language: '<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>',
                    elements: "body",
                    theme: "advanced",
                    skin: "o2k7",
                    plugins: "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras, insertdatetime,contextmenu,paste,directionality,visualchars,xhtmlxtras,table,media,youtube",

                    theme_advanced_buttons1: "bold,italic,underline,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,forecolor,|,bullist,numlist,|,link,unlink,image,youtube,|,code",
                    <? if (!session::has_credential('admin')) { ?>
                    theme_advanced_buttons2: "tablecontrols,|,fontselect,fontsizeselect,",
                    theme_advanced_buttons3: "",
                    theme_advanced_buttons4: "",
                    <? } else { ?>
                    theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,unlink,link,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                    theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid",
                    theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
                    theme_advanced_buttons5: "styleselect,formatselect,fontselect,fontsizeselect,link",
                    <? } ?>
                    theme_advanced_toolbar_location: "top",
                    theme_advanced_toolbar_align: "left",
                    <? if (session::has_credential('admin')) { ?>
                    theme_advanced_statusbar_location: "bottom",
                    theme_advanced_resizing: true,
                    <? } ?>

                    content_css: '/static/css/typography.css',
                    document_base_url: "https://meritokrat.org/",
                    remove_script_host: false,
                    relative_urls: false,
                    convert_urls: true,
                    height: 350

                });
            </script>

        </form>
        <?
        load::view_helper('image');
        ?>
        <form method="post" id="photo_form" class="form_bg mt10 form hidden" enctype="multipart/form-data">
            <table width="100%" class="fs12">
                <tr>
                    <td>
                        <div class="left acenter" style="width: 250px;">
                            <?
                            if (!$post_data['photo'])
                                echo user_helper::photo(31, 'p', array('class' => 'border1', 'id' => 'photo'));
                            else
                                echo image_helper::photo($post_data['photo'], 'p', 'blogs', array('class' => 'border1', 'id' => 'photo'));
                            ?>
                        </div>
                    </td>
                    <td class="aright" width="30%"><?= t('Выберите файл') ?></td>
                    <td><input type="file" name="file"
                               rel="<?= t('Картинка неверная либо слишком большая') ?>"/><br/><br/>
                        <input type="submit" name="submit" class="button" value="<?= t('Сохранить') ?> ">
                        <input onclick="history.go(-1);" type="button" name="cancel" class="button_gray"
                               value=" <?= t('Отмена') ?> "></td>
                </tr>
            </table>
        </form>
    </div>
    <? if (!session::has_credential('admin')) { ?>
        <form id="upload_form" class="hide" action="/profile/upload" class="form" enctype="multipart/form-data"
              style="position:absolute;">
            <input type="file" id="file" class="text ml5" name="file" alt=""/>
            <input type="button" id="add_img" name="add_img" class="button" value=" <?= t('Добавить') ?> ">
            <?= tag_helper::wait_panel() ?>
            <input type="submit" name="submit" style="opacity:0;"/>
        </form>
    <? } ?>
    <script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
    <script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
    <? include 'partials/datepicker.php' ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var settings = {
                changeMonth: true,
                changeYear: true,
                autoSize: true,
                showOptions: {direction: 'left'},
                dateFormat: 'dd.mm.yy',
                yearRange: '2010:2012',
                firstDay: true
            };
            $('#created_ts').datetimepicker(settings);
            $('.mpubox').change(function () {
                if ($(this).is(':checked')) {
                    $('.mpubox').attr('checked', false);
                    $(this).attr('checked', true);
                }
            });
        });
    </script>
    <script>
        var adds = 0;
        <? if (session::has_credential('admin')) { ?>adds = 10;<? } ?>
        $(document).ready(function () {
            var pos = $('#imageformholder').position();
            $('#upload_form').css({'top': pos.top + adds, 'left': pos.left}).show();
            _clear();
        });
        function _clear() {
            $('#add_img').click(function () {
                if ($('#file').val()) {
                    $('#upload_form').trigger('submit');
                    $('#file').val('');
                    document.getElementById('file').innerHTML = document.getElementById('file').innerHTML;
                }
            });
        }
    </script>

</div>
