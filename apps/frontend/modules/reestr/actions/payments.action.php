<?
load::app('modules/reestr/controller');
class reestr_payments_action extends reestr_controller
{
	public function execute()
	{
                if(!$this->access)
                {
                    throw new public_exception('Недостаточно прав');
                }

                load::model('user/user_payments_log');
                load::action_helper('pager');
                $this->limit = db_key::i()->get('reestr_'.session::get_user_id().'_limit');
                if(intval($this->limit)<10 || intval($this->limit)>100)$this->limit = 10;

                $this->list = user_auth_peer::instance()->get_reestr_payments($this->region,$this->city,$this->ppo,0,request::get_int('metod'),request::get_int('way'.request::get_int('metod')));
                //if(session::get_user_id()==1360)die(print_r($this->list));
                $this->list = array_unique($this->list);
                $this->total = count($this->list);
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), $this->limit);
                $this->list = $this->pager->get_list();
                db_key::i()->set('user_'.session::get_user_id().'_viewpay_time',time());
                mem_cache::i()->delete('pay_'.session::get_user_id().'_newz');
	}
}