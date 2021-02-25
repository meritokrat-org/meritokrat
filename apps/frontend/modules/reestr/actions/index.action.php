<?
load::app('modules/reestr/controller');
class reestr_index_action extends reestr_controller
{
	public function execute()
	{
                if(!$this->access)
                {
                    throw new public_exception('Недостаточно прав');
                }

                load::action_helper('pager');
                $this->limit = db_key::i()->get('reestr_'.session::get_user_id().'_limit');
                if(intval($this->limit)<10 || intval($this->limit)>100)$this->limit = 10;

                $this->list = user_auth_peer::instance()->get_reestr($this->region,$this->city,$this->ppo);
				$this->debts = user_auth_peer::instance()->analyze_debt($this->list, request::get_string("order") != "dolg" ? false : true);
                $this->total = count($this->list);
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), $this->limit);
                $this->list = $this->pager->get_list();
                db_key::i()->set('user_'.session::get_user_id().'_viewreestr_time',time());
                mem_cache::i()->delete('reestr_'.session::get_user_id().'_newz');
	}
}