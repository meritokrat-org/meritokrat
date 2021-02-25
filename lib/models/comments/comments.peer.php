<?

class comments_peer extends db_peer_postgre
{

        /**
	 * @return comments_peer
	 */
	public static function instance()
	{
            return parent::instance( 'comments_peer' );
	}

        public function get_new_count()
	{
            if($num=mem_cache::i()->get('user_'.session::get_user_id().'_newcom'))
            {
                return $num;
            }
            else
            {
                load::model('bookmarks/bookmarks');
                load::model('blogs/posts');
                load::model('blogs/comments');

                $res[] = $this->get_mine();
                $res[] = $this->get_ppl();
                $res[] = $this->get_mine_child_comments();
                $res[] = $this->get_fav();
                $res[] = $this->get_favppl();
                $res[] = $this->get_bkm();
                $result = array();
                foreach($res as $k=> $v)
                {
                    if(is_array($v))
                    {
                        $result += $v;
                    }
                }
                //if (session::get_user_id()==29 || session::get_user_id()==996) var_dump(array_unique($result));
                $result = count(array_unique($result));//-count($mine_comments);
                mem_cache::i()->set('user_'.session::get_user_id().'_newcom',$result,60*5);
                return $result;
            }
        }

        private function get_mine()
        {
            $posts = blogs_posts_peer::instance()->get_by_user(session::get_user_id());
            return $this->get_count_by_posts($posts);
        }

        private function get_ppl()
        {
            $posts = blogs_comments_peer::instance()->get_posts_by_user(session::get_user_id());
            return $this->get_count_by_posts($posts);
        }

        private function get_fav()
        {
            $users = bookmarks_peer::instance()->get_by_usr(session::get_user_id(),6);
            $posts = blogs_posts_peer::instance()->get_by_users($users);
            return $this->get_count_by_posts($posts);
        }

        private function get_favppl()
        {
            $users = bookmarks_peer::instance()->get_by_usr(session::get_user_id(),6);
            return $this->get_count_by_users($users);
        }

        private function get_bkm()
        {
            $posts = bookmarks_peer::instance()->get_by_usr(session::get_user_id(),1);
            return $this->get_count_by_posts($posts);
        }

        private function get_count_by_posts($posts_id)
        {
            if(count($posts_id)>0)
            {
                return db::get_cols('SELECT id FROM blogs_comments WHERE post_id IN ('.implode(',',$posts_id).') AND created_ts > '.$this->get_time(session::get_user_id()));
            }
        }

        private function get_mine_child_comments()
        {
                return @db::get_cols('SELECT id FROM blogs_comments WHERE parent_id IN (SELECT id FROM blogs_comments WHERE user_id='.session::get_user_id().') AND  user_id!='.session::get_user_id().' AND created_ts > '.$this->get_time(session::get_user_id()));
        }

        private function get_count_by_users($users_id)
        {
            if(count($users_id)>0)
            {
                return db::get_cols('SELECT id FROM blogs_comments WHERE user_id IN ('.implode(',',$users_id).') AND created_ts > '.$this->get_time(session::get_user_id()));
            }
        }

        private function get_time($user_id)
        {
            if($time = db_key::i()->get('user_'.$user_id.'_viewcom_time'))
                return $time;
            else
                return 1293494400;
        }

}