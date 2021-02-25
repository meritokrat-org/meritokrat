<?

load::app('modules/admin/controller');
class admin_broshure_action extends admin_controller
{
	public function execute()
	{
                /**load::model('user/user_agitmaterials');
                $users = db::get_cols('SELECT user_id FROM user_desktop');
                $users = array_unique($users);
                foreach($users as $id)
                {
                    $r = db::get_row('SELECT * FROM user_desktop WHERE user_id = '.$id);
                    $array = array(
                        'user_id' => $r['user_id'],
                        'type' => 1,
                        'receive' => intval($r['information_brochure_receive']),
                        'given' => intval($r['information_brochure_given']),
                        'presented' => intval($r['information_brochure_presented'])
                    );
                    user_agitmaterials_peer::instance()->insert($array);
                    print_r($array);echo "<br><br>";
                }*/
	}
}
