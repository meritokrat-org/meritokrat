<?

class admin_updatepartkvitok_action extends basic_controller
{
	public function execute()
	{
		$list = db::get_cols('SELECT user_id FROM membership WHERE kvnumber = 0');
		$max = db::get_scalar('SELECT max(kvnumber) FROM membership');
		$max += 1;

		foreach ($list as $user) {
			$zayava = db::get_row('SELECT * FROM user_zayava WHERE user_id = ' . $user);
			$payment = db::get_scalar('SELECT COUNT(*) FROM user_payments WHERE user_id = ' . $user . ' AND type = 1 AND approve = 2');
			if (($zayava['id'] && !$zayava['kvitok']) OR $payment) {
				echo 'UPDATE membership SET kvnumber = ' . $max . ' WHERE user_id = ' . $user . '<br/>';
				//db::exec('UPDATE membership SET kvnumber = '.$max.' WHERE user_id = '.$user);
				$max++;
			}
		}
	}
}
