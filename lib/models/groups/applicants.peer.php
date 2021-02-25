<?

class groups_applicants_peer extends db_peer_postgre
{
    protected $table_name = 'groups_applicants';
    protected $primary_key = null;

    /**
     * @return groups_applicants_peer
     */
    public static function instance()
    {
        return parent::instance('groups_applicants_peer');
    }

    public function add($group_id, $user_id)
    {
        $this->insert(['group_id' => $group_id, 'user_id' => $user_id]);
    }

    public function remove($group_id, $user_id)
    {
        $sql = 'DELETE FROM '.$this->table_name.' WHERE group_id = :group_id AND user_id = :user_id';

        return db::exec($sql, ['group_id' => $group_id, 'user_id' => $user_id], $this->connection_name);
    }

    public function is_applicant($group_id, $user_id)
    {
        return in_array($user_id, $this->get_by_group($group_id));
    }

    public function get_by_group($group_id)
    {
        $sql = 'SELECT user_id FROM '.$this->table_name.' WHERE group_id = :group_id';

        return db::get_cols($sql, ['group_id' => $group_id], $this->connection_name);
    }
}