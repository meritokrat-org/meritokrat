<?

load::app('modules/blogs/controller');
class blogs_change_type_action extends blogs_controller
{
	protected $authorized_access = true;
        protected $credentials = array('admin');

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		blogs_posts_peer::instance()->update(array(
                    'id'=>request::get_int('id'),
                    'type'=>request::get_int('type')
                ));
	}
}