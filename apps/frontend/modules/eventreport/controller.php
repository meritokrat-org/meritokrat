<?

abstract class eventreport_controller extends frontend_controller
{
	protected $authorized_access = true;
        public $has_access;
        public $ppo_list;

	public function init()
	{
		parent::init();

		load::model('eventreport/eventreport');
                load::model('eventreport/eventreport_log');
                load::model('events/events');
                load::model('events/members');
                load::model('ppo/ppo');
                load::model('ppo/members');

                load::view_helper('photo');
		load::model('photo/photo');
                load::model('photo/photo_albums');

		load::action_helper('pager', true);

		client_helper::set_title( t('Наша агитация') . ' | ' . conf::get('project_name') );

                $this->has_access = $this->check_access();
	}

        protected function check_album($report)
        {
            if($report['photo'])
            {
                $album = photo_albums_peer::instance()->get_item($report['photo']);
            }
            if(!$report['photo'] || !$album['id'])
            {
                $album_id = photo_albums_peer::instance()->insert(array(
                    'obj_id'=>$report['po_id'],
                    'user_id'=>$report['user_id'],
                    'title'=>$report['name'],
                    'type'=>2
                ));
                eventreport_peer::instance()->update(array(
                    'id'=>$report['id'],
                    'photo'=>$album_id
                    ));
            }
        }

        protected function set_status($status=0)
        {
            if(!$status)return;

            load::action_helper('user_email',false);

            eventreport_peer::instance()->update(array(
                'id'=>request::get_int('id'),
                'status'=>$status
            ));
            eventreport_log_peer::instance()->insert(array(
                'report_id'=>request::get_int('id'),
                'user_id'=>session::get_user_id(),
                'status'=>$status,
                'message'=>addslashes(request::get_string('message')),
                'date'=>time()
            ));

            $report = eventreport_peer::instance()->get_item(request::get_int('id'));
            $ppoleaders = db::get_cols('SELECT user_id FROM ppo_members WHERE function>0 AND group_id=:group_id',array('group_id'=>$report['po_id']));
            $options = array(
                "%event%" => '<a href="http://'.context::get('server').'/event'.$event['id'].'">'.$event['name'].'</a>',
                "%report%" => '<a href="http://'.context::get('server').'/eventreport/view&id='.$report['id'].'">'.$report['name'].'</a>',
                "%profile%" => user_helper::full_name(session::get_user_id(), true, array(), false),
                "%message%" => request::get('message'),
                
            );
            if($status==1)
            {
                foreach ( user_auth_peer::get_admins() as $admin )
                {
                    $options['%settings%'] = 'http://'. context::get('host') . '/profile/edit?id='.$admin.'&tab=settings';
                    user_email_helper::send_sys('eventreport_send',$admin,31,$options);
                }
            }
            if($status==2)
            {
                if(is_array($ppoleaders))
                {
                    foreach($ppoleaders as $coordinator)
                    {
                        $options['%settings%'] = 'http://'. context::get('host') . '/profile/edit?id='.$coordinator.'&tab=settings';
                        user_email_helper::send_sys('eventreport_back',$coordinator,31,$options);
                    }
                }
            }
            if($status==3)
            {
                if(is_array($ppoleaders))
                {
                    foreach($ppoleaders as $coordinator)
                    {
                        $options['%settings%'] = 'http://'. context::get('host') . '/profile/edit?id='.$coordinator.'&tab=settings';
                        user_email_helper::send_sys('eventreport_ok',$coordinator,31,$options);
                    }
                }
            }
        }

        private function check_access()
        {
            #вычисляем по в котором юзер является лидером и все по которые подчинены ему (если это рпо или мпо)
            $user_ppo = ppo_members_peer::instance()->is_leader(session::get_user_id());
            //if(session::get_user_id()==620 || session::get_user_id()==1360)print_r($user_ppo);
            if($user_ppo)
            {
                $this->ppo_list = ppo_peer::instance()->get_all_children($user_ppo);
            }
                
            if(session::has_credential('admin'))
            {
                return true;
            }
            else
            {
                if(request::get_int('id'))
                {
                    $report = eventreport_peer::instance()->get_item(request::get_int('id'));
                    if($user_ppo)
                    {
                        if(in_array($report['po_id'], $this->ppo_list)) #вычисляем находится ли данное по в числе тех что может редактировать наш лидер
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    if(ppo_members_peer::instance()->is_leader(session::get_user_id()))
                    {
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }

            }
        }

}
