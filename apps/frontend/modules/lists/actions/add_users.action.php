<?

load::app('modules/lists/controller');
class lists_add_users_action extends lists_controller
{
	public function execute()
	{
                $this->set_renderer('ajax');
                $users = request::get('fr');
                $id = request::get_int('id');
                $action = request::get_string('act');

                /* type
                 * 0 - in_list
                 * 1 - viewer
                 * 2 - editor
                 */

                if(count($users)>0)
                {
                    foreach ( $users as $user_id )
                    {
                            if($action=='add')
                            {
                                    if(!lists_users_peer::instance()->check_in_list($id, $user_id))
                                    {
                                        $id = lists_users_peer::instance()->insert(array(
                                            'list_id' => $id,
                                            'user_id' => $user_id
                                        ));
                                    }
                            }
                            elseif($action=='view')
                            {
                                    if(!lists_users_peer::instance()->check_in_list($id, $user_id, 1))
                                    {
                                        db::exec('DELETE FROM lists2users WHERE list_id = '.$id.' AND user_id = '.$user_id.' AND type = 2');
                                        $id = lists_users_peer::instance()->insert(array(
                                            'list_id' => $id,
                                            'user_id' => $user_id,
                                            'type' => 1
                                        ));
                                    }
                            }
                            elseif($action=='edit')
                            {
                                    if(!lists_users_peer::instance()->check_in_list($id, $user_id, 2))
                                    {
                                        db::exec('DELETE FROM lists2users WHERE list_id = '.$id.' AND user_id = '.$user_id.' AND type = 1');
                                        $id = lists_users_peer::instance()->insert(array(
                                            'list_id' => $id,
                                            'user_id' => $user_id,
                                            'type' => 2
                                        ));
                                    }
                            }
                            elseif($action=='undo')
                            {
                                    db::exec('DELETE FROM lists2users
                                        WHERE list_id = '.$id.'
                                        AND user_id = '.$user_id.'
                                        AND (type = 1 OR type = 2)');
                            }
                            elseif($action=='del')
                            {
                                    db::exec('DELETE FROM lists2users
                                        WHERE list_id = '.$id.'
                                        AND user_id = '.$user_id.'
                                        AND type = 0');
                            }
                    }
                }
	}
}