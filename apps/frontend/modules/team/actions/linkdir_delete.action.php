<?

load::app('modules/ppo/controller');
class ppo_linkdir_delete_action extends ppo_controller
{
	public function execute()
	{
		load::model('ppo/links_dirs');
		load::model('ppo/links');
		if ( !$this->linkdir = ppo_links_dirs_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Папка не найдена');
		}

		$this->group = ppo_peer::instance()->get_item($this->linkdir['group_id']);

		if ( ($this->linkdir['user_id'] == session::get_user_id()) || ppo_peer::instance()->is_moderator($this->linkdir['group_id'], session::get_user_id())  )
		{

			$links = ppo_links_dirs_peer::instance()->get_by_group($this->group['id'], request::get_int('id'));
                        foreach($links as $value) ppo_links_peer::instance()->delete_item($value);
                        ppo_links_dirs_peer::instance()->delete_item($this->linkdir['id']);
		}

		$this->redirect('/ppo/file?id=' . $this->group['id']);
	}
}
