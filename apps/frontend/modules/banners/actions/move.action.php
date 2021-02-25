<?
load::app('modules/banners/controller');
class banners_move_action extends banners_controller
{
    protected $credentials = array('admin');
    
    public function execute()
	{
		if (!session::has_credential('admin'))
		{
			$this->redirect('/banners');
		}
		load::model('banners/items');
		if ( request::get('id') AND request::get('pos') )
        {
			if(request::get_int('mov')!=0)
            {
                $sign = '>';
            }
            else
            {
                $sign = '<';
            }
            //print_r('SELECT id,position FROM banners WHERE position '.$sign.' '.request::get_int('pos').' ORDER BY position DESC LIMIT 1');
            $next = db::get_row('SELECT id,position FROM banners WHERE position '.$sign.' '.request::get_int('pos').' ORDER BY position DESC LIMIT 1');
            if(is_array($next))
            {  
                banners_items_peer::instance()->update(array(
                    'id' => request::get_int('id'),
                    'position' => $next['position']
                ));
                banners_items_peer::instance()->update(array(
                    'id' => $next['id'],
                    'position' => request::get_int('pos')
                ));
            //db::exec("UPDATE banners SET position = ".$next['position']." WHERE id = :id", array('id'=>request::get_int('id')));
            //db::exec("UPDATE banners SET position = ".request::get_int('pos')." WHERE id = :id", array('id'=>$next['id']));
            }
            $this->redirect('/banners');
		}
	}
}
