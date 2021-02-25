<?

load::app('modules/admin/controller');
class admin_groups_action extends admin_controller
{
	public function execute()
	{
		load::model('groups/groups');
		load::view_helper('group');

		if ( $this->group_key = request::get('key') )
		{
			$this->group = groups_peer::instance()->get_item($this->group_key);
		}

		if ( $this->group && request::get('submit') )
		{
			groups_peer::instance()->update( array(
				'id' => $this->group['id'],
				'rate' => request::get('rate')
			));

			$this->group = groups_peer::instance()->get_item($this->group['id']);
			$this->saved = true;
		}
	}
}
