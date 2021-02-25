<?
class help_index_action extends frontend_controller
{
//        protected $authorized_access = true;
        public function execute()
        {
                load::model('help/help');

                foreach ( $_GET as $key => $value )
                {
                    if ( !$value )
                    {
                        $this->data = help_peer::instance()->get_data($key);
                        if (!user_auth_peer::get_rights(session::get_user_id(),db_key::i()->get('text_min_status:'.$this->data['alias']))) throw new public_exception('Немає повноважень');
                        if (session::get('language')!='ru')
                            client_helper::set_title($this->data['title_ua']);
                        else
                            client_helper::set_title($this->data['title_ru']);

                        if(!is_array($this->data))
                                $this->redirect ('/ooops');
                            //throw new public_exception('Страница не найдена');
                        break;
                    }
                }
        }
}
