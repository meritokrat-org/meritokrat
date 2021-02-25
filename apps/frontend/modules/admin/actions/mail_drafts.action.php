<?

load::app('modules/admin/controller');
class admin_mail_drafts_action extends admin_controller
{
	public function execute()
	{
            $this->set_renderer('ajax');
            $this->json = array();

            load::model('drafts');
            load::model('email/email');

            $draft_id = request::get_int('draft_id');
            $mail_id = request::get_int('mail_id');

            if(!$mail_id)return;

            $mail = email_peer::instance()->get_item($mail_id);
            preg_match('#<!\-\-content\-\->(.*)<!\-\-\/content\-\->#Uis',$mail['body_ua'],$match['ua']);
            preg_match('#<!\-\-content\-\->(.*)<!\-\-\/content\-\->#Uis',$mail['body_ru'],$match['ru']);
            if($match['ua'][1])
                $mail['body_ua'] = $match['ua'][1];
            if($match['ru'][1])
                $mail['body_ru'] = $match['ru'][1];

            if($draft_id > 0 && $draft_id != 9999)
            {
                $data = drafts_peer::instance()->get_item($draft_id);
                $mail['body_ua'] = str_replace('[content]', '<!--content-->'.$mail['body_ua'].'<!--/content-->', stripslashes($data['text']));
                $mail['body_ru'] = str_replace('[content]', '<!--content-->'.$mail['body_ru'].'<!--/content-->', stripslashes($data['text']));
            }

            return $this->json = array('body_ua'=>$mail['body_ua'],'body_ru'=>$mail['body_ru']);
        }
}
