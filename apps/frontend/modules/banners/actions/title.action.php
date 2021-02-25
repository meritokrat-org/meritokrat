<?
load::app('modules/banners/controller');
class banners_title_action extends banners_controller
{
    protected $credentials = array('admin');

    public function execute()
	{
            db_key::i()->set('banners_block_title', trim(request::get('title')));
            exit();
	}
}
