<?
class signatures_district_peoples_action extends frontend_controller
{
    public function execute()
    {
             $this->district_peoples = db::get_rows("SELECT DISTINCT on (user_id) user_id, sum(fact) as fact FROM user_desktop_signatures_fact WHERE city_id=".request::get_int('id',0)." GROUP BY user_id");
    }
}
