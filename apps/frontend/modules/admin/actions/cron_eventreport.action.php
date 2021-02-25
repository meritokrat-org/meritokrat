<?

class admin_cron_eventreport_action extends basic_controller
{
	public function execute()
	{
            load::model('eventreport/eventreport');
            load::model('photo/photo_albums');
            load::action_helper('user_email',false);

            $list = eventreport_peer::instance()->get_new_events();

            foreach($list as $id)
            {
                $event = db::get_row('SELECT * FROM events WHERE id = :id',array('id'=>$id));
                if(!$event['content_id'])$event['content_id'] = 63; #boss ppo :)

                $album_id = photo_albums_peer::instance()->insert(array(
                    'obj_id'=>$event['content_id'],
                    'user_id'=>$event['user_id'],
                    'title'=>$event['name'],
                    'type'=>2
                ));

                $report_id = eventreport_peer::instance()->insert(array(
                    'event_id'=>$event['id'],
                    'user_id'=>$event['user_id'],
                    'po_id'=>$event['content_id'],
                    'name'=>$event['name'],
                    'description'=>$event['description'],
                    'photo'=>$album_id,
                    'format'=>$event['format'],
                    'start'=>$event['start'],
                    'end'=>$event['end']
                ));

                $ppoleaders = db::get_cols('SELECT user_id FROM ppo_members WHERE function>0 AND group_id=:group_id',array('group_id'=>$event['content_id']));
                if(is_array($ppoleaders))
                {
                    $options = array(
                            "%event%" => '<a href="http://'.context::get('server').'/event'.$event['id'].'">'.$event['name'].'</a>',
                            "%report%" => '<a href="http://'.context::get('server').'/eventreport/view&id='.$report_id.'">'.$event['name'].'</a>'
                            );
                    foreach($ppoleaders as $coordinator)
                    {
                        user_email_helper::send_sys('eventreport_new',$coordinator,31,$options);
                    }
                }
            }
            die();
        }
}
