<?
load::app('modules/admin/controller');
class admin_edit_attention_action extends admin_controller
{
	public function execute()
	{
                load::model('attentions');

                if ( $attention_id=request::get_int('id') )
		{
			$this->attention = attentions_peer::instance()->get_item( $attention_id );
		}

		if ( request::get('submit') && trim(request::get('text')) && trim(request::get('anounces')) )
		{
			$data = array(
				'id' =>$attention_id,
				'user_id' => session::get_user_id(),
				'hidden' => request::get('hidden'),
				'title' => request::get('title'),
				'text' => request::get('text'),
                                'anounces'=>trim(request::get_string('anounces')),
				'text_ru' => request::get('text_ru'),
                                'anounces_ru'=>trim(request::get_string('anounces_ru'))
			);
			
			attentions_peer::instance()->update($data);
                        
                        $this->redirect('/admin/attentions');
		}
	}
}
