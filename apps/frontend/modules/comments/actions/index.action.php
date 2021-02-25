<?

class comments_index_action extends frontend_controller
{
	protected $authorized_access = true;
        public $remote = 11;

	public function execute()
	{
            client_helper::set_title( t('Комментарии') . ' | ' . conf::get('project_name') );

            load::model('bookmarks/bookmarks');
            load::model('blogs/posts');
            load::model('blogs/comments');
            load::model('groups/groups');

            $this->type = request::get_string('type');
            $this->user = session::get_user_id();
            $this->created = false;

            if($this->type=='ideal' && !session::has_credential('admin'))$this->type = '';
            
            switch($this->type){
                case 'mine': #user blogs comments
                    $this->list = $this->get_mine();
                    break;

                case 'ppl': #people blogs comments which was commented by user
                    $this->list = $this->get_ppl();
                    break;

                case 'fav': #favorite people comments
                    $this->list = $this->get_fav();
                    break;

                case 'favppl': #favorite people blogs comments
                    $this->list = $this->get_favppl();
                    break;

                case 'bkm': #bookmarked blogs comments
                    $this->list = $this->get_bkm();
                    break;
                
                case 'groups_posts_comments': #bookmarked blogs comments
                    load::model('groups/members');
                    $this->user_groups=groups_members_peer::instance()->get_groups(session::get_user_id());
                    $this->list = $this->get_by_groups($this->user_groups);
                    break;
                
                case 'my_child_comments': #child comments for user commnets
                    $this->list = $this->get_mine_child_comments();
                    break;

                case 'ideal': #child comments for ideal country
                    $this->list = $this->get_ideal_comments();
                    break;

                case 'all': #all comments
                    $this->allowed_groups=groups_peer::instance()->get_new();
                    $this->list = $this->get_all($this->allowed_groups);
                    break;

                default: #all in 1
                    $this->created = true;
                    $res[] = $this->get_mine();
                    $res[] = $this->get_mine_child_comments();
                    $res[] = $this->get_ppl();
                    $res[] = $this->get_fav();
                    $res[] = $this->get_favppl();
                    $res[] = $this->get_bkm();
                    $result = array();
                    foreach($res as $k=> $v)
                    {
                        if(is_array($res))
                        {
                            foreach($v as $val)
                            {
                                $new[$k][$val['created_ts']] = $val['id'];
                            }
                            if(is_array($new[$k]))
                            {
                                $result += $new[$k];
                            }
                        }
                    }
                    krsort($result);
                    $this->list = $result;
            }

            db_key::i()->set('user_'.$this->user.'_viewcom_time',time());
            mem_cache::i()->set('user_'.session::get_user_id().'_newcom',0,60*5);
//            if(session::get_user_id()==5968) {
//                echo "<pre>";
//                print_r($this->list);
//                exit;
//            }
            if ($this->list) {
                
            load::action_helper('pager', true);
            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
            $this->list = $this->pager->get_list();
            }
            else $this->list=array();
        }

        private function get_mine()
        {
            $posts = blogs_posts_peer::instance()->get_by_users(array($this->user), array(
                blogs_posts_peer::TYPE_BLOG_POST,
                blogs_posts_peer::TYPE_GROUP_POST,
                blogs_posts_peer::TYPE_MIND_POST));
            ksort($posts);
            return blogs_comments_peer::instance()->get_by_posts($posts,$this->created);
        }

        private function get_ppl()
        {
            $posts = blogs_comments_peer::instance()->get_posts_by_user($this->user,array(blogs_posts_peer::TYPE_MIND_POST,blogs_posts_peer::TYPE_BLOG_POST,blogs_posts_peer::TYPE_GROUP_POST));
            ksort($posts);
            return blogs_comments_peer::instance()->get_by_posts($posts,$this->created);
        }

        private function get_fav()
        {
            $users = bookmarks_peer::instance()->get_by_usr($this->user,6);
            $posts = blogs_posts_peer::instance()->get_by_users($users, array(
                blogs_posts_peer::TYPE_BLOG_POST,
                blogs_posts_peer::TYPE_GROUP_POST,
                blogs_posts_peer::TYPE_MIND_POST));
            ksort($posts);
            return blogs_comments_peer::instance()->get_by_posts($posts,$this->created);
        }

        private function get_favppl()
        {
            $users = bookmarks_peer::instance()->get_by_usr($this->user,6);
            return  blogs_comments_peer::instance()->get_by_users($users,array(
                blogs_posts_peer::TYPE_MIND_POST,
                blogs_posts_peer::TYPE_BLOG_POST,
                blogs_posts_peer::TYPE_GROUP_POST));
        }

        private function get_bkm()
        {
            $posts = bookmarks_peer::instance()->get_by_usr($this->user,1);
            return blogs_comments_peer::instance()->get_by_posts($posts,$this->created);
        }

        private function get_mine_child_comments()
        {
            return @db::get_cols('SELECT id FROM blogs_comments WHERE parent_id IN (SELECT id FROM blogs_comments WHERE user_id='.session::get_user_id().') AND  user_id!='.session::get_user_id().'  ORDER BY id DESC');
        }

        
        private function get_all($groups=array(0))
        {
//            if ($groups && count($groups)>1 && is_array($groups))
//                    {
                        return db::get_cols('SELECT id FROM blogs_comments WHERE post_id in (SELECT id FROM blogs_posts WHERE visible=true AND (type=0 OR group_id in ('.implode(',',$groups).'))) ORDER BY id DESC');
//                    }
//            else return blogs_comments_peer::instance()->get_list(array(), array(), array('id DESC'), 500);
        }
        
        private function get_by_groups($groups=false)
        {
            if ($groups && count($groups)>1 && is_array($groups))
            {
                return db::get_cols('SELECT id FROM blogs_comments WHERE post_id in (SELECT id FROM blogs_posts WHERE group_id in ('.implode(',',$groups).')) ORDER BY id DESC');
            }
            else return false;
        }

        private function get_ideal_comments()
        {
            return db::get_cols('SELECT c.id FROM blogs_comments c, blogs_posts b WHERE b.mpu > 0 AND b.id=c.post_id ORDER BY id DESC');
        }

}
