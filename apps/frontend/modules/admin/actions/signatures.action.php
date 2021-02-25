<?

load::app('modules/admin/controller');
class admin_signatures_action extends admin_controller
{
	public function execute()
	{
             if(request::get_int('signatures') && request::get_int('districts') && request::get_int('signatures_region_leader'))
             {
                    db_key::i()->set('signatures',request::get_int('signatures'));
                    db_key::i()->set('districts',request::get_int('districts'));
                    db_key::i()->set('signatures_region_leader',request::get_int('signatures_region_leader'));
                    db_key::i()->set('last_signatures_time',time());
             }
	}
}
