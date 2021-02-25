<?
class home_attention_action extends frontend_controller
{
	public function execute()
	{
                load::model('attentions');
		if ( !$this->attention = attentions_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/');
		}
	}
}