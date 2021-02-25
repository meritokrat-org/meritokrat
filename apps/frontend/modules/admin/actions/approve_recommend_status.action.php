<?

class admin_approve_recommend_status_action extends frontend_controller
{

	protected $authorized_access = true;

	public function execute()
	{
		load::model('user/user_recommend');
		if ($recomendation_id = request::get_int('id') and session::has_credential('admin')) {

			$this->recomended_user = user_recommend_peer::instance()->get_item($recomendation_id);
			if ($this->recomended_user['accept_user_id'] > 0) throw  new public_exception ('Рекомендация вже схвалена. ' . user_helper::full_name($this->recomended_user['accept_user_id']));
			else {
				user_recommend_peer::instance()->update(array(
					'id' => $recomendation_id,
					'accept_ts' => time(),
					'accept_user_id' => session::get_user_id(),
					'checked' => 1
				));
				/*
				user_auth_peer::instance()->update(array(
					'id'=>$this->recomended_user['user_id'],
					'status'=>$this->recomended_user['status']
					));
				 */
			}

			load::action_helper('user_email', false);
			$options = array(
				'%first_name%' => $this->recomended_user['name'],
				'%last_name%' => $this->recomended_user['last_name'],
				'%from%' => strip_tags(user_helper::full_name($this->recomended_user['user_id'], false))
			);
			//user_email_helper::send_sys('users_create_recommend',$this->recomended_user['user_id'],false,$options);

			//db::exec("UPDATE user_desktop SET people_attracted=people_attracted+1 WHERE user_id=:user_id",array('user_id'=> $this->recomended_user['user_id']));
		}
		$this->redirect('/admin/recommend_status');
	}
}
