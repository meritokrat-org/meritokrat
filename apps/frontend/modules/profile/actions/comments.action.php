<?php

class profile_comments_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
            if(!$this->user_id = request::get_int('id'))
                $this->user_id = session::get_user_id ();

            load::model('blogs/posts');
            load::model('blogs/comments');
            load::model('groups/groups');
            $this->allowed_groups=groups_peer::instance()->get_new();
            
            $this->list = blogs_comments_peer::instance()->get_by_users(array($this->user_id), false, array(blogs_posts_peer::TYPE_GROUP_POST,blogs_posts_peer::TYPE_MIND_POST,blogs_posts_peer::TYPE_BLOG_POST), $this->allowed_groups);
            arsort($this->list);
            load::action_helper('pager', true);
            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
            $this->list = $this->pager->get_list();
        }
}
