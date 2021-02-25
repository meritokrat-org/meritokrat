<?

load::app('modules/admin/controller');

class admin_maillist_action extends admin_controller
{
	public function execute()
	{
		load::action_helper('user_email', false);
		$subject_tpl = trim(request::get_string('subject'));
		$body_tpl = str_replace('<img ', '<img align="left" hspace="5" vspace="5" ', request::get_string('body'));
		$body_tpl = str_replace('<table ', '<table width="690" ', $body_tpl);
		$body_tpl = trim(stripslashes($body_tpl));
		$sender_email = trim(request::get_string('sender_email'));
		$sender_name = trim(request::get_string('sender_name'));

		if (trim(request::get_string('testmail')) != '' && request::get('send') == '') {
			$name = trim($names[$i]);

			$subject = str_replace('NAME', $name, $subject_tpl);
			$body = str_replace('NAME', $name, $body_tpl);

			$email = new email();
			$email->setSender($sender_email, $sender_name);
			$email->setReceiver(trim(request::get_string('testmail')));
			$email->setSubject($subject);
			$email->setBody($body);
			$email->isHTML();
			$status = $email->send();

			echo json_encode(array("status" => $status));
			die;
		}

		if (request::get('send') && request::get_int('todraft') == 0 && request::get_int('edit') == 0) {
			$this->sent = 0;
			#load::system('email/email');
			if (request::get('maillists')) {
				$this->send_lists($subject_tpl, $body_tpl, $sender_email, $sender_name);
			} else {
				if (request::get('mail_mode') == 'unknown') {
					$this->send_unknown($subject_tpl, $body_tpl, $sender_email, $sender_name);
				} else {
					$this->send_known($subject_tpl, $body_tpl, $sender_email, $sender_name);
				}
			}
			return;
		}

		if (request::get_int('getsends') == 1 || request::get_int('user_sends') == 1 || request::get_int('getdrufts') == 1 || request::get_int('getact') == 1) {
			load::model('mailing');
			if (request::get_int('getsends') == 1)
				$list = mailing_peer::instance()->get_mailings_send();
			elseif (request::get_int('getdrufts') == 1) $list = mailing_peer::instance()->get_mailings_druft();
			elseif (request::get_int('user_sends') == 1) $list = mailing_peer::instance()->get_mailings_users_send();
			elseif (request::get_int('getact') == 1) $list = mailing_peer::instance()->get_mailings_act();

			if (count($list)) {
				$echo = '<table id="send_table">
                            <tbody><tr>
                            <td>Інформація</td><td>Дії</td></tr>
                            ';
				foreach ($list as $l) {
					$echo .= '<tr id="mailing' . $l['id'] . '"><td valign="top" class="p0"><table>
                                <tbody><tr><td valign="top" width="60">Вiд:</td><td valign="top">' . $l['sender_name'] . ' ' . $l['sender_email'] . '</td></tr>
                                <tr><td valign="top">Тема:</td><td valign="top">' . $l['subject'] . '</td></tr>';
					if (request::get_int('getdrufts') != 1) {
						$echo .= '<tr><td valign="top">Почата:</td><td valign="top">';
						($l['start'] > 0) ? $echo .= date("Y-m-d H:i:s", $l['start']) : $echo .= "";
						$echo .= '</td></tr>';
					}

					if (request::get_int('getact') != 1 && request::get_int('getdrufts') != 1) {
						$echo .= '<tr><td valign="top">Закiнчена:</td><td valign="top">';
						($l['end'] > 0) ? $echo .= date("Y-m-d H:i:s", $l['end']) : $echo .= "";
						$echo .= '</td></tr>';
					}

					if (request::get_int('getact') == 1 || request::get_int('getsends') == 1)
						$echo .= '<tr><td valign="top">Листiв:</td><td valign="top">' . $l['count'] . '</td></tr>
                                    <tr><td valign="top">Надiслано:</td><td valign="top">' . $l['count_send'] . '</td></tr>';
					$echo .= '</tbody></table>
                                </td>
                                <td>
                                <a href="javascript:;" onclick="viewList(\'' . $l['id'] . '\');">Переглянути</a><br>
                                <a href="javascript:delMailing(\'' . $l['id'] . '\');" onclick="return confirm(\'' . t('Удалить') . '\');">Видалити</a>
                                </td>
                                </tr>';
				}
				$echo .= '</tbody></table>';
				die($echo);
			} else
				die('Нема.');
		}

		if (request::get_int('mailing_id') > 0) {
			load::model('mailing');
			$mailing = mailing_peer::instance()->get_list_info(request::get_int('mailing_id'));
			die(json_encode($mailing));
		}

		if (request::get('todraft') == 1) {
			load::model('mailing');
			$data = $_POST;
			unset($data['body']);
			unset($data['subject']);
			unset($data['sender_email']);
			unset($data['sender_name']);
			if (request::get('filter') == 'maillists')
				mailing_peer::instance()->add_mailing(request::get('filter'), $data, $subject_tpl, $body_tpl, $sender_email, $sender_name, false, true, request::get('maillists'), true);
			else
				mailing_peer::instance()->add_mailing(request::get('filter'), $data, $subject_tpl, $body_tpl, $sender_email, $sender_name, false, false, array(), true);

		}

		if (request::get_int('delete_mailing') > 0) {
			load::model('mailing');
			mailing_peer::instance()->del_mailing(request::get_int('delete_mailing'));
			die();
		}

		if (request::get_int('edit') > 0) {
			load::model('mailing');
			if (request::get_int('send') == 1)
				mailing_peer::instance()->save_mailing(request::get_int('edit'), $subject_tpl, $body_tpl, $sender_email, $sender_name, false);
			else mailing_peer::instance()->save_mailing(request::get_int('edit'), $subject_tpl, $body_tpl, $sender_email, $sender_name, true);

			return;
		}

	}

	public function send_unknown($subject_tpl, $body_tpl, $sender_email, $sender_name)
	{
		$emails = request::get('email');
		$names = request::get('name');
		foreach ($emails as $i => $mail) if ($mail = trim($mail)) {
			$name = trim($names[$i]);

			$subject = str_replace('NAME', $name, $subject_tpl);
			$body = str_replace('NAME', $name, $body_tpl);

			$email = new email();
			$email->setSender($sender_email, $sender_name);
			$email->setReceiver($mail);
			$email->setSubject($subject);
			$email->setBody($body);
			$email->isHTML();
//			$email->send();

			$this->sent++;
		}
	}

	public function send_known($subject_tpl, $body_tpl, $sender_email, $sender_name)
	{
		load::model('mailing');

		mem_cache::i()->disable_inner_cache();
		$data = $_POST;

		unset($data['body']);
		unset($data['subject']);
		unset($data['sender_email']);
		unset($data['sender_name']);

		if (request::get('filter') == 'common' && request::get_int('activ') != 1)
			mailing_peer::instance()->add_mailing(request::get('filter'), $data, $subject_tpl, $body_tpl, $sender_email, $sender_name, true);
		else
			mailing_peer::instance()->add_mailing(request::get('filter'), $data, $subject_tpl, $body_tpl, $sender_email, $sender_name);
		$this->sent = 1;
	}

	function send_lists($subject_tpl, $body_tpl, $sender_email, $sender_name)
	{
		load::model('mailing');
		$this->sentall = 1;
		mailing_peer::instance()->add_mailing(request::get('filter'), array(), $subject_tpl, $body_tpl, $sender_email, $sender_name, true, true, request::get('maillists'));
	}
}
