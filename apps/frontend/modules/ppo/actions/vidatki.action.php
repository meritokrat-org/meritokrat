<?

load::app('modules/ppo/controller');
class ppo_vidatki_action extends ppo_controller
{
	public function execute()
	{
                $this->disable_layout();
                $this->set_template('vidatki');

                if(request::get_int('id') && request::get_int('date_day'))
                {
                    #edit
                    ppo_finance_peer::instance()->update(array(
                        'id'=>request::get_int('id'),
                        'date'=>user_helper::dateval('date'),
                        'summ'=>request::get_int('summ'),
                        'text'=>strip_tags(request::get('text')),
                        'user_id'=>session::get_user_id()
                    ));
                    ppo_finance_log_peer::instance()->insert(array(
                        'finance_id'=>request::get_int('id'),
                        'date'=>time(),
                        'user_id'=>session::get_user_id()
                    ));
                    $finance = ppo_finance_peer::instance()->get_item(request::get_int('id'));
                }
                elseif(!request::get_int('id') && request::get_int('date_day'))
                {
                    #add
                    $id = ppo_finance_peer::instance()->insert(array(
                        'date'=>user_helper::dateval('date'),
                        'summ'=>request::get_int('summ'),
                        'text'=>strip_tags(request::get('text')),
                        'user_id'=>session::get_user_id(),
                        'region_id'=>request::get_int('region_id')
                    ));
                    ppo_finance_log_peer::instance()->insert(array(
                        'finance_id'=>$id,
                        'date'=>time(),
                        'user_id'=>session::get_user_id()
                    ));
                    $finance['region_id'] = request::get_int('region_id');
                }
                elseif(request::get_int('id'))
                {
                    #delete
                    $finance = ppo_finance_peer::instance()->get_item(request::get_int('id'));
                    ppo_finance_peer::instance()->delete_item(request::get_int('id'));
                    die();
                }
                
                $this->finances = ppo_finance_peer::instance()->get_by_region($finance['region_id']);
	}
}