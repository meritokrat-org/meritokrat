<?
class blogs_views_history_action extends frontend_controller
{	
        protected $authorized_access = true;
        protected $credentials = array('admin');
        
        public function execute()
	{
            load::model('blogs/posts');
            if (!$this->post=blogs_posts_peer::instance()->get_item(request::get('id'))) throw new public_exception ("Публікації не існує");
            
            $this->viewers=db::get_row("SELECT users FROM blogs_posts_views WHERE post_id=".$this->post['id']);
            $this->viewers=explode(',',$this->viewers['users']);
        }
}