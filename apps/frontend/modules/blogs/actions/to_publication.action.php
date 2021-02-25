<?

class blogs_to_publication_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                load::model('blogs/votes');
                load::model('blogs/posts');
                $veto=request::get('veto_id');
                if (session::get_user_id()==29 && $veto)
                {
                    db_key::i()->set('post_'.$veto.'_veto', 1);
                    echo '0';
                }
                elseif ($id=request::get_int('id',false))
                {   
                    if (!db_key::i()->exists('post_'.request::get('id').'_veto'))
                    {
                        db_key::i()->set('post_'.$id.'_vote_'.session::get_user_id(), 1);
                        if (!db::get_scalar('SELECT count(*) FROM blogs_votes WHERE post_id='.$id.' and user_id='.session::get_user_id()))
                        {
                            $insert_data=array('post_id'=>$id,'user_id'=>session::get_user_id(), 'time'=>time());
                            blogs_votes_peer::instance()->insert($insert_data);
                        }
                        $count=db::get_scalar('SELECT count(*) FROM blogs_votes WHERE post_id='.$id);
                        if ($count==3) db::exec("UPDATE blogs_posts SET type=0 WHERE id=".$id);
                        echo $count;
                    }    
                }
                die();
	}
}