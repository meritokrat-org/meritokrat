<?php


namespace App\Model\Repository\Person;

/**
 * Class PersonInvitationRepository
 */
class PersonInvitationRepository
{
    public function __construct()
    {

    }

    /**
     * @param $userId
     *
     * @return array
     * @throws \dbException
     */
    public function getInvitationsTreeByUserId($userId)
    {
        $sql = 'SELECT array_to_json(array_agg(id)) AS ids, count(id), depth FROM linked_users WHERE :user_id = ANY(ancestors) GROUP BY depth';

        $statement  = \db::exec($sql, ['user_id' => (int) $userId]);
        $collection = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return array_map([$this, 'handleEntry'], $collection);
    }

    public function getInvitations($userId)
    {
        $sql = 'SELECT id, active, activated_ts, invited_by FROM user_auth WHERE invited_by = :user_id';

        return db::get_rows(
            $sql,
            [
                'user_id' => $userId,
            ]
        );
    }

    /**
     * @param array $entry
     *
     * @return array
     */
    private function handleEntry($entry)
    {
        $ids = $entry['ids'];

        return array_merge(
            $entry,
            [
                'ids' => json_decode($ids, true),
            ]
        );
    }
}