<?php

class profile_user_inv_ajax_action extends frontend_controller
{
	public function execute()
	{
		$this->disable_layout();
		$this->set_renderer('ajax');

		$id = request::get_int('id');
		$action = request::get('act');
		$user_id = session::get_user_id();

		switch ($action) {
			case 'resend':

				$user = user_auth_peer::instance()->get_item($id);
				$user_data = user_data_peer::instance()->get_item($id);
				$invited_users = user_auth_peer::instance()->get_all_recomended_by_user($user_id);

				if ($user && $user_data) { // && in_array($id, $invited_users)

					if (filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
						$password = substr(md5(microtime(true)), 0, 8);
						$last_invite = time();
						user_auth_peer::instance()->update(array(
							'id' => $id,
							'password' => md5($password),
							'last_invite' => $last_invite
						));

						load::action_helper('user_email', false);
						$options = array(
							"%fullname%" => $user_data['first_name'] . " " . $user_data['last_name'],
							"%email%" => $user['email'],
							"%password%" => $password
						);
						user_email_helper::send_sys('user_resend', $user['id'], null, $options);
						if (db_key::i()->exists($user['id'] . '_invited_again')) {
							$tmp = db_key::i()->get($user['id'] . '_invited_again');
							db_key::i()->set($user['id'] . '_invited_again', ($tmp + 1));
						} else
							db_key::i()->set($user['id'] . '_invited_again', 2);

						$this->json = array('success' => 'ok', 'c' => db_key::i()->get($user['id'] . '_invited_again'), 'last_invite' => $last_invite);
					} else {
						$this->set_template('popup');
						$this->json = array(
							'success' => false,
							'html' => file_get_contents($this->get_template_path()),
							'message' => t('Невозможно отправить письмо на текущий email.<br/> Перейдите в профиль и проверьте коррекность<br/> адреса электронной почты')
						);
					}
				} else {
					$this->set_template('popup');
					$this->json = array(
						'success' => false,
						'html' => file_get_contents($this->get_template_path()),
						'message' => t('Некорректные входящие данные')
					);
				}
				break;
			case 'delete':
				break;
			default:
				$this->set_template('popup');
				$this->json = array(
					'success' => false,
					'html' => file_get_contents($this->get_template_path()),
					'message' => t('Некорректные входящие данные')
				);
				break;
		}
	}
}

?>
