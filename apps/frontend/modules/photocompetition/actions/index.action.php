<?
class photocompetition_index_action extends frontend_controller
{
	public function execute()
	{
		load::action_helper('pager', true);
		load::model('photo/photo_competition');
                if (request::get('sort')=='votes') $order='votes DESC';
                if (request::get('sort')=='ts') $order='ts DESC';
                if (!$order) $order='votes DESC'; //   $order='random()';                
                
                $this->limit = db_key::i()->get('photocompetition_'.session::get_user_id().'_limit');
                if(intval($this->limit)<12 || intval($this->limit)>100)$this->limit = 12;

                $this->photos = photo_competition_peer::instance()->get_list(array(), array(), array($order));
                $this->count = count($this->photos);
                $this->pager = pager_helper::get_pager($this->photos, request::get_int('page'), $this->limit);
                $this->photos = $this->pager->get_list();
                        
	}
}