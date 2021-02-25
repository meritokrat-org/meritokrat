<?

class maillist_task extends shell_task
{
	public function execute()
	{
		$mail_data = file_get_contents(dirname(__FILE__). '/../../data/maillist.csv');
		$mail_data = explode("\n", $mail_data);

		$list = array();
		foreach ( $mail_data as $row )
		{
			$row_data = explode(",", $row);

			if ( strpos($row_data[1], '@') )
			{
				$list[] = array(trim($row_data[0], '"'), trim($row_data[1], '"'));
			}
		}
		
		foreach ( $list as $data )
		{
			$this->out($data[0] . ' ' . $data[1]);

			load::system('email/email');

			$email = new email();
			$email->setReceiver($data[1]);
			
			$body =
			'Доброго дня, ' . $data[0] . '! Запрошую Вас приєднатися до політичної соціальної мережі Politiko' . "\n" .
			'Ведіть блог, приймайте участь у дебатах, оцінюйте партії і політиків, читайте новини громадських організацій, створюйте групи, шукайте однодумців  і ще багато цікавих можливостей на сайті - www.politiko.com.ua' . "\n\n" .
			'Формуємо громадське і політичне життя України разом!' . "\n\n" .
			'http://www.slideshare.net/mitray/politiko-1253192 - за цим лінком знаходиться презентація Politiko.com.ua' . "\n\n" .
			'http://www.slideshare.net/mitray/instruction-1333888 - інструкція для політичних партій і політичних діячів' . "\n\n" .
			'З повагою, команда Politiko.';

			$email->setBody($body);
			$email->setSubject( 'Політична соціальна мережа - Politiko.com.ua');

			$email->send();
		}
	}
}