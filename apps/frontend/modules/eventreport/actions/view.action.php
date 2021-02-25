<?

load::app('modules/eventreport/controller');
class eventreport_view_action extends eventreport_controller
{
	public function execute()
	{
            if(request::get('save'))
            {
                if(session::has_credential('admin'))
                {
                    $this->set_status(3);
                }
            }
            elseif(request::get('send'))
            {
                if(session::has_credential('admin'))
                {
                    $this->set_status(2);
                }
                else
                {
                    $this->set_status(1);
                }
            }
            elseif(request::get('reject'))
            {
                if(session::has_credential('admin'))
                {
                    $this->set_status(4);
                }
            }
            elseif(request::get('delete'))
            {
                eventreport_peer::instance()->update(array(
                    'id'=>request::get_int('id'),
                    'del'=>1
                    ));
                $this->redirect('/eventreport');
            }

            $this->item = eventreport_peer::instance()->get_item(request::get_int('id'));
            if(!$this->item['id'] || $this->item['del'])
            {
                throw new public_exception( t('Отчет не найден') );
            }
            if($this->item['status']==0 || $this->item['status']==2)
            {
                $this->editable = true;
            }
            if($this->item['status']!=3 && !$this->has_access)
            {
                $this->redirect('/');
            }
            if($this->has_access)
            {
                $this->log = eventreport_log_peer::instance()->get_list(array('report_id'=>$this->item['id']));
            }
            $this->check_album($this->item);
            $this->event = events_peer::instance()->get_item($this->item['event_id']);
            $this->photos = photo_peer::instance()->get_album($this->item['photo']);
            $this->photos = array_slice($this->photos, 0, 3);

            if(request::get('resend'))
            {
                load::action_helper('user_email',false);
                $options = array(
                    "%event%" => '<a href="http://'.context::get('server').'/event'.$this->event['id'].'">'.$this->event['name'].'</a>',
                    "%report%" => '<a href="http://'.context::get('server').'/eventreport/view&id='.$this->item['id'].'">'.$this->event['name'].'</a>'
                    );
                $ppoleaders = db::get_cols('SELECT user_id FROM ppo_members WHERE function>0 AND group_id=:group_id',array('group_id'=>$this->item['po_id']));
                foreach($ppoleaders as $coordinator)
                {
                    user_email_helper::send_sys('eventreport_new',$coordinator,31,$options);
                }
            }
        }
}
