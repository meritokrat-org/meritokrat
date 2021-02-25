<?
load::app('modules/ideas/controller');
class ideas_index_action extends ideas_controller
{
	public function execute()
	{	
        load::view_helper('tag', true);
        if(request::get('bookmark'))
        {
            load::model('bookmarks/bookmarks');
        }
		if ( $this->tag = trim(request::get('tag')) )
		{
			if ( $tag_id = ideas_tags_peer::instance()->get_by_name( $this->tag ) )
			{
				$this->list = ideas_tags_peer::instance()->get_by_tag($tag_id);
				//tag_helper::$rss = 'http://' . context::get('host') . '/ideas/rss?tag=' . urlencode(request::get('tag'));
//                               / var_dump($this->list);
				client_helper::set_title($this->tag . ' | ' . conf::get('project_name'));
			}
			else
			{
				$this->redirect('/ideas');
			}
		}
		elseif ( $this->segment = trim(request::get('segment')) )
		{
			$this->list = ideas_peer::instance()->get_by_segment($this->segment);
		}
		else
		{
			$this->list = ideas_peer::instance()->get_new();
		}


		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}