<?

class eventreport_show_action extends frontend_controller
{
	public function execute()
	{
            load::model('ppo/ppo');
            load::model('eventreport/eventreport');
            if(request::get_int('po_id'))
            {
                $this->list = eventreport_peer::instance()->get_list(array('status'=>3,'po_id'=>request::get_int('po_id')),array(),array('id desc'));
            }
            else
            {
                $this->list = eventreport_peer::instance()->get_list(array('status'=>3),array(),array('id desc'));
            }

            load::action_helper('pager', true);

            $this->total = count($this->list);
            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);

            client_helper::set_title( t('Наша агитация') . ' | ' . conf::get('project_name') );
	}

}