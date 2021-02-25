<?
load::app('modules/reestr/controller');
class reestr_members_action extends reestr_controller
{
	public function execute()
	{
                if(!intval(db_key::i()->get('schanger'.session::get_user_id())) && !in_array(session::get_user_id(),array(2,5,29,1360,3949,5968,1299)))
                {
                    throw new public_exception('Недостаточно прав');
                }

                $this->disable_layout();

                load::view_helper('image');
                load::action_helper('page',false);

                //$this->users = db::get_cols("SELECT * FROM user_auth WHERE status != 20");
                $this->users = db::get_cols("SELECT * FROM user_auth");
                $this->invited = db::get_cols("SELECT * FROM user_auth WHERE status = 20");
                $this->userpager = pager_helper::get_pager($this->users, request::get_int('page'), 12);
                $this->users = $this->userpager->get_list();
	}
}