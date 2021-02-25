<?

load::app('modules/admin/controller');
class admin_editlists_action extends admin_controller
{
	public function execute()
	{ 
        load::model('mailing');
        switch(request::get('mail_mode')){
	case 'add_mail':
        case 'mail':
            $this->count=0;
            $users=request::get('email');
            $first_name=request::get('first_name');
            $last_name=request::get('last_name');
            if(count($users))
                foreach ($users as $k=>$v){
            if($v){
                $error = mailing_peer::instance()->add_maillists_user($v,trim($first_name[$k]),trim($last_name[$k]),request::get('lists'));
                if($error!=1)$this->er_mails[]= $error;
                else $this->count++;
            }
                }
            $this->send=1;
        break;
        case 'find_mail':
        $this->mail=mailing_peer::instance()->get_maillists_user(request::get('findmail'));
        if($this->mail)$this->user_lists=mailing_peer::instance()->get_maillists_user_lists($this->mail['id']);
        if(!count($this->user_lists))$this->user_lists=array();
        if(!$this->mail)$this->error=1;
        break;
        case 'save_mail': 
        mailing_peer::instance()->save_maillists_user(
                request::get('mail_userid'),trim(request::get('first_name')),trim(request::get('last_name')),
                intval(request::get('blacklisted')));
        mailing_peer::instance()->save_maillists_user_lists(request::get('mail_userid'),request::get('userlists'));
        break;
        }

        if (request::get_int('list')>0) {
        mailing_peer::instance()->del_maillists(request::get_int('list'));
        die();
        }

        if (request::get_string('listname')!='') {
        mailing_peer::instance()->add_maillists(request::get_string('listname'));
        die();
        }

         if (request::get_int('id')>0){
         load::action_helper('pager', true);
         $users=mailing_peer::instance()->get_maillists_list_users(request::get_int('id'),request::get_int('page'));
         $this->users=$users['list'];  
         $this->pager = pager_helper::get_pager($users['count'], request::get_int('page'), 50);
         $this->listname = db::get_scalar("SELECT name FROM email_lists WHERE id=".request::get_int('id'));
         }

        if (request::get_int('deluser')>0) {
        mailing_peer::instance()->del_maillists_user(request::get_int('deluser'));
        $this->redirect("/admin/editlists?id=".request::get_int('id'));
        }
    }
}
