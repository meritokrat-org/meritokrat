<?
class photocompetition_delete_comment_action extends frontend_controller
{
	public function execute()
	{
		load::model('photo/photo_competition_comments');

		$this->set_renderer('ajax');
		$this->json = array();

		if ( $comment_id = request::get_int('id') )
		{

                        $this->comment = photo_competition_comments_peer::instance()->get_item(request::get_int('id'));
                        if (session::has_credential('admin') || session::get_user_id()==$this->comment['user_id'])
                        {
                             photo_competition_comments_peer::instance()->delete_item($comment_id);
                        }
		}
	}
}