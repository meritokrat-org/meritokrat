<?php

/**
 * @var array $user
 */

load::model('user/invitation');

$invitations = UserInvitationRepository::i()->getInvitationsByUserId($user['id']);

$activatedUsers    = [];
$nonActivatedUsers = [];
array_walk(
        $invitations,
        static function ($row) use (&$activatedUsers, &$nonActivatedUsers) {
            if ($row['activated_ts'] > 0) {
                $activatedUsers[] = $row;
            } else {
                $nonActivatedUsers[] = $row;
            }
        }
);

?>
<a href="javascript:void(0);" class="atab sidebar-link" id="astats"><?= t('Приглашенные мною') ?> <span
            class="right"><span style="color: black"><?= count($activatedUsers) ?></span> / <span
                style="color: #7E7E7E"><?= count($nonActivatedUsers) ?></span> / <span
                style="color: #7E7E7E"><?= (count($activatedUsers) + count($nonActivatedUsers)) ?></span></span></a>
