<?
load::app('modules/eventreport/controller');
class eventreport_index_action extends eventreport_controller
{
	public function execute()
	{
            if(!$this->has_access)
            {
                $this->redirect('/eventreport/show');
            }
            else
            {
                $this->list = eventreport_peer::instance()->get_reports($this->ppo_list,intval(request::get_int('status')));
            }
            $this->total = count($this->list);
            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
	}

        private function update()
        {
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

                eventreport_peer::instance()->insert(array(
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
            }
        }
}