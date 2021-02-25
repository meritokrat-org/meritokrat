<?php

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class User
 */
class User
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * User constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
}

/**
 * Class UserInvitation
 */
class UserInvitationDepth
{

    /** @var ArrayCollection */
    private $references;

    /**
     * UserInvitation constructor.
     *
     * @param array $context
     */
    public function __construct($context = [])
    {
        $this->references = new ArrayCollection();

        if (isset($context['ids']) && is_string($context['ids'])) {
            $context['ids'] = json_decode($context['ids'], true);
            array_walk(
                $context['ids'],
                function ($id) {
                    $this->references->add(new User((int) $id));
                }
            );
        }
    }

    /**
     * @param array $context
     *
     * @return UserInvitationDepth
     */
    public static function create($context)
    {
        return new self($context);
    }

    /**
     * @return ArrayCollection
     */
    public function getReferences()
    {
        return $this->references;
    }
}

/**
 * Class UserInvitationRepository
 *
 * @method static UserInvitationRepository i()
 */
class UserInvitationRepository extends db_peer_postgre
{
    public function getInvitationsTreeByUserId($userId)
    {
        $sql = <<<SQL
SELECT array_to_json(array_agg(id)) AS ids,
       count(id),
       depth
FROM linked_users
WHERE :user_id = ANY(ancestors)
GROUP BY depth;
SQL;

        $statement  = db::exec($sql, ['user_id' => (int) $userId]);
        $collection = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'handleEntry'], $collection);
    }

    public function getInvitationsByUserId($userId)
    {
        $sql = <<<SQL
SELECT id, active, activated_ts, invited_by FROM user_auth WHERE invited_by = :user_id;
SQL;

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

