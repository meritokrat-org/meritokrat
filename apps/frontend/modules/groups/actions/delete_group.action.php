<?
load::app('modules/groups/controller');
class groups_delete_group_action extends groups_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

	public function execute()
	{
		$this->group = groups_peer::instance()->get_item(request::get_int('group_id'));
                groups_peer::instance()->delete_item(request::get_int('group_id'));

                $name=explode(" ",user_helper::full_name($this->group['creator_id'],false));
                $this->group['active']==1 ?  $body='Вітаю, '.$name[0].'. '.t('Ваше сообщество').' "'.$this->group['title'].'" '.t('было удалено из сети.') : $body='Вітаю, '.$name[0].'. '.t('Ваше сообщество').' "'.$this->group['title'].'" '.t('не одобрили.');
                $this->redirect('/messages/compose?user_id='.$this->group['creator_id'].'&sender_id=31&body='.$body);
        }
}