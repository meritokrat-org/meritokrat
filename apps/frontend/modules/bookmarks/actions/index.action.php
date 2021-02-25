<?

load::app('modules/bookmarks/controller');
class bookmarks_index_action extends bookmarks_controller
{
    public $types = array(1,2,3,6,4,5,7,8);

    public function execute()
    {
        load::model('blogs/posts');

        load::model('user/user_data');
        load::model('user/user_auth');
        load::model('geo');
        load::model('user/user_work');
        
        load::model('groups/groups');
        
        load::model('ideas/ideas');
        load::model('ideas/comments');
        
        load::model('polls/polls');
        load::model('polls/votes');

        load::model('events/events');

        load::model('ppo/ppo');

        $type = request::get_int('type');
        if(!$type)
        {
            $this->type = 1;
        }
        else
        {
            $this->type = $type;
        }
        $main = 'list_'.$this->type;

        foreach($this->types as $t)
        {
            $name = 'list_'.$t;
            $this->$name = bookmarks_peer::instance()->get_by_user(session::get_user_id(),$t);
            $this->items[$t] = $name;
        }

        load::action_helper('pager');
        $this->pager = pager_helper::get_pager($this->$main, request::get_int('page'), 30);
        $this->$main = $this->pager->get_list();
    }
}