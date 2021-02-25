<?php
/**
 * @var array $user
 */
?>

<script src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script>
    jQuery(document).ready(function ($) {
        $.datepicker.setDefaults($.extend(
                $.datepicker.regional['uk']),
        );
        $('#tdate').datepicker({
            changeMonth: true,
            changeYear: true,
            autoSize: true,
            showOptions: {direction: 'left'},
            dateFormat: 'mm/dd/yy',
        });
    });
</script>

<style type="text/css">
    .ui-datepicker {
        margin-left: 100px;
    }

    .tab_pane a {
        margin-left: 5px;
    }

    .atab {
        margin-right: 5px;
    }

    #ui-datepicker-div {
        display: none;
    }
</style>

<div class="profile row"
     style="background: url('/static/images/common/bg-lines-bg.png') repeat-x scroll 0 0 transparent;">
    <div class="col-4">
        <?php include 'partials/profile.nav.php' ?>
    </div>

    <div class="col ml-0 pl-0 pt-2 position-relative">

        <?php $user_old = $user; ?>

        <?php include __DIR__.'/partials/profile.head.php' ?>

        <?php if (($user_old['offline'] || $user_old['del']) && $user_old['why']) { ?>
            <div class="clear"></div>
            <div class="mb10 ml10"><span style="font-weight:bold;color:#660000"><?= t('Причина удаления') ?>
                    :</span> <?= $user_old['why'] ?></div>
        <?php } ?>


        <!-- USER CONTENT -->

        <div id="divacontent" class="hide">
            <div class="pt5 fs11 tab_pane_gray">

                <a rel="comments" href="javascript:void 0;"
                   class="selected"><?= t('Комментарии') ?><?= $count_coms > 0 ? '&nbsp;'.$count_coms : '' ?></a>
                <a rel="files"
                   href="javascript:;"><?= t('Библиотека') ?><?= ($filecount > 0) ? '&nbsp;'.$filecount : '' ?></a>
                <a rel="foto"
                   href="javascript:;"><?= t('Фото') ?><?= ($photocount = count(
                            $photos
                    )) > 0 ? '&nbsp;'.$photocount : '' ?></a>

                <?php /* if ( $user_data['bio'] ) { ?>
				<a rel="bio" href="javascript:;"><?=t('Биография')?></a>
			<? } ?>
			<? if ( $candidate['program'] ) { ?>
				<a rel="program" href="javascript:;"><?=t('Програма')?></a>
			<? } */ ?>
                <!--a rel="debates" href="javascript:;"><?= t('Дебаты') ?></a-->
                <a rel="polls"
                   href="javascript:;"><?= t('Опросы') ?><?= $count_polls > 0 ? '&nbsp;'.$count_polls : '' ?></a>
                <!--a rel="ideas" href="javascript:;"><?= t('Идеи') ?></a-->


                <?php if (user_auth_peer::get_rights(session::get_user_id(), $user_data['contact_access'])) {/*if ($user_education && @count(array_unique(@array_values($user_education)))>2  ) { ?>
				<a rel="education" href="javascript:;"><?=t('Образование')?></a>
			<? } ?>
			<? if ($user_work && @count(array_unique(@array_values($user_work)))>2  ) { ?>
				<a rel="work" href="javascript:;"><?=t('Работа')?></a>
			<? } */
                    ?>
                <?php } ?>
                <div class="clear"></div>
            </div>

            <?php include 'partials/boxes/files.php' ?>
            <?php // include 'partials/boxes/program.php' ?>
            <?php // include 'partials/boxes/debates.php' ?>
            <?php include 'partials/boxes/polls.php' ?>

            <?php include 'partials/boxes/comments.php' ?>
            <?php include 'partials/boxes/foto.php' ?>

            <?php // include 'partials/boxes/ideas.php' ?>

        </div>

        <div id="divablog" class="hide">
            <div class="pt5 fs11 tab_pane_gray">
                <a rel="blog" href="javascript:;" class="selected">
                    <?= session::get_user_id() === $user_old['id'] ? t('Мой блог') : t('Блог') ?>

                    <?= $count_blog_posts > 0 ? '&nbsp;'.$count_blog_posts : '' ?></a>
                <?php if (session::get_user_id() == $user['id'] || session::has_credential('admin')) { ?>
                    <a rel="notate"
                       href="javascript:;"> <?= t('Заметки') ?> <?= $count_note_post ? $count_note_post : '' ?></a>
                    <a rel="archive"
                       href="javascript:;"> <?= t('Архив') ?> <?= $count_archive_post ? $count_archive_post : '' ?></a>

                    <?php if (user_auth_peer::instance()->get_rights(
                                    session::get_user_id(),
                                    10
                            ) && session::get_user_id() == $user_old['id']) { ?>
                        <a class="" href="/blogs/edit"><?= t('Добавить запись') ?></a>
                    <?php } ?>
                <?php } ?>
                <div class="clear"></div>
            </div>
            <?php include 'partials/boxes/blogs.php' ?>
            <?php if (session::get_user_id() == $user_old['id'] || session::has_credential('admin')) { ?>
                <?php include 'partials/boxes/notate.php' ?>
                <?php include 'partials/boxes/archive.php' ?>
            <?php } ?>


        </div>

        <?php if (session::has_credential('admin')) { ?>
            <div class="fs11 box p10 mt10">
                <form action="/admin/save_user">
                    <input type="hidden" name="user_id" value="<?= $user_old['id'] ?>">
                    <?php $credentials = (array) explode(',', $user_old['credentials']) ?>
                    <?php if (!$user_old['offline']) { ?>
                        Призначити права:
                        <input type="checkbox" <?= in_array('editor', $credentials) ? 'checked' : '' ?>
                               name="credentials[]" value="editor"/> Публикатор
                        <input type="checkbox" <?= in_array('admin', $credentials) ? 'checked' : '' ?>
                               name="credentials[]" value="admin"/> Администратор
                        <input type="checkbox" <?= in_array('moderator', $credentials) ? 'checked' : '' ?>
                               name="credentials[]" value="moderator"/> Модератор
                        <input type="checkbox" <?= in_array('selfmoderator', $credentials) ? 'checked' : '' ?>
                               name="credentials[]" value="selfmoderator"/> Самомодератор
                        <br/>
                        <input type="checkbox" <?= in_array('programmer', $credentials) ? 'checked' : '' ?>
                               name="credentials[]" style="margin-left:103px" value="programmer"/> Программист
                        <?php if (session::has_credential('superadmin') || session::get_user_id() == 5) { ?><input
                            type="checkbox" <?= in_array('redcollegiant', $credentials) ? 'checked' : '' ?>
                            name="credentials[]"
                            value="redcollegiant" /> Член редколегії<?php } elseif (in_array(
                                'redcollegiant',
                                $credentials
                        )) { ?>
                            <input type="hidden" name="credentials[]" value="redcollegiant"/><?php } ?>
                        <br/>
                    <?php } ?>
                    Мітки:
                    <input type="checkbox" <?= $user_old['interesting'] ? 'checked' : '' ?> name="interesting"
                           value="TRUE"/> Цікава особистість
                    <br>
                    <?php if (in_array(session::get_user_id(), [5, 11752, 18181], true)) { ?>
                        Може змiнювати статуси:
                        <?= tag_helper::select(
                                'schanger',
                                [0 => 'нi', 1 => 'так'],
                                [
                                        'id' => 'schanger',
                                        'value' => intval(db_key::i()->get('schanger'.$user_data['user_id'])),
                                ]
                        ) ?>
                        <br>
                    <?php } ?>
                    Контактує:
                    <?php $who_contacts = user_novasys_data_peer::get_who_contacts();
                    $who_contacts['']   = '&mdash;';
                    ksort($who_contacts);
                    ?>
                    <?= tag_helper::select(
                            'contacted',
                            $who_contacts,
                            ['use_values' => false, 'value' => $user_novasys['contacted'], 'style' => 'width:200px;']
                    ) ?>
                    <input type="checkbox" name="sendcontact"
                           class="sendcontact <?= in_array(
                                   $user_novasys['contacted'],
                                   [1, 2, 3, 4, 5]
                           ) ? '' : 'hide' ?>"
                           value="1"/><span
                            class="sendcontact <?= in_array(
                                    $user_novasys['contacted'],
                                    [1, 2, 3, 4, 5]
                            ) ? '' : 'hide' ?>"><?= t('Отправить сообщение') ?></span>
                    <br/>
                    <textarea name="contactedtext" id="contactedtext" class="hide"></textarea>
                    <br/>

                    <input type="submit" class="button" value="Зберегти"/>
                </form>
            </div>
        <?php } ?>

    </div>
</div>

<?php /* if ( $user['id'] == session::get_user_id() ) { ?>
	<div class="box_content fs10 p5 mr10 quiet">
		<?=t('Ссылка на мой профиль')?>:
		<input readonly class="quiet" onclick="this.select();" style="border:0px; width: 510px; background: #FAFAFA; font-size: 11px;" type="text" value="<?=htmlspecialchars('<a href="http://' . context::get('host') . '/profile-' . $user['id'] . '">Я на Meritokrat.org</a>')?>" />
	</div>
<? } */ ?>

<script>
    $(document).ready(function () {
        $('select[name$="contacted"]').change(function () {
            if ($(this).val() != '' && $(this).val() != 10) {
                $('.sendcontact').show();
            } else {
                $('.sendcontact').attr('checked', '').hide();
                $('#contactedtext').val('').hide();
            }
        });
        $('input.sendcontact').click(function () {
            if ($(this).is(':checked'))
                $('#contactedtext').show();
            else
                $('#contactedtext').val('').hide();
        });
        $('.info').click(function () {
            if (!$('#file_describe_' + this.id).is(':visible')) {
                $('#file_describe_' + this.id).slideDown(100);

            } else {
                $('#file_describe_' + this.id).slideUp(100);
            }
        });
        $('.tab_pane_gray > a').bind('click', function () {
            $(this).parent().find('a').removeClass('selected');
            $(this).addClass('selected');
            $(this).parent().parent().find('.content_pane').hide();
            $('#pane_' + $(this).attr('rel')).show();
            $(this).blur();
        });
    });
</script>
