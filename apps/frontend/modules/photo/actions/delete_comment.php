<?
load::app('modules/photo/controller');
class photo_delete_comment_action extends photo_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		load::model('photo/photo_comments');

		$this->set_renderer('ajax');
		$this->json = array();

		if ( $comment_id = request::get_int('id') )
		{
                        if ($this->access)
                        {
                             photo_comments_peer::instance()->delete_item($comment_id);
                        }
		}
	}
}