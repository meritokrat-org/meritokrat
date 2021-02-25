<?

load::model('messages/threads');

class messages_peer extends db_peer_postgre
{
	protected $table_name = 'messages';

	/**
	 * @return messages_peer
	 */
	public static function instance()
	{
		return parent::instance( 'messages_peer' );
	}

        public function get_smiles()
	{
                return array(
                   '[:)]' => tag_helper::image('smiles/smile.gif'),
                    '[:(]' => tag_helper::image('smiles/sad.gif'),
                    '[8)]' => tag_helper::image('smiles/cool.gif'),
                    '[;)]' => tag_helper::image('smiles/wink.gif'),
                    '[:D]' => tag_helper::image('smiles/d.gif'),
                    '[:P]' => tag_helper::image('smiles/p.gif'),
                    '[:Q]' => tag_helper::image('smiles/q.gif'),
                    '[:-|]' => tag_helper::image('smiles/m.gif'),
                    '[:R]' => tag_helper::image('smiles/r.gif'),
                    '[:rofl:]' => tag_helper::image('smiles/rofl.gif'),
                  //  ':-Z' => tag_helper::image('smiles/z.gif'),
                    '[:E]' => tag_helper::image('smiles/e.gif'),
                    '[:-)]' => tag_helper::image('smiles/happy.gif'),
                    '[:cry:]' => tag_helper::image('smiles/cry.gif'),
                    '[;/]' => tag_helper::image('smiles/hm.gif')
                );
	}


	public function add( $data, $owner_copy = true, $sys = 1, $html=false )
	{
		$thread_id = messages_threads_peer::instance()->insert(array(
			'sender_id' => $data['sender_id'],
			'receiver_id' => $data['receiver_id']
		));
               $html ? $message_body=self::replase_smiles($data['body']) : $message_body=self::replase_smiles(htmlspecialchars($data['body']));
		if ( $owner_copy )
		{
			$this->insert(array(
				'owner' => $data['sender_id'],
				'sender_id' => $data['sender_id'],
				'receiver_id' => $data['receiver_id'],
                                'body' => $message_body,
				'attached' => $data['attached'],
				'created_ts' => time(),
				'thread_id' => $thread_id,
				'is_read' => true,
                                'sys' => $sys
			));

			mem_cache::i()->delete('user_messages:' . $data['sender_id']);
		}

		$this->insert(array(
			'owner' => $data['receiver_id'],
			'sender_id' => $data['sender_id'],
			'receiver_id' => $data['receiver_id'],
			'body' => $message_body,
			'attached' => $data['attached'],
			'created_ts' => time(),
			'thread_id' => $thread_id,
			'is_read' => false,
                        'sys' => $sys
		));

		mem_cache::i()->delete('user_messages:' . $data['receiver_id']);
		$this->reset_new_messages($data['receiver_id']);

                /*if($data['receiver_id']==5)
                {
                    $data['receiver_id'] = 31;
                    $data['body'] = 'Переадресоване повiдомлення вiд Iгоря Шевченка: '.$data['body'];
                    $this->add($data,false,0);
                }*/

		return $thread_id;
	}

	public function reply( $data )
	{
		$id = $this->insert(array(
			'owner' => $data['sender_id'],
			'sender_id' => $data['sender_id'],
			'receiver_id' => $data['receiver_id'],
			'body' => self::replase_smiles(htmlspecialchars($data['body'])),
			'created_ts' => time(),
			'thread_id' => $data['thread_id'],
			'is_read' => true
		));

		$this->insert(array(
			'owner' => $data['receiver_id'],
			'sender_id' => $data['sender_id'],
			'receiver_id' => $data['receiver_id'],
			'body' => self::replase_smiles(htmlspecialchars($data['body'])),
			'created_ts' => time(),
			'thread_id' => $data['thread_id'],
			'is_read' => false
		));

		mem_cache::i()->delete('user_messages:' . $data['sender_id']);
		mem_cache::i()->delete('user_messages:' . $data['receiver_id']);
		$this->reset_new_messages($data['receiver_id']);

		return $id;
	}

	public function get_by_user( $user_id )
	{
		/*$sql = '
		SELECT MAX(id) as id FROM (SELECT *
		FROM ' . $this->table_name . '
		WHERE
                    (owner = '.$user_id.' AND sys = 0)
                    OR
                    (owner = '.$user_id.' AND sender_id != '.$user_id.' AND sys = 1)
                    ORDER BY is_read
                ) as foo
                GROUP BY thread_id
		';*/
            //$new = db::get_cols($sql, array(), $this->connection_name, 'user_messages:' . $user_id);
            $cache_key = 'user_messages:' . $user_id;
            if ( mem_cache::i()->exists($cache_key) ) return mem_cache::i()->get($cache_key);

            $threads = db::get_cols( 'SELECT DISTINCT ON(thread_id) thread_id FROM ' . $this->table_name . '
                    WHERE
                        (owner = '.$user_id.' AND sys = 0 AND is_read = false)
                        OR
                        (owner = '.$user_id.' AND sender_id != '.$user_id.' AND sys = 1 AND is_read = false)');
                if(count($threads)>0)$where = ' AND thread_id NOT IN ('.implode(',',$threads).')';

                $new = db::get_cols( 'SELECT MAX(id) as id FROM ' . $this->table_name . '
                    WHERE
                        (owner = '.$user_id.' AND sys = 0 AND is_read = false)
                        OR
                        (owner = '.$user_id.' AND sender_id != '.$user_id.' AND sys = 1 AND is_read = false)
                    GROUP BY thread_id ORDER BY id DESC');

		$old = db::get_cols('SELECT MAX(id) as id FROM ' . $this->table_name . '
                    WHERE
                        ((owner = '.$user_id.' AND sys = 0 AND is_read = true)
                        OR
                        (owner = '.$user_id.' AND sender_id != '.$user_id.' AND sys = 1 AND is_read = true))
                        '.$where.'
                    GROUP BY thread_id ORDER BY id DESC');
                $result = array_merge($new, $old);
                if ( $cache_key ) mem_cache::i()->set($cache_key, $result);

                return $result;
	}

	public function get_new_count_by_user( $user_id )
	{
		$sql = '
		SELECT count(id)
		FROM ' . $this->table_name . '
		WHERE
                (owner = '.$user_id.' AND sys = 0 AND "is_read" = false)
                OR
                (owner = '.$user_id.' AND sender_id != '.$user_id.' AND sys = 1 AND "is_read" = false)';

		return db::get_scalar($sql, array(), $this->connection_name, 'user_new_messages:' . $user_id);
	}

	public function reset_new_messages( $user_id )
	{
		mem_cache::i()->delete('user_new_messages:' . $user_id);
	}

	public function get_by_thread( $id, $user_id )
	{
		return $this->get_list(array('thread_id' => $id, 'owner' => $user_id), array(), array('id ASC'));
	}

	public function delete_by_thread( $id, $user_id )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE owner = :user_id AND thread_id = :id';
		db::exec($sql, array('id' => $id, 'user_id' => $user_id));
		mem_cache::i()->delete('user_messages:' . $user_id);
		$this->reset_new_messages($user_id);
	}

        public function get_history_by_user($owner_id,$user_id)
        {
            $sql="SELECT id FROM messages WHERE (owner=:owner and (receiver_id=:user_id or sender_id=:user_id)) or (sys=1 and (sender_id=:owner and receiver_id=:user_id)) ORDER BY id ASC";
            return db::get_cols($sql, array('user_id' => $user_id,'owner'=>$owner_id), $this->connection_name);
        }

        public function replase_smiles($message)
        {
               return strtr($message, self::get_smiles());
        }

        public function remove_smiles($message)
        {
               return stripslashes(strtr($message, array_flip(self::get_smiles())));
        }

        public function get_message($id)
        {
               $item = parent::get_item($id);
               $item['body'] = "\n\n".user_helper::full_name($item['sender_id'],false,array(),false).' '.date("H:i d/m",$item['created_ts'])."\n".$this->remove_smiles($item['body']);
               return $item;
        }

        public function get_thread($id)
        {
               $result['body'] = '';
               foreach($this->get_by_thread($id,session::get_user_id()) as $item)
               {
                   $txt = $this->get_message($item);
                   $result['body'] .= $txt['body'];
               }
               return $result;
        }

        public function get_sender($id)
        {
               $result['body'] = '';
               foreach($this->get_history_by_user(session::get_user_id(),$id) as $item)
               {
                   $txt = $this->get_message($item);
                   $result['body'] .= $txt['body'];
               }
               return $result;
        }
}
