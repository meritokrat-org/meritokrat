<?
load::app('modules/banners/controller');
class banners_index_action extends banners_controller
{
    protected $credentials = array('admin');

    public function execute()
	{
            load::model('banners/items');
            
            if ( request::get('submit') )
            {
                $photo = request::get_file('photo');
                banners_items_peer::instance()->insert(array(
                        'photo' => $photo['name'],
                        'author' => trim(request::get_string('author')),
                        'title' => trim(request::get_string('title')),
                        'link' => trim(request::get_string('link')),
                        'position' => request::get_int('position')
                     ));
                load::model('banners/items');
                load::system('storage/storage_simple');
                $storage = new storage_simple();
                $salt = substr(md5($photo['name']),0,10);
                $key = 'banners/' . $salt . '.jpg';
                $storage->save_uploaded($key, request::get_file('photo'));
                $this->redirect('/banners/');
            }

            $this->items = banners_items_peer::instance()->get_list(array(),array(),array('position ASC'));
            $this->block_title = db_key::i()->get('banners_block_title');
	}
}