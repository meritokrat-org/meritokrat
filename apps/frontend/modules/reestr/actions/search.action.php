<?
load::app('modules/reestr/controller');
class reestr_search_action extends reestr_controller
{
	public function execute()
	{
                if(!session::has_credential('admin'))
                {
                    throw new public_exception('Недостаточно прав');
                }

                load::action_helper('pager');
                $this->limit = db_key::i()->get('reestr_'.session::get_user_id().'_limit');
                if(intval($this->limit)<10 || intval($this->limit)>100)$this->limit = 10;

                if(request::get('submit'))
                {
                    $this->list = user_auth_peer::instance()->get_reestr_search();

                    if(request::get_int('excel'))
                    {
                        $this->set_layout('');
                        $this->set_template('excel');
                    }
                    elseif(request::get_int('photo'))
                    {
                        $this->set_layout('');
                        $this->set_template('photo');

                        ini_set('memory_limit','1024M');
                        ini_set('max_execution_time', 300);
                        load::app('modules/reestr/zip');
                        load::system('storage/storage_simple');
                        $storage = new storage_simple();

                        $fileName = '/var/www/meritokrat/www/files/photo.zip';
                        $zip = new Zip();
                        foreach($this->list as $id)
                        {
                            $user = user_data_peer::instance()->get_item($id);
                            $zip->add_data($id.'.jpg',$storage->get('profile/'.$id.$user['photo_salt'].'.jpg'));
                        }
                        $zip->archive($fileName);

                        $this->file = $fileName;
                    }
                    else
                    {
                        $this->total = count($this->list);
                        if(!request::get_int('all'))
                        {
                            $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), $this->limit);
                            $this->list = $this->pager->get_list();
                        }
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