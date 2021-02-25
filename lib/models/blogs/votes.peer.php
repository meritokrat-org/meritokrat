<?
class blogs_votes_peer extends db_peer_postgre
{
	protected $table_name = 'blogs_votes';
        protected $primary_key = false;

	/**
	 * @return blogs_votes_peer
	 */
	public static function instance()
	{
		return parent::instance( 'blogs_votes_peer' );
	}
        
	public function get_viewed_list($user_id=false)
	{
                if (!$user_id) $user_id=session::get_user_id();
                return db_key::i()->exists('posts_viewed_list:' . $user_id) ? unserialize(db_key::i()->get('posts_viewed_list:' . $user_id)) : array();
        }
        
	public function get_viewed_posts($user_id=false)
	{
                if (!$user_id) $user_id=session::get_user_id();
                return db_key::i()->exists('posts_viewed_list:' . $user_id) ? unserialize(db_key::i()->get('posts_viewed_list:' . $user_id)) : array();
        }
        
	public function add_viewed_post($post_id,$user_id=false)
	{        
                if (!$user_id) $user_id=session::get_user_id();
		$posts = $this->get_viewed_posts($user_id);
		$posts[] = $post_id;
		$posts = array_unique($posts);
		db_key::i()->set('posts_viewed_list:' . $user_id, serialize($posts));
        }
            
	public function not_viewed_posts($user_id=false)
	{        
                if (!$user_id) $user_id=session::get_user_id();
		$posts = $this->get_viewed_posts($user_id);
                load::model('blogs/posts');
                
        }
                
}