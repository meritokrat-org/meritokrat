<?
load::app('modules/invites/controller');
class invites_delete_action extends invites_controller
{
        public function execute()
	{
            $this->set_renderer('ajax');

            $user_id = request::get_int('uid');
            $type = request::get_int('tp');
            $object_id = request::get_int('oid');

            if($user_id && $type && $object_id)
            {
                db::exec('DELETE FROM invites WHERE to_id = '.$user_id.' AND obj_id = '.$object_id.' AND type = '.$type);
            }
            else
            {
                echo 'error';
            }
        }
}