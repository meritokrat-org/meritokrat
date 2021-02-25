<?

load::app('modules/admin/controller');
class admin_add_attention_action extends admin_controller
{
	public function execute()
	{
            load::model('attentions');

		if ( request::get('submit') && trim(request::get('text')) && trim(request::get('anounces')) )
		{
			$data = array(
				'created_ts' => time(),
				'user_id' => session::get_user_id(),
				'hidden' => request::get('hidden'),
				'title' => request::get('title'),
				'text' => request::get('text'),
                                'anounces'=>trim(request::get_string('anounces')),
				'text_ru' => request::get('text_ru'),
                                'anounces_ru'=>trim(request::get_string('anounces_ru'))
			);
			
			$attention_id = attentions_peer::instance()->insert($data);
                        
                        $this->redirect('/');
		}
	}
}
