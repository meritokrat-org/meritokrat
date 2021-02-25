<?
load::app('modules/birthdays/controller');
class birthdays_index_action extends birthdays_controller
{
        public function execute()
	{
            $this->list = friends_peer::instance()->get_by_order(session::get_user_id());
            /*if( count($this->list)>0 )
            {
               $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 21);
               $this->list = $this->pager->get_list();
            }*/
        }
}