<?

load::app('modules/blogs/controller');
class blogs_favorite_action extends blogs_controller
{
    protected $authorized_access = true;
    //protected $credentials = array('moderator');

    public function execute()
    {
        load::model('bookmarks');
        if ( request::get_int('id') )
        {
            //$this->post_data = blogs_posts_peer::instance()->get_item( request::get_int('id') );

                /*blogs_posts_peer::instance()->update(array(
                        'id' => $this->post_data['id'],
                        'favorite' => !$this->post_data['favorite']
                ));*/
            bookmarks_peer::instance()->add(session::get_user_id(), bookmarks_peer::TYPE_BLOG_POST, request::get_int('id'));

            $this->redirect('/blogpost' . $this->post_data['id']);
        }
    }
}