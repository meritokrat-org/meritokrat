<?
load::app('modules/groups/controller');
class groups_add_news_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                if (request::get('text') and request::get('id') and groups_peer::instance()->is_moderator(request::get_int('id'), session::get_user_id()) )
                {
                        $id = request::get_int('id');
                        load::model('blogs/posts');
                        $clean_text = blogs_posts_peer::instance()->clean_text(stripslashes(trim(request::get('text'))));
                        $this->id = groups_news_peer::instance()->insert(array(
				'group_id' => $id,
                                'title' => trim(mb_substr(request::get_string('title'),0,250)),
				'created_ts' => time(),
                                'text' => $clean_text
                        ));
                $this->redirect('/groups/news?id='.$id);
                }
                
	}
}