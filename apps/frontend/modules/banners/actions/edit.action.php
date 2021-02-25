<?
load::app('modules/banners/controller');
class banners_edit_action extends banners_controller
{
    protected $credentials = array('admin');
    
    public function execute()
	{
            if (!session::has_credential('admin'))
            {
                $this->redirect('/banners');
            }

            load::model('banners/items');

            if ( request::get('submit') )
            {
                $photo = request::get_file('photo');
                $ins = array(
                        'id' => request::get_int('id'),
                        'author' => trim(request::get_string('author')),
                        'title' => trim(request::get_string('title')),
                        'link' => trim(request::get_string('link'))
                     );
                if($photo['name'])
                {
                    $ins['photo'] = $photo['name'];
                    load::model('banners/items');
                    load::system('storage/storage_simple');
                    $storage = new storage_simple();
                    $salt = substr(md5($photo['name']),0,10);
                    $key = 'banners/' . $salt . '.jpg';
                    $storage->save_uploaded($key, request::get_file('photo'));
                }
                banners_items_peer::instance()->update($ins);
                $this->redirect('/banners/');
            }
            $this->item = banners_items_peer::instance()->get_item(request::get('id'));
	}
}
