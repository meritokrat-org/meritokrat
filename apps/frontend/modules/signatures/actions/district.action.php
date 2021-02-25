<?
class signatures_district_action extends frontend_controller
{
    public function execute()
    {
             load::model('geo');
             load::model('user/user_desktop');
             $this->districts = geo_peer::instance()->get_cities(request::get_int('region_id',0));
    }
}
