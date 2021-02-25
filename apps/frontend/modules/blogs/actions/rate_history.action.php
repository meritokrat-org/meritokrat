<?

load::app('modules/blogs/controller');
class blogs_rate_history_action extends blogs_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

	public function execute()
	{
		if ( request::get_int('id') )
		{
			$this->history_post_data = blogs_posts_peer::instance()->get_item( request::get_int('id') );
                        
			load::model('rate_history');
			$this->list = rate_history_peer::instance()->get_by_object( rate_history_peer::TYPE_BLOG_POST, $this->history_post_data['id'] );
		}
	}
}
