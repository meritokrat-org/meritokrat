<?

class reestr_settings_action extends frontend_controller
{
	public function execute()
	{
                $this->set_renderer('ajax');

                $key = trim(strip_tags(addslashes(request::get('key'))));
                $value = request::get_int('value');

                if(in_array($key,array(
                    'ppo',
                    'ppolog',
                    'dolg',
                    'payments',
                    'recomend',
                    'number',
                    'rishenna',
                    'status',
                    'zayava',
                    'limit'
                )))
                {
                    db_key::i()->set('reestr_'.session::get_user_id().'_'.$key,$value);
                }
	}
}