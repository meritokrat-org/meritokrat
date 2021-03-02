<script>
    function viewBody($id) {
        $.post(
                '/admin/internallist',
                {getbody: 1, recId: $id},
                function (response) {
                    $('#bdy_' + $id).html(response);
                },
        );
    }

    function delMailing($id) {
        $.post(
                '/admin/internallist',
                {del: $id},
                function () {
                    $('#mailing' + $id).remove();
                },
        );
    }
</script>
<div class="left mt10" style="width: 35%;"><?php include 'partials/left.php' ?></div>


<div class="left ml10 mt10" id="rmcol" style="width: 62%;">
    <h1 class="column_head">Внутрішня розсилка</h1>

    <div class="box_content acenter p10 fs12">
        <?php if ($sent) { ?>
            Відправлено листів: <b><?= $sent ?></b><br/>
            <a href="/admin/internallist">Відправити ще</a>
        <?php } else { ?>
            <div class="fs11" id="stat_panel">
                <a class="dotted" href="javascript:;" onclick="Application.showInternalList();return false;">Iндивiдуальна
                    розсилка</a>&nbsp;
                <a class="mail_mode dotted" id="mode_create" href="javascript:;">Створити розсилку</a>&nbsp;
                <a class="mail_mode dotted" id="mode_act_sends" href="javascript:;">Активнi</a>&nbsp;
                <a class="mail_mode dotted" id="mode_user_sends" href="javascript:;">Розiсланi</a>&nbsp;
                <img align="absmiddle" width="15" src="https://s1.meritokrat.org/common/loading.gif" class="hidden ml10"
                     id='wp'>
            </div>
            <div id="pane_user_sends"></div>
            <form id="send_form" method="post">
                <input type="hidden" name="send" value="1">
                <input type="hidden" name="groups" value="<?= request::get_string('group') ?>">
                <input type="hidden" id="mail_mode" name="mail_mode" value="unknown">
                <div class="aleft mode_pane" id="pane_known">
                    <div class="left" style="width: 150px;">
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="common" checked="checked"/>Усім<br/>
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="group"/>Спiльноти<br/>
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="status"/>Статус<br/>
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="func"/>Функції<br/>
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="region"/>Регіони<br/>
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="district"/>Регіон/район<br/>
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="sferas"/>Сфера діяльності<br/>
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="targets"/>Цiльова группа<br/>
                        <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                               value="visit"/>Останнє відвідування<br/>
                        <?php load::model('lists/lists') ?>
                        <?php $list_data = lists_peer::instance()->get_list_data(session::get_user_id()) ?>
                        <?php if (0 != count($list_data)) { ?>
                            <input onclick="adminController.showMailFilter(this.value);" type="radio" name="filter"
                                   value="lists"/>Списки<br/>
                        <?php } ?>
                    </div>
                    <div style="margin-left: 160px; margin-top: 10px;">
                        <div class="mfilter" id="common_filter"></div>
                        <div class="mfilter hidden" id="group_filter">
                            <?php load::model('groups/groups') ?>
                            <?= tag_helper::select(
                                    'group[]',
                                    groups_peer::instance()->get_select_list(),
                                    ['style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 8]
                            ) ?>
                        </div>
                        <div class="mfilter hidden" id="status_filter">
                            <?= tag_helper::select(
                                    'status[]',
                                    user_auth_peer::instance()->get_statuses(),
                                    ['style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 8]
                            ) ?>
                        </div>

                        <div class="mfilter hidden" id="func_filter">
                            <?= tag_helper::select(
                                    'func[]',
                                    user_auth_peer::get_functions(),
                                    ['style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 8]
                            ) ?>
                        </div>

                        <div class="mfilter hidden" id="region_filter">
                            <?= tag_helper::select(
                                    'region[]',
                                    geo_peer::instance()->get_regions(1),
                                    ['style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 8]
                            ) ?>
                        </div>

                        <?php if (0 != count($list_data)) { ?>
                            <div class="mfilter hidden" id="lists_filter">
                                <?= tag_helper::select(
                                        'lists[]',
                                        $list_data,
                                        ['style' => 'width: 250px;', 'multiple' => 'multiple']
                                ) ?>
                            </div>
                        <?php } ?>

                        <div class="mfilter hidden" id="district_filter">
                            <input name="region_id" type="hidden" value="13"/>
                            <? $regns = geo_peer::instance()->get_regions(1);
                            $regns[0] = "Виберіть регiон";
                            ksort($regns); ?>
                            <?= tag_helper::select(
                                    'regionc',
                                    $regns,
                                    [
                                            'use_values' => false,
                                            'value'      => 999,
                                            'id'         => 'region',
                                            'rel'        => t('Выберите регион'),
                                    ]
                            ); ?><br/>
                            <?php if ($user_data['region_id'] > 0 and 9999 != $user_data['region_id']) {
                                $cities = geo_peer::instance()->get_cities($user_data['region_id']);
                            } elseif ($user_data['country_id'] > 1) {
                                $cities['9999'] = 'закордон';
                            } else {
                                $cities = ["" => t('Выберите город/район')];
                            } ?>
                            <?= tag_helper::select(
                                    'city[]',
                                    $cities,
                                    [
                                            'use_values' => false,
                                            'value'      => $user_data['city_id'],
                                            'id'         => 'city',
                                            'multiple'   => 'multiple',
                                            'rel'        => t('Выберите город/район'),
                                    ]
                            ); ?>

                        </div>
                        <div class="mfilter hidden" id="sferas_filter">
                            <?= tag_helper::select(
                                    'sferas[]',
                                    user_auth_peer::instance()->get_segments(),
                                    ['style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 8]
                            ) ?>
                        </div>
                        <div class="mfilter hidden" id="targets_filter">
                            <?= tag_helper::select(
                                    'targets[]',
                                    user_helper::get_targets(),
                                    ['style' => 'width: 250px;', 'multiple' => 'multiple', 'size' => 8]
                            ) ?>
                        </div>
                        <div class="mfilter hidden" id="visit_filter">
                            <select name="visit" value="" use_values="">
                                <option value="1" <?= 1 == request::get('visit_ts') ? 'selected' : '' ?>><?= t(
                                            'сегодня'
                                    ) ?></option>
                                <option value="3" <?= 3 == request::get('visit_ts') ? 'selected' : '' ?>><?= t(
                                            '3 дня'
                                    ) ?></option>
                                <option value="7" <?= 7 == request::get('visit_ts') ? 'selected' : '' ?>><?= t(
                                            'неделя'
                                    ) ?></option>
                                <option value="30" <?= 30 == request::get('visit_ts') ? 'selected' : '' ?>><?= t(
                                            'месяц'
                                    ) ?></option>
                                <option value="-7" <?= '-7' == request::get('visit_ts') ? 'selected' : '' ?>><?= t(
                                            'больше недели'
                                    ) ?></option>
                                <option value="-30" <?= '-30' == request::get('visit_ts') ? 'selected' : '' ?>><?= t(
                                            'больше месяца'
                                    ) ?></option>
                                <option value="-163" <?= '163' == request::get('visit_ts') ? 'selected' : '' ?>><?= t(
                                            'больше полгода'
                                    ) ?></option>
                                <option value="-365" <?= '-365' == request::get('visit_ts') ? 'selected' : '' ?>><?= t(
                                            'больше года'
                                    ) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="fs11">Повідомлення</div>
                <textarea rel="Введите сообщение" name="body" style="width: 100%;"></textarea>
                <div class="fs10">* <b>NAME</b> - для зазначення імені в листі</div>

                <br/>

                <input type="button" class="button" onclick="fsubmit()" value="<?= t('Отправить') ?>"/>
            </form>

        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    function fsubmit() {
        let error = 0;

        $('div.mfilter:visible').find('select').each(function () {
            const data = $(this).val();
            if (data === '' || data === 0 || data == null) {
                error = 1;
            }
        });

        if (error === 0) {
            $('#send_form').submit();
        } else {
            alert('<?=t('Не выбран тип рассылки')?>');
        }
    }

    $('#stat_panel').find('a.mail_mode').each(function () {
        $(this).click(function () {
            if ($(this).attr('id') === 'mode_create') {
                $('#send_form').show();
                $('#pane_user_sends').hide();
            } else {
                $('img#wp').css('top', ($(window).height() / 2) + 'px');
                $('img#wp').css('left', ($(window).width() / 2) + 'px');
                $('img#wp').show();
                $.post('/admin/internallist',
                        {
                            'stat_type': $(this).attr('id'),
                            'get_statistic': 1,
                        },
                        function (result) {
                            $('#pane_user_sends').html(result);
                            $('#pane_user_sends').show();
                            $('#send_form').hide();
                            $('img#wp').hide();
                            return false;
                        });
            }
        });
    });
</script>