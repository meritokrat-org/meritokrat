<?

load::app('modules/bookmarks/controller');
class bookmarks_delete_action extends bookmarks_controller
{
	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();
        if(request::get_int('id'))
        {
    		if ( $this->item = bookmarks_peer::instance()->get_item(request::get_int('id')) )
    		{
    			if ( $this->item['user_id'] == session::get_user_id() )
    			{
    				bookmarks_peer::instance()->delete_item($this->item['id']);
    			}
    		}
        }
        else
        {
            if( $this->item = db::get_row('SELECT * FROM bookmarks WHERE type = '.request::get_int('type').' AND user_id = '.session::get_user_id().' AND oid = '.request::get_int('oid') ) )
            {
               bookmarks_peer::instance()->delete_item($this->item['id']);      
            }
        }
        
	}
}