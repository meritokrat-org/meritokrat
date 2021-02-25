<?
class invites_peer extends db_peer_postgre
{
	protected $table_name = 'invites';

        const TYPE_INV_EVENT = 1;
        const TYPE_INV_GROUP = 2;
        const TYPE_INV_LEADER = 3;
	/**
	 * @return invites_peer
	 */
	public static function instance()
	{
		return parent::instance( 'invites_peer' );
	}

        public function add( $to, $data )
        {

                return $this->insert(array(
                            'from_id' => $data['from_id'],
                            'to_id' => $to,
                            'obj_id' => $data['obj_id'],
                            'type' => $data['type'],
                            'created_ts' => time()
                        ));
        }

        public function get_by_user( $user_id, $type = 0, $obj = 0 )
	{
                $bind['to_id'] = $user_id;
                if($type)
                {
                    $where = "AND type = :type";
                    $bind['type'] = $type;
                }
                $sql = 'SELECT * FROM (SELECT DISTINCT ON(obj_id) * FROM ' . $this->table_name . ' WHERE to_id = :to_id AND status = 0 '.$where.') foo ORDER BY created_ts DESC';
                if($obj)
                {
                    $where .= " AND obj_id = :obj_id";
                    $bind['obj_id'] = $obj;
                    $sql = 'SELECT DISTINCT ON(from_id) * FROM ' . $this->table_name . ' WHERE to_id = :to_id AND status = 0 '.$where.'';

                }               
                return db::get_rows($sql,$bind);
	}

        public function get_objects( $user_id, $type = 0, $status = 0 )
        {
                $bind['to_id'] = $user_id;
                $bind['status'] = $status;
                if($type)
                {
                    $where = "AND type = :type";
                    $bind['type'] = $type;
                }
                $sql = 'SELECT DISTINCT ON(obj_id) obj_id FROM ' . $this->table_name . ' WHERE to_id = :to_id AND status = :status '.$where;
                return db::get_cols($sql,$bind);
        }

        public function get_from_user( $user_id, $type = 0, $obj = 0 )
	{
                $bind['from_id'] = $user_id;
                $where = "AND type = :type";
                $bind['type'] = $type;
                $where .= " AND obj_id = :obj_id";
                $bind['obj_id'] = $obj;
                $sql = 'SELECT DISTINCT ON(to_id) to_id FROM ' . $this->table_name . ' WHERE from_id = :from_id '.$where.'';
                return db::get_cols($sql,$bind);
	}

        public function get_by_id( $user_id, $obj_id, $type=1 )
	{
                $sql = 'SELECT id FROM ' . $this->table_name . ' WHERE to_id = '.$user_id.' AND obj_id = '.$obj_id.' AND type = '.$type;
                return db::get_scalar($sql);
	}

        public function get_users($type=0,$array=array())
        {
            if($type && $type!='friend')
            {
                switch ( $type )
                {
                    case 'groups':
                    load::model('groups/members');
                    $sql='SELECT t.user_id FROM ' . groups_members_peer::instance()->get_table_name() . ' t, ' . user_auth_peer::instance()->get_table_name() . ' a
                        WHERE t.group_id IN (' . implode(',', $array) . ') AND t.user_id=a.id AND a.del=0';
                    break;

                    case 'status':
                    $sql='SELECT id as user_id FROM ' . user_auth_peer::instance()->get_table_name() . '
                        WHERE status IN (' . implode(',', $array) . ') AND del=0';
                    break;

                    case 'regions':
                    $sql='SELECT t.user_id FROM ' . user_data_peer::instance()->get_table_name() . ' t, ' . user_auth_peer::instance()->get_table_name() . ' a
                        WHERE t.region_id IN (' . implode(',', $array) . ') AND t.user_id=a.id AND a.del=0';
                    break;

                    case 'functions':
                    load::model('user/user_desktop');
                    foreach($array as $a)
                    {
                        $where[] = "t.functions && '{".$a."}'";
                    }
                    $sql='SELECT t.user_id FROM ' . user_desktop_peer::instance()->get_table_name() . ' t, ' . user_auth_peer::instance()->get_table_name() . ' a
                        WHERE ('.implode(' OR ',$where).') AND t.user_id=a.id AND a.del=0';
                    break;

                    case 'lists':
                    load::model('lists/lists_users');
                    $sql='SELECT t.user_id FROM ' . lists_users_peer::instance()->get_table_name() . ' t, ' . user_auth_peer::instance()->get_table_name() . ' a
                        WHERE t.type = 0 AND t.list_id IN (' . implode(',', $array) . ') AND t.user_id=a.id AND a.del=0';
                    break;
                }
                $array = db::get_cols($sql);
            }
            return $array;
        }

}