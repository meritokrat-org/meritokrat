<?

load::app('modules/admin/controller');
class admin_infoedit_action extends admin_controller
{
	public function execute()
	{
            load::model('help/info');

            $id = request::get_int('id');
            if(!$id) $this->redirect('/admin/info');

            if(request::get('submit'))
            {
                $this->set_renderer('ajax');
                $this->json = array();

                $array['id'] = $id;
                $array['title'] = request::get('title');

                $lang = request::get('lang');

                if( $lang != 'ru' )
                {
                    $array['name_ua'] = request::get('name_ua');
                    $array['text_ua'] = $_POST['text_ua'];
                    if(session::has_credential('programmer'))
                    {
                        $array['admin_text_ua'] = $_POST['admin_text_ua'];
                    }
                }
                else
                {
                    $array['name_ru'] = request::get('name_ru');
                    $array['text_ru'] = $_POST['text_ru'];
                    if(session::has_credential('programmer'))
                    {
                        $array['admin_text_ru'] = $_POST['admin_text_ru'];
                    }
                }

                help_info_peer::instance()->update($array);
            }

            $this->info = help_info_peer::instance()->get_item($id);
        }
}
