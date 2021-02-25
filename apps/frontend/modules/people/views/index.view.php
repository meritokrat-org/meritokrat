<?php
load::model('user/user_sessions');
load::model('geo');
?>

<div class="left" style="width: 35%;">
    <?php include 'partials/left.php' ?>
</div>

<div class="left ml10"
     style="<?php if (!session::is_authenticated()) { ?>width: 98%;<?php } else { ?>width: 62%;<?php } ?>">
    <h1 class="column_head mt10">
        <?php if ($cur_type > 0) { ?>
            <?= user_auth_peer::instance()->get_type_s($cur_type) ?>
        <?php } elseif ($cur_status > 0 && request::get_int('region')) { ?>
            <?= user_auth_peer::instance()->get_status_s($cur_status) ?>
        <?php } elseif (request::get_int('region')) { ?>
            <?= geo_peer::instance()->get_region_name(request::get_int('region')) ?>
        <?php } elseif ($cur_status > 0) { ?>
            <?= user_auth_peer::instance()->get_status_s($cur_status) ?>
        <?php } elseif (request::get_int('function')) { ?>
            <?= t('По функции') ?>
            <?= user_auth_peer::get_function(request::get_int('function')) ?>
        <?php } elseif (request::get_int('expert')) { ?>
            <?= t('Эксперты') ?>
        <?php } elseif (request::get_int('famous')) { ?>
            <?= t('Известные люди') ?>
        <?php } elseif (request::get_int('suslik')) { ?>
            <?= t('Скрытый профиль') ?>
        <?php } elseif (request::get('identification')) { ?>
            <?= t('Не идентифицированные') ?>
        <?php } elseif (request::get_int('activate')) { ?>
            <?= t('Останнi активованi') ?>
        <?php } elseif (request::get('offline')) { ?>
            <?= t('Офф-лайн сторонники') ?>
        <?php } elseif (request::get_int('del')) { ?>
            <?= t('Удаленные') ?>
        <?php } elseif (request::get_int('target')) { ?>
            <?= t('По целевой группе') ?>
        <?php } elseif (request::get_int('admintarget')) { ?>
            *<?= t('По целевой группе') ?>
        <?php } elseif (request::get_int('list')) { ?>
            <?= t('По списку') ?>
        <?php } elseif (request::get_int('meritokrat')) { ?>
            <?= t('Меритократы') ?>
        <?php } else { ?>
            <!--<a href="/people"><?= t('Все') ?></a>-->
        <?php } ?>
        <!--<?= $total ?>-->
        <!--div style="display:inline; margin-left:140px"><a style="text-transform: none;" href="/people?online=1">Посл посещение</a></div-->

        <span class="right">
                    <a href="/people?online=1" style="text-transform:none;">Онлайн</a>
                    <?php if ($cur_type > 0) { ?>
                        <?= db::get_scalar(
                                'SELECT count(user_id) FROM user_sessions WHERE visit_ts>=:visit_ts AND user_id IN (SELECT id FROM user_auth WHERE type=:type)',
                                ['visit_ts' => time() - 600, 'type' => $cur_type]
                        ) ?>
                    <?php } elseif (request::get_int('function')) { ?>
                        <?= db::get_scalar(
                                'SELECT count(user_id) FROM user_sessions WHERE visit_ts>=:visit_ts AND user_id IN (SELECT user_id FROM user_desktop WHERE functions && :function)',
                                ['visit_ts' => time() - 600, 'function' => '{'.request::get_int('function').'}']
                        ) ?>
                    <?php } else { ?>
                        <?= db::get_scalar(
                                'SELECT count(user_id) FROM user_sessions WHERE visit_ts>=:visit_ts',
                                ['visit_ts' => time() - 600]
                        ) ?>
                    <?php } ?>
                </span>
    </h1>
    <?php if (request::get('type') > 0
            || request::get_string('type') === '0'
            || request::get_int('expert')
            || request::get_int('famous')
            || request::get('identification')
            || request::get_int('activate')
            || request::get('offline')
            || request::get_int('del')) { ?>
        <div class="mt5 fs11 mb10">
            <a href="/people" class="cgray">&larr; <?= t('Вернуться к общему списку') ?></a>
        </div>
    <?php } ?>

    <?php if (!$list) { ?>
        <div class="acenter fs11 quiet m10 p10"><?= t('Тут еще никого нет') ?>...</div>
    <?php } else { ?>

    <?php /* if ( $cur_type == 4 ) { ?>
			<? $sorts = array('popularity' => t('поддержке'), 'rating' => t('рейтингу') ) ?>

			<div class="box_content mb10 p5 fs11">
				<?=t('Показать по')?>
				<? foreach ( $sorts as $filter => $title ) { ?>
					<?  if ( $sort == $filter ) { ?>
						<b><?=$title?></b>
					<? } else { ?>
						<a href="/people/index?type=<?=$cur_type?>&sort=<?=$filter?>"><?=$title?></a>
					<? } ?>
				<? } ?>
			</div>
		<? } */ ?>

        <style>
            .placeholder {
                height: 1.5em;
                line-height: 1.2em;
            }
        </style>

    <?php if (session::has_credential('admin') && context::get_controller()->get_module() === 'people') { ?>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <script>
            var j = jQuery.noConflict();
            j(document).ready(function () {

                j('#sort_list').sortable({
                    connectWith: '#sort_buffer',
                    placeholder: 'placeholder',
                    start: function (event, ui) {
                        $('div.minimize', ui.item).show();
                        $('div.maximize', ui.item).hide()
                                .parent()
                                .removeClass('p10 mb10');
                    },
                    stop: function (event, ui) {
                        if ($(ui.item).parent().attr('id') == 'sort_buffer') {
                            $('div.minimize', ui.item).show();
                            $('div.maximize', ui.item).hide()
                                    .parent().removeClass('p10 mb10');
                            $('a.del_sort', ui.item).show();
                        } else {
                            $('div.minimize', ui.item).hide();
                            $('div.maximize', ui.item).show()
                                    .parent().addClass('p10 mb10');
                            $('a.del_sort', ui.item).hide();
                        }

                        $(ui.item).removeClass('sortable_li');

                        var sort_ids = [];

                        $('li', '#sort_buffer').each(function (n, e) {
                            sort_ids.push($(e).attr('data-user-id'));
                        });

                        $.post('/people', {
                            sort: true,
                            list: sort_ids,
                        }, function (response) {
                            console.log(response);
                        }, 'json');
                    },
                    remove: function (event, ui) {

                        var id = $(ui.item).attr('data-user-id');

                        if ($('#sort_buffer').children('li[data-user-id=' + id + ']').length > 1) {
                            ui.item.remove();
                        }

                        $('#sort_list').children('li[data-user-id=' + id + ']:not(.sortable_li)').remove();

                        ui.item.clone().prependTo('#sort_list');

                        $('#sort_list').children('li[data-user-id=' + id + ']').children('div').children('.minimize').hide();
                        $('#sort_list').children('li[data-user-id=' + id + ']').children('div').addClass('p10 mb10');
                        $('#sort_list').children('li[data-user-id=' + id + ']').children('div').children('.maximize').show();
                    },
                }).disableSelection();

                j('#sort_buffer').sortable({
                    connectWith: '#sort_list',
                    stop: function (event, ui) {
                        var sort_ids = [];

                        $('li', '#sort_buffer').each(function (n, e) {
                            sort_ids.push($(e).attr('data-user-id'));
                        });

                        $.post('/people', {
                            sort: true,
                            list: sort_ids,
                        }, function (response) {
                        }, 'json');
                    },
                    remove: function (event, ui) {
                        var id = $(ui.item).attr('data-user-id');

                        $('#sort_list').children('li[data-user-id=' + id + ']:not(.sortable_li)').remove();

                    },
                }).disableSelection();

                $('.del_sort').live('click', function () {
                    $(this).parent().remove();

                    var sort_ids = [];

                    $('li', '#sort_buffer').each(function (n, e) {
                        sort_ids.push($(e).attr('data-user-id'));
                    });

                    $.post('/people', {
                        sort: true,
                        list: sort_ids,
                    }, function (response) {
                        console.log(response);
                    }, 'json');
                });
            });
        </script>
    <?php } ?>

    <?php if (count($all_coordinators) > 0 && (!request::get_int('page') || request::get_int('page') == 1)){ ?>
        <div class="mb10 mt10"
             style="-moz-border-radius: 4px 4px 4px 4px;border:solid 1px gray;float:left;width:469px;padding-top:10px">
            <ul class="search-results" style="">
                <?php foreach ($all_coordinators as $id) { ?>
                    <li class="mb10" style="min-height:91px; margin-left: 8px">
                        <?php include __DIR__.'/../../search/views/partials/user.php' ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="clear"></div>
    <?php } ?>

        <ul id="sort_list" class="sort" style="list-style: none; margin: 0">
            <?php foreach ($list as $id) { ?>
                <li class="sortable_li" data-user-id="<?= $id ?>">
                    <?php include 'partials/person.php'; ?>
                </li>
            <?php } ?>
        </ul>

        <div class="bottom_line_d mb10"></div>
    <?php if (request::get_int('list')){ ?>
        <div class="left">
            <a href="javascript:" class="right" id="get_print">
                <img src="http://shevchenko.ua/templates/images/icons/print.png" alt="print">
            </a>
        </div>
        <div id="search_form_holder" class="hide">
            <table class="srch fs12">
                <tr>
                    <td class="aleft">
                        <input type="checkbox" name="ft" value="1"/><?= t('Фото') ?></td>
                    <td class="aleft">
                        <input type="checkbox" name="ph" value="1"/><?= t('Телефон') ?></td>
                </tr>
                <tr>
                    <td class="aleft">
                        <input type="checkbox" name="nm" value="1"
                               checked="checked"/><?= t('Имя и фамилия') ?></td>
                    <td class="aleft">
                        <input type="checkbox" name="fn" value="1"/><?= t('Функции') ?></td>
                </tr>
                <tr>
                    <td class="aleft">
                        <input type="checkbox" name="ot" value="1"/><?= t('Отчество') ?></td>
                    <td class="aleft">
                        <input type="checkbox" name="st" value="1"/><?= t('Статус') ?></td>
                </tr>
                <tr>
                    <td class="aleft">
                        <input type="checkbox" name="rg" value="1"/><?= t('Регион') ?></td>
                    <td class="aleft">
                        <input type="checkbox" name="br" value="1"/><?= t('Дата рождения') ?></td>
                </tr>
                <tr>
                    <td class="aleft">
                        <input type="checkbox" name="ct" value="1"/><?= t('Район') ?></td>
                    <td class="aleft">
                        <input type="checkbox" name="at" value="1"/><?= t('Активность') ?></td>
                </tr>
                <tr>
                    <td class="aleft">
                        <input type="checkbox" name="sg" value="1"/><?= t('Место проживания') ?></td>
                    <td class="aleft">
                        <input type="checkbox" name="rt" value="1"/><?= t('Активный с ...') ?></td>
                </tr>
                <?php if (session::has_credential('admin')) { ?>
                    <tr>
                        <td class="aleft">
                            <input type="checkbox" name="hd" value="1"/><?= t('Закрытый статус') ?></td>
                        <td class="aleft">
                            <?php if (request::get_int('contacts')) { ?>
                                <input type="checkbox" name="cd" value="1"
                                       checked="checked"/><?= t('Ограничить по дате контакта') ?>
                            <?php } else { ?>
                                <input type="checkbox" name="rf" value="1"/><?= t('Как попал в Меритократ') ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="aleft">
                            <input type="checkbox" name="pc" value="1"/><?= t('Профиль создан ...') ?>
                        </td>
                        <td class="aleft"><?php if (request::get_int('contacts')) { ?>
                                <input type="checkbox" name="cn"
                                       value="1"
                                       checked="checked"/><?= t('Содержание контакта') ?><?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="aleft">
                        <input type="checkbox" name="em" value="1"/><?= t('E-mail') ?></td>
                    <td class="aleft">
                        <input type="checkbox" name="all_pages"
                               value="1"/>
                        <b><?= t('Печать всех результатов') ?></b></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center">
                        <input type="button" class="button" id="print_on" value="<?= t('Печатать') ?>"
                               onclick="printon();"/>
                        <input type="button" class="button_gray" id="print_off" value="<?= t('Назад') ?>"
                               onclick="printoff();"/>
                    </td>
                </tr>
            </table>
        </div>
    <?php } ?>
        <div class="right pager"><?= pager_helper::get_full($pager, null, null, 16) ?></div>
    <?php } ?>

</div>

<?php if (request::get_int('list')) { ?>
    <script type="text/javascript">
        $(document).ready(function ($) {
            $('#get_print').click(function () {
                Popup.show();
                Popup.setHtml($('#search_form_holder').html());
                $('#popup_box').css({'left': parseInt($('#popup_box').css('left')) - 150 + 'px'});
            });
            $('.delfromlist').click(function () {
                $.post('/lists/delete_user', {'uid': $(this).attr('rel'), 'lid': <?=request::get_int('list')?>});
                $('#ul' + $(this).attr('rel')).remove();
            });
        });

        function printoff() {
            $('#popup_box').hide();
        }

        function printon() {
            var str = '&print=1';
            $('#popup_box').find('input[type="checkbox"]:checked').each(function () {
                str += '&' + $(this).attr('name') + '=1';
            });
            window.location = "<?=$_SERVER['REQUEST_URI']?>" + str;
        }
    </script>
<?php } ?>
