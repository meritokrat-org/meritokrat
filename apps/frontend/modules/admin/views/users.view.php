<div class="left mt10" style="width: 35%;"><?php include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
    <h1 class="column_head"><?= t('Пользователи') ?></h1>

    <div class="box_content acenter p10 fs12">
        <form>
            <label class="fs10 quiet"><?= t('Email либо ID пользователя'); ?></label>
            <input type="text" class="text" name="key" value="<?= $user_key ?>"/>
            <input type="submit" class="button" value="<?= t('Искать') ?>"/>
        </form>

        <br/><br/>
        <a href="/admin/users_list"><?= t('Список пользователей') ?></a> | <a
                href="/admin/users_create"><?= t('Создать пользователя') ?></a>
    </div>
    <?php if ($user_key) { ?>
        <?php if (!$user) { ?>
            <div class="acenter screen_message acenter"><?= t('Пользователь не найден') ?></div>
        <?php } else { ?>
            <form method="post">
                <table class="fs12 mt10">
                    <tr>
                        <td width="30%"><?= t('Имя') ?></td>
                        <td>
                            <?= user_helper::full_name($user['id']) ?><br/>
                            <?= user_helper::photo($user['id'], 't', ['class' => 'mt5']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>IP</td>
                        <td>
                            <?= $user['ip'] ?>
                        </td>
                    </tr>
                    <!--tr>
						<td>Синонимы имени</td>
						<td>
							<textarea style="width: 250px;" class="text" name="synonyms"><?= htmlspecialchars(
                            $dictionary_names['names']
                    ) ?></textarea><br />
							<input type="checkbox" name="enable_synonyms" value="1" <?= $dictionary_names['names'] ? 'checked' : '' ?> /> Включить в словарь персон
						</td>
					</tr-->
                    <?php if (
                            intval(db_key::i()->get('schanger'.session::get_user_id()))
                            || in_array(session::get_user_id(), [2, 5, 11752])
                    ) { ?>
                        <tr>
                            <td><?= t('Статус') ?></td>
                            <td>
                                <?= tag_helper::select(
                                        'user_status',
                                        [0 => '&mdash;'] + user_auth_peer::get_statuses(
                                                user_auth_peer::STATUS_TYPE_MAIN
                                        ),
                                        ['value' => $user['status']]
                                ) ?>
                                <input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php $collection = [0 => '&mdash;'] + user_auth_peer::get_statuses(
                                                user_auth_peer::STATUS_TYPE_FUNCTIONARY
                                        ) ?>
                                <?= tag_helper::select(
                                        'user_status_functionary',
                                        $collection,
                                        ['value' => $user['status_functionary']]
                                ) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php $collection = [0 => '&mdash;'] + user_auth_peer::get_statuses(
                                                user_auth_peer::STATUS_TYPE_POLITICIAN
                                        ) ?>
                                <?= tag_helper::select(
                                        'user_status_politician',
                                        $collection,
                                        ['value' => $user['status_politician']]
                                ) ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?= t('Функция') ?></td>
                            <td>
                                <?php foreach (user_auth_peer::get_user_functions() as $key => $name) { ?>
                                    <?php if (!is_array($user['functions'])) { ?>
                                        <?php $user['functions'] = json_decode($user['functions'], true) ?>
                                    <?php } ?>
                                    <label style="display: block">
                                        <input
                                                type="checkbox"
                                                name="user_functions[]"
                                                value="<?= $key ?>"
                                                <?= in_array(
                                                        (string) $key,
                                                        $user['functions'],
                                                        true
                                                ) ? 'checked' : '' ?>
                                        > <?= $name ?>
                                    </label>
                                <?php } ?>
                                <!--                                --><?php //= tag_helper::select(
                                //                                    'user_function',
                                //                                    [0 => '&mdash;'] + user_auth_peer::get_user_functions(),
                                //                                    ['value' => $user['function']]
                                //                                ) ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?= t('Позиция в агиткоманде') ?></td>
                            <td>
                                <?= tag_helper::select(
                                        'user_position',
                                        [0 => '&mdash;'] + user_auth_peer::get_user_positions(),
                                        ['value' => $user['position']]
                                ) ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td><?= t('Закриті статуси') ?></td>
                        <td>
                            <?= tag_helper::select(
                                    'hidden_type',
                                    user_auth_peer::get_hidden_types(),
                                    ['value' => $user['hidden_type']]
                            ) ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= t('Експерт з') ?></td>
                        <td>
                            <?php

                            $expert = (strlen($user["expert"]) > 2) ? unserialize($user["expert"]) : [];

                            $experts = [];
                            foreach (user_auth_peer::instance()->get_expert_types() as $key => $name) {
                                ?>
                                <lable>
                                    <input type="checkbox" name="expert[]"
                                           value="<?= $key ?>" <?= in_array(
                                            $key,
                                            $expert
                                    ) ? "checked" : "" ?>> <?= $name ?>
                                </lable><br>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?= t('Идентификация') ?></td>
                        <td>
                            <input <?= $user['identification'] ? 'checked' : '' ?> type="radio" name="identification"
                                                                                   id="identification_1" value="1"/>
                            <label for="active_1"><?= t('Идентифицирован') ?></label>
                            <input <?= !$user['identification'] ? 'checked' : '' ?> type="radio" name="identification"
                                                                                    id="identification_0" value="0"/>
                            <label for="active_0"><?= t('Не идентифицирован') ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td><?= t('Запрошуючий') ?></td>
                        <td>
                            <input <?= $user['inviter'] ? 'checked' : '' ?> type="radio" name="inviter" id="inviter_1"
                                                                            value="1"/> <label
                                    for="active_1"><?= t('Так') ?></label>
                            <input <?= !$user['inviter'] ? 'checked' : '' ?> type="radio" name="inviter" id="inviter_0"
                                                                             value="0"/> <label
                                    for="active_0"><?= t('Ні') ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>Відомий</td>
                        <td>
                            <input <?= $user['famous'] ? 'checked' : '' ?> type="radio" name="famous" id="famous_1"
                                                                           value="1"/> <label
                                    for="active_1">Відомий</label>
                            <input <?= !$user['famous'] ? 'checked' : '' ?> type="radio" name="famous" id="famous_0"
                                                                            value="0"/> <label
                                    for="active_0">Невідомий</label>
                        </td>
                    </tr>
                    <!--tr>
						<td><?= t('Опыт') ?></td>
						<td>
							<input type="text" class="text" name="rate" value="<?= $user_data['rate'] ?>" />
						</td>
					</tr-->
                    <tr>
                        <td><?= t('Активен') ?></td>
                        <td>
                            <input <?= $user['active'] ? 'checked' : '' ?> type="radio" name="active" id="active_1"
                                                                           value="1"/> <label
                                    for="active_1"><?= t('Активен') ?></label>
                            <input <?= !$user['active'] ? 'checked' : '' ?> type="radio" name="active" id="active_0"
                                                                            value="0"/> <label
                                    for="active_0"><?= t('Не активен') ?></label>
                        </td>
                    </tr>
                    <tr>
                        <td>Шевченко</td>
                        <td>
                            <input <?= $user['shevchenko'] == 1 ? 'checked' : '' ?> type="checkbox" name="shevchenko"
                                                                                    value="1"/> Так
                        </td>
                    </tr>
                    <tr>
                        <td>Роб стол</td>
                        <td>
                            <input <?= $user['desktop'] == 1 ? 'checked' : '' ?> type="checkbox" name="desktop"
                                                                                 value="1"/> Не залежно від статусу
                        </td>
                    </tr>
                    <tr>
                        <td><?= t('Cкрытый профиль') ?></td>
                        <td>
                            <input <?= $user['suslik'] == 1 ? 'checked' : '' ?> type="checkbox" name="suslik"
                                                                                value="1"/> Так
                        </td>
                    </tr>
                    <tr>
                        <td><?= t('Обмежити правами гостя') ?></td>
                        <td><?= tag_helper::select('ban', ban_peer::get_types(), ['value' => $ban_days]) ?></td>
                    </tr>
                    <!--tr>
						<td>Выборы</td>
						<td>
							<input <?= candidates_peer::instance()->is_candidate($user['id']) ? 'checked' : '' ?> type="checkbox" name="candidate" value="1" id="candidate"/>
							<label for="candidate"><?= t('Кандидат на выборы') ?></label>
						</td>
					</tr-->
                    <?php if (candidates_peer::instance()->is_candidate($user['id'])) { ?>
                        <tr>
                            <td></td>
                            <td>
                                Добавить голосов:
                                <input type="text" class="text" name="candidate_votes" value="0" size="8"/>

                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <?php if ($saved) { ?>
                                <div class="success"><?= t('Данные сохранены') ?></div>
                            <?php } ?>
                            <input type="submit" name="submit" class="button" value=" <?= t('Сохранить') ?> ">
                        </td>
                    </tr>
                </table>
            </form>
        <?php } ?>
    <?php } ?>
</div>
