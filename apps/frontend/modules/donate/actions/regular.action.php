<?php
class donate_regular_action extends frontend_controller
{
    
	protected $authorized_access = false;
	public function execute()
	{
           if ($email=request::get('email',null))
           {
               
             $data=array(
                        "email"=>$email,
                        "blacklisted"=>0
                    );
                    if(!db::get_scalar("SELECT id FROM email_users WHERE email='$email'"))
                    {
                            db::exec("INSERT INTO email_users(email,blacklisted)
                                VALUES(:email,:blacklisted)",$data);                            
                    }
            $id=db::get_scalar("SELECT id FROM email_users WHERE email='$email'");
            db::exec("INSERT INTO email_lists_users(user_id,list_id) VALUES(:user_id,:list_id)",
                                      array('user_id'=>$id,'list_id'=>308));
           }
        }
}

?>
