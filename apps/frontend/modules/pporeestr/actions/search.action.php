<?
load::app('modules/pporeestr/controller');
class pporeestr_search_action extends pporeestr_controller
{
	public function execute()
	{

                load::action_helper('pager');
                $this->limit = db_key::i()->get('pporeestr_'.session::get_user_id().'_limit');
                if(intval($this->limit)<10 || intval($this->limit)>100)$this->limit = 10;

                if(request::get('submit'))
                {
                    $this->list = ppo_peer::instance()->get_reestr_search();

                        $this->total = count($this->list);
                        if(!request::get_int('all'))
                        {
                            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), $this->limit);
                            $this->list = $this->pager->get_list();
                        }
                    
                }

                if (request::get_int('print')==1 )
                {
                    $array = array('rec','reg','num','fio','ppo','ris','his','sta','dol','zay','vne','all');
                    foreach($array as $a)
                    {
                        $this->ft[$a] = request::get_int($a);
                    }
                    $this->set_template('print');
                    $this->set_layout('');
                }
	}
}