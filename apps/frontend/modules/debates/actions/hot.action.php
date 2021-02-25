<?

load::app('modules/debates/controller');
class debates_hot_action extends debates_controller
{
	public function execute()
	{
		if ( $this->tag = trim(request::get('tag')) )
		{
			$tag_id = debates_tags_peer::instance()->get_by_name( $this->tag );
			$this->list = debates_peer::instance()->get_by_tag($tag_id);
		}
		else
		{
			$this->list = debates_peer::instance()->get_hot();
		}

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 5);
		$this->list = $this->pager->get_list();
	}
}