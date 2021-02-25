<?

class results_agitation_user_action extends frontend_controller
{
	protected $authorized_access = true;
        protected $credentials = array('admin');

	public function execute()
	{
		$this->set_layout('');

                load::model('user/user_agitmaterials_log');

                if(request::get_int('user_id'))
                {
                    if(request::get_int('type'))
                        $sqladd = ' AND type = '.request::get_int('type').' ';

                    $query = db::get_rows('SELECT id, type
                        FROM user_agitmaterials_log
                        WHERE user_id = '.request::get_int('user_id').'
                        '.$sqladd.'
                        ORDER BY date');
                    foreach($query as $q)
                    {
                        $arr[$q['type']][] = $q['id'];
                    }
                    $this->list = $arr;
                }
                else
                {
                    $this->redirect('/results');
                }

	}
}