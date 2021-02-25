<?
load::app('modules/admin/controller');
class admin_currency_action extends admin_controller
{
	public function execute()
	{
             if(request::get_string('dollar') && request::get_string('ruble') && request::get_string('euro'))
             {
                    db_key::i()->set('currency_dollar',request::get_string('dollar'));
                    db_key::i()->set('currency_euro',request::get_string('euro'));
                    db_key::i()->set('currency_ruble',request::get_string('ruble'));
             }
             
        }
}