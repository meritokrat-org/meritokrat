<?
load::app('modules/admin/controller');
class admin_recomendations_action extends admin_controller
{
	public function execute()
	{
            load::model('user/user_recomendations');
                if ( request::get('submit'))
		{

                        $first_name = trim(request::get('first_name'));
			$last_name = trim(request::get('last_name'));
			$email = strtolower(trim(request::get_string('email','none')));
                        if (request::get_int('inviter')>0) $inviter_id=request::get_int('inviter');
                        else
                            {
                                    $inviter=explode(' ',request::get('inviter'));
                                    $inviter_id=db::get_scalar("SELECT user_id FROM user_data WHERE last_name=:last_name AND first_name=:first_name",array('last_name'=>$inviter[1],'first_name'=>$inviter[0]));
                                    if (!$inviter_id) die('Запрошуючий не знайдений');
                            }
                        if (db::get_row("SELECT * FROM user_recomendations WHERE name=:name AND last_name=:last_name LIMIT 1", array('name' => $first_name,'last_name' => $last_name))) die('рекомендація вже існує');
                        //elseif (db::get_row("SELECT * FROM user_recomendations WHERE email=:email LIMIT 1", array('email' => strtolower($email)))) die('рекомендація вже існує');
			elseif (!db::get_row("SELECT user_id FROM user_data WHERE (first_name=:first_name AND last_name=:last_name) OR email=:email", array('first_name' => $first_name,'last_name' => $last_name,'email' => $email))) throw new public_exception('Помилка, користувач не знайдений! Люда, спробуй, будь ласка, пошукати такого в <a href="/search?submit=1&last_name='.$last_name.'">пошуку</a> а може не вказаний email запрошенного, чи вертайсь <a href="'.$_SERVER['HTTP_REFERER'].'">назад</a> нащо воно тобі');
                        elseif ( $first_name && $last_name  && $email)
                            {
                            db::exec("UPDATE user_auth SET recomended_by=:inviter WHERE id=(SELECT user_id FROM user_data WHERE (first_name=:first_name AND last_name=:last_name) or email=:email)", array('inviter'=>$inviter_id,'first_name' => $first_name,'last_name' => $last_name,'email' => $email)); 
                            load::model('user/user_recomendations');
                            $recomendation_id=user_recomendations_peer::instance()->insert(array(
                             'user_id' => $inviter_id,
                             'name' =>  $first_name,
                             'last_name' =>  $last_name,
                             'recomendation' =>  request::get('recomendation'),
                             'created_ts' =>  1,
                             'accepted_ts' =>  1,
                             'accepted_user_id' =>  1,
                             'email' =>  $email,
                             'gender' => request::get_string('gender','m'),
                             'country_id' => request::get_int('country',1)
                             ));
                            $this->recomendation=1;
                            }
                }
                
                      //  mail('andimov@gmail.com', 'Error 3', request::get('id'));
              //   die();
	}
}