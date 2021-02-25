<?
load::app('modules/docs/controller');
class docs_edit_action extends docs_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');
        
        public function execute()
	{
            load::model('blogs/posts');

            $id = request::get_int('id');
            
            if(request::get('submit'))
            {
                $this->set_renderer('ajax');
                $this->json = array();

                $array = array(
                    'alias' => mb_substr(trim(addslashes(strip_tags(request::get_string('alias')))), 0, 250, 'UTF-8'),
                    'title' => mb_substr(trim(addslashes(strip_tags(request::get_string('title')))), 0, 250, 'UTF-8'),
                    'text' => blogs_posts_peer::instance()->clean_text(stripslashes(request::get('text')))
                    //'text' => stripslashes(request::get('text'))
                );

                if(docs_peer::instance()->check_alias($array['alias']))
                {
                    $this->json = array('errors' => array('alias' => array(t('Такой alias уже существует'))));
                }
                else
                {
                    if($id)
                    {
                        $array['id'] = $id;
                        docs_peer::instance()->update($array);
                    }
                    else
                    {
                        $id = docs_peer::instance()->insert($array);
                    }
                }
            }

            $this->doc = docs_peer::instance()->get_item($id);
            if(!$this->doc['id'] && request::get_int('id'))
            {
                throw new public_exception('Документ не найден');
            }

	}
}