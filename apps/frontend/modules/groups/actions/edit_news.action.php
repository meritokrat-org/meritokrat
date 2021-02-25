<?
load::app('modules/groups/controller');
class groups_edit_news_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                     if (request::get_file('file')) {
                        $photo = request::get_file('file');
                        load::system('storage/storage_simple');
                        $storage = new storage_simple();
                        $salt = substr(md5($photo['name']), 0, 10);
                        $key = 'groupnews/' . $salt . '.jpg';
                        $storage->save_uploaded($key, request::get_file('file'));
                        groups_news_peer::instance()->update(array('id' => request::get_int('id'), 'photo' => $photo['name']));
                    }
            if ( request::get('submit') && !request::get_file('file'))
                    {    
            if (request::get('text') and request::get('id') and groups_peer::instance()->is_moderator(request::get_int('id'), session::get_user_id()) )
                {
                        $id = request::get_int('id');
                        load::model('blogs/posts');
                        $clean_text = blogs_posts_peer::instance()->clean_text(stripslashes(trim(request::get('text'))));
                        $this->id = groups_news_peer::instance()->update(array(
                                'id' => $id,
                                'title' => trim(mb_substr(request::get_string('title'),0,250)),
				'created_ts' => time(),
                                'text' => $clean_text
                        ));
                $this->redirect('/groups/newsread?id='.$id);
                }
                    }
                    
                    $this->post_data=groups_news_peer::instance()->get_item(request::get('id'));
                
	}
}