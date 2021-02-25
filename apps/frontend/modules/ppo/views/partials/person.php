<div class="box_empty box_content left p10 mb10 mr10" style="width: 45.2%;">
    <?= user_helper::photo($id, 't', ['class' => 'border1 left'], true) ?>
    <div style="margin-left: 87px;height: 95px;">
        <div style="height: 55px;">
            <b><?= user_helper::full_name($id) ?></b><br/>
            <? $friend = user_auth_peer::instance()->get_item($id) ?>
            <span class="fs11 quiet">
                                <?= user_auth_peer::get_status($friend['status']) ?>
                                <? if (session::has_credential('admin')) { ?>
                                    <?= ', *'.ppo_members_peer::instance()->get_type($group['id'], $id) ?>
                                <? } ?>
                        </span>
        </div>
        <? if (session::is_authenticated()) { ?>
            <div class="fs11">
                <a href="/messages/compose?user_id=<?= $id ?>"><?= t('Написать') ?></a>
            </div>
        <? } ?>
        <? if (session::has_credential('admin') || ppo_peer::instance()->is_moderator($group['id'], session::get_user_id())) { ?>
            <div class="fs11">
                <a href="/ppo/delete_user?group_id=<?= $group['id'] ?>&id=<?= $id ?>"><?= t('Удалить') ?></a>
            </div>
        <? } ?>
    </div>
    <div class="clear"></div>
</div>