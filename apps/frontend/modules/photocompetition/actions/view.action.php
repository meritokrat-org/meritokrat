<?
class photocompetition_view_action extends frontend_controller
{
	#protected $authorized_access = true;

	public function execute()
	{
		load::model('photo/photo_competition');
		load::model('photo/photo_competition_comments');
		if ( $this->photo = photo_competition_peer::instance()->get_item( request::get_int('id') ) )
		{
			$this->photo_comments=photo_competition_comments_peer::instance()->get_list(array('photo_id'=>$this->photo['id'],'parent_id'=>0),array(),array('id ASC'));
		}
                else $this->redirect ('/photocompetition');
	}
}