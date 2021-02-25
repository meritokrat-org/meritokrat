<?

load::app('modules/lists/controller');
class lists_index_action extends lists_controller
{
	public function execute()
	{
		$this->own_lists = lists_peer::instance()->own_lists(session::get_user_id());
                $this->edit_lists = lists_users_peer::instance()->get_lists_by_user(session::get_user_id(),2);
                $this->edit_lists = array_diff($this->edit_lists,$this->own_lists);
                $this->list = array_merge($this->own_lists,$this->edit_lists);

                if(count($this->list)==0 && !session::has_credential('admin'))
                        $this->redirect('/');
	}
}