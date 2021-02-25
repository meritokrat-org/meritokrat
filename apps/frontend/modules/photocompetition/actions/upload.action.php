<?
class photocompetition_upload_action extends frontend_controller
{
	public function execute()
	{
            if (request::get('submit') && request::get_file('file')) {
                
                   load::model('photo/photo_competition');
                   load::system('storage/storage_simple');
                   
                   $storage = new storage_simple();

                   $id=photo_competition_peer::instance()->insert(array(
                            'user_id' => session::get_user_id(),
                            'title' => mb_substr(request::get_string('title'), 0,250),
                            'text' => request::get_string('text'),
                            'ts' => time(),
                            'votes' => 0
                   ));
                   
                   $salt = substr(md5($id),0,8);
                   
                   $key = 'photocompetition/' . $salt . '.jpg';
                   $storage->save_uploaded($key, request::get_file('file'));
                   
                   $this->redirect('/photocompetition/'.$id);
            }
                
                        
	}
}