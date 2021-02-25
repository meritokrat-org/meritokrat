<?

class blogs_comments_peer extends db_peer_postgre
{
	protected $table_name = 'blogs_comments';

	/**
	 * @return blogs_comments_peer
	 */
	public static function instance()
	{
		return parent::instance( 'blogs_comments_peer' );
	}

        public function get_item($id)
	{
		return db::get_row('SELECT * FROM ' . $this->table_name . ' WHERE id = :id', array('id' => $id));
	}

	public function insert($data)
	{
		$id = parent::insert($data);

		$c = 'blog_post_comments_' . $data['post_id'];
		mem_cache::i()->delete($c);

		# Todo: increment
		$c = 'blog_post_comments_count_' . $data['post_id'];
		mem_cache::i()->delete($c);

		$this->count_comment( $data['post_id'], $data['user_id'] );

		return $id;
	}

        public function update($data)
	{
		$id = parent::update($data);

		$c = 'blog_post_comments_' . $data['post_id'];
		mem_cache::i()->delete($c);
	}

	public function is_allowed( $post_id, $user_id )
	{
		$c = 'blog_post_comments_user_limit_' . $post_id . $user_id;
		return mem_cache::i()->get($c) < 3;
	}

	public function count_comment( $post_id, $user_id )
	{
		$c = 'blog_post_comments_user_limit_' . $post_id . $user_id;
		mem_cache::i()->set($c, mem_cache::i()->get($c) + 1, 60*60);
	}

	public function get_count_by_post( $post_id )
	{
			$c = 'blog_post_comments_count_' . $post_id;

			if ( !mem_cache::i()->exists($c) )
			{
				$data = db::get_scalar('SELECT count(id) FROM ' . $this->table_name . ' WHERE post_id = :id', array('id' => $post_id));
				mem_cache::i()->set($c, $data);

			}
			else
			{
				$data = mem_cache::i()->get($c);
			}

			return intval($data);
	}


	public function get_by_post( $post_id )
	{
		$c = 'blog_post_comments_' . $post_id;

		if ( !mem_cache::i()->exists($c) )
		{
			$data = $this->get_list(array('post_id' => $post_id, 'parent_id' => 0), array(), array('ID ASC'));
			mem_cache::i()->set($c, $data);

		}
		else
		{
			$data = mem_cache::i()->get($c);
		}

		return $data;
	}

	public function has_rated( $comment_id, $user_id )
	{
		return db_key::i()->exists('blog_comment_rate:' . $comment_id . ':' . $user_id);
	}

	public function rate( $comment_id, $user_id )
	{
		db_key::i()->set('blog_comment_rate:' . $comment_id . ':' . $user_id, true);
	}

	public function delete_item($id)
	{
		$data = $this->get_item($id);
		parent::delete_item($id);
		mem_cache::i()->delete('blog_post_comments_' . $data['post_id']);
		parent::reset_item($id);
	}

        public function get_posts_by_user($user_id,$types=array(blogs_posts_peer::TYPE_MIND_POST,blogs_posts_peer::TYPE_BLOG_POST), $groups=false)
        {
                if ($groups && count($groups)>1 && is_array($groups))
                    {
                        //$types[]=blogs_posts_peer::TYPE_GROUP_POST;
                        $sql=" AND group_id in (".implode(',',$groups).")";   
                    }
                return db::get_cols('SELECT DISTINCT ON(post_id) post_id FROM '.$this->table_name.' WHERE user_id = '.$user_id.' AND post_id in (SELECT id FROM blogs_posts WHERE type in ('.implode(',',$types).') '.$sql.')');
        }

        public function get_by_posts($posts_id, $created = false)
        {
                if(count($posts_id)>0)
                {
                    if(!$created)
                        return db::get_cols('SELECT id FROM '.$this->table_name.' WHERE post_id IN ('.implode(',',$posts_id).') ORDER BY id DESC');
                    else
                        return db::get_rows('SELECT id,created_ts FROM '.$this->table_name.' WHERE post_id IN ('.implode(',',$posts_id).')');
                }
                else
                    return array();
        }

        public function get_by_users($users_id, $created = false, $types=array(blogs_posts_peer::TYPE_MIND_POST,blogs_posts_peer::TYPE_BLOG_POST), $groups=false)
        {
                if(count($users_id)>0)
                {
                    if (in_array(blogs_posts_peer::TYPE_GROUP_POST,$types))
                    {
                        $types=array_diff($types,array(blogs_posts_peer::TYPE_GROUP_POST));
                        if ($groups && count($groups)>1 && is_array($groups))
                        {
                            $sql="or (type=5 AND group_id in (".implode(',',$groups)."))";   
                        }
                        else 
                        {
                            load::model('groups/groups');
                            $allowed_groups=groups_peer::instance()->get_new();
                            $sql="or (type=5 AND group_id in (".implode(',',$allowed_groups)."))";
                        }
                    }
                    if(!$created)
                    {
                        return db::get_cols('SELECT id FROM '.$this->table_name.' WHERE post_id in (SELECT id FROM blogs_posts WHERE type in ('.implode(',',$types).') '.$sql.') AND user_id IN ('.implode(',',$users_id).') ORDER BY id DESC');
                    }
                    else
                    {
                        return db::get_rows('SELECT id,created_ts FROM '.$this->table_name.' WHERE post_id in (SELECT id FROM blogs_posts WHERE type in ('.implode(',',$types).') '.$sql.') AND user_id IN ('.implode(',',$users_id).')');
                    }
                }
                else
                    return array();
        }

        public function get_all_by_users($users_id, $created = false, $types=array(blogs_posts_peer::TYPE_MIND_POST,blogs_posts_peer::TYPE_BLOG_POST))
        {
                if(count($users_id)>0)
                {
                    if(!$created)
                        return db::get_cols('SELECT id FROM '.$this->table_name.' WHERE user_id IN ('.implode(',',$users_id).') AND post_id in (SELECT id FROM blogs_posts WHERE type in ('.implode(',',$types).')) ORDER BY id DESC');
                    else
                        return db::get_rows('SELECT id,created_ts FROM '.$this->table_name.' WHERE user_id IN ('.implode(',',$users_id).') AND post_id in (SELECT id FROM blogs_posts WHERE type in ('.implode(',',$types).'))');
                }
                else
                    return array();
        }
}
