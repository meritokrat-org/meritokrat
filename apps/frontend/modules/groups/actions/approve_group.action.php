<?
load::app('modules/groups/controller');
class groups_approve_group_action extends groups_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		$this->group = groups_peer::instance()->update(array('active'=>1),array('id'=>request::get_int('group_id')));
		$this->group = groups_peer::instance()->get_item(request::get_int('group_id'));
		$this->group['creator_id'];
                groups_peer::instance()->add_moderator($this->group['id'], $this->group['creator_id']);
		load::action_helper('user_email', false);
		/*user_email_helper::send($this->group['creator_id'],
			null,
			array(
				'subject' => t('Ваше сообщество одобрили'),
				'body' =>
					'%receiver%,' . "\n\n" .
					t('Предложенное вами сообщество') .
					' "' . $this->group['title'] . '"' .
					t('было одобрено администрацией.') .
				    t('Сообщество стало видно для остальных пользователей, сейчас уже можно публиковать обсуждения, загружать фотографии и приглашать дургих участников.') . ": " .
					'http://' . context::get('host') . '/group' . $this->group['id']
			)
		);*/
                $options = array(
                        '%title%' => $this->group['title'],
                        '%link%' => 'http://' . context::get('host') . '/group' . $this->group['id']
                    );
                user_email_helper::send_sys('groups_approve_group',$this->group['creator_id'],null,$options);
                
                $this->redirect('/group'.request::get_int('group_id'));
        }
}