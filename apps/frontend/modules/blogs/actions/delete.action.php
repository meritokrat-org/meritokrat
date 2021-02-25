<?

load::app('modules/blogs/controller');
class blogs_delete_action extends blogs_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( request::get_int('id') )
		{
			$this->post_data = blogs_posts_peer::instance()->get_item( request::get_int('id') );
			if ( ( $this->post_data['user_id'] != session::get_user_id() ) && !session::has_credential('moderator') && !(groups_peer::instance()->is_moderator($this->post_data['group_id'], session::get_user_id())) )
			{
				$this->redirect('/blog-' . session::get_user_id());
			}

			blogs_posts_peer::instance()->delete_item($this->post_data['id']);

                        db::exec('DELETE FROM blogs_programs WHERE blog_id = '.$this->post_data['id']);

			if ( session::has_credential('moderator') )
			{
				load::model('admin_feed');
				/*$text = $this->post_data['text_rendered'] . '<br /><br /> Автор: ' .
						user_helper::full_name($this->post_data['user_id']);*/

				admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_BLOG_POST, $text, $this->post_data, request::get_string('why'));
			}


                        $this->post_data['group_id']>0 ? $this->redirect('/groups/posts?group_id=' . $this->post_data['group_id']) : $this->redirect('/blog-' . session::get_user_id());
		}
	}
}