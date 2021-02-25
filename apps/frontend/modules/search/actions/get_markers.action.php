<?
class search_get_markers_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
                
            $this->set_renderer('ajax');
            $data['data'] = user_data_peer::instance()->smap_search(request::get_string('locationlng'),request::get_string('locationlat'),request::get_int('user_id'));
            if(is_array($data['data']))$data['result']='ok';
            $this->json=$data;
            #echo json_encode($this->json);
        }
}
