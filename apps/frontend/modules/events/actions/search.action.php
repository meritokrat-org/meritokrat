<?
load::app('modules/events/controller');
class events_search_action extends events_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		if ( request::get('submit') )
		{
                    if (request::get_string('name')) $filters['name'] = htmlspecialchars(request::get_string('name'));
                    if (request::get_int('start_day') && request::get_int('start_month') && request::get_int('start_year')) $filters['start'] = date("d/m/Y",user_helper::dateval('start'));
                    if (request::get_int('end_day') && request::get_int('end_month') && request::get_int('end_year')) $filters['end'] = date("d/m/Y",user_helper::dateval('end'));
                    if (request::get_int('cat')) $filters['cat'] = request::get_int('cat');
                    if (request::get_int('section')) $filters['section'] = request::get_int('section');
                    if (request::get_int('level')) $filters['section'] = request::get_int('level');
                    if (request::get_int('region')) $filters['region'] = request::get_int('region');
                    if (request::get_int('city_id')) $filters['city_id'] = request::get_int('city_id');
                    if (request::get_int('price'))(request::get_int('price')==2)?$filters['price']=true:$filters['price']=false;
                    if (request::get_int('status')) $filters['status'] = request::get_int('status');
                    $events = events_peer::instance()->search($filters);
                    $this->events = $events['list'];
                    $this->pager = pager_helper::get_pager($events['count'], request::get_int('page'), 15);
		}
	}
}
