<?

class admin_cron_donate_action extends basic_controller
{
	public function execute()
	{
        load::model('mailing');
        load::action_helper('user_email', false);
        load::view_helper('user', false);     
        
        $emails = db::get_rows("SELECT email FROM email_users WHERE id in (SELECT user_id FROM email_lists_users WHERE list_id=308)");
        $template = db::get_row("SELECT * FROM email_system WHERE id=61");
        foreach($emails as $mails){
          
			$email = new email();
			$email->setSender($template['sender_mail'], $template['sender_name_ua']);
			$email->setReceiver($mails['email']);
			$email->setSubject($template['title_ua']);
			$email->setBody(stripslashes($template['body_ua']));
			$email->isHTML();
                        $email->send();
        }
        echo count($emails); die();       
        }
}
