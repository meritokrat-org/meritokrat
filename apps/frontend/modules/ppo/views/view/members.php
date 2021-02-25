<?php

use App\Component\Member\Card;
use App\Component\Member\Widget;

return static function ($ppo) {
    // $members = db::get_cols('select user_id from ppo_members where group_id = :ppoId', ['ppoId' => $ppo['id']]);
    $members = ppo_peer::instance()->getPpoMembersRecursive($ppo);

    if (empty($members)) {
        return null;
    }

    $sql = sprintf(
        'select status, array_to_json(array_agg(id)) as ids from user_auth where id in (%s) group by status order by status desc;',
        implode(', ', $members)
    );

    $rows = db::get_rows($sql);

    $groups = [];
    foreach ($rows as $row) {
        $status    = user_auth_peer::getStatus($row['status']);
        $memberIds = json_decode($row['ids'], true);

        if ($row['status'] === user_auth_peer::POTENTIAL_SUPPORTER && !session::has_credential('admin')) {
            $sql = 'select ua.id from ppo_members pm join user_auth ua on pm.user_id = ua.id where pm.group_id = :ppoId and ua.status = :userStatus and ua.invited_by = :invitedBy';

            $memberIds = db::get_cols(
                $sql,
                [
                    'ppoId'      => $ppo['id'],
                    'userStatus' => user_auth_peer::POTENTIAL_SUPPORTER,
                    'invitedBy'  => session::get_user_id(),
                ]
            );
        }

        $groups[] = Widget::create()
            ->setTitle(sprintf('%s - %d', $status, count($memberIds)))
            ->addAction(
                sprintf('/ppo/members?id=%d&status=%d', $ppo['id'], $row['status']),
                sprintf('%s &rarr;', t('Все'))
            )
            ->setMembers(
                array_map(
                    static function ($id) {
                        $data = user_data_peer::instance()->get_item($id);

                        return Card::create()
                            ->setId($id)
                            ->setFirstName($data['first_name'])
                            ->setLastName($data['last_name']);
                    },
                    $memberIds
                )
            )
            ->render();
    }

    return implode(PHP_EOL, $groups);
};
