<?php

class profile_delete_photo_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                $user_id = request::get_int('id');
		if (!session::has_credential('admin') && session::get_user_id()!=$user_id)
		{
                    throw new public_exception('Помилка, недостатно прав');
                }
		else
		{
                    user_data_peer::instance()->update(array(
					'user_id' => $user_id,
					'photo_salt' => NULL
				));
                    $this->redirect('/profile/edit?tab=photo');
		}
        }
}

?>
