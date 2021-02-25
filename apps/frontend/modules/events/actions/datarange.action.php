<?

load::app('modules/events/controller');
class events_datarange_action extends events_controller
{
	protected $authorized_access = true;

	public function execute()
	{
            $this->set_renderer('ajax');

            $data = explode(' ',request::get('start'));
            $date = explode('.',$data[0]);
            $time = explode(':',$data[1]);

            $start = mktime($time[0], $time[1], 0, $date[1], $date[0], $date[2]);

            $result = events_peer::instance()->get_item(request::get_int('id'));
            $range = $result['end'] - $result['start'];
            $end = $start + $range;
 
            die(date("d.m.Y H:i",$end));
	}
}