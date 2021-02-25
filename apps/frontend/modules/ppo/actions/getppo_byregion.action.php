<?

load::app('modules/ppo/controller');
class ppo_getppo_byregion_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
                $this->json = array();

		$query['regions'] = ppo_peer::instance()->get_by_region(request::get_int('region_id'),false,1);
                $this->json = $query;
	}
}
