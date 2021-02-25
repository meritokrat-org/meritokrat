<?
load::app('modules/invites/controller');
class invites_add_group_action extends invites_controller
{
	public function execute()
	{
                $this->set_renderer('ajax');
                $this->friends = request::get('fr');
                $this->from = session::get_user_id();
                $this->to = request::get_int('user_id');
                $this->message = request::get_string('message');

                if(!$this->to)
                    return;

                if(count($this->friends)>0)
                {
                    load::model('groups/groups');
                    load::view_helper('group');

                    $this->name = strip_tags(user_helper::full_name(session::get_user_id()),"<a>");

                    load::action_helper('user_email', false);

                    foreach ( $this->friends as $friend_id )
                    {
                        $this->data = groups_peer::instance()->get_item($friend_id);
                        $this->title = $this->data['title'];
                        $this->profile = 'http://'.conf::get('server').'/group'.$friend_id;
                        $this->image = group_helper::photo($friend_id, 's', false, array('align'=>'left','hspace'=>'10','vspace'=>'5'));

                        $options = array(
                                '%name%' => $this->name,
                                '%title%' => $this->title,
                                '%image%' => $this->image,
                                '%profile%' => $this->profile,
                                '%message%' => $this->message,
                                '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$this->to.'&tab=settings'
                                );

                        $id = invites_peer::instance()->add($this->to,array(
                                'from_id' => $this->from,
                                'obj_id' => $friend_id,
                                'type' => 2
                            ));

                        $options['%link%'] = 'http://'.conf::get('server').'/invites/edit?commit=1&user='.$this->to.'&id='.$id.'&status=1';
                        user_email_helper::send_sys('invites_add_group',$this->to,$this->from,$options);

                    }
                }
	}
}