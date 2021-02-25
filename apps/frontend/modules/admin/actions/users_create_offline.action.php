<?

class admin_users_create_offline_action extends frontend_controller
{

	public function execute()
	{
                $this->set_renderer('ajax');
                $this->json = array();
   
                $first_name = trim(htmlspecialchars(addslashes(request::get('firstname'))));
                $last_name = trim(htmlspecialchars(addslashes(request::get('lastname'))));
                $region_id = request::get_int('region');

                if($first_name && $last_name)
                {
                    $num = db::get_scalar("SELECT COUNT(*) FROM user_data WHERE (first_name ILIKE '%$first_name%'
                        AND last_name ILIKE '%$last_name%' AND region_id = $region_id) OR (last_name ILIKE '%$first_name%'
                        AND first_name ILIKE '%$last_name%' AND region_id = $region_id)");
                    if ($num)
                    {
                        $this->json = array('error'=>"В мережi вже є учасник з таким iм'ям та прiзвищем. Ви все одно хочете створити офф-лайн профiль з такими даними?");
                        return;
                    }
                }

	}
}
