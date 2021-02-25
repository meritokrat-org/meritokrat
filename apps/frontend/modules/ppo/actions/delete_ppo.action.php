<?
load::app('modules/ppo/controller');
class ppo_delete_ppo_action extends ppo_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

	public function execute()
	{
		$this->group = ppo_peer::instance()->get_item(request::get_int('ppo_id'));
                ppo_peer::instance()->delete_item(request::get_int('ppo_id'));

                $name=explode(" ",user_helper::full_name($this->group['creator_id'],false));
                $this->group['active']==1 ?  $body='Вітаю, '.$name[0].'. '.t('Вашу партийную организацию').' "'.$this->group['title'].'" '.t('было удалено из сети.') : $body='Вітаю, '.$name[0].'. '.t('Вашу партийную организацию').' "'.$this->group['title'].'" '.t('не одобрили.');
                $this->redirect('/messages/compose?user_id='.$this->group['creator_id'].'&sender_id=31&body='.$body);
        }
}