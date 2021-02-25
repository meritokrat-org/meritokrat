<?

class send_invites_task extends shell_task
{
	public function execute()
	{
		load::model('user/user_auth');

		$list = db::get_rows('SELECT * FROM ' . user_auth_peer::instance()->get_table_name());
		foreach ( $list as $data )
		{
			if ( strpos($data['email'], '@') )
			{
				$this->out($data['email']);

				load::system('email/email');

				$email = new email();
				$email->setReceiver($data['email']);

				$body =
				'Вітаємо на відкритті сайту Politiko!' . "\n" .
				"\n" .
				'Для того, щоб зареєструватися на сайті, перейдіть за посиланням:' . "\n" .
				'http://politiko.com.ua/sign/up?email=' . urlencode($data['email']) . '&name=' . urlencode($data['name']) .
				"\n" .
				"\n" .
				'Politiko.com.ua';

				$email->setBody($body);
				$email->setSubject( 'Вітаєто на Politiko.com.ua');

				$email->send();
			}
		}
	}
}