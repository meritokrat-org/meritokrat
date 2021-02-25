<?

load::app('modules/admin/controller');
class admin_medit_action extends admin_controller
{
	public function execute()
	{
            load::model('email/email');

            $id = request::get_int('id');
            if(!$id) $this->redirect('/admin/mails');

            if(request::get('submit'))
            {
                $this->set_renderer('ajax');
                $this->json = array();

                $array['id'] = $id;
                $array['sender_mail'] = request::get_string('sender_mail');
                $array['name'] = request::get('name');
                $array['mail'] = request::get_int('mail');

                if(request::get('has_footer')) $array['has_footer'] = 1;
                else $array['has_footer'] = 0;

                $lang = request::get('lang');

                if( $lang != 'ru' )
                {
                    $array['title_ua'] = request::get('title_ua');
                    $array['body_ua'] = request::get('body_ua');
                    $array['sender_name_ua'] = request::get('sender_name_ua');
                }
                else
                {
                    $array['title_ru'] = request::get('title_ru');
                    $array['body_ru'] = request::get('body_ru');
                    $array['sender_name_ru'] = request::get('sender_name_ru');
                }

                email_peer::instance()->update($array);
            }
            else
            {
                load::model('drafts');
                $drafts = drafts_peer::instance()->get_drafts(1);
                $drafts[0] = '&mdash;';
                $drafts[9999] = t('Без шаблона');
                ksort($drafts);
                $this->drafts = $drafts;
                $this->mail = email_peer::instance()->get_item($id);
            }
        }
}
