<div id="friend_<?= $id ?>" class="d-flex flex-row box_empty box_content p-2">
    <?= user_helper::photo($id, 'sm', array('class' => 'img-fluid img-thumbnail'), true) ?>
    <div class="flex-grow-1 ml-2">
        <div>
            <b><?= user_helper::full_name($id) ?></b><br/>
            <?php $friend = user_auth_peer::instance()->get_item($id) ?>
            <span class="fs11 quiet"><?= user_auth_peer::get_status($friend['status']) ?></span>
        </div>
        <?php if (session::get_user_id() == $user_id) { ?>
            <div class="fs11">
                <a href="/messages/compose?user_id=<?= $id ?>"><?= t('Написать') ?></a>
                <a class="ml10 maroon" onclick="friendsController.deleteFriend(<?= $id ?>);"
                   href="javascript:;"><?= t(
                        'Удалить'
                    ) ?></a>
            </div>
        <?php } ?>
    </div>
</div>
