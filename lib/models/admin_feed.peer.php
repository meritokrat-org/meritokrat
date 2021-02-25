<?

class admin_feed_peer extends db_peer_postgre
{
	protected $table_name = 'admin_feed';

	const TYPE_BLOG_COMMENT = 1;
	const TYPE_BLOG_POST = 2;
	const TYPE_DEBATE_COMMENT = 3;
	const TYPE_DEBATE = 4;
        const TYPE_POLL_COMMENT = 5;
        const TYPE_IDEA_COMMENT = 6;
        const TYPE_EVENT_COMMENT = 7;
        const TYPE_POLL = 8;

	/**
	 * @return admin_feed_peer
	 */
	public static function instance()
	{
		return parent::instance( 'admin_feed_peer' );
	}

	public function add( $user_id, $type, $text, $data=array(), $why='', $action=0 )
	{


            $array = array(//array('module_name','table_cell_name','link_name')
                    '1' => array('blogpost','post_id','посту',t('комментарий к мысли')),
                    '2' => array('blog','post_id','посту',t('мысль')),
                    '5' => array('poll','poll_id','опросу',t('комментарий к опросу')),
                    '6' => array('idea','idea_id','идее',t('комментарий к идее')),
                    '7' => array('event','event_id','событию',t('комментарий к событию')),
                    '8' => array('poll','id','опросу',t('опрос'))
                    );
            $actions = array(t('удален'),t('отредактирован'));
            $act = array(t('удалил'),t('отредактировал'));
            switch($type)
            {
                case "1":
                    $text = $data['text'];
                    load::model('blogs/posts');
                    $item = blogs_posts_peer::instance()->get_item($data['post_id']);
                    if($action)$com = '#comment'.$data['id'];
                    $link = '<a href="http://'.conf::get('server').'/blogpost'.$item['id'].$com.'">'.stripslashes($item['title']).'</a>';
                    break;

                case "2":
                    $text = $data['text_rendered'];
                    if($action)
                        $link = '<a href="http://'.conf::get('server').'/blogpost'.$data['id'].'">'.stripslashes($data['title']).'</a>';
                    else
                        $link = stripslashes($data['title']);
                    break;

                case "5":
                    $text = $data['text'];
                    load::model('polls/polls');
                    $item = polls_peer::instance()->get_item($data['poll_id']);
                    if($action)$com = '#comment'.$data['id'];
                    $link = '<a href="http://'.conf::get('server').'/poll'.$item['id'].$com.'">'.stripslashes($item['question']).'</a>';
                    break;

                case "6":
                    $text = $data['text'];
                    load::model('ideas/ideas');
                    $item = ideas_peer::instance()->get_item($data['idea_id']);
                    if($action)$com = '#comment'.$data['id'];
                    $link = '<a href="http://'.conf::get('server').'/idea'.$item['id'].$com.'">'.stripslashes($item['title']).'</a>';
                    break;

                case "7":
                    $text = $data['text'];
                    load::model('events/events');
                    $item = events_peer::instance()->get_item($data['event_id']);
                    if($action)$com = '#comment'.$data['id'];
                    $link = '<a href="http://'.conf::get('server').'/event'.$item['id'].$com.'">'.stripslashes($item['name']).'</a>';
                    break;

                case "8":
                    $text = $data['question'];
                    if($action)
                        $link = '<a href="http://'.conf::get('server').'/poll'.$data['id'].'">'.stripslashes($data['question']).'</a>';
                    else
                        $link = stripslashes($data['question']);
                    break;
            }

            $id = parent::insert(array(
                    'created_ts' => time(),
                    'user_id' => $user_id,
                    'type' => $type,
                    'text' => $text,
                    'author_id' => $data['user_id'],
                    'data' => serialize($data),
                    'why' => $why,
                    'action' => $action,
                    'link' => $link
                ));
            if($user_id!=$data['user_id'])
            {
                load::action_helper('user_email', false);
                $typetext = $array[$type][3];
                if($type==2)
                {
                    if(session::get('language')=='ua')
                        $typetext = 'думку';
                }
                $nlink = strip_tags(user_helper::full_name($data['user_id']),'<a>');
                $mlink = strip_tags(user_helper::full_name($user_id),'<a>');
                $options = array(
                       '%name%' => strip_tags($nlink),
                       '%nlink%' => $nlink,
                       '%title%' => $link,
                       '%action%' => $act[$action],
                       '%moderator%' => strip_tags($mlink),
                       '%mlink%' => $mlink,
                       '%type%' => $typetext,
                       '%text%' => $text,
                       '%why%' => $why,
                       '%undo%' => 'http://'.conf::get('server').'/admin/undo?id='.$id,
                       '%link%' => 'http://'.conf::get('server').'/admin/mfeed'
                    );
                foreach ( user_auth_peer::get_admins() as $admin )
                {
                    //user_email_helper::send_sys('admin_feed',$admin,false,$options);
                }
                
                if (!session::has_credential('admin'))
                {
                    load::model('messages/messages');
                    load::model('user/user_data');
                    $usr = user_data_peer::instance()->get_item($data['user_id']);
                    $keywords = array('%content_type%'=>$array[$type][3],
                                      '%link%'=>$link,
                                      '%action%'=>$actions[$action],
                                      '%reason%'=>($why!='undefined') ? '<br> Причина: '.$why : '',
                                     );
                    $msg = email_peer::instance()->get_mail('admin_edit');
                    foreach ($keywords as $key => $value)
                        $msg['body_'.session::get('language')] = preg_replace ('/'.$key.'/', $value, $msg['body_'.session::get('language')]);
                    messages_peer::instance()->add(array(
                            'sender_id' => session::get_user_id(),
                            'receiver_id' => $data['user_id'],
                            'body' => $msg['body_'.session::get('language')]
                        ));
                }
            }
	}

	public function get($page = 1, $per_page = 10)
	{
		return db::get_rows(
			'SELECT id,user_id,author_id,type,created_ts,text,why,action,link FROM ' . $this->get_table_name() . ' ORDER BY id DESC LIMIT :limit OFFSET :offset',
			array('limit' => $per_page, 'offset' => ( $page - 1 ) * $per_page));
	}
        
}
