<?

load::app('modules/blogs/controller');
class blogs_valuable_action extends blogs_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

	public function execute()
	{
		if ( request::get_int('id') )
		{
			$this->post_data = blogs_posts_peer::instance()->get_item( request::get_int('id') );
                        
                        $this->post_data['for']<7 ? $for=7 : $for=$this->post_data['for'];
			blogs_posts_peer::instance()->update(array(
				'id' => $this->post_data['id'],
				'for' => $for
			));

			$this->redirect('/blogs');
		}
	}
} 