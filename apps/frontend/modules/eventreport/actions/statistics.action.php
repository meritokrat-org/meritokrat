<?
load::app('modules/eventreport/controller');
class eventreport_statistics_action extends eventreport_controller
{
	protected $credentials = array('admin');

        public function execute()
	{
            $this->list = eventreport_peer::instance()->statistics();
	}
}