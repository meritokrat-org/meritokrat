<?

load::app('modules/debates/controller');
class debates_view_action extends debates_controller
{
	public function execute()
	{
		if ( !$this->data = debates_peer::instance()->get_item( request::get_int('id') ) )
		{
			$this->redirect('/debates');
		}

		$this->arguments = debates_arguments_peer::instance()->get_by_debate($this->data['id']);

		client_helper::set_title($this->data['text']);
	}
}