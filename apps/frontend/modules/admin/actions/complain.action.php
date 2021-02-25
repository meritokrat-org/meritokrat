<?

class admin_complain_action extends frontend_controller
{
        protected $authorized_access = true;

        public function execute()
	{
                $user_id = request::get_int('uid');
                $moderator_id = request::get_int('mid');
                $content_id = request::get_int('cid');
                $content_type = request::get_int('ct');
                $action = request::get_int('ac');

                if(!$user_id || !$moderator_id || !$content_id || !$content_type || !$action)
                    return false;

                load::model('admin_complaint');

                $item = admin_complaint_peer::instance()->get_row(array(
                    'user_id' => $user_id,
                    'moderator_id' => $moderator_id,
                    'content_id' => $content_id
                    ));
                if($item['id'])
                {
                    throw new public_exception(t('Эта жалоба уже зарегистрирована'));
                }
                else
                {
                    admin_complaint_peer::instance()->insert(array(
                        'user_id' => $user_id,
                        'moderator_id' => $moderator_id,
                        'content_id' => $content_id,
                        'content_type' => $content_type,
                        'created_ts' => time(),
                        'action' => $action
                    ));
                    throw new public_exception(t('Ваша жалоба зарегистрирована'));
                }
	}
}
