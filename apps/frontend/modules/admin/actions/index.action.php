<?

load::app('modules/admin/controller');

class admin_index_action extends admin_controller
{
	public function execute()
	{

		if ( ! session::has_credential('admin')) $this->redirect('/');
		$this->stats = array(
			'Користувачів' => db::get_scalar('SELECT count(id) FROM user_auth'),
			'Приватних повідомлень' => db::get_scalar('SELECT count(id) FROM messages'),
			'Думок' => db::get_scalar('SELECT count(id) FROM blogs_posts'),
			'Опитувань' => db::get_scalar('SELECT count(id) FROM polls'),
			'Ідей' => db::get_scalar('SELECT count(id) FROM ideas'),
			'Спільнот' => db::get_scalar('SELECT count(id) FROM groups'),
			# 'Обновлений' => db::get_scalar('SELECT count(id) FROM feed'),
		);

		$this->list = db::get_rows(
		    "SELECT user_data.user_id AS id, count(user_auth.id) AS invited, user_data.first_name, user_data.last_name "
		    ."FROM user_data "
		    ."INNER JOIN user_auth ON user_auth.invited_by = user_data.user_id AND user_auth.active = true AND user_auth.hide_inviter = false GROUP BY user_data.user_id ORDER BY count(user_auth.id) DESC");
	}

}
