<?

load::app('modules/ppo/controller');
class ppo_link_delete_action extends ppo_controller
{
	public function execute()
	{
		load::model('ppo/links');
		if ( !$this->link = ppo_links_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Файл не найден');
		}

		$this->group = ppo_peer::instance()->get_item($this->link['group_id']);

		if ( ($this->link['user_id'] == session::get_user_id()) || ppo_peer::instance()->is_moderator($this->link['group_id'], session::get_user_id())  )
		{
			ppo_links_peer::instance()->delete_item($this->link['id']);
		}

		$this->redirect('/ppo/file?id=' . $this->group['id'] . '&dir_id=' . $this->link['dir_id']);
	}
}
